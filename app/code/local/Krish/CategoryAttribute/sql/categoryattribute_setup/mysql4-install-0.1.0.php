<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_CategoryAttribute
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
$installer = $this;
$installer->startSetup();


$installer->addAttribute("catalog_category", "krish_influencer",  array(
    "type"     => "int",
    "backend"  => "",
    'group'         => 'General Information',
    "frontend" => "",
    "label"    => "Select Influencer",
    "input"    => "select",
    "class"    => "",
    "source"   => "categoryattribute/eav_entity_attribute_source_categoryoptions",
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "visible"  => true,
    "required" => false,
    "user_defined"  => true,
    'visible_on_front' => true,
    'is_html_allowed_on_front' => true,
    "unique"     => false,
    "note"       => ""

	));


$installer->endSetup();
	 