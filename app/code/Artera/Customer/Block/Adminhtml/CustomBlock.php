<?php

namespace Artera\Customer\Block\Adminhtml;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Backend\Block\Template;

class CustomBlock extends Template
{
    protected $_urlInterface;
    private $scopeconfig;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\UrlInterface $urlInterface,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_urlInterface = $urlInterface;
        $this->scopeconfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getUrlInterfaceData()
    {
        return $this->_urlInterface->getUrl();
    }
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
    public function getvalue()
    {
        return $this->scopeconfig->getValue('Firstsection/Firstgroup/enable');
    }
}
