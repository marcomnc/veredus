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
 
class IG_EuroSuite_Model_Tax_Mysql4_Calculation extends Mage_Tax_Model_Mysql4_Calculation
{
  protected $_euroSuite = null;
	
	protected function getEuroSuite()
	{
		if (!$this->_euroSuite)
			$this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');
			
		return $this->_euroSuite;
	}
	
	public function _getRates($request)
	{
		$rates = parent::_getRates($request);
		
		$eurosuite = $this->getEuroSuite();
		
		if (!$eurosuite->isEuVatEnabled())
			return $rates;
			
		// Find VAT address
		$address = null;
		if ($eurosuite->canUseShippingAddress())
			$address = $request->getShippingAddress();
			
		if (!$address || !$address->getCountry())
			$address = $request->getBillingAddress();
		$address = $request->getShippingAddress();
		
		if (!$address || !$address->getCountry())
			return $rates;

		// Check if must exclude VAT
		$excludeVat = false;			
    
		if (!$eurosuite->isSameCountry($address->getCountry()))
		{
			if ($eurosuite->isEuCountry($address->getCountry()))
			{
				if ($address->getIgEurosuiteVat() && !$eurosuite->applyTaxEuVat())
					$excludeVat = true;
			}
			else
			{
				if (!$eurosuite->applyTaxNonEu())
					$excludeVat = true;
			}
		}
		
		if (!$excludeVat)
			return $rates;
			
		$out = array();
		foreach ($rates as $rate)
		{
			if ($eurosuite->isVatTax($rate['tax_calculation_rate_id']))
				continue;
				
			$out[] = &$rate;
		}
		
		return $out;
	}
}
