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

class IG_EuroSuite_Model_Tax_Calculation extends Mage_Tax_Model_Calculation {
    protected $_euroSuite = null;

    protected function getEuroSuite()
    {
        if(!$this->_euroSuite)
            $this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');

        return $this->_euroSuite;
    }

    public function getRateRequest($shippingAddress =null, $billingAddress =null, $customerTaxClass =null, $store =null)
    {
        $request = parent::getRateRequest($shippingAddress, $billingAddress, $customerTaxClass, $store);
        if($this->getEuroSuite()->isEuVatEnabled() && $request)
        {
            $request
                ->setShippingAddress($shippingAddress)
                ->setBillingAddress($billingAddress);
        }

        return $request;
    }

}
