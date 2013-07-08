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
class Veredus_Social_Helper_Fb extends Mcgroup_Social_Helper_Data
{
    
    protected $_isEnable;
    protected $_appId;
    protected $_secretKey;


    public function __construct() {        
        parent::__construct();
        $this->_isEnable =  (Mage::getStoreConfig('vsocial/settings/enabled',Mage::app()->getStore()->getId()) == '1') ? true : false;                
        $this->_appId =  Mage::getStoreConfig('vsocial/settings/fb_appId',Mage::app()->getStore()->getId())."";
        $this->_secretKey = Mage::getStoreConfig('vsocial/settings/fb_secretKey',Mage::app()->getStore()->getId())."";
    }
    
    public function isEnabled() {
        return $this->_isEnable;
    }
    
    public function getAppId() {
        return $this->_appId;        
    }
    
    public function getSecretKey() {
        return $this->_secretKey;
    }
    
}
?>
