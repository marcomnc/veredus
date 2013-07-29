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

class Veredus_Veredus_Block_Adminhtml_Catalog_Product_List_Type extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract 
{   
    
    protected $_layoutType = array();
    protected $_attributeList = array();


    
    public function __construct()
    {     
        $this->_layoutType =array('color' => Mage::helper('veredus')->__('Title'),
                                  'size' => Mage::helper('veredus')->__('Cms Block')
                                  );
        $this->addColumn('attribute', array(
            'label' => Mage::helper('veredus')->__('Title'),
            'style' => 'width:200px',                        
        ));
        
        $this->addColumn('type', array(
            'label' => Mage::helper('veredus')->__('Cms Block Id'),
            'style' => 'width:200px',
        ));
        
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('veredus')->__('Add Catalog Search');
        parent::__construct();
    }
}

?>
