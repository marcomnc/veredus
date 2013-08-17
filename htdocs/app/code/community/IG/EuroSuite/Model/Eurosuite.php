<?php 
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.idealiagroup.com/magento-ext-license.html
 *
 * @category   IG
 * @package    IG_EuroSuite
 * @copyright  Copyright (c) 2010-2011 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://www.idealiagroup.com/magento-ext-license.html
 */
 
class IG_EuroSuite_Model_Eurosuite
{
	const XML_IG_EUROSUITE_ENABLED = 'ig_eurosuite/general/enabled';
	
	const XML_IG_EUROSUITE_PRIVACY = 'ig_eurosuite/privacy';
	const XML_IG_EUROSUITE_TOS = 'ig_eurosuite/tos';
	
	const XML_IG_EUROSUITE_EUVAT_ENABLED = 'ig_eurosuite/euvat/enabled';
	const XML_IG_EUROSUITE_EUVAT_COUNTRIES = 'ig_eurosuite/euvat/eu-countries';
	const XML_IG_EUROSUITE_EUVAT_VAT_TAXES = 'ig_eurosuite/euvat/vat-taxes';
	const XML_IG_EUROSUITE_EUVAT_APPLY_TAX_FOR_EU_VAT = 'ig_eurosuite/euvat/apply-tax-for-eu-vat';
	const XML_IG_EUROSUITE_EUVAT_APPLY_TAX_FOR_NON_EU = 'ig_eurosuite/euvat/apply-tax-for-non-eu';
	const XML_IG_EUROSUITE_EUVAT_USE_SHIPPING_ADDRESS = 'ig_eurosuite/euvat/use-shipping-address';
	const XML_IG_EUROSUITE_EUVAT_VAT_VALIDATE = 'ig_eurosuite/euvat/vat-validate';
	const XML_IG_EUROSUITE_EUVAT_FISCAL_CODE = 'ig_eurosuite/euvat/fiscal-code';
	const XML_IG_EUROSUITE_EUVAT_FISCAL_CODE_VALIDATE = 'ig_eurosuite/euvat/fiscal-code-validate';
	const XML_IG_EUROSUITE_EUVAT_FISCAL_CODE_COUNTRIES = 'ig_eurosuite/euvat/fiscal-code-countries';
	const XML_IG_EUROSUITE_EUVAT_VAT_REQUIRED = 'ig_eurosuite/euvat/vat-required';
	const XML_SHIPPING_ORIGIN_COUNTRY = 'shipping/origin/country_id';
	
	const VIES_SOAP_URL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
	
	protected $_euCountries = null;
	protected $_fiscalCodeCountries = null;
	protected $_vatTaxes = null;
	
