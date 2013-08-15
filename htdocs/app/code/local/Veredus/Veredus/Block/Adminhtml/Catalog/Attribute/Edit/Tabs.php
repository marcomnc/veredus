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
class Veredus_Veredus_Block_Adminhtml_Catalog_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tabs {
    
    protected function _beforeToHtml(){
        
        parent::_beforeToHtml();

        $colorAttribute = MAge::Helper('veredus/media')->getAttributeColorArray();
        if ($colorAttribute !== false) {
            
            $model = Mage::registry('entity_attribute');

            if (array_search($model->getAttributeCode(), $colorAttribute) !== false) {    

                $this->addTab('colors', array(
                    'label'     => Mage::helper('veredus')->__('Manage Color'),
                    'title'     => Mage::helper('veredus')->__('Manage Color'),
                    'content'   => $this->getLayout()->createBlock('veredus/adminhtml_catalog_attribute_edit_tab_color')->toHtml(),
                ));
                
                Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
            }
            
            
            
        }
                
    }
    
    
}

?>
