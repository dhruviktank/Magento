<?php

class Mage_Catalog_Model_Product_Brand extends Varien_Object
{
    const BALENCIAGA = 236;
    const LOUIE_VITTON = 234;
    const GUCCI = 235;
    const PRADA = 233;
    
    static public function getOptionArray()
    {
        return array(
            self::BALENCIAGA=> Mage::helper('catalog')->__('Balenciaga'),
            self::LOUIE_VITTON => Mage::helper('catalog')->__('LV'),
            self::PRADA  => Mage::helper('catalog')->__('Prada'),
            self::GUCCI       => Mage::helper('catalog')->__('Gucci')
        );
    }
}