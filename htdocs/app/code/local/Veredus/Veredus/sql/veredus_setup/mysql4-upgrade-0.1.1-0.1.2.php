<?php

$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_product');
$attributeSetIds   = $installer->getAllAttributeSetIds($entityTypeId);

// Creo l'attributo Zoom 360 Url
$installer->addAttribute('catalog_product', 'zoom_360_url',  array(
    'type'     => 'varchar',
    'label'    => 'Url Zoom 360',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => ''
));

if (is_array($attributeSetIds)) {
    foreach ($attributeSetIds as $attributeSetId) {
        
        // Se c'è il gruppo design aggiunfo l'attributo zoom URL
        $installer->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            'Design',
            'zoom_360_url'
        );
    }
}
$installer->endSetup();

?>
