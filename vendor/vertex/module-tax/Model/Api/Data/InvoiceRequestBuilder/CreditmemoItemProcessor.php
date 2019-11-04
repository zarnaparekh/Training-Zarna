<?php
/**
 * @copyright  Vertex. All rights reserved.  https://www.vertexinc.com/
 * @author     Mediotype                     https://www.mediotype.com/
 */

namespace Vertex\Tax\Model\Api\Data\InvoiceRequestBuilder;

use Magento\Framework\Stdlib\StringUtils;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\CreditmemoItemInterface;
use Vertex\Data\LineItemInterface;
use Vertex\Data\LineItemInterfaceFactory;
use Vertex\Services\Invoice\RequestInterface;
use Vertex\Tax\Model\Api\Data\FlexFieldBuilder;
use Vertex\Tax\Model\Api\Utility\MapperFactoryProxy;
use Vertex\Tax\Model\Repository\TaxClassNameRepository;

/**
 * Processes Items on a Creditmemo and converts them to an array of LineItemInterface
 */
class CreditmemoItemProcessor implements CreditmemoProcessorInterface
{
    /** @var FlexFieldBuilder */
    private $flexFieldBuilder;

    /** @var ItemProcessor */
    private $itemProcessor;

    /** @var LineItemInterfaceFactory */
    private $lineItemFactory;

    /** @var TaxClassNameRepository */
    private $taxClassNameRepository;

    /** @var StringUtils */
    private $stringUtilities;

    /** @var MapperFactoryProxy */
    private $mapperFactory;

    /**
     * @param ItemProcessor $itemProcessor
     * @param LineItemInterfaceFactory $lineItemFactory
     * @param TaxClassNameRepository $taxClassNameRepository
     * @param FlexFieldBuilder $flexFieldBuilder
     * @param StringUtils $stringUtils
     * @param MapperFactoryProxy $mapperFactory
     */
    public function __construct(
        ItemProcessor $itemProcessor,
        LineItemInterfaceFactory $lineItemFactory,
        TaxClassNameRepository $taxClassNameRepository,
        FlexFieldBuilder $flexFieldBuilder,
        StringUtils $stringUtils,
        MapperFactoryProxy $mapperFactory
    ) {
        $this->itemProcessor = $itemProcessor;
        $this->lineItemFactory = $lineItemFactory;
        $this->taxClassNameRepository = $taxClassNameRepository;
        $this->flexFieldBuilder = $flexFieldBuilder;
        $this->stringUtilities = $stringUtils;
        $this->mapperFactory = $mapperFactory;
    }

    /**
     * @inheritdoc
     */
    public function process(RequestInterface $request, CreditmemoInterface $creditmemo)
    {
        /** @var CreditmemoItemInterface[] $memoItems All Creditmemo items indexed by id */
        $memoItems = [];

        /** @var int[] $productIds All Product IDs in the invoice */
        $productSku = [];

        /** @var LineItemInterface[] $lineItems Vertex SDK LineItems to be returned */
        $lineItems = [];

        foreach ($creditmemo->getItems() as $item) {
            if ($item->getBaseRowTotal() === null) {
                continue;
            }
            $memoItems[$item->getOrderItemId()] = $item;
            $productSku[] = $item->getSku();
        }

        $products = $this->itemProcessor->getProductsIndexedBySku($productSku);

        $storeId = $creditmemo->getStoreId();

        $lineItemMapper = $this->mapperFactory->getForClass(LineItemInterface::class, $storeId);

        /** @var int[] $taxClasses Key is Creditmemo Item's Order Item ID, Value is Tax Class ID */
        $taxClasses = [];

        foreach ($memoItems as $item) {
            $product = $products[$item->getSku()];
            $taxClassAttribute = $product->getCustomAttribute('tax_class_id');
            $taxClassId = $taxClassAttribute ? $taxClassAttribute->getValue() : 0;

            if ($item->getBaseRowTotal() === null) {
                // For bundle products, the parent has a row total of NULL
                continue;
            }

            /** @var LineItemInterface $lineItem */
            $lineItem = $this->lineItemFactory->create();
            $lineItem->setProductCode(
                $this->stringUtilities->substr($item->getSku(), 0, $lineItemMapper->getProductCodeMaxLength())
            );
            $lineItem->setQuantity($item->getQty());
            $lineItem->setUnitPrice(-1 * $item->getBasePrice());
            $lineItem->setExtendedPrice(-1 * ($item->getBaseRowTotal() - $item->getBaseDiscountAmount()));
            $lineItem->setLineItemId($item->getOrderItemId());

            $taxClasses[$item->getOrderItemId()] = $taxClassId;

            if ($lineItem->getExtendedPrice() == 0) {
                continue;
            }
            $lineItem->setFlexibleFields($this->flexFieldBuilder->buildAllFromCreditMemoItem($item, $storeId));
            $lineItems[] = $lineItem;
        }

        /** @var string[int] $taxClassNames Tax Classes indexed by ID */
        $taxClassNames = $this->taxClassNameRepository->getListByIds(array_values($taxClasses));

        foreach ($lineItems as $lineItem) {
            $lineItemId = $lineItem->getLineItemId();
            $taxClass = $taxClasses[$lineItemId];
            $taxClassName = $taxClassNames[$taxClass];
            $lineItem->setProductClass(
                $this->stringUtilities->substr($taxClassName, 0, $lineItemMapper->getProductTaxClassNameMaxLength())
            );
        }

        $request->setLineItems(array_merge($request->getLineItems(), $lineItems));

        return $request;
    }
}
