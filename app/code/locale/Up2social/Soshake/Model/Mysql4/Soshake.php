<?php 

class Up2social_Soshake_Model_Mysql4_Soshake extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('soshake/soshake', 'ID');
    }	
}