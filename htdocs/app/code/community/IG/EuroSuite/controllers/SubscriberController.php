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
 
require_once 'Mage/Newsletter/controllers/SubscriberController.php';
class IG_EuroSuite_SubscriberController extends Mage_Newsletter_SubscriberController
{
	protected $_euroSuite = null;
	
	protected function getEuroSuite()
	{
		if (!$this->_euroSuite)
			$this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');
			
		return $this->_euroSuite;
	}
	
	public function newAction()
	{
		$eurosuite = $this->getEuroSuite();
		if ($eurosuite->isEuVatEnabled() && $this->getRequest()->isPost())
		{
			if (
				($eurosuite->isPrivacyRequired('newsletter') && !$this->getRequest()->getParam('privacy')) ||
				($eurosuite->isTosRequired('newsletter') && !$this->getRequest()->getParam('tos'))
			) {
				$this->loadLayout();
				$this->renderLayout();
				return;
			}
			
			if (($eurosuite->isPrivacyRequired('newsletter') || $eurosuite->isTosRequired('newsletter')))
			{
				$_SERVER['HTTP_REFERER'] = Mage::getBaseUrl();
			}
		}

		parent::newAction();
	}
}
