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
 * Product tier price backend attribute
 * 
 * @category   Innoexts
 * @package    Innoexts_StorePricing
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_StorePricing_Model_Catalog_Product_Attribute_Backend_Tierprice 
    extends Mage_Catalog_Model_Product_Attribute_Backend_Tierprice 
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
    /**
     * Validate data
     * 
     * @param array $data
     * @param int $storeId
     * @param bool $filterEmpty
     * @param bool $filterInactive
     * @param bool $filterAncestors
     * @return bool
     */
    protected function validateData($data, $storeId, $filterEmpty = true, $filterInactive = true, $filterAncestors = true)
    {
        $helper             = $this->getStorePricingHelper();
        $attribute          = $this->getAttribute();
        if ($filterEmpty) {
            if (empty($data['price_qty']) || !isset($data['cust_group']) || !empty($data['delete'])) {
                return false;
            }
        }
        if ($filterInactive) {
            if ($helper->isInactiveData($attribute, $data, $storeId)) {
                return false;
            }
        }
        if ($filterAncestors) {
            if ($helper->isAncestorData($attribute, $data, $storeId)) {
                return false;
            }
        }
        return true;
    }
    /**
     * Get data key
     * 
     * @param array $data
     * @param bool $allWebsites
     * @return string 
     */
    protected function getDataKey($data, $allWebsites = false)
    {
        return join('-', array(
            (($allWebsites) ? 0 : $data['website_id']), $data['store_id'], $data['cust_group'], $data['price_qty'] * 1
        ));
    }
    /**
     * Get short data key
     * 
     * @param array $data
     * @return string 
     */
    protected function getShortDataKey($data)
    {
        return join('-', array($data['cust_group'], $data['price_qty'] * 1));
    }
    /**
     * Validate tier price data
     *
     * @param Mage_Catalog_Model_Product $object
     * @throws Mage_Core_Exception
     * @return bool
     */
    public function validate($object)
    {
        $helper = $this->getStorePricingHelper();
        $attribute = $this->getAttribute();
        $attributeName = $attribute->getName();
        $tiers = $object->getData($attributeName);
        if (empty($tiers)) { 
            return true; 
        }
        $duplicateMessage = $helper->__('Duplicate website tier price store, customer group and quantity.');
        $duplicates = array();
        foreach ($tiers as $tier) {
            if (!empty($tier['delete'])) { 
                continue; 
            }
            $compare = $this->getDataKey($tier);
            if (isset($duplicates[$compare])) {
                Mage::throwException($duplicateMessage);
            }
            $duplicates[$compare] = true;
        }
        if (($attribute->isScopeStore() || $attribute->isScopeWebsite()) && $object->getStoreId()) {
            $storeId = $object->getStoreId();
            $origTierPrices = $object->getOrigData($attributeName);
            foreach ($origTierPrices as $tier) {
                if ($helper->isAncestorData($attribute, $tier, $storeId)) {
                    $compare = $this->getDataKey($tier);
                    $duplicates[$compare] = true;
                }
            }
        }
        $baseCurrency = Mage::app()->getBaseCurrencyCode();
        $rates = $this->_getWebsiteRates();
        foreach ($tiers as $tier) {
            if (!empty($tier['delete'])) {
                continue;
            }
            if ($tier['website_id'] == 0) {
                continue;
            }
            $websiteCurrency = $rates[$tier['website_id']]['code'];
            $compare = $this->getDataKey($tier);
            $globalCompare = $this->getDataKey($tier, true);
            if ($baseCurrency == $websiteCurrency && isset($duplicates[$globalCompare])) {
                Mage::throwException($duplicateMessage);
            }
        }
        return true;
    }
    /**
     * Sort price data
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function _sortPriceDataByStore($a, $b)
    {
        if ($a['website_id'] != $b['website_id']) {
            return $a['website_id'] < $b['website_id'] ? 1 : -1;
        }
        if ($a['store_id'] != $b['store_id']) {
            return $a['store_id'] < $b['store_id'] ? 1 : -1;
        }
        return 0;
    }
    /**
     * Sort price data
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function _sortPriceDataByQty($a, $b)
    {
        if ($a['price_qty'] != $b['price_qty']) {
            return $a['price_qty'] < $b['price_qty'] ? -1 : 1;
        }
        return 0;
    }
    /**
     * Prepare tier prices data for website
     *
     * @param array $priceData
     * @param string $productTypeId
     * @param int $websiteId
     * @param int $storeId
     * @return array
     */
    public function preparePriceData2(array $priceData, $productTypeId, $websiteId, $storeId)
    {
        $rates  = $this->_getWebsiteRates();
        $data   = array();
        $price  = Mage::getSingleton('catalog/product_type')->priceFactory($productTypeId);
        usort($priceData, array($this, '_sortPriceDataByStore'));
        foreach ($priceData as $v) {
            $key = $this->getShortDataKey($v);
            if (
                !isset($data[$key]) && 
                (
                    ( $v['website_id'] == $websiteId && $v['store_id'] == $storeId ) || 
                    ( $v['website_id'] == $websiteId && $v['store_id'] == 0 ) || 
                    ( $v['website_id'] == 0 )
                )
            ) {
                $data[$key] = $v;
                $data[$key]['website_id'] = $websiteId;
                $data[$key]['store_id'] = $storeId;
                if ($v['website_id'] == 0) {
                    if ($price->isTierPriceFixed()) {
                        $data[$key]['price'] = $v['price'] * $rates[$websiteId]['rate'];
                        $data[$key]['website_price'] = $v['price'] * $rates[$websiteId]['rate'];
                    }
                }
            }
        }
        usort($data, array($this, '_sortPriceDataByQty'));
        return $data;
    }
    /**
     * After load
     * 
     * @param Mage_Catalog_Model_Product $object
     * @return Innoexts_CurrencyPricing_Model_Catalog_Product_Attribute_Backend_Tierprice
     */
    public function afterLoad($object)
    {
        $helper             = $this->getStorePricingHelper();
        $resource           = $this->_getResource();
        $websiteId          = null;
        $storeId            = null;
        $store              = $helper->getStoreById($object->getStoreId());
        $attribute          = $this->getAttribute();
        $attributeName      = $attribute->getName();
        if ($attribute->isScopeGlobal()) {
            $websiteId = null;
            $storeId = null;
        } else if ($attribute->isScopeWebsite() && $store->getId()) {
            $websiteId = $helper->getWebsiteIdByStoreId($store->getId());
            $storeId = null;
        } else if ($attribute->isScopeStore() && $store->getId()) {
            $websiteId = $helper->getWebsiteIdByStoreId($store->getId());
            $storeId = $store->getId();
        }
        $data = $resource->loadPriceData2($object->getId(), $websiteId, $storeId);
        foreach ($data as $k => $v) {
            $data[$k]['website_price'] = $v['price'];
            if ($v['all_groups']) {
                $data[$k]['cust_group'] = Mage_Customer_Model_Group::CUST_GROUP_ALL;
            }
        }
        if (!$object->getData('_edit_mode') && ($websiteId || $storeId)) {
            $data = $this->preparePriceData2($data, $object->getTypeId(), $websiteId, $storeId);
        }
        $object->setData($attributeName, $data);
        $object->setOrigData($attributeName, $data);
        $valueChangedKey = $attributeName.'_changed';
        $object->setOrigData($valueChangedKey, 0);
        $object->setData($valueChangedKey, 0);
        return $this;
    }
    /**
     * After save
     *
     * @param Mage_Catalog_Model_Product $object
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Tierprice
     */
    public function afterSave($object)
    {
        $helper             = $this->getStorePricingHelper();
        $resource           = $this->_getResource();
        $objectId           = $object->getId();
        $storeId            = $object->getStoreId();
        $websiteId          = $helper->getWebsiteIdByStoreId($storeId);
        $attribute          = $this->getAttribute();
        $attributeName      = $attribute->getName();
        $tierPrices         = $object->getData($attributeName);
        if (empty($tierPrices)) {
            if ($attribute->isScopeGlobal() || $websiteId == 0) {
                $resource->deletePriceData2($objectId);
            } else if ($attribute->isScopeWebsite()) {
                $resource->deletePriceData2($objectId, $websiteId);
            } else if ($attribute->isScopeStore()) {
                $resource->deletePriceData2($objectId, $websiteId, $storeId);
            }
            return $this;
        }
        $old                = array();
        $new                = array();
        $origTierPrices     = $object->getOrigData($attributeName);
        if (!is_array($origTierPrices)) { 
            $origTierPrices = array(); 
        }
        foreach ($origTierPrices as $data) {
            if (!$this->validateData($data, $storeId, false, false, true)) {
                continue;
            }
            $key = $this->getDataKey($data);
            $old[$key] = $data;
        }
        foreach ($tierPrices as $data) {
            if (!$this->validateData($data, $storeId, true, true, true)) {
                continue;
            }
            $key = $this->getDataKey($data);
            $useForAllGroups = $data['cust_group'] == Mage_Customer_Model_Group::CUST_GROUP_ALL;
            $customerGroupId = !$useForAllGroups ? $data['cust_group'] : 0;
            $new[$key] = array(
                'website_id'        => $data['website_id'], 
                'store_id'          => $data['store_id'], 
                'all_groups'        => $useForAllGroups ? 1 : 0,
                'customer_group_id' => $customerGroupId,
                'qty'               => $data['price_qty'],
                'value'             => $data['price'], 
            );
        }
        $delete = array_diff_key($old, $new);
        $insert = array_diff_key($new, $old);
        $update = array_intersect_key($new, $old);
        $isChanged  = false;
        $productId  = $objectId;
        if (!empty($delete)) {
            foreach ($delete as $data) {
                $resource->deletePriceData2($productId, null, null, $data['price_id']);
                $isChanged = true;
            }
        }
        if (!empty($insert)) {
            foreach ($insert as $data) {
                $price = new Varien_Object($data);
                $price->setEntityId($productId);
                $resource->savePriceData($price);
                $isChanged = true;
            }
        }
        if (!empty($update)) {
            foreach ($update as $k => $v) {
                if ($old[$k]['price'] != $v['value']) {
                    $price = new Varien_Object(array('value_id' => $old[$k]['price_id'], 'value' => $v['value']));
                    $resource->savePriceData($price);
                    $isChanged = true;
                }
            }
        }
        if ($isChanged) {
            $valueChangedKey = $attributeName.'_changed';
            $object->setData($valueChangedKey, 1);
        }
        return $this;
    }
}