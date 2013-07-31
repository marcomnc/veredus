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
 * Store pricing helper
 * 
 * @category   Innoexts
 * @package    Innoexts_StorePricing
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_StorePricing_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Websites
     * 
     * @var array of Mage_Core_Model_Website
     */
    protected $_websites;
    /**
     * Get version helper
     * 
     * @return Innoexts_InnoCore_Helper_Version
     */
    public function getVersionHelper()
    {
        return Mage::helper('innocore')->getVersionHelper();
    }
    /**
     * Check if admin store is active
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }
    /**
     * Get websites
     * 
     * @return array of Mage_Core_Model_Website
     */
    public function getWebsites()
    {
        if (is_null($this->_websites)) {
            $this->_websites = Mage::app()->getWebsites();
        }
        return $this->_websites;
    }
    /**
     * Get store by identifier
     * 
     * @param mixed $storeId
     * @return Mage_Core_Model_Store
     */
    public function getStoreById($storeId)
    {
        return Mage::app()->getStore($storeId);
    }
    /**
     * Get website by store identifier
     * 
     * @param mixed $storeId
     * @return Mage_Core_Model_Website 
     */
    public function getWebsiteByStoreId($storeId)
    {
        return $this->getStoreById($storeId)->getWebsite();
    }
    /**
     * Get website identifier by store identifier 
     * 
     * @param mixed $storeId
     * @return int
     */
    public function getWebsiteIdByStoreId($storeId)
    {
        return $this->getStoreById($storeId)->getWebsiteId();
    }
    /**
     * Check if create order request is active
     * 
     * @return bool
     */
    public function isCreateOrderRequest()
    {
        if ($this->isAdmin()) {
            $controllerName = Mage::app()->getRequest()->getControllerName();
            if (in_array(strtolower($controllerName), array('sales_order_edit', 'sales_order_create'))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Get current store
     * 
     * @return Mage_Core_Model_Store
     */
    public function getCurrentStore()
    {
        if ($this->isAdmin() && $this->isCreateOrderRequest()) {
            return Mage::getSingleton('adminhtml/session_quote')->getStore();
        } else {
            return Mage::app()->getStore();
        }
    }
    /**
     * Get current store identifier
     * 
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->getCurrentStore()->getId();
    }
    /**
     * Get escaped price
     * 
     * @param float $price
     * @return float
     */
    public function getEscapedPrice($price)
    {
        if (!is_numeric($price)) {
            return null;
        }
        return number_format($price, 2, null, '');
    }
    /**
     * Get product price scope
     * 
     * @return int
     */
    public function getPriceScope()
    {
        return Mage::helper('catalog')->getPriceScope();
    }
    /**
     * Check if global price scope is active
     * 
     * @return bool 
     */
    public function isGlobalPriceScope()
    {
        return ($this->getPriceScope() == 0)  ? true : false;
    }
    /**
     * Check if website price scope is active
     * 
     * @return bool
     */
    public function isWebsitePriceScope()
    {
        return ($this->getPriceScope() == 1)  ? true : false;
    }
    /**
     * Check if store price scope is active
     * 
     * @return bool
     */
    public function isStorePriceScope()
    {
        return ($this->getPriceScope() == 2)  ? true : false;
    }
    /**
     * Check if single store mode is in effect
     * 
     * @return bool 
     */
    public function isSingleStoreMode()
    {
        return Mage::app()->isSingleStoreMode();
    }
    /**
     * Check if data is ancestor
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param array $data
     * @param mixed $storeId
     * @return bool
     */
    public function isAncestorData($attribute, $data, $storeId)
    {
        $websiteId = $this->getWebsiteIdByStoreId($storeId);
        if (!$attribute->isScopeGlobal() && ($websiteId != 0)) {
            if (
                ($attribute->isScopeWebsite() && ((int) $data['website_id'] == 0)) || 
                ($attribute->isScopeStore() && (((int) $data['website_id'] == 0) || ((int) $data['store_id'] == 0)))
            ) {
                return true;
            }
        }
        return false;
    }
    /**
     * Check if data is inactive
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param array $data
     * @param mixed $storeId
     * @return bool
     */
    public function isInactiveData($attribute, $data, $storeId)
    {
        if (
            ($attribute->isScopeGlobal() && (($data['website_id'] > 0) || ($data['store_id'] > 0))) || 
            ($attribute->isScopeWebsite() && ($data['store_id'] > 0))
        ) {
            return true;
        }
        return false;
    }
    /**
     * Add price index store filter
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function addPriceIndexStoreFilter($collection)
    {
        $select = $collection->getSelect();
        $fromPart = $select->getPart(Zend_Db_Select::FROM);
        if (isset($fromPart['price_index'])) {
            $oldJoinCond = $fromPart['price_index']['joinCondition'];
            if (strpos($oldJoinCond, 'store_id') === false) {
                $connection = $collection->getConnection();
                if (!$collection->getFlag('store_id')) {
                    $storeId = $this->getCurrentStoreId();
                } else {
                    $storeId = $collection->getFlag('store_id');
                }
                $storeId = $connection->quote($storeId);
                $joinCond = $oldJoinCond." AND ((price_index.store_id = 0) OR (price_index.store_id = {$storeId}))";
                $fromPart['price_index']['joinCondition'] = $joinCond;
                $select->setPart(Zend_Db_Select::FROM, $fromPart);
            }
        }
        return $collection;
    }
    /**
     * Get product price process 
     * 
     * @return Mage_Index_Model_Process
     */
    protected function getProductPriceProcess()
    {
        return Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_price');
    }
    /**
     * Get product flat process 
     * 
     * @return Mage_Index_Model_Process
     */
    protected function getProductFlatProcess()
    {
        return Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_flat');
    }
    /**
     * Reindex product price
     * 
     * @return Innoexts_StorePricing_Helper_Data
     */
    public function reindexProductPrice()
    {
        $process = $this->getProductPriceProcess();
        if ($process) {
            $process->reindexAll();
        }
        return $this;
    }
    /**
     * Reindex product flat
     * 
     * @return Innoexts_StorePricing_Helper_Data
     */
    public function reindexProductFlat()
    {
        $process = $this->getProductFlatProcess();
        if ($process) {
            $process->reindexAll();
        }
        return $this;
    }
    /**
     * Change product price index process status
     * 
     * @param int $status
     * @return Innoexts_StorePricing_Helper_Data
     */
    public function changeProductPriceIndexProcessStatus($status)
    {
        $process = $this->getProductPriceProcess();
        if ($process) {
            $process->changeStatus($status);
        }
        return $this;
    }
    /**
     * Change product flat index process status
     * 
     * @param int $status
     * @return Innoexts_StorePricing_Helper_Data
     */
    public function changeProductFlatIndexProcessStatus($status)
    {
        $process = $this->getProductFlatProcess();
        if ($process) {
            $process->changeStatus($status);
        }
        return $this;
    }
    /**
     * Get price attributes names
     * 
     * @return array
     */
    protected function getPriceAttributesNames()
    {
        return array('price', 'special_price', 'special_from_date', 'special_to_date', 'tier_price');
    }
    /**
     * Get EAV entity attribute resource
     * 
     * @return Mage_Eav_Model_Resource_Entity_Attribute
     */
    protected function getEavEntityAttributeResource()
    {
        return Mage::getResourceModel('eav/entity_attribute');
    }
    /**
     * Change price scope
     * 
     * @param int $priceScope
     * @return Innoexts_StorePricing_Helper_Data
     */
    public function changeProductPriceScope($priceScope)
    {
        $scope = null;
        switch ($priceScope) {
            case 0: 
                $scope = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL;
                break;
            case 1: 
                $scope = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
                break;
            case 2: 
                $scope = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
                break;
            default:
                $scope = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL;
                break;
        }
        $eavEntityAttributeResource = $this->getEavEntityAttributeResource();
        $priceAttributesNames = $this->getPriceAttributesNames();
        foreach ($priceAttributesNames as $attributeName) {
            $attributeId = $eavEntityAttributeResource->getIdByCode('catalog_product', $attributeName);
            $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
            $attribute->setIsGlobal($scope);
            $attribute->save();
        }
        return $this;
    }
}