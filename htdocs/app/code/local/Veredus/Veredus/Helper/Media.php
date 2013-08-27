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
class Veredus_Veredus_Helper_Media extends Veredus_Veredus_Helper_Data {
    
    /**
     * Ritorna l'array con gli attributi che rappresentano i colori
     * @return type
     */
    public function getAttributeColorArray() {
        return $this->_getAttributeArray('color');
    }
    
    /**
     * Ritorna l'array con gli attributi che rappreentano le taglie
     * @return type
     */
    public function getAttributeSizeArray() {
        return $this->_getAttributeArray('size');
    }
    
    /**
     * Ritorna la lista delle opzioni di un attriubuto
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @return type
     */
    public function getAttributeOptions($attribute) {
        
        $ret = array();
        if ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute) {
            
            $ret = $attribute->getSource()->getAllOptions(false);
        }
        return $ret;
    }
    
    public function getImageUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)  . 'mps/colormanager/';
    }
    
    /**
     * Data un id opzione di ritorna il percorso dell'immagine relativa
     * @param type $optionId
     * @return string
     */
    public function getImageColorUrl($optionId, $imgFormat = 'jpg')
    {
        $uploadDir = $this->getUploadDir();
        if (file_exists($uploadDir . $optionId . '.' . $imgFormat))
        {
            return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)  . 'mps/colormanager/' . $optionId . '.' . $imgFormat;
        }
        return '';
    }
    
    /**
     * Cancello il file immagine selezionato
     * @param type $filename
     */
    public function deleteImageFile($filename) {
        $file = $this->getUploadDir() . $filename;
        if (file_exists($file)) {
            if (!unlink($file)) {
                Mage::throwException($this->__('errore in fase di cancellazione di') . " $file");
            }
        } else {
            Mage::throwException($this->__('File da cancellare inesistente') ." ($file)");
        }
    }
    
    /**
     * Legge la lista dei colori associati ad un articolo
     * @param type $product
     * @param type $attName
     * @return type
     */
    public function loadColorList($product, $attName) {
        
        $list = array();
        
        foreach ($product->getTypeInstance(true)->getConfigurableAttributesAsArray($product) as $att) {
            if ($attName == $att['attribute_code']) {
                foreach ($att['values'] as $val) {
                    $list[$val['value_index']] = $val['store_label'];
                }
            }
         }
        
        return $list;
        
    }

    /**
     * Verifica se un'immagine deve essere sempre visibile per tutti i colori
     * @param type $product
     * @param type $imgFile
     * @return type
     */
    public function AlwayEnable ($product, $imgFile) {
        
        return ($this->getAssociateColorValue($product, $imgFile) == '');
        
    }
    
    /**
     * Ritorna i codice colore associato ad un immagine
     * @param type $product
     * @param type $imgFile
     * @return type
     */
    public function getAssociateColorValue($product, $imgFile) {
        
        $value = '';
        if ($product->hasMpsColorSwitcher()) {
            $colors = unserialize($product->getMpsColorSwitcher());

            foreach ($colors['Type'] as $val) {
                
                if ($val['key'] == $imgFile) {
                    $value = $val['value'];
                    break;
                }            
            }
        } 
        return $value;
        
    }
    
    public function IsDefault($product, $imgFile) {
        $value = false;
        if ($product->hasMpsColorSwitcher()) {
            $colors = unserialize($product->getMpsColorSwitcher());

            foreach ($colors['Default'] as $val) {
                
                if ($val['key'] == $imgFile) {
                    $value = $val['value'];
                    break;
                }            
            }
        } 
        return ($value)? 1: 0;
    }


    public function getUploadDir() {
        return Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'mps' . DS . 'colormanager' . DS;
    }
    
    private function _getAttributeArray($type) {
        if (Mage::getStoreConfig("veredus/settings/attribute_$type") != "") {
            return preg_split('/,/', Mage::getStoreConfig("veredus/settings/attribute_$type"));
        } else {
           return false;
        }
    }
    
    
}

?>
