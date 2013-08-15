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
class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Catalog_Product_Attribute_Select {
    
    public function toOptionArray() {
        $_attList =  $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                        ->addFieldToFilter('frontend_input',array("in" => array("select")))
                        ->addStoreLabel(Mage::app()->getStore()->getId())
                        ->setOrder('main_table.attribute_id', 'asc')
                        ->load();
        $_retArray = array();
        foreach ($_attList as $_att) {
            $_retArray[] = array ('value' => $_att->getAttributeCode(), 
                                  'label' => $_att->getFrontendLabel());
        }
        return $_retArray;
    }
    
}
?>
