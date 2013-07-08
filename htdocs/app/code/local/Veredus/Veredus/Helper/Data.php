<?php
/**
 *
 * std Helper
 *
 * @category    Mage
 * @package     Mage_Helper
 * @author      Marco Mancinelli
 * 
 *
 * 
 */

class Veredus_Veredus_Helper_Data  extends Mage_Core_Helper_Abstract
{
    //put your code here
  function getProductImageArrayForJavascript ($context, $_product, $size){
    $arrayString = '';
    if ($_product->getTypeId() == "configurable") {
        $arrayString = $arrayString."{";
        $associated_products = $_product->loadByAttribute('sku', $_product->getSku())->getTypeInstance()->getUsedProducts();
        foreach ($associated_products as $assoc)
        {
            if($assoc->image == "no_selection" || $assoc->image == ""){
              $imageSrc = $context->helper('catalog/image')->init($_product, 'image', $_product->image)->resize($size);
            }else{
              $imageSrc = $context->helper('catalog/image')->init($assoc, 'image', $assoc->image)->resize($size);
            }
            $dados[] = $assoc->getId().":'".$imageSrc."'";
        }
    } else {
        $dados[] =  "''";
    }
    $arrayString = $arrayString.implode(',', $dados );     
    if ($_product->getTypeId() == "configurable") {
        $arrayString = $arrayString."}";
    }
    return $arrayString;
  }
}

?>
