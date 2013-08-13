<?php

$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_product');
$attributeSetIds   = $installer->getAllAttributeSetIds($entityTypeId);

// Creo l'attributo immagine saldi
$installer->addAttribute('catalog_product', 'img_saldi',  array(
    'type'     => 'varchar',
    'label'    => 'Immagine Saldi',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
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
            'img_saldi'
        );
    }
}

$installer->endSetup();

?>
