<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Externalws
 *
 * @author Doctor
 */

class myIp {       
    /**     
     * @var string Indirizzo ip da geolocalizzare
     */
    public $IPAddress = "";    
}

class Veredus_Veredus_Helper_Local extends Veredus_Veredus_Helper_Data {
    
    const AllowCountryCacheID = "MPS_ALLOW_COUNTRY";


    /**
     * Recupero la country in base all'IP utilizzando i WS di webservicex.net
     * @param type $ip
     * @param type $debug
     */
    protected function _getGeoIpState($ip = "", $debug = false) {
        //Verifico se ho in sessione la country
        if ($debug) {
            Mage::Log("ip to check $ip", null, '', true);
            Mage::Log($_SERVER, null, '', true);
        }
        if ($ip == "") {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        if ($ip != "") {
            try {
                $ms = new SoapClient('http://www.webservicex.net/geoipservice.asmx?WSDL');
                $myIp = new myIp();
                $myIp->IPAddress = $ip;
                $wsCountry = $ms->GetGeoIP($myIp);
                if ($debug) {
                    Mage::Log($myIp, null, '', true);
                    Mage::Log($wsCountry, null, '', true);
                }
                if ($wsCountry->GetGeoIPResult->ReturnCodeDetails == "Success") {
                        if ($wsCountry->GetGeoIPResult->CountryName != "Reserved") {
                            return $wsCountry->GetGeoIPResult->CountryCode;                            
                        }
                }
            } catch (Exception $e) {
                Mage::LogException($e);
            }
        }
        return false;
    }
    
    public function getCountryFromIp($ip = "") {        
        $country = false;
        $stateIso3 = $this->_getGeoIpState($ip,true);
        if ($stateIso3 !== false) {
            $country = Mage::getModel('directory/country')->Load($stateIso3, 'iso3_code');
        }
        
        if ($country === false && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        	foreach (explode(",", strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $accept) {
            	if (preg_match("!([a-z-]+)(;q=([0-9.]+))?!", trim($accept), $found)) {
                	$langs[] = $found[1];
                	$quality[] = (isset($found[3]) ? (float) $found[3] : 1.0);
                	Mage::log("Found language in request: ".$langs." with quality ".$quality);
            	}
        	}
        	// Order the codes by quality
        	array_multisort($quality, SORT_NUMERIC, SORT_DESC, $langs);
        	// iterate through languages found in the accept-language header
        	foreach ($langs as $lang) {
	            $lang = substr($lang,0,2);
	            foreach (Mage::getModel('core/store')->getCollection() as $_store) {
    				if ((strtolower(Mage::getStoreConfig('general/country/default', $_store)) == strtolower($lang) || 
    					( strtolower($lang) == "en" && strtolower(Mage::getStoreConfig('general/country/default', $_store)) == 'gb' )) &&
    					$_store->getIsActive()) {
    						$country = Mage::getModel('directory/country')->Load(Mage::getStoreConfig('general/country/default', $_store), 'country_id');
    						return $country;
    				}
    			}
        	}
    	}
        
        return $country;
    }
    
    public function getCountryFromCode($code, $codeType = 'country_id') {
        return Mage::getModel('directory/country')->Load($code, $codeType);
    }
    
    public function getStoreFromCountry($country = null) {
        
        if (is_null($country)) {
            $country = $this->getCountryFromIp();
        }
        if (is_string($country)) {
            $country = $this->getCountryFromCode($country);
        }

        if ($country !== false) {
            foreach (Mage::getModel('core/store')->getCollection() as $store) {
                if ($store->getIsActive()) {
                    $countries = Mage::getStoreConfig('general/country/allow', $store);
                    if (strpos(','.$countries.',', ','.  strtoupper($country->getCountryId()).',') !== false) {
                        return $store;
                    }
                }
            }
        }
        return false;
    }
    
    public function getDefaultCountry () {
        $countryCode = Mage::getStoreConfig('veredus/setting/defaul-country');
        $country = false;
        if ($countrCode."" != "") {
            $country = Mage::getModel('directory/country')->Load($countryCode);
        }
        return $country;
    }

    public function getAllowCountry () {
        
        $allowCountry = unserialize(Mage::getModel("core/cache")->load(self::AllowCountryCacheID));
        
        if (!$allowCountry instanceof Varien_Data_Collection) {
            $allowCountry = new Varien_Data_Collection();
            $countryList = ",";
            foreach (Mage::getModel('core/store')->getCollection() as $store) {
                if ($store->getIsActive()) {
                    $countryList .= Mage::getStoreConfig('general/country/allow', $store) . ",";
                }
            }
            
            $order = unserialize( Mage::getStoreConfig('veredus/country/list_order'));

            $ii = 1000;
            foreach (Mage::getModel('directory/country')->getCollection() as $country) {
                if (strpos($countryList, ','.  strtoupper($country->getCountryId()).',') !== false) {
                    $pos = 0;
                    foreach ($order as $value) {
                        if ($value['country_code'] == $country->getId()) {
                            $pos = $value['order'] * 1000;
                            break;
                        }
                    }
                    if ($pos == 0) {
                        $pos = $ii--;
                    }
                    $countryArray[$pos] = $country;                    
                } 
            }
            krsort($countryArray);
            foreach ($countryArray as $country) {
                $allowCountry->addItem($country);
            }
            Mage::getModel("core/cache")->save(serialize($allowCountry),self::AllowCountryCacheID, array(self::AllowCountryCacheID), null);
        }        
        return $allowCountry;
    }
    
    public function CheckCountry($country) {
        
        $status = "";
        $message = "";
        if (!$country instanceof Mage_Directory_Model_Country) {
            $status = get_class($counrty);
            $message = "Oggetto non valido";
        } else {
            $storeList =  array();
            $groupId = array();
            foreach (Mage::getModel('core/store')->getCollection() as $store) {
                if ($store->getIsActive()) {
                    $countries = Mage::getStoreConfig('general/country/allow', $store);
                    if (strpos(','.$countries.',', ','.  strtoupper($country->getCountryId()).',') !== false) {
                        $storeList[] = $store;
                        if (!in_array($store->getGroupId(),$groupId)) {
                            $groupId[] = $store->getGroupId();
                        }
                    }
                }
            }
        }
        
        
        if (sizeof($storeList) == 0) {
            $status = $this->__("Warning");
            $message = $this->__("Stato non attivo");
        } else{
            if(sizeof($groupId)>1) {
                $status = $this->__("Error");
                $message = $this->__("Stato appartente a più gruppi listino ");
                $first = true;
                foreach ($storeList as $store) {
                    if ($first) {
                        $first = false;
                        $message .= " - "; 
                    }
                    
                    $message .=" " . $store->getName()."/".$store->getGroup()->getName();
                }
            } else {
                foreach (Mage::getModel('core/store')->getCollection() as $store) {
                    if (in_array($store->getGroupId(), $groupId)) {
                        $found = false;
                        foreach ($storeList as $st) {
                            if ($store->getId() == $st->getId()) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $status = $this->__('Warning');
                            if ($message == "") {
                                $message = "Non assegnato allo store";
                            }
                            $message .= " " . $store->getName() . "/". $store->getGroup()->getName();
                        }
                    }
                }
            }
        }

        $return = "";
        if ($status != "") {
            $return = $status . " : ";
        }
        if ($message != "") {
            $return .= $message;
        }

        return $return;
        
    }
    
    
}

?>
