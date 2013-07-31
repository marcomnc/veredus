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
class Veredus_Veredus_Block_Widget_Product_List extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface 
{
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('veredus/template/widget/product/list.phtml');
        
    }

    protected function _beforeToHtml() {
        
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        $attrToSelect = Mage::getSingleton('catalog/config')->getProductAttributes();
        array_push($attrToSelect, "image");
        array_push($attrToSelect, "image_view_home");
        
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($attrToSelect)
            ->addUrlRewrite()
            ->addStoreFilter()
            ->addAttributeToFilter($this->getData('attribute_home_product'), 1);
        
        $collection->getSelect()->reset(Zend_Db_Select::LIMIT_OFFSET)
                ->limit(($this->getRow() * $this->getColumn()), 0);
        
        $this->setProductCollection($collection);
        
        parent::_beforeToHtml();
    }


    protected function _toHtml() {
        return parent::_toHtml();
    }
    
    
}
?>
