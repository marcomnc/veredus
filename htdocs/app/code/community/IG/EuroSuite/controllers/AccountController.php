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

require_once 'Mage/Customer/controllers/AccountController.php';
class IG_EuroSuite_AccountController extends Mage_Customer_AccountController
{
    protected $_euroSuite = null;

    protected function getEuroSuite()
    {
        if(!$this->_euroSuite)
            $this->_euroSuite = Mage::getModel('ig_eurosuite/eurosuite');

        return $this->_euroSuite;
    }

    public function createPostAction()
    {
        $eurosuite = $this->getEuroSuite();
        
        if ($eurosuite->isEuVatEnabled())
        {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost();
                $errors = $eurosuite->checkAddress($data);
                if (sizeof($errors))
                {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    foreach($errors as $error)
                        $this->_getSession()->addError($error);

                    $this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
                    return ;
                }
            }
        }

        return parent::createPostAction();
    }

}
