<?php

namespace Artera\Customer\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;

class InitializeDefaultBrands implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * Example of implementation:
     *
     * [
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch1::class,
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch2::class
     * ]
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [
            \Magento\Store\Setup\Patch\Schema\InitializeStoresAndWebsites::class
        ];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [
            \Artera\Customer\Setup\Patch\Data\CreateDefaultBrands::class
        ];
    }

    /**
     * Run code inside patch
     * If code fails, patch must be reverted, in case when we are speaking about schema - then under revert
     * means run PatchInterface::revert()
     *
     * If we speak about data, under revert means: $transaction->rollback()
     *
     * @return $this
     */
    public function apply()
    {
        /*$brands = [
            ['name' => 'Nike', 'description' => 'Cool'],
            ['name' => 'Puma', 'description' => 'Cooler'],
            ['name' => 'Woodland', 'description' => 'More Cool'],
        ];
        $records = array_map(function ($brand) {
            return array_merge($brand, ['is_enabled' => 1, 'website_id' => 1]);
        }, $brands);

        $this->moduleDataSetup->getConnection()->insertMultiple('example', $records);
        return $this;*/
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'alternative_color',
            [
                'group' => 'Content',
                'type' => 'int',
                'label' => 'Alternative Color',
                'input' => 'text',
                'used_in_product_listing' => true,
                'user_defined' => true,
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' =>true
        ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'is_show',
            [
                'group' => 'Content',
                'type' => 'text',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' =>true,
                'required' => false,
                'searchable' => false,
                'used_in_product_listing' => true,
                'label' => 'Show Enabled',
                'input' => 'select',
                'source' => \Artera\Customer\Model\Config\Options::class
            ]
        );

        $eavSetup->addAttribute(
            Customer::ENTITY,
            'custom_eav',
            [
                'type' => 'text',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' =>true,
                'required' => false,
                'searchable' => false,
                'label' => 'Custom_Eav',
                'input' => 'text'
            ]
        );
    }
}
