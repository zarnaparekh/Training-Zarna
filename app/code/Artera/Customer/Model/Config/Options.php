<?php

namespace Artera\Customer\Model\Config;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Options extends AbstractSource
{
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Yes'),'value'=>'yes'],
            ['label' => __('No'),'value'=>'no'],
        ];
        return $this->_options;
    }
}
