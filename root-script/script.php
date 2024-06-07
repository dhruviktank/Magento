<?php

require_once('../app/Mage.php');

Mage::app();

$observer = new Ccc_Outlook_Model_Observer();

$observer->fetchUsers();