<?php
 
class Iconic_Job_Model_Feature extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('job/feature');
    }

}