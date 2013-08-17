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
 
class IG_EuroSuite_Helper_Tos extends Mage_Core_Helper_Abstract
{
	const XML_PATH_PRIVACY_CMS_PAGE = 'ig_eurosuite/privacy/cms-page';
	const XML_PATH_TOS_CMS_PAGE = 'ig_eurosuite/tos/cms-page';
	
	public function getConfig($key)
	{
		return Mage::getStoreConfig(self::XML_PATH.'/'.$key);
	}
	
	public function getPrivacyUrl()
	{
		return Mage::helper('cms/page')->getPageUrl(Mage::getStoreConfig(self::XML_PATH_PRIVACY_CMS_PAGE));
	}
	
	public function getTosUrl()
	{
		return Mage::helper('cms/page')->getPageUrl(Mage::getStoreConfig(self::XML_PATH_TOS_CMS_PAGE));
	}
	
	public function getPrivacyText()
	{
		return $this->__('By checking this box you are agreeing to the <a href="%s">Privacy Policy</a>.', $this->getPrivacyUrl());
	}
	
	public function getTosText()
	{
		return $this->__('By checking this box you are agreeing to the <a href="%s">Terms of Service</a>.', $this->getTosUrl());
	}
}
