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
class Veredus_Veredus_Block_Template_Home extends Mage_Core_Block_Template {
    
    const HOME_BLOCK_TEMPLATE_CACHE_KEY = '_template_home_page_';
    
    protected $_block = array();
    protected $_cache = null;
    
    protected function _construct() {
        parent::_construct();
        
        $this->_cache = Mage::getModel("core/cache");        
        $this->_block = unserialize($this->_cache->load(self::HOME_BLOCK_TEMPLATE_CACHE_KEY . Mage::app()->getStore()->getId()));
Mage::Log($this->_block);        
        for ($i = 1; $i <= 3; $i++) {
            if (!isset($this->_block['id'][$i]) && Mage::getStoreConfig("veredus/home/cms-block-$i").'' != '') {
                $this->_block['id'][$i] = Mage::getStoreConfig("veredus/home/cms-block-$i").'';
            
            }
        }
        
    }
    
    public function isBlock($blockId) {
        return isset($this->_block['id'][$blockId]);
    }
    
    public function getBlockHtml($blockId) {
        
        $html = "";
    
        if (!isset($this->_block['html'][$blockId])) {        
            if ($this->isBlock($blockId)) {
                $block = $this->getLayout()->createBlock('cms/block')->setBlockId($this->_block['id'][$blockId]);
                if ($block instanceof Mage_Cms_Block_Block) {
                    $html = $block->toHtml();
                    $this->_block['html'][$blockId] = $html;
                }
            }
            $this->_cache->save(serialize($this->_block),self::HOME_BLOCK_TEMPLATE_CACHE_KEY . Mage::app()->getStore()->getId(), array(self::HOME_BLOCK_TEMPLATE_CACHE_KEY . Mage::app()->getStore()->getId()), null);
        } else {
            $html = $this->_block['html'][$blockId]; 
        }
        return $html;
    }
    
}

?>
