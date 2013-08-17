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
 
require_once 'Mage/Checkout/controllers/MultishippingController.php';
class IG_EuroSuite_MultishippingController extends Mage_Checkout_MultishippingController
{
    protected $_euroSuite = null;
	
	protected function getEuroSuite()
	{
		if (!$this->_euroSuite)
			$this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');
			
		return $this->_euroSuite;
	}
	
	public function addressesPostAction()
    {
        $eurosuite = $this->getEuroSuite();
		if ($eurosuite->isEuVatEnabled())
		{
			$quote = $this->_getCheckout()->getQuote();
			$addresses = $quote->getAllShippingAddresses();
			
			foreach ($addresses as $address)
			{
				$errors = $this->getEuroSuite()->checkAddress(null, $address->getCustomerAddressId());
				if (sizeof($errors))
				{
					foreach ($errors as $error)
						Mage::getSingleton('core/session')->addError($error);
					
					$this->_redirect('customer/address/edit', array('id' => $address->getCustomerAddressId()));
					return;
				}
			}
		}
		
		return parent::addressesPostAction();
    }
}
