<?php
class Iconic_Job_IndexController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction(){        
		$this->loadLayout();
       	$this->getLayout()->getBlock('head')->setTitle($this->__('Jobs Board For IconicVN')); 
		$langs = Mage::getModel('job/location')->getCollection();
		foreach($langs as $lang){
			$urlkey = Mage::helper('job')->formatUrlKey($lang->getNameEn());
			$lang->setUrlKey($urlkey)->save();
			echo $urlkey . '<br />';
		}
    }
	
	public function contactAction(){
		$post = $this->getRequest()->getPost();
		if($post['email'] && $post['subject'] && $post['message']){
			try{
				$mail = new Zend_Mail('UTF-8');
				$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
				$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
				
				
				$config = array(
		                    'auth' => 'login',
		                    'ssl'  => 'tls',
						    'port' => 587,
						    'username' => 'test',
						    'password' => 'testing'
							);
		 
				$transport = new Zend_Mail_Transport_Smtp('mail.iconicvn.com', $config);
				
				
				$bodyHtml = '<table><tbody>';
				$bodyHtml .= '<tr><td align="center" colspan="2">' . Mage::helper('job')->__('Liên lạc từ IconicVN') . '</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Email').':</td><td> '.$post['email'].'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Nội dung').':</td><td> '.$post['message'].'</td></tr>';
				$bodyHtml .= '</tbody></table>';
				
				$mail->setBodyHtml($bodyHtml);
				$mail->addTo('info@iconic-intl.com', Mage::helper('job')->__('IconicVN'));
				$mail->setFrom($post['email'], $post['email']);
				$mail->setSubject($post['subject']);
				$checkSend = $mail->send($transport);
				if($checkSend){
					echo Mage::helper('job')->__('Email của bạn đã được gửi thành công. Cảm ơn.');
				}
			}catch(Exception $e){
				echo Mage::helper('job')->__('Đã có lỗi xảy ra. Xin vui lòng thử lại sau.');
			}
		}else{
			$this->_redirect('/');
			return;
		}
		
	}
	
	public function afterregisterAction(){
		$this->loadLayout();
		//set breadcrumbs		
		$helper = Mage::helper('job');
		/*
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('Trang chủ'), 'title'=>$helper->__('Trang chủ'), 'link'=>Mage::getBaseUrl()));
			$breadcrumbs->addCrumb('search_results', array('label'=>$helper->__('Đăng ký'), 'title'=>$helper->__('Đăng ký'), 'link'=>Mage::getUrl(Mage::helper('job')->getRegisterUrl())));
		}
		*/
		$this->renderLayout();
	}
	
	public function createcvAction(){
		$this->loadLayout();
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('core/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $session->setBeforeAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $this->_redirect('/');
            return $this;
        }
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('create_cv', array('label'=>$helper->__('転職支援サービスに申し込む'), $helper->__('転職支援サービスに申し込む')));
		}
		$this->getLayout()->getBlock('head')->setTitle($helper->__('転職支援サービスに申し込む'));
		if($this->getRequest()->getPost()){
			$data = $this->getRequest()->getPost();
			$birthday = $data['month'].'/'.$data['day'].'/'.$data['year'];
			//explode the date to get month, day and year
			$birthDate = explode("/", $birthday);
			//get age from date or birthdate
			$now = date('d/m/Y');
			$loc = Mage::helper('job')->noAccent($data['location']);
			$loc2 = Mage::helper('job')->noAccent($data['location2']);
			if($data['education'] == 1){
				$arr = array($data['ho'].' '.$data['ten'], $birthday, $data['sex'], $data['nation'], $data['address'], $loc, $data['country'], $data['phone'], $data['email'], $data['salary'], 
							$data['currency'], $data['salarytype'], $data['salary2'], $data['currency2'], $data['salarytype2'],  
							$data['category'], $data['function'], $loc2, $data['level'], '', $data['school'], $data['spec'], $data['degree'], $data['graduate'], 
							$data['category2'], $data['function2'], $data['level2'], $data['exp'], $data['detail'], $data['jp'], $data['en'], $data['vn'], $data['otherlang'], $data['skill'], 
							$data['decide'], $now);
			}else{
				$arr = array($data['ho'].' '.$data['ten'], $birthday, $data['sex'], $data['nation'], $data['address'], $loc, $data['country'], $data['phone'], $data['email'], $data['salary'], 
							$data['currency'], $data['salarytype'], $data['salary2'], $data['currency2'], $data['salarytype2'],  
							$data['category'], $data['function'], $loc2, $data['level'], $data['school'], '', $data['spec'], $data['degree'], $data['graduate'], 
							$data['category2'], $data['function2'], $data['level2'], $data['exp'], $data['detail'], $data['jp'], $data['en'], $data['vn'], $data['otherlang'], $data['skill'], 
							$data['decide'], $now);
			}
			
			
			//Write Excel File
			/** PHPExcel */
			include Mage::getBaseDir().'/lib/PHPExcel.php';
			/** PHPExcel_Writer_Excel2007 */
			include Mage::getBaseDir().'/lib/PHPExcel/Writer/Excel2007.php';
			$locationFile = Mage::getBaseDir()."/tmp/".Mage::getSingleton('customer/session')->getCustomer()->getEmail().".xlsx";
			Mage::helper('job')->writeExcel($locationFile,$arr);
			
			//Send mail
			$mail = new Zend_Mail('UTF-8');
			$at = new Zend_Mime_Part(file_get_contents($locationFile));
			$at->filename = basename($locationFile);
			$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$mail->addAttachment($at);
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
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('CV for importing to IS.').':</td></tr>';
			$bodyHtml .= '</tbody></table>';
			
			$mail->setBodyHtml($bodyHtml);
			$mail->addTo('auto_iconic_vn@iconic-intl.com',Mage::helper('job')->__('IconicVN'));
			//$mail->addTo('enjoy3013@gmail.com',Mage::helper('job')->__('IconicVN'));
			$mail->setFrom('info@iconicvn.com', Mage::helper('job')->__('IconicVN'));
			$mail->setSubject(Mage::helper('job')->__('[IS] CV của %s %s', $data['ho'], $data['ten']));
			$checkSend = $mail->send($transport);
			if($checkSend){
				Mage::getSingleton('customer/session')->getCustomer()->setCreatecv(1)->save();
				$this->_redirect('job/index/aftercreatecv');return;
			}else{
				Mage::getSingleton('core/session')->addError(Mage::helper('job')->__('Cannot send email.'));
				$this->_redirect(Mage::helper('job')->getCreateCVUrl()); return;
			}
		}
		$this->renderLayout();
	}

	public function aftercreatecvAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function sitemapAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function ajaxloginPostAction(){
        $session = Mage::getSingleton('customer/session');

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                    }
					$status = true;
					$message = $session->getAfterAuthUrl();
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
                            $status = false;
                            $message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            $status = false;
                            break;
                        default:
                            $message = $e->getMessage();
                            $status = false;
                    }
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                	$message = $e;
                    $status = false;
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $status = false;
                $message = $this->__('Login and password are required.');
            }
			header('Content-Type: application/json');
			echo json_encode(array('message' => $message, 'status' => $status));
        }
	}

	/**
     * Forgot customer password action
     */
    public function ajaxforgotPasswordAction()
    {
    	$session = Mage::getSingleton('customer/session');
        $email = (string) $this->getRequest()->getPost('email');
        if ($email) {
			if (!Zend_Validate::is($email, 'EmailAddress')) {
                $session->setForgottenEmail($email);
                $message = $this->__('Invalid email address.');
                $status = false;
            }
			
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);
            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken =  Mage::helper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
					$message = $this->__('%sでアカウントがあるお方はパスワードリセットの為メール送信致しました。メールをご確認ください。', $email);
					$status = true;
                } catch (Exception $exception) {
                    $message = $exception->getMessage();
					$status = false;
                }
            }else{
            	$message = $this->__('This email has not been used.');
				$status = false;
            }
        } else {
            $message =  $this->__('Please enter your email.');
			$status = false;
        }
		header('Content-Type: application/json');
		echo json_encode(array('message' => $message, 'status' => $status));
    }

	public function requestAction(){
		$this->loadLayout();
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('create_cv', array('label'=>$helper->__('求人依頼申込'), $helper->__('求人依頼申込')));
		}
		$this->getLayout()->getBlock('head')->setTitle($helper->__('求人依頼申込'));
		if($this->getRequest()->getPost()){
			try{
				$requestModel = Mage::getModel('job/request');
				$requestModel->setData($this->getRequest()->getPost())->save();
				$this->_redirect('job/success/request');
				return;
			}catch(Exception $e){
				Mage::getSingleton('core/session')->addError($helper->__('Cannot send request.'));
			}
		}
		
		$this->renderLayout();
	}
}