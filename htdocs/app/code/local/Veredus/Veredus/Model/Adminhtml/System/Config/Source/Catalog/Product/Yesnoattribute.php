<?php


/**
 *
 * Option Array con gli attributi immagini 
 *
 * @category    Custom
 * @package     Mage_Catalog
 * @author      Marco Mancinelli
 * 
 */

class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Catalog_Product_Yesnoattribute {
    
    public function toOptionArray() {
        $_attList =  $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                        ->addFieldToFilter('frontend_input',array("in" => array("select","boolean")))
                        ->addStoreLabel(Mage::app()->getStore()->getId())
                        ->setOrder('main_table.attribute_id', 'asc')
                        ->load();
        $_retArray = array();
        $_retArray[] = array ('value' => null, 'label' => Mage::Helper('veredus')->__('Seleziona Attributo'));
        foreach ($_attList as $_att) {
            $_retArray[] = array ('value' => $_att->getAttributeCode(), 
                                  'label' => $_att->getFrontendLabel());
        }
        return $_retArray;
    }
    
}

?>
