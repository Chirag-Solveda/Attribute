<?php

namespace Chirag\Attribute\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;


class InstallData implements InstallDataInterface
{

    private $attributeSetFactory;
    protected $customerSetupFactory;

public function __construct(
    AttributeSetFactory $attributeSetFactory,
    CustomerSetupFactory $customerSetupFactory
   

)


{
  $this->attributeSetFactory = $attributeSetFactory;
  $this->customerSetupFactory =$customerSetupFactory;
}

public function install(ModuleDataSetupInterface $setup , ModuleContextInterface $context)
{

$customerSetup = $this ->customerSetupFactory->create(['setup' => $setup]);

$customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
$attributeSetId = $customerEntity->getDefaultAttributeSetId();

$attributeSet = $this->attributeSetFactory->create();
$attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

$customerSetup->addAttribute(Customer::ENTITY,'gst', [
    'type' => 'varchar',
    'label' => 'GST NUMBER',
    'input' => 'varchar',
    'user_defined' => true,
    'visible' => true,
    'required' => true,
    'position' => 10,
    'sort_order' => 10,
    'system' => 0,
    'is_used_in_grid' => true,
    'is_html_allowed_on_front' => false,
    'is_visible_in_grid' => true,
    'unique' => true,
    'visible_on_front' => true
]);

$attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY,'gst')
->addData([
    'attribute_set_id' => $attributeSetId,
    'attribute_group_id' => $attributeGroupId,
    'used_in_forms' => ['adminhtml_customer' , 'customer_account_edit']
]);

   $attribute->save();
}


public static function getDependencies()
{
    return [];
}

/**
 * {@inheritdoc}
 */
public function getAliases()
{
    return [];
}

/**
* {@inheritdoc}
*/
public static function getVersion()
{
   return '2.0.0';
}


}