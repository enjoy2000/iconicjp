<?php
class Iconic_Job_PayloadController extends Mage_Core_Controller_Front_Action
{
	public function indexAction(){
		if ( $_POST['payload'] ) {
		  	shell_exec( 'cd /var/www/iconicjp/ && git reset --hard HEAD && git pull' );
			echo 'success';die;
		}else{
			$this->_redirect('/');
			return; //test payload again
		}
	}
}