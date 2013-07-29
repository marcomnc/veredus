<?php

$installer = $this;
$installer->startSetup();
$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
$installer->addAttribute('catalog_category', 'idx_type',  array(
    'type'     => 'int',
    'label'    => 'Index Type',
    'input'    => 'select',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0
));
$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'new_cat_attrb',
    '11'                    //last Magento's attribute position in General tab is 10
);
$attributeId = $installer->getAttributeId($entityTypeId, 'idx_type');
$option['attribute_id'] = $attributeId;
$option['value']['catalog_product_view'][0] = 'Prodotti più visti';
$option['value']['sendfriend_product'][0] = 'Prodotti inviati ad amici';
$option['value']['catalog_product_compare_add_product'][0] = 'Prodotti aggiunti al confronto';
$option['value']['checkout_cart_add_product'][0] = 'Prodotti aggiunti al carrello';
$option['value']['wishlist_add_product'][0] = 'Prodtti aggiunti alla wish list';
$option['value']['wishlist_share'][0] = 'Wishlist condivise';

$installer->addAttributeOption($option);

$installer->endSetup();

?>
