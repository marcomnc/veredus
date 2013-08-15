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
class Veredus_Veredus_Block_Adminhtml_Catalog_Attribute_Edit_Tab_Color extends Mage_Core_Block_Template
{
    private $_currentAttribute = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('veredus/catalog/product/attribute/tab/color.phtml');
        $this->_currentAttribute = Mage::registry('entity_attribute');
    }
    
    
    public function getAttribute() {
        return $this->_currentAttribute;
    }
}

?>
