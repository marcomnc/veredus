<?php

$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_product');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);

// Creo l'attributo Immagine brand
$installer->addAttribute('catalog_product', 'brand_img',  array(
    'type'     => 'varchar',
    'label'    => 'Img Brand',
    'input'    => 'media_image',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => ''
));

$installer->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            'Images',
            'brand_img'
        );

$installer->endSetup();

?>
