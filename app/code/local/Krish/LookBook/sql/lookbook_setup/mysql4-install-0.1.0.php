<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('krish_lookbook')};
CREATE TABLE {$this->getTable('krish_lookbook')} (
  `lookbook_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `youtubeid` varchar(255) NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lookbook_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('krish_lookbook_products')};
CREATE TABLE  {$this->getTable('krish_lookbook_products')}  (
  `lookbook_entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `lookbook_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`lookbook_entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('krish_lookbook_stores')};
CREATE TABLE {$this->getTable('krish_lookbook_stores')} (
  `lookbook_id` smallint(6) NOT NULL,
  `store_id` smallint(6) NOT NULL,
  PRIMARY KEY (`lookbook_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lookbook Stores';



DROP TABLE IF EXISTS {$this->getTable('krish_influencer')};
CREATE TABLE {$this->getTable('krish_influencer')} (
 `influencer_id` int(11) unsigned NOT NULL auto_increment,
 `influencer_name` varchar(255) NOT NULL default '',
 `influencer_code` text NOT NULL,
 `influencer_bio` text NOT NULL, 
 `influencer_tagline` text NOT NULL, 
 `influencer_image` varchar(255) NOT NULL DEFAULT '',
 `lookbook_ids` varchar(255) NOT NULL default '',
 `status` smallint(6) NOT NULL default '0',
 `created_time` DATETIME NULL,
 `update_time` DATETIME NULL,
 PRIMARY KEY (`influencer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 