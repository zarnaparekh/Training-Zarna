<?php

namespace SimplifiedMagento\FirstModule\Controller\Page;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use SimplifiedMagento\FirstModule\Api\PencilInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use SimplifiedMagento\FirstModule\Model\PencilFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\App\Request\Http;
use SimplifiedMagento\FirstModule\Model\HeavyService;

class HelloWorld extends \Magento\Framework\App\Action\Action
{

    protected $pencilInterface;
    protected $productRepository;
    protected $pencilFactory;
    protected $productFactory;
    protected $_eventManager;
    protected $http;
    protected $heavyService;

    public function __construct(Context $context,
                                Http $http,
                                HeavyService $heavyService,
                                ProductFactory $productFactory,
                                PencilFactory $pencilFactory,
                                PencilInterface $pencilInterface,
                                ProductRepositoryInterface $productRepository,
                                ManagerInterface $_eventManager)
    {
       $this->pencilFactory = $pencilFactory;
       $this->pencilInterface = $pencilInterface;
       $this->productRepository = $productRepository;
       $this->productFactory = $productFactory;
       $this->_eventManager = $_eventManager;
       $this->http = $http;
       $this->heavyService = $heavyService;
       parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        //echo $this->pencilInterface->getPencilType();
        //echo get_class($this->productRepository);

       /* $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $pencil = $objectManager->create('SimplifiedMagento\FirstModule\Model\Pencil');
        var_dump($pencil);*/
        /*$book = $objectManager->create('SimplifiedMagento\FirstModule\Model\Book');
        var_dump($book);

        $student = $objectManager->create('SimplifiedMagento\FirstModule\Model\Student');
        var_dump($student);*/

        /*$pencil = $this->pencilFactory->create(array('name'=>'Alex','school'=>'International School'));
        var_dump($pencil);*/

        /*$product = $this->productFactory->create()->load( 1);
        $product->setName("Iphone 6");
        $productName = $product->getIdBySku("WJ06-XS-Blue");*/
        //echo $productName;
        //echo "main method"."</br>";

        /*$message = new \Magento\Framework\DataObject(array("greeting"=>"good afternoon"));
        $this->_eventManager->dispatch('custom_event',["greeting"=>$message]);
        echo $message->getGreeting();*/

        $id = $this->http->getParam( 'id', 0);
        if($id == 1)
        {
             $this->heavyService->getheayservicemessge();
        }
        else
        {
            echo "heavy service not used";
        }
    }
}
