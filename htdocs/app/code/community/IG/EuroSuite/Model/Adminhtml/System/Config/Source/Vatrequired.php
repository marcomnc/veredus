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
 
class IG_EuroSuite_Model_Adminhtml_System_Config_Source_Vatrequired extends Mage_Core_Model_Abstract
{
	protected $_options;
	
	public function toOptionArray()
	{
		if (!$this->_options)
		{
			$this->_options = array();
			$this->_options['always'] = Mage::helper('ig_eurosuite')->__('Always');
			$this->_options['never'] = Mage::helper('ig_eurosuite')->__('Never');
			$this->_options['eu'] = Mage::helper('ig_eurosuite')->__('Only for EU');
			$this->_options['eu-companies'] = Mage::helper('ig_eurosuite')->__('Only for EU Companies');
		}
		
		return $this->_options;
	}
}
