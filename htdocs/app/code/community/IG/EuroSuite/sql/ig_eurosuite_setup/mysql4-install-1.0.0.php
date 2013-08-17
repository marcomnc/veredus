<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->startSetup();

$attrCode = 'ig_eurosuite_vat';
$attrSettings = array(
	'label'				=> 'VAT Number',
	'input'				=> 'text',
	'type'				=> 'varchar',
	'backend'			=> null,
	'visible'			=> true,
	'visible_on_front'	=> true,
	'global'			=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
	'required'			=> false
);

$setup->addAttribute('customer_address', $attrCode, $attrSettings);
#$setup->addAttribute('order_address', $attrCode, $attrSettings);
#$setup->addAttribute('quote_address', $attrCode, $attrSettings);

$attrCode = 'ig_eurosuite_fcode';
$attrSettings = array(
	'label'				=> 'Fiscal code',
	'input'				=> 'text',
	'type'				=> 'varchar',
	'backend'			=> null,
	'visible'			=> true,
	'visible_on_front'	=> true,
	'global'			=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
	'required'			=> false
);

$setup->addAttribute('customer_address', $attrCode, $attrSettings);
#$setup->addAttribute('order_address', $attrCode, $attrSettings);
#$setup->addAttribute('quote_address', $attrCode, $attrSettings);

$setup->getConnection()->addColumn($setup->getTable('sales_flat_order_address'), 'ig_eurosuite_vat', 'varchar(255)');
$setup->getConnection()->addColumn($setup->getTable('sales_flat_order_address'), 'ig_eurosuite_fcode', 'varchar(255)');
$setup->getConnection()->addColumn($setup->getTable('sales_flat_quote_address'), 'ig_eurosuite_vat', 'varchar(255)');
$setup->getConnection()->addColumn($setup->getTable('sales_flat_quote_address'), 'ig_eurosuite_fcode', 'varchar(255)');


$eavConfig = Mage::getSingleton('eav/config');
$attribute = $eavConfig->getAttribute('customer_address', 'ig_eurosuite_vat');
$attribute->setData('used_in_forms', array('customer_address_edit', 'customer_account_create', 'adminhtml_customer', 'adminhtml_customer_address', 'customer_register_address'));
$attribute->save();
$attribute = $eavConfig->getAttribute('customer_address', 'ig_eurosuite_fcode');
$attribute->setData('used_in_forms', array('customer_address_edit', 'customer_account_create', 'adminhtml_customer', 'adminhtml_customer_address', 'customer_register_address'));
$attribute->save();


$setup->endSetup();
$installer->endSetup();
