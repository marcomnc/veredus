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
 
class IG_EuroSuite_Block_Newsletter_Subscriber_New extends Mage_Core_Block_Template
{
	protected $_euroSuite = null;
	
	protected function getEuroSuite()
	{
		if (!$this->_euroSuite)
			$this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');
			
		return $this->_euroSuite;
	}
	
	protected function getEmail()
	{
		return $this->getRequest()->getParam('email');
	}
	
	protected function getFormAction()
	{
		return $this->getUrl('*/*/*');
	}
}
