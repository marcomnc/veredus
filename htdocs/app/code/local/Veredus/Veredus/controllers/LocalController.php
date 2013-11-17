<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocaleController
 *
 * @author Doctor
 */
class Veredus_Veredus_LocalController extends Mage_Core_Controller_Front_Action
{
    public function setcountryAction() {
        $params = $this->getRequest()->getParams();
        $return = array('store' => '',
                        'name' => '',
                        'rewrite' => '');
        if (isset($params['code'])) {
            $store = Mage::Helper('veredus/local')->getStoreFromCountry($params["code"]);
            Mage::getSingleton('customer/session')->setCountryShipping(Mage::Helper('veredus/local')->getCountryFromCode($params["code"]));
            //Contorllo che lo store sia dello stesso gruppo, altrimenti rileggo
            $currentStore = Mage::app()->getStore();
            if ($store instanceof Mage_Core_Model_Store && $store->getGroupId() != $currentStore->getGroupId()) {
                $return['store'] = $store->getCode();
                if (Mage::getStoreConfig('web/url/use_store')) {
                    $return['rewrite'] = str_replace($currentStore->getBaseUrl(), $store->getBaseUrl(), $this->_getRefererUrl());                    
                }
                
            }
            $return['name'] = Mage::getModel('directory/country')->Load($params['code'], 'country_id')->getName();            
        } 
        
        $this->getResponse()->setBody(json_encode($return));
    }
    
    public function localajaxAction() {
        $str = $this->getRequest()->getPost('billing:city');
        $country = $this->getRequest()->getPost('country');
        
        $read = Mage::getSingleton('core/resource')->getConnection('core_read'); 
        $select = "SELECT comune FROM veredus_comuni WHERE comune like '$str%' AND country_id = '$country'";
        
        $fetch = $read->FetchAll($select);
        
        $html = "";
        if (sizeof($fetch) > 0) {
         
            $html = "<UL>";

            foreach ($fetch as $record) {

                $html .= "<LI>" . $record['comune'] . "</LI>";
                
            }
            
            $html .= "</UL>";
        }
        
        $this->getResponse()->setBody($html);
    }
}

?>
