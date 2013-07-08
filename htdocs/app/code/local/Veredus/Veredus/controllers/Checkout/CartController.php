<?php
/**
 
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Marco Mancinelli
 * 
 * Recupero la prima spesa di trasporto e l'assegno alla quota
 */

/**
 * Shopping cart controller
 */
// Controllers are not autoloaded so we will have to do it manually:
require_once 'Mage/Checkout/controllers/CartController.php';
class Veredus_Veredus_Checkout_CartController extends Mage_Checkout_CartController
{
    /**
     * Initialize shipping information
     */
    public function estimateShippingAction()
    {
        $country    = (string) $this->getRequest()->getParam('country_id');
        $postcode   = (string) $this->getRequest()->getParam('estimate_postcode');
        $city       = (string) $this->getRequest()->getParam('estimate_city');
        $regionId   = (string) $this->getRequest()->getParam('region_id');
        $region     = (string) $this->getRequest()->getParam('region');

        $this->_getQuote()->getShippingAddress()
            ->setCountryId($country)
            ->setCity($city)
            ->setPostcode($postcode)
            ->setRegionId($regionId)
            ->setRegion($region)
            ->setCollectShippingRates(true);
        $this->_getQuote()->save();

        foreach (Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getAllShippingRates() as $_rate) {
           $this->_getQuote()->getShippingAddress()->setShippingMethod($_rate->getCode());
           $this->_getQuote()->getShippingAddress()->save();
           break;
        }
        $this->_goBack();
    }

}
