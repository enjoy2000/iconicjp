<?php
 
class Iconic_Job_Model_Request extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('job/request');
    }

}