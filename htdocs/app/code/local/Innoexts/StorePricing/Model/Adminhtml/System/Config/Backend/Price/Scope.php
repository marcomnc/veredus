<?php
/**
 * Innoexts
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the InnoExts Commercial License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://innoexts.com/commercial-license-agreement
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_StorePricing
 * @copyright   Copyright (c) 2012 Innoexts (http://www.innoexts.com)
 * @license     http://innoexts.com/commercial-license-agreement  InnoExts Commercial License
 */

/**
 * Price scope backend
 * 
 * @category   Innoexts
 * @package    Innoexts_StorePricing
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_StorePricing_Model_Adminhtml_System_Config_Backend_Price_Scope  
    extends Mage_Adminhtml_Model_System_Config_Backend_Price_Scope 
{
    /**
     * Get store pricing helper
     * 
     * @return Innoexts_StorePricing_Helper_Data
     */
    protected function getStorePricingHelper()
    {
        return Mage::helper('storepricing');
    }
    /**
     * Callback function which called after transaction commit in resource model
     *
     * @return Innoexts_StorePricing_Model_Adminhtml_System_Config_Backend_Price_Scope
     */
    public function afterCommitCallback()
    {
        parent::afterCommitCallback();
        if ($this->isValueChanged()) {
            $helper = $this->getStorePricingHelper();
            $helper->changeProductPriceScope($this->getValue());
            $helper->reindexProductPrice();
            $helper->reindexProductFlat();
        }
        return $this;
    }
    /**
     * Save object data
     *
     * @return Mage_Core_Model_Abstract
     */
    public function save()
    {
        parent::save();
        if ($this->isValueChanged()) {
            $helper = $this->getStorePricingHelper();
            $helper->changeProductPriceIndexProcessStatus(Mage_Index_Model_Process::STATUS_PENDING);
            $helper->changeProductFlatIndexProcessStatus(Mage_Index_Model_Process::STATUS_PENDING);
        }
    }
}