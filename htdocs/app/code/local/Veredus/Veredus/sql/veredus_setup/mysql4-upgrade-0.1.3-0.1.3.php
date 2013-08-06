<?php

$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_product');
$attributeSetIds   = $installer->getAllAttributeSetIds($entityTypeId);

// Creo l'attributo Manutenzione
$installer->addAttribute('catalog_product', 'care_info',  array(
    'type'     => 'text',
    'label'    => 'Manutenzione',
    'input'    => 'textarea',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => ''
));

// Creo l'attributo Informazioni spedizioni
$installer->addAttribute('catalog_product', 'shipping_info',  array(
    'type'     => 'text',
    'label'    => 'Info Spedizione',
    'input'    => 'textarea',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => ''
));

$installer->endSetup();

?>
