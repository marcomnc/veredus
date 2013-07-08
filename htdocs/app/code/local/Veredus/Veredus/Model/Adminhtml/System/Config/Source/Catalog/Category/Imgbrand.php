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

class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Catalog_Category_Imgbrand {
    
    public function toOptionArray() {
        $_retArray[] = array ('value' => "img", 'label' => Mage::Helper('veredus')->__('Immagine'));
        $_retArray[] = array ('value' => "thm", 'label' => Mage::Helper('veredus')->__('Thumbnail'));
        return $_retArray;
    }
    
}

?>
