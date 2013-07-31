<?php
/**
 * Innoexts
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_StorePricing
 * @copyright   Copyright (c) 2012 Innoexts (http://www.innoexts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product price attribute backend
 * 
 * @category   Innoexts
 * @package    Innoexts_StorePricing
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_StorePricing_Model_Catalog_Product_Attribute_Backend_Price extends Mage_Catalog_Model_Product_Attribute_Backend_Price 
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
     * Redefine Attribute scope
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Price
     */
    public function setScope($attribute)
    {
        $helper = $this->getStorePricingHelper();
        if ($helper->isGlobalPriceScope()) {
            $attribute->setIsGlobal(Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL);
        } else if ($helper->isWebsitePriceScope()) {
            $attribute->setIsGlobal(Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE);
        } else if ($helper->isStorePriceScope()) {
            $attribute->setIsGlobal(Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
        } else {
            $attribute->setIsGlobal(Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL);
        }
        return $this;
    }
}