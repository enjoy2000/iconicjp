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
	            if(!Mage::helper('client')->isEmployerSite()){	
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
						//set new pic
						$pic = Mage::helper('job')->getPic();
						$customer->setPic($pic)->save();
			        }
					//Send mail
					$mail = new Zend_Mail('UTF-8');
					$transport = Mage::helper('job')->getMailConfig();
					//get general contact from config admin
					/* Sender Name */
					$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
					/* Sender Email */
					$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
					$age = (int)date('Y' ) - (int)$birth[0];
					$birthDate = $customer->getBirthYear();
					//explode the date to get month, day and year
					$birthDate = explode("/", $birthDate);
					//get age from date or birthdate
					$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
					? ((date("Y") - $birthDate[0]) - 1)
					: (date("Y") - $birthDate[0]));
	
					$location = Mage::getModel('job/listlocation')->load($customer->getLocation())->getName();				
					$bodyHtml = '<table><tbody>';			
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('登録日(Registration Date)').':</td><td> '.date('d-M-Y').'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getName().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名カナ(Name in Kana)').':</td><td> '.$customer->getKana().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getEmail().'</td></tr>';			
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('性別(Gender)').':</td><td> '.$customer->getSex().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('年齢 (Age)').':</td><td> '. $age .'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('現在住んでいる国(Resident Country)').':</td><td> '.$location.'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('担当(PIC)').':</td><td> '.$customer->getPic().'</td></tr>';
					$bodyHtml .= '</tbody></table>';
					
					$mail->setBodyHtml($bodyHtml);
					$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicJP'));
					//$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicVN'));
					$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
					$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Registration - %s - %s', $customer->getName() , $customer->getPic()));
					$checkSend = $mail->send($transport);
					
					//check email send or not
					if($checkSend){
						$this->_redirect('job/index/afterregister');
	                	return;
					}else{
						$session->addError(Mage::helper('job')->__('Your mail has not been sent. We will try to fix this ASAP. Please be patient!'));
						$session->setCustomerFormData($this->getRequest()->getPost());
						$this->_redirect('*/*/create');
						return;
					}
	        	}else{
	        		$data = $this->getRequest()->getPost();
	        		if($data['email'] && $data['company_name'] && $data['company_address'] && $data['company_detail'] && $data['firstname']){
						/* Check Url Structure */
						if($data['company_website'] && !filter_var($data['company_website'],FILTER_VALIDATE_URL)){
							$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
							$this->_getSession()->addError($this->__('Invalid website URL.'));
		                	$this->_redirect('*/*/create');
							return;
						}
						/* Check logo image exist or not */
						if(!file_exists(Mage::getBaseDir().'/media/logo/'.$data['company_logo'])){
							$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
							$this->_getSession()->addError($this->__('You\'ve not yet uploaded your company logo.'));
		                	$this->_redirect('*/*/create');
							return;
						}
						$customer->setData($data)->save();
					}else{
	                	$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
						$this->_getSession()->addError($this->__('Not enough information.'));
	                	$this->_redirect('*/*/create');
						return;
					}
	        		
	        		$this->_dispatchRegisterSuccess($customer);
					//success action
					$session = $this->_getSession();
		            $session->setCustomerAsLoggedIn($customer);
		            $session->renewSession();
		            $url = $this->_welcomeCustomer($customer);
					
					//Send mail
					$mail = new Zend_Mail('UTF-8');
					$transport = Mage::helper('job')->getMailConfig();
					$bodyHtml = '<table><tbody>';			
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('登録日(Registration Date)').':</td><td> '.date('d-M-Y').'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getCompanyName().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getCompanyAddress().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getCompanyWebsite().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getCompanySize().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getCompanyDetail().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getEmail().'</td></tr>';	
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名(Name)').':</td><td> '.$customer->getName().'</td></tr>';
					$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$customer->getEmail().'</td></tr>';		
					$bodyHtml .= '</tbody></table>';
					$mail->setBodyHtml($bodyHtml);
					$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicJP'));
					//$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicVN'));
					$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
					$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Company Registration - %s', $customer->getCompanyName()));
					$checkSend = $mail->send($transport);
					
					/* Remove session for upload company logo */
					Mage::getSingleton('core/session')->unsCompanyLogo();
					
	                $this->_redirect('job/index/afterregister');
	                return;
	        	}
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('job/success/forgotpass');
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
        $errUrl = $this->_getUrl(Mage::helper('job')->getRegisterUrl(), array('_secure' => true));
        $this->_redirectError($errUrl);
    }

	/**
     * Customer login form page
     */
    public function loginAction()
    {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if ($this->_getSession()->isLoggedIn()) {
	            $this->_redirect('*/*/');
	            return;
	        }
			$this->getResponse()->setHeader('Login-Required', 'true');
	        $this->loadLayout();
			
	        $this->_initLayoutMessages('customer/session');
	        $this->_initLayoutMessages('catalog/session');
	        $this->renderLayout();
		}else{
			$this->_getSession()->setShowLogin(1);
			$this->_redirect('/');
			return;
		}
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

        header("Location: " . Mage::helper('job')->getBaseUrl());die;
    }
	
	/**
     * Forgot customer password page
     */
    public function forgotPasswordAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('forgotPassword')->setEmailValue(
            $this->_getSession()->getForgottenEmail()
        );
        $this->_getSession()->unsForgottenEmail();

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }
	
	/**
     * Reset forgotten password
     * Used to handle data recieved from reset forgotten password form
     */
    public function resetPasswordPostAction()
    {
        $resetPasswordLinkToken = (string) $this->getRequest()->getQuery('token');
        $customerId = (int) $this->getRequest()->getQuery('id');
        $password = (string) $this->getRequest()->getPost('password');
        $passwordConfirmation = (string) $this->getRequest()->getPost('confirmation');

        try {
             if (!is_int($customerId)
	            || !is_string($resetPasswordLinkToken)
	            || empty($resetPasswordLinkToken)
	            || empty($customerId)
	            || $customerId < 0
	        ) {
	            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Invalid password reset token.'));
	        }
	
	        /** @var $customer Mage_Customer_Model_Customer */
	        $customer = $this->_getModel('customer/customer')->load($customerId);
	        if (!$customer || !$customer->getId()) {
	            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Wrong customer account specified.'));
	        }
	
	        $customerToken = $customer->getRpToken();
	        if (strcmp($customerToken, $resetPasswordLinkToken) != 0 || $customer->isResetPasswordLinkTokenExpired()) {
	            throw Mage::exception('Mage_Core', $this->_getHelper('customer')->__('Your password reset link has expired.'));
	        }
        } catch (Exception $exception) {
            $this->_getSession()->addError( $this->_getHelper('customer')->__('Your password reset link has expired.'));
            $this->_redirect('*/*/resetpassword');
            return;
        }

        $errorMessages = array();
        if (iconv_strlen($password) <= 0) {
            array_push($errorMessages, $this->_getHelper('customer')->__('New password field cannot be empty.'));
        }
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->_getModel('customer/customer')->load($customerId);

        $customer->setPassword($password);
        $customer->setConfirmation($passwordConfirmation);
        $validationErrorMessages = $customer->validate();
        if (is_array($validationErrorMessages)) {
            $errorMessages = array_merge($errorMessages, $validationErrorMessages);
        }

        if (!empty($errorMessages)) {
            $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
            foreach ($errorMessages as $errorMessage) {
               Mage::getSingleton('customer/session')->addError($errorMessage);
            }
            $this->_redirect('*/*/resetpassword', array(
                'id' => $customerId,
                'token' => $resetPasswordLinkToken
            ));
            return;
        }

        try {
            // Empty current reset password token i.e. invalidate it
            $customer->setRpToken(null);
            $customer->setRpTokenCreatedAt(null);
            $customer->setConfirmation(null);
            $customer->save();
            Mage::getSingleton('customer/session')->addSuccess( $this->_getHelper('customer')->__('Your password has been updated.'));
            Mage::getSingleton('customer/session')->setShowLogin(1);
            $this->_redirect('/');
        } catch (Exception $exception) {
            Mage::getSingleton('customer/session')->addException($exception, $this->__('Cannot save a new password.'));
            $this->_redirect('*/*/resetpassword', array(
                'id' => $customerId,
                'token' => $resetPasswordLinkToken
            ));
            return;
        }
    }

	/**
     * Forgot customer account information page
     */
    public function editAction()
    {
    	// redirect if user not login 
		if (!$this->_getSession()->isLoggedIn()) {
            $session = $this->_getSession();
			$session->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $session->setBeforeAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $this->_redirect('/');
            return $this;
        }
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
		if(!Mage::helper('client')->isEmployerSite()){
    		header("Location: ".Mage::helper('job')->getUrl().'company/');
    		die;
    	}
        $block = $this->getLayout()->getBlock('customer_edit');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $data = $this->_getSession()->getCustomerFormData(true);
        $customer = $this->_getSession()->getCustomer();
        $this->_getSession()->setCustomerFormData(false);
        if ($this->getRequest()->getParam('changepass') == 1) {
            $customer->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));
        $this->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        $this->renderLayout();
    }

	/**
     * Change customer password action
     */
    public function editPostAction()
    {
    	if(!Mage::helper('client')->isEmployerSite()){
    		header("Location: ".Mage::helper('job')->getUrl().'company/');
    		die;
    	}
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
        	$customer = $this->_getSession()->getCustomer();
			
            // If password change was requested then add it to common validation scheme
            if ($this->getRequest()->getParam('confirmation')) {
                $currPass   = $this->getRequest()->getPost('current_password');
                $newPass    = $this->getRequest()->getPost('password');
                $confPass   = $this->getRequest()->getPost('confirmation');

                $oldPass = $customer->getPasswordHash();
                if ( $this->_getHelper('core/string')->strpos($oldPass, ':')) {
                    list($_salt, $salt) = explode(':', $oldPass);
                } else {
                    $salt = false;
                }

                if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                    if (strlen($newPass)) {
                        /**
                         * Set entered password and its confirmation - they
                         * will be validated later to match each other and be of right length
                         */
                        $customer->setPassword($newPass);
                        $customer->setConfirmation($confPass);
						
						// Validate account and compose list of errors if any
		                $customerErrors = $customer->validate();
		                if (is_array($customerErrors)) {
		                	foreach($customerErrors as $error){
		                    	$this->_getSession()->addError($error);
		                	}
		                	$this->_redirect('*/*/edit', array('changepass'=>1));
							return;
		                }
                    } else {
						$this->_getSession()->addError($this->__('New password field cannot be empty.'));
	                	$this->_redirect('*/*/edit', array('changepass'=>1));
						return;
                    }
                } else {
					$this->_getSession()->addError($this->__('このパスワードは有効ではありません'));
                	$this->_redirect('*/*/edit', array('changepass'=>1));
					return;
                }
			}else{
				$data = $this->getRequest()->getPost();
				/* Check data POST enough or not */
				if($data['company_logo'] && $data['company_name'] && $data['company_address'] && $data['company_detail'] && $data['firstname']){
					$customer->setCompanyLogo($data['company_logo'])
							->setCompanyName($data['company_name'])
							->setCompanyAddress($data['company_address'])
							->setCompanySize($data['company_size'])
							->setCompanyWebsite($data['company_website'])
							->setCompanyDetail($data['company_detail'])
							->setFirstname($data['firstname']);
					/* Check Url Structure */
					if($data['company_website'] && !filter_var($data['company_website'],FILTER_VALIDATE_URL)){
						$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
						$this->_getSession()->addError($this->__('Invalid website URL.'));
	                	$this->_redirect('*/*/edit');
						return;
					}
					/* Check logo image exist or not */
					if(!file_exists(Mage::getBaseDir().'/media/logo/'.$data['company_logo'])){
						$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
						$this->_getSession()->addError($this->__('You\'ve not yet uploaded your company logo.'));
	                	$this->_redirect('*/*/edit');
						return;
					}
				}else{
                	$this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
					$this->_getSession()->addError($this->__('情報が不足しています'));
                	$this->_redirect('*/*/edit');
					return;
				}
				
				// Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                	foreach($customerErrors as $error){
                    	$this->_getSession()->addError($error);
                	}
                	$this->_redirect('*/*/edit');
					return;
                }
			}

            try {
                $customer->save();
                $this->_getSession()->setCustomer($customer);
                $this->_getSession()->addSuccess($this->__('あなたのアカウント情報は確かに保存されました'));
                $this->_getSession()->setCustomerFormData(false);
                $this->_redirect('*/*/edit');
                return;
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    }

	/**
     * Default customer account page
     */
    public function indexAction()
    {
    	if(Mage::helper('client')->isEmployerSite()){
			$this->_redirect('client/job/manage');
			return;
		}else{
			$this->_redirect('/');
			return;
		}
    	
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }
}