<?php

namespace Artera\Customer\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;

class GetData extends Action
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;
    protected $_urlInterface;

    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_urlInterface = $urlInterface;
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $order_id = $this->getRequest()->getParam('get_order_data');
        $product_id = $this->getRequest()->getParam('get_product_data');
        $customer_id = $this->getRequest()->getParam('get_customer_data');

        $resultRedirect = $this->redirectFactory->create();
        if ($order_id == ''  && $product_id == '' && $customer_id == '') {
            $url = $this->_redirect->getRefererUrl();
            $resultRedirect->setUrl($url);
            return $resultRedirect;
        } else {
            if (!empty($order_id)) {
                $resultRedirect->setPath('sales/order/view/order_id/' . $order_id);
                return $resultRedirect;
            }
            if (!empty($product_id)) {
                $resultRedirect->setPath('catalog/product/edit/id/' . $product_id);
                return $resultRedirect;
            }
            if (!empty($customer_id)) {
                $resultRedirect->setPath('customer/index/edit/id/' . $customer_id);
                return $resultRedirect;
            }
        }
    }
}
