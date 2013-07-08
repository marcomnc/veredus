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

//Import della libreria facebook
require_once BP . DS . 'lib'. DS . 'facebook-php-sdk' . DS . 'src' . DS . 'facebook.php';


class Veredus_Social_Model_Fbconnect extends Mage_Core_Model_Abstract {
    
    protected $_facebook;
    protected $_userProfile;


    public function __construct() {
        $this->_facebook = new Facebook(array(
                          'appId'  => Mage::helper("vsocial/fb")->getAppId(),
                          'secret' => Mage::helper("vsocial/fb")->getSecretKey(),
                          'cookie' => true
                        ));
        $user = $this->_facebook->getUser();
        if ($user) {
            try {
                $this->_userProfile = $this->_facebook->api('/me');
            } catch (FacebookApiException $e) {
                Mage::log($e, 'ERROR');
                $this->_userProfile = null;
            }
        }
        parent::__construct();
    }
    
    public function isFbConnect() {
        return (!is_null ($this->_userProfile))?true:false;
    }
    
    public function getFbMail() {
        return (array_key_exists('email', $this->_userProfile))?$this->_userProfile['email']:null;
    }
    
    public function getFbUserProfileInfo ($infoKey) {
        return (array_key_exists($infoKey, $this->_userProfile))?$this->_userProfile[$infoKey]:null;
    }


    public function getUserBasicInfo(){
        $arrUserInfo = array();
        $arrUserInfo['first_name'] = $this->_userProfile['first_name'];
        $arrUserInfo['last_name'] = $this->_userProfile['last_name'];
        $arrUserInfo['birthday'] = $this->_userProfile['birthday'];
        $arrUserInfo['gender'] = $this->_userProfile['gender'];
        $arrUserInfo['email'] = $this->_userProfile['email'];
        $arrUserInfo['uid'] = $this->_userProfile['id'];
        return $arrUserInfo;
    }
}