<?php 

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

//Use {$this->getTable('soshake_soshake')} for table name

$installer->run("
CREATE TABLE IF NOT EXISTS `up2social` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `hook` varchar(250) NOT NULL,
  `feature` varchar(250) NOT NULL,
  `BorA` varchar(15) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1  ;
");

$installer->endSetup();