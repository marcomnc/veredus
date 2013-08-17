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
 
class IG_EuroSuite_Model_Adminhtml_System_Config_Source_Fiscalcode extends Mage_Core_Model_Abstract
{
	protected $_options;
	
	public function toOptionArray()
	{
		if (!$this->_options)
		{
			$this->_options = array();
			$this->_options['no'] = Mage::helper('ig_eurosuite')->__('Do not use');
			$this->_options['yes'] = Mage::helper('ig_eurosuite')->__('Not required');
			$this->_options['always'] = Mage::helper('ig_eurosuite')->__('Always Required');
			$this->_options['selected'] = Mage::helper('ig_eurosuite')->__('Always Required for selected countries');
		}
		
		return $this->_options;
	}
}
