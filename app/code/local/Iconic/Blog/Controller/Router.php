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
			$blog = Mage::getModel('blog/blog')->load($parts[0], 'url_key');
			if($blog->getId()){
				//get parent category list
				$parents = substr($blog->getCategoryId(), 1, -1);
				$parents = explode(',', $parents);
				$parentArr = array();
				foreach($parents as $catId){
					$catModel = Mage::getModel('blog/category')->load($catId);
					$parent = Mage::getModel('blog/parentcategory')->load($catModel->getParentcategoryId());
					if(!in_array($parent->getId(), $parentArr)){
						$parentArr[] = $parent->getId();
					}
				}
				$request
					->setModuleName('blog')
	                ->setControllerName('detail')
	                ->setActionName('index')
					->setParam('id', $blog->getId())
					->setParam('cat', $parents)
					->setParam('parent', $parentArr);
					return true;
			}else{
				if(Mage::getSingleton('core/session')->getBlogSearch()){
					$request
						->setModuleName('blog')
		                ->setControllerName('index')
		                ->setActionName('index')
						->setParam('q', Mage::getSingleton('core/session')->getBlogSearch());
					//Mage::getSingleton('core/session')->unsBlogSearch();
					return true;
				}else{
					$request
						->setModuleName('blog')
		                ->setControllerName('index')
		                ->setActionName('index');
					//Mage::getSingleton('core/session')->unsBlogSearch();
					return true;
				}
			}
			//var_dump($parts);die;
			switch(count($parts)){
				case 1: //parent category
					$parent = Mage::getModel('blog/parentcategory')->load($parts[0], 'url_key');
						if($parent->getId()){
							$request
								->setParam('parent', $parent->getId());
		                }else{
		                	return false;
		                }
					break;
				
				case 2: //sub category
					$parent = Mage::getModel('blog/parentcategory')->load($parts[0], 'url_key');
						if($parent->getId()){
							$request
								->setParam('parent', $parent->getId());
		                }else{
		                	return false;
		                }
					$cat = Mage::getModel('blog/category')->load($parts[1], 'url_key');
					if($cat->getId()){
						$request
							->setParam('cat', $cat->getId());
					}else{
	                	return false;
	                }
					break;
			}
					
			return true;
		}
        return false;
    }
}
