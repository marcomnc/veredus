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

class Veredus_Veredus_Block_Adminhtml_Catalog_Product_Helper_Form_Gallery_Content extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content {
    
    protected $_colorValueList = array();
    protected $_masterColorAttribute = '';
    
    public function __construct()
    {
        parent::__construct();        
                
        $this->setData('product', Mage::registry("product"));
        
        $atrtibuteList = $this->getProduct()->getAttributes($this->getProduct()->getAttributeSetId());
        
        $colorAttribute = MAge::Helper('veredus/media')->getAttributeColorArray();        

        if ($this->getProduct()->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE && $colorAttribute !== false) {
            // && array_search('mps_color_switcher', $atrtibuteList) !== false) {
            
            foreach ($this->getProduct()->getTypeInstance(true)->getConfigurableAttributes($this->getProduct()) as $att) {
                if (array_search($att->getProductAttribute()->getAttributeCode(), $colorAttribute) !== false) { 
                    $this->setTemplate('veredus/catalog/product/helper/gallery.color.phtml');
                    $this->masterColorAttribute = $att->getProductAttribute()->getAttributeCode();
                    $this->_colorValueList = Mage::Helper('veredus/media')->loadColorList($this->getProduct(), $att->getProductAttribute()->getAttributeCode());
                    
                    break;
                }
            };
        }        
        
    }
    
    public function getColorList() {
        return $this->_colorValueList;
    }
    
    public function getColorsValuesJson() {
        if ($this->getProduct()->getData('mps_color_switcher') != "")
            return Mage::Helper('core')->JsonEncode(unserialize($this->getProduct()->getData('mps_color_switcher')));
        else            
            return '';
    }

}

?>
