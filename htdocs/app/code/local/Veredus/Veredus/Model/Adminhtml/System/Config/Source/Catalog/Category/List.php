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

class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Catalog_Category_List {
    
    public function toOptionArray() {
        $category = Mage::getModel('catalog/category'); 
        $tree = $category->getTreeModel(); 
        $tree->load();

        $ids = $tree->getCollection()->getAllIds(); 
        $_retArray = array();
        $_retArray[] = array ('value' => null, 'label' => Mage::Helper('veredus')->__('Seleziona Categoria'));
        foreach ($ids as $id) {
            $cat = Mage::getModel('catalog/category')->Load($id);
            $_retArray[] = array ('value' => $id, 
                                  'label' => $cat->getName());
        }
        return $_retArray;
    }
    
}

?>
