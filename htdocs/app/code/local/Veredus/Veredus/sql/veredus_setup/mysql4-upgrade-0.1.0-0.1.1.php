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
$option['order']['catalog_product_view'] = 0;
$option['value']['sendfriend_product'][0] = 'Prodotti inviati ad amici';
$option['order']['sendfriend_product'] = 1;
$option['value']['catalog_product_compare_add_product'][0] = 'Prodotti aggiunti al confronto';
$option['order']['catalog_product_compare_add_product'] = 2;
$option['value']['checkout_cart_add_product'][0] = 'Prodotti aggiunti al carrello';
$option['order']['checkout_cart_add_product'] = 3;
$option['value']['wishlist_add_product'][0] = 'Prodtti aggiunti alla wish list';
$option['order']['wishlist_add_product'] = 4;
$option['value']['wishlist_share'][0] = 'Wishlist condivise';
$option['order']['wishlist_share'] = 5;

$installer->addAttributeOption($option);

$installer->endSetup();

?>
