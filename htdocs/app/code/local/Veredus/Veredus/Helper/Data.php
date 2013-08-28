<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *
 * @category    
 * @package        
 * @copyright   Copyright (c) 2013 Mps Sistemi (http://www.mps-sistemi.it)
 * @author      MPS Sistemi S.a.s - Marco Mancinelli <marco.mancinelli@mps-sistemi.it>
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
