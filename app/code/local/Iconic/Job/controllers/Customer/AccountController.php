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
		if($this->getRequest()->getParam('agree') != 1){
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
				$birthyear = $this->getRequest()->getParam('year').'/'.$this->getRequest()->getParam('month').'/'.$this->getRequest()->getParam('day');
            	$customer->setBirthYear($birthyear);
				$customer->setPhone($this->getRequest()->getParam('phone'));
				$customer->setKana($this->getRequest()->getParam('kanafirst'). ' ' .$this->getRequest()->getParam('kanalast'));
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
				//Send mail
				$mail = new Zend_Mail('UTF-8');
				$config = array(
		                    'auth' => 'login',
		                    'ssl'  => 'tls',
						    'port' => 587,
						    'username' => 'test',
						    'password' => 'testing'
							);
		 		
				$transport = new Zend_Mail_Transport_Smtp('mail.iconicvn.com', $config);
				//get general contact from config admin
				/* Sender Name */
				$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
				/* Sender Email */
				$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
				
				$bodyHtml = '<table><tbody>';			
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('登録日(Registration Date)').':</td><td> '.date('d-M-Y').'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getName().'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名カナ(Name in Kana)').':</td><td> '.$customer->getKana().'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getEmail().'</td></tr>';			
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('性別(Gender)').':</td><td> '.$customer->getSex().'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('年齢 (Age)').':</td><td> '.date('y')-$customer->getBirthYear().'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('現在住んでいる国(Resident Country)').':</td><td> '.$customer->getLocation().'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('担当(PIC)').':</td><td> '.Mage::helper('job')->getPic().'</td></tr>';
				$bodyHtml .= '</tbody></table>';
				
				$mail->setBodyHtml($bodyHtml);
				$mail->addTo('info@iconic-intl.com',Mage::helper('job')->__('IconicJP'));
				//$mail->addTo('enjoy3013@gmail.com',Mage::helper('job')->__('IconicVN'));
				$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
				$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Registration - %s - %s', $customer->getName() ,Mage::helper('job')->getPic()));
				$checkSend = $mail->send($transport);
				
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
                $message = $this->__('このメールアドレスは既に登録されています。パスワードをお忘れの場合は、<a href="%s">こちら</a>からパスワードの再発行をしてください。', $url);
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

	/**
     * Customer logout action
     */
    public function logoutAction()
    {
        $this->_getSession()->logout()
            ->renewSession()
            ->setBeforeAuthUrl($this->_getRefererUrl());

        $this->_redirect('/');
    }

}