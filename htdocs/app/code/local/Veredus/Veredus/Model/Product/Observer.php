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

                        $result = $uploader->save(Mage::helper('veredus/media')->getUplaodDir(), "$id.jpg" );

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
    
}

?>
