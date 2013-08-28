<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require 'app/Mage.php';
Mage::app();

$obs = MAge::getModel('veredus/product_observer');

$obs->scheduledIndex();

?>
