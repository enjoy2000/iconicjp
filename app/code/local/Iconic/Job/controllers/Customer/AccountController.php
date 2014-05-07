<?php
# Controllers are not autoloaded so we will have to do it manually:
require_once 'Mage/Customer/controllers/AccountController.php';
class Iconic_Job_Customer_AccountController extends Mage_Customer_AccountController
{
	
    # Overloaded createPostAction
    public function createPostAction()
    {
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if (!$this->getRequest()->isPost()) {
            $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
            	$customer->setSex($this->getRequest()->getParam('sex'));
            	$customer->setLocation($this->getRequest()->getParam('location'));
            	$customer->setBirthYear($this->getRequest()->getParam('birthyear'));
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
				//success action
				$session = $this->_getSession();
		        if ($customer->isConfirmationRequired()) {
		            /** @var $app Mage_Core_Model_App */
		            $app = $this->_getApp();
		            /** @var $store  Mage_Core_Model_Store*/
		            $store = $app->getStore();
		            $customer->sendNewAccountEmail(
		                'confirmation',
		                $session->getBeforeAuthUrl(),
		                $store->getId()
		            );
		            $customerHelper = $this->_getHelper('customer');
		            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
		                $customerHelper->getEmailConfirmationUrl($customer->getEmail())));
		            $url = $this->_getUrl('*/*/index', array('_secure' => true));
		        } else {
		            $session->setCustomerAsLoggedIn($customer);
		            $session->renewSession();
		            $url = $this->_welcomeCustomer($customer);
		        }
                $this->_redirect('job/index/afterregister');
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl(Mage::helper('job')->getForgotUrl());
                $message = $this->__('Email này đã được dùng để đăng ký. hãy bấm vào <a href="%s">đây</a> để lấy lại mật khẩu và quyền truy cập tài khoản.', $url);
                $session->setEscapeMessages(false);
            } else {
                $message = $e->getMessage();
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost())
                ->addException($e, $this->__('Cannot save the customer.'));
        }
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
        $this->_redirectError($errUrl);
    }

	/**
     * Customer login form page
     */
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
		
		Mage::getSingleton('core/session')->setShowLogin(1);
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
	
	/**
     * Customer register form page
     */
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }

        $this->loadLayout();
		$helper = Mage::helper('job');
		$this->getLayout()->getBlock('head')->setTitle($helper->__('公開求人情報の詳細を閲覧する'));
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム '), 'title'=>$helper->__('ホーム '), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('job_apply', array('label'=>$helper->__('公開求人情報の詳細を閲覧する'), $helper->__('公開求人情報の詳細を閲覧する')));
		}
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }
}