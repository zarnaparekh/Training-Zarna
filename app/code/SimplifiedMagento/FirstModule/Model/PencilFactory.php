<?php


namespace SimplifiedMagento\FirstModule\Model;


use function PHPSTORM_META\type;

class PencilFactory
{
    private $objectManager;
    public function __construct( \Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(array $data)
    {
        return $this->objectManager->create('SimplifiedMagento\FirstModule\Api\PencilInterface',$data);
    }
}