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
class Veredus_Social_Block_Fbconnect extends Mcgroup_Social_Block_Fbabstract {
    
    protected $_fbSettings = array();
    
    public function __construct() {        
        $this->_fbSettings['appId'] = Mage::helper("vsocial/fb")->getAppId();
        $this->_fbSettings['secretKey'] = Mage::helper("vsocial/fb")->getSecretKey();
        parent::__construct(); 
    }
    
    public function getFBSettings() {
        return $this->_fbSettings;        
    }
    
    public function getFBSetting($key) {    
        return (array_key_exists($key,$this->_fbSettings))?$this->_fbSettings[$key]:null;        
    }
    
    public function getFBConnectUrl () {
        return Mage::getUrl('vsocial/fbconnect') . '?url='. Mage::helper('core/url')->getCurrentUrl();        
    }

    public function getFBAjaxConnectUrl () {
        return Mage::getUrl('vsocial/fbconnect') . 'ajaxindex?url='. Mage::helper('core/url')->getCurrentUrl();        
    }
    public function getImageLoading () {
        return $this->getSkinUrl('images/social/fb_loader.gif');
    }
}
?>
