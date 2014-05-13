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
				if($this->getRequest()->getParam('newsletter') == 1){
					$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
					$subscriber->setStatus(1)->save();
				}
                $this->_redirect('job/index/afterregister');
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = Mage::helper('job')->getBaseUrl();
				Mage::getSingleton('core/session')->setShowForgotPassword(1);
                $message = $this->__('This email has been use for register. Please click <a href="%s">here</a> to reset password.', $url);
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
		
		$helper = Mage::helper('job');
		
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム '), 'title'=>$helper->__('ホーム '), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('register', array('label'=>$helper->__('公開求人情報の詳細を閲覧する'), $helper->__('公開求人情報の詳細を閲覧する')));
		}
        $this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle($helper->__('公開求人情報の詳細を閲覧する'));
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }
	
	 /**
     * Confirm customer account by id and confirmation key
     */
    public function confirmAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        try {
            $id      = $this->getRequest()->getParam('id', false);
            $key     = $this->getRequest()->getParam('key', false);
            $backUrl = $this->getRequest()->getParam('back_url', false);
            if (empty($id) || empty($key)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load customer by id (try/catch in case if it throws exceptions)
            try {
                $customer = $this->_getModel('customer/customer')->load($id);
                if ((!$customer) || (!$customer->getId())) {
                    throw new Exception('Failed to load customer by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong customer account specified.'));
            }

            // check if it is inactive
            if ($customer->getConfirmation()) {
                if ($customer->getConfirmation() !== $key) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate customer
                try {
                    $customer->setConfirmation(null);
                    $customer->save();
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm customer account.'));
                }

                $session->renewSession();
                // log in and send greeting email, then die happy
                $session->setCustomerAsLoggedIn($customer);
                $successUrl = $this->_welcomeCustomer($customer, true);
                $this->_redirect('job/success/confirm');
                return;
            }

            // die happy
            $this->_redirectSuccess($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError($this->_getUrl('*/*/index', array('_secure' => true)));
            return;
        }
    }

}