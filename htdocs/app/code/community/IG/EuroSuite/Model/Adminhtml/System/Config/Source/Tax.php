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
 
class IG_EuroSuite_Model_Adminhtml_System_Config_Source_Tax extends Mage_Core_Model_Abstract
{
	protected $_options;
	
	public function toOptionArray($isMultiselect = false)
	{
		if (!$this->_options)
		{
			$this->_options = Mage::getModel('tax/calculation_rate')->getCollection()->toOptionArray(false);
		}
		
		$options = $this->_options;
		if (!$isMultiselect)
		{
			array_unshift($options, array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('--Please Select--')));
		}
		
		return $options;
	}
}
