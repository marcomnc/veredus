<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *
 * @category    
 * @package        
 * @copyright   Copyright (c) 2013 Mps Sistemi (http://www.mps-sistemi.it)
 * @author      MPS Sistemi S.a.s - Marco Mancinelli <marco.mancinelli@mps-sistemi.it>
 *
 */
class Veredus_Veredus_Model_Product_Observer {
    
    /**
     * Salvataggio 
     * @param type $observer
     */
    public function on_attribute_after_save($observer) {
        
        $data = $observer->getAttribute()->getData();
        
        //Carico eventuali file
        if(isset($_FILES['filename']['name']) && is_array($_FILES['filename']['name'])) {
            foreach ($_FILES['filename']['name'] as $id => $name) {
                if (($name . "") != "") {
                    try {
                        $uploader = new Varien_File_Uploader("filename[$id]");

                        $uploader->setAllowedExtensions(array('jpg'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);

                        $result = $uploader->save(Mage::helper('veredus/media')->getUploadDir(), "$id.jpg" );

                    } catch (Exception $ex) {
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('veredus/media')->__($ex->getMessage()));
                    }
                }
            }
        }
        
        //Verfifico se devo cancellare l'immagine
        if (isset($data['filename_delete']) && is_array($data['filename_delete'])) {
            foreach ($data['filename_delete'] as $id => $value) {
                try {
                    Mage::Helper('veredus/media')->deleteImageFile("$id.jpg");
                } catch (exception $ex) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('veredus/media')->__($ex->getMessage()));
                }
            
            }
        }
    }
    
    /**
     * Assegno al prodotto l'attributo mps_color_switcher
     * @param type $observer
     */
    public function on_product_prepare_save($observer) {
        
        $product = $observer->getProduct();
        $request = $observer->getRequest();
        
        try {
            $data = $request->getPost('product');
            Mage::log($data);
            if (isset($data['media_gallery']['colors']) && $data['media_gallery']['colors'] != '') {
                $colors = Mage::Helper('core')->jsonDecode($data['media_gallery']['colors']);
                Mage::log($colors);
                $product->setData('mps_color_switcher', serialize($colors));
                $observer->setProduct($product);
            }
        } catch (Exception $ex) {
            Mage::logException($ex);
        }
            
    }
    
    /**
     * Calacolo automatico delle categorie legate ad indici
     */
    public function scheduledIndex() {
        
        $categoryLayred = Mage::getStoreConfig('veredus/settings/shop_by_plus') + 0;
        
        $optionList = Mage::getModel('eav/entity')
                        ->setType(Mage::getModel('eav/entity_type')->LoadByCode('catalog_category')->getId())
                        ->getAttribute('idx_type')
                        ->getSource()->getAllOptions(false);
//        echo "<pre>";
//        var_dump ($optionList);
//        var_dump ();
//        echo "</pre>";
        if ($categoryLayred > 0) {
            $parent = Mage::getModel('catalog/category')->getCollection();
                
            $parent->getSelect()
                   ->Where('e.parent_id = ?', $categoryLayred);
            
                      
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            foreach ($parent as $children) {
//                echo "--- " . $children->getId() . "<br>";
                if ($categoryLayred != $children->getId()) {
                    
                    $category = Mage::getModel('catalog/category')->Load($children->getId());
                    $idxType = $category->getIdxType();
//                    echo "idx $idxType<br>";
                    foreach ($optionList as $k => $v) {
                        if ($idxType == $v['value']) {
                            
                            $elabType = Mage::Helper('veredus/index')->getElabType($k);
                            
//                            echo "category: " . $category->getName() . "<br>";
//                            echo "Elab: $elabType<br>";
//                            echo "Prototti da Associare<br><pre>";                            
//                            print_r(Mage::Helper('veredus/index')->getProductReport($elabType));
//                            echo "<pre><br>";
                            
                            $productList = Mage::Helper('veredus/index')->getProductReport($elabType);
                            
                            $category->setPostedProducts($productList);
                            $category->setIsActive((sizeof($productList) == 0) ? false: true );
                            $category->save();                            
                        }
                    }                                    
                    
                }
                
            }
        }      
        
    }
        
}

?>
