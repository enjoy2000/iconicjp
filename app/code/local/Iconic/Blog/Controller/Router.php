<?php
class Iconic_Blog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('blog', $this);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
    	Mage::getSingleton('core/session', array("name" => "frontend"));
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $route = Mage::helper('blog')->getRoute();

        $identifier = $request->getPathInfo();
		


        $identifier = substr_replace($request->getPathInfo(), '', 0, 1);
        $identifier = trim($identifier, " /");

        $parts = explode("/", $identifier);
		//url for tim-viec-lam
		if($parts[0] == Mage::helper('blog')->getRoute()){
			$request
				->setModuleName('blog')
                ->setControllerName('index')
                ->setActionName('index');
			$parts = array_slice($parts, 1);
			//var_dump($parts);die;
			return true;
		}
        return false;
    }
}