	public function isEnabled()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_ENABLED) ? true : false;
	}
	
	public function isPrivacyRequired($zone)
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_PRIVACY."/$zone-enabled") ? true : false;
	}
	
	public function isTosRequired($zone)
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_TOS."/$zone-enabled") ? true : false;
	}
	
	public function isEuVatEnabled()
	{
		if (!$this->isEnabled())
			return false;
		
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_ENABLED) ? true : false;
	}
	
	public function isEuCountry($countryId)
	{
		if (!$this->_euCountries)
			$this->_euCountries = explode(',', Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_COUNTRIES));
			
		return in_array($countryId, $this->_euCountries);
	}
	
	public function isFiscalCodeCountry($countryId)
	{
		if (!$this->_fiscalCodeCountries)
			$this->_fiscalCodeCountries = explode(',', Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_FISCAL_CODE_COUNTRIES));
			
		return in_array($countryId, $this->_fiscalCodeCountries);
	}
	
	public function isVatTax($taxId)
	{
		if (!$this->_vatTaxes)
			$this->_vatTaxes = explode(',', Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_VAT_TAXES));
			
		return in_array($taxId, $this->_vatTaxes);
	}
	
	public function getFiscalCode()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_FISCAL_CODE);
	}
		public function getVatRequired()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_VAT_REQUIRED);
	}
	
	protected function cacheVies($country, $vat, $valid = null)
	{
		$session = Mage::getSingleton('core/session');
		
		if (!$session->getData('ig_euriosuite_vies'))
			$session->setData('ig_euriosuite_vies', array());

		$data = $session->getData('ig_euriosuite_vies');
			
		if (is_null($valid))
		{
			if (array_key_exists("$country/$vat", $data))
				return $data["$country/$vat"];
				
			return null;
		}
		
		$data["$country/$vat"] = $valid;
		$session->setData('ig_euriosuite_vies', $data);
		
		return $valid;
	}
	
	public function viesValidation($country, $vat)
	{
		if (!is_null($this->cacheVies($country, $vat)))
			return $this->cacheVies($country, $vat);
			
		try
		{
			$vies = new SoapClient(self::VIES_SOAP_URL);
			
			$res = $vies->checkVat(array('countryCode'=>$country, 'vatNumber'=>$vat));
			$this->cacheVies($country, $vat, $res->valid);
			return $res->valid;
		}
		catch(exception $e)
		{
			Mage::getSingleton('core/session')->addError(Mage::helper('ig_eurosuite')->__('Could not verify VAT number, VIES server was unavailable'));
		}
		
		return true;
	}
	
	public function checkEuVat($country, $vat)
	{
		$vat = trim($vat);
		
		if (!$vat)
			return false;
			
		if (Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_VAT_VALIDATE))
			return $this->viesValidation($country, $vat);
			
		return true;
	}
	
	public function checkFiscalCode($country, $code)
	{
		if (!Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_FISCAL_CODE_VALIDATE))
			return true;
		
		// VAT
		if (preg_match("/^(\w{2})?\d+$/", $code))
			return $this->checkEuVat($country, $code);
		
		$code = trim(strtoupper($code));
		
		$validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
		
		// Verifica lunghezza codice
		if (strlen($code) != 16)
			return false;
		
		// Verifica caratteri
		for ($i=0; $i<strlen($code); $i++)
		{
			if (strpos($validi, $code[$i]) === false)
				return false;
		}
		
		// Verifica codice di controllo
		$sum = 0;
		for ($i=0; $i<strlen($code)-1; $i++)
		{
			$sum += ($i % 2) ?
				strpos($setpari, $set2[strpos($set1, $code[$i])]) :
				strpos($setdisp, $set2[strpos($set1, $code[$i])]);
		}
		
		$checkN = $sum % 26;
		if ($code[15] != chr(65+$checkN))
			return false;
		
		return true;
	}
	
	public function checkAddress($data, $customerAddressId = null)
	{
		if (! empty($customerAddressId))
		{
			$customerAddress = Mage::getModel('customer/address')->load($customerAddressId);
			$data = $customerAddress->getData();
		}
		
		$errors = array();
		
		$fCodeRequired = false;
		$vatRequired = false;
		
		if (!array_key_exists('country_id', $data))
			return array();
			
		if (!array_key_exists('ig_eurosuite_fcode', $data))
			$data['ig_eurosuite_fcode'] = null;
		if (!array_key_exists('ig_eurosuite_vat', $data))
			$data['ig_eurosuite_vat'] = null;
			
		if ($this->getFiscalCode() == 'always')
			$fCodeRequired = true;
		elseif (($this->getFiscalCode() == 'selected') && $this->isFiscalCodeCountry($data['country_id']))
			$fCodeRequired = true;
			
		if ($this->getVatRequired() == 'always')
			$vatRequired = true;
		elseif (($this->getVatRequired() == 'eu') && $this->isEuCountry($data['country_id']))
			$vatRequired = true;
		elseif (($this->getVatRequired() == 'eu-companies') && $this->isEuCountry($data['country_id']) && $data['company'])
			$vatRequired = true;
			
		if ($fCodeRequired && !$data['ig_eurosuite_fcode'])
			$errors[] = Mage::helper('ig_eurosuite')->__('Fiscal Code is required!');
			
		if ($vatRequired && !$data['ig_eurosuite_vat'])
			$errors[] = Mage::helper('ig_eurosuite')->__('VAT Number is required!');
			
		if ($data['ig_eurosuite_vat'] && $this->isEuCountry($data['country_id']) && !$this->checkEuVat($data['country_id'], $data['ig_eurosuite_vat']))
		{
			$errors[] = Mage::helper('ig_eurosuite')->__('Invalid European VAT number, VIES verification failed.');
		}
		
		if ($data['ig_eurosuite_fcode'] && !$this->checkFiscalCode($data['country_id'], $data['ig_eurosuite_fcode']))
		{
			$errors[] = Mage::helper('ig_eurosuite')->__('Invalid Fiscal Code Format.');
		}
		
		return $errors;
	}
	
	public function isSameCountry($countryId)
	{
		return ($countryId == Mage::getStoreConfig(self::XML_SHIPPING_ORIGIN_COUNTRY));
	}
	
	public function canUseShippingAddress()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_USE_SHIPPING_ADDRESS) ? true : false;
	}
	
	public function applyTaxNonEu()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_APPLY_TAX_FOR_NON_EU) ? true : false;
	}
	
	public function applyTaxEuVat()
	{
		return Mage::getStoreConfig(self::XML_IG_EUROSUITE_EUVAT_APPLY_TAX_FOR_EU_VAT) ? true : false;
	}
}
