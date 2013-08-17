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
 
class IG_EuroSuite_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
	protected $_euroSuite = null;
	
	protected function getEuroSuite()
	{
		if (!$this->_euroSuite)
			$this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');
			
		return $this->_euroSuite;
	}

	public function saveBilling($data, $customerAddressId)
	{
		if ($this->getEuroSuite()->isEuVatEnabled())
		{
			$errors = $this->getEuroSuite()->checkAddress($data, $customerAddressId);
			if (sizeof($errors))
			{
				if (!empty($customerAddressId))
				{
					foreach ($errors as $error)
						Mage::getSingleton('core/session')->addError($error);
						
					return array('error'=>0, 'redirect' => Mage::getUrl('customer/address/edit', array('id' => $customerAddressId)));
				}
					
				return array('error'=>1, 'message'=>implode("\n", $errors));
			}
		}
		
		return parent::saveBilling($data, $customerAddressId);
	}
	
	/*public function saveShipping($data, $customerAddressId)
	{
		if ($this->getEuroSuite()->isEuVatEnabled())
		{
			$errors = $this->getEuroSuite()->checkAddress($data, $customerAddressId);
			if (sizeof($errors))
			{
				if (!empty($customerAddressId))
				{
					foreach ($errors as $error)
						Mage::getSingleton('core/session')->addError($error);
						
					return array('error'=>0, 'redirect' => Mage::getUrl('customer/address/edit', array('id' => $customerAddressId)));
				}
					
				return array('error'=>1, 'message'=>implode("\n", $errors));
			}
		}
		
		return parent::saveShipping($data, $customerAddressId);
	}*/
}
