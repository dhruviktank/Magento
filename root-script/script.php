<?php

require_once('../app/Mage.php');

Mage::app();

Mage::getModel('vendorinventory/observer')->applyRule();
