<?php
class Iconic_Job_IndexController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction(){        
		$this->loadLayout();
       	$this->getLayout()->getBlock('head')->setTitle($this->__('Jobs Board For IconicVN')); 
		$langs = Mage::getModel('job/location')->getCollection();
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$birth = explode('/', $customer->getBirthYear());
		$age = (int)date('Y') - (int)$birth[0];
		$birthDate = $customer->getBirthYear();
		  //explode the date to get month, day and year
		  $birthDate = explode("/", $birthDate);
		  //get age from date or birthdate
		  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
		    ? ((date("Y") - $birthDate[0]) - 1)
		    : (date("Y") - $birthDate[0]));
    }
	
	public function contactAction(){
		$this->loadLayout();
		$post = $this->getRequest()->getPost();
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('create_cv', array('label'=>$helper->__('問い合わせる'), $helper->__('問い合わせる')));
		}
		$this->getLayout()->getBlock('head')->setTitle($helper->__('問い合わせる'));
		if(count($post)){
			if (!$this->_validateFormKey()) {
	            return $this->_redirect('*/*/contact');
	        }
			try{
				$mail = new Zend_Mail('UTF-8');
				$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
				$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
				
				
				$transport = Mage::helper('job')->getMailConfig();
				
				
				$bodyHtml = '<table><tbody>';
				$bodyHtml .= '<tr><td align="center" colspan="2">' . Mage::helper('job')->__('Contact from IconicJP') . '</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Name').':</td><td> '.$post['name'].'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Email').':</td><td> '.$post['email'].'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Company Name').':</td><td> '.$post['companyname'].'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Phone').':</td><td> '.$post['phone'].'</td></tr>';
				$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('Nội dung').':</td><td> '.nl2br($post['message']).'</td></tr>';
				$bodyHtml .= '</tbody></table>';
				
				$mail->setBodyHtml($bodyHtml);
				$mail->addTo('auto_iconicjp@iconic-intl.com', Mage::helper('job')->__('IconicJP'));
				$mail->setFrom($post['email'], Mage::helper('job')->__('IconicJP'));
				$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Contact from %s-%s', $post['name'], $post['companyname']));
				$checkSend = $mail->send($transport);
				if($checkSend){
					Mage::getSingleton('core/session')->addSuccess(Mage::helper('job')->__('メッセージが送信されました。'));
					$this->_redirect('*/*/contact');
				}
			}catch(Exception $e){
				Mage::getSingleton('core/session')->addError(Mage::helper('job')->__('There is some error. Please try again later.'));
				$this->_redirect('*/*/contact');
			}
		}
		$this->renderLayout();
	}
	
	public function afterregisterAction(){
		$this->loadLayout();
		//set breadcrumbs		
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('search_results', array('label'=>$helper->__('転職支援サービスに申し込む'), 'title'=>$helper->__('転職支援サービスに申し込む')));
		}
		$this->getLayout()->getBlock('head')->setTitle($helper->__('転職支援サービスに申し込む'));
		$this->renderLayout();
	}
	
	public function createcvAction(){
		$this->loadLayout();
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
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
			if (!$this->_validateFormKey()) {
	            return $this->_redirect('*/*/createcv');
	        }
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$data = $this->getRequest()->getPost();
			$birthday = $data['month'].'/'.$data['day'].'/'.$data['year'];
			//explode the date to get month, day and year
			$birthDate = explode("/", $birthday);
			//get age from date or birthdate
			$now = date('d/m/Y');
			$arr = array(
					$customer->getName(),
					$customer->getKana() ,
					$birthday,
					($data['sex'] ==1) ? 'M' : 'F',
					$data['nation'],
					$data['phone1'].'-'.$data['phone2'].'-'.$data['phone3'],
					$data['email'],
					$data['country'],
					$data['province'],
					$data['location'],
					$data['degree'].'('.$data['graduated'].')',
					$data['major'],
					$data['school'],
					$data['numbercompany'],
					$data['workfunction'],
					$data['companyname'],
					$data['companysalary'],
					$data['companycategory'],
					$data['companytitle'],
					$data['division'],
					$data['companystatus'],
					$data['companyemploye'],
					$data['yearfrom'].'/'.$data['monthfrom'].'-'.$data['yearto'].'/'.$data['monthto'] . '(' . $data['workstatus'] . ')',
					$data['jobcontent'],
					$data['jobdescription'],
					$data['otherwork'],
					$data['english'],
					$data['japanese'],
					$data['otherlang'],
					$data['category'],
					$data['function'],
					$data['expectedlevel'],
					$data['expectedcountry'],
					$data['expectedsalary'],
					$data['requirements']
			);
			
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
			foreach($data['filenames'] as $filename){
				$file = Mage::getBaseDir().'/files/'.$customer->getId().'/'.$filename;
				$at = new Zend_Mime_Part(file_get_contents($file));
				$at->filename = basename($file);
				$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
				$at->encoding = Zend_Mime::ENCODING_BASE64;
				        
				$mail->addAttachment($at);
			}
			$transport = Mage::helper('job')->getMailConfig();
			//get general contact from config admin
			/* Sender Name */
			$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
			/* Sender Email */
			$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
			
			$bodyHtml = '<table><tbody>';			
			$bodyHtml .= '<tr><td><h1>'.Mage::helper('job')->__('CV for importing to IS.').'</h1></td></tr>';			
			$bodyHtml .= '<tr><td>Name:</td><td>'.$customer->getName().'</td></tr>';		
			$bodyHtml .= '<tr><td>Email:</td><td>'.$data['email'].'</td></tr>';	
			$bodyHtml .= '</tbody></table>';
			
			
			$baseurl = Mage::getBaseUrl();
			$logourl = $baseurl.'skin/frontend/default/iconic/images/mail-logo.png';
			$name = $customer->getFirstname();
			$mail->setBodyHtml($bodyHtml);
			$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicJP'));
			//$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicVN'));
			$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
			$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Candidate - %s - %s', $data['first']. ' ' .$data['last'] , $customer->getPic()));
			$checkSend = $mail->send($transport);
			
			//send mail to customer
			$bodyHtml2 = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
						<title>Your Message Subject or Title</title>
						<style type="text/css">
					
					
							p {margin: 1em 0;}
					
							/** Hotmail header color reset: Hotmail replaces your header color styles with a green color on H2, H3, H4, H5, and H6 tags. In this example, the color is reset to black for a non-linked header, blue for a linked header, red for an active header (limited support), and purple for a visited header (limited support).  Replace with your choice of color. The !important is really what is overriding Hotmail\'s styling. Hotmail also sets the H1 and H2 tags to the same size.
					
							Bring inline: Yes.
							**/
							h1, h2, h3, h4, h5, h6 {color: black !important;}
					
							h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
					
							h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
								color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
							 }
					
							h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
								color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
							}
					
							table td {border-collapse: collapse;}
					
							table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
							a {color: orange;}
					
					
							@media only screen and (max-device-width: 480px) {
					
								a[href^="tel"], a[href^="sms"] {
											text-decoration: none;
											color: black; /* or whatever your want */
											pointer-events: none;
											cursor: default;
										}
					
								.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
											text-decoration: default;
											color: orange !important; /* or whatever your want */
											pointer-events: auto;
											cursor: default;
										}
							}
					
							/* More Specific Targeting */
					
							@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
								/* You guessed it, ipad (tablets, smaller screens, etc) */
					
								/* Step 1a: Repeating for the iPad */
								a[href^="tel"], a[href^="sms"] {
											text-decoration: none;
											color: blue; /* or whatever your want */
											pointer-events: none;
											cursor: default;
										}
					
								.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
											text-decoration: default;
											color: orange !important;
											pointer-events: auto;
											cursor: default;
										}
							}
						</style>
					
						<!-- Targeting Windows Mobile -->
						<!--[if IEMobile 7]>
						<style type="text/css">
					
						</style>
						<![endif]-->
					
						<!-- ***********************************************
						****************************************************
						END MOBILE TARGETING
						****************************************************
						************************************************ -->
					
						<!--[if gte mso 9]>
						<style>
							/* Target Outlook 2007 and 2010 */
						</style>
						<![endif]-->
					</head>
					<body>
						<table width="600" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
							<tr height="15" bgcolor="#b5c6f7"><td></td></tr>
							<tr>
								<td>
								<table>
									<tr height="20">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="500"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr>
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td><a href="'.$baseurl.'"><img src="'.$logourl.'" /></a></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr>
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td>
											'.$name.$customer->getLastname().'さん、はじめまして。<br />
											<br />
											この度は、アイコニックの転職支援サービスにお申込みいただき、ありがとうございます！<br />
											<br />
											これから'.$name.'さんがアジアを舞台にご活躍できるよう、'.$name.'さんの転職活動をアジア転職のプロであるアイコニックのキャリアアドバイザーがサポートしてまいります。<br />
											<br />
											一両日中に担当のキャリアアドバイザーより、'.$name.'さんに個別に連絡を入れさせていただきます。転職が成功するよう、一緒にがんばりましょう！！<br />
											<br />
											'.$name.'さんのアジア転職が成功します様に！<br />
											<br />  
											――――――――――――――――――――――――――――――――――――<br />
											「国境を越えて働く人たちを応援する」<br />
											<br />
											発行:　株式会社アイコニックジャパン　<br />
											配信停止：　件名「配信停止希望」にて info@iconic-jp.com へご連絡下さい。<br />
											アイコニックのウェブサイト　www.iconic-jp.com <br />
											Facebook 公式ページ　https://www.facebook.com/iconicvn <br />
											個人情報の取扱いについては<a href="'.$baseurl.'privacy-policy">個人情報保護方針</a>をご覧下さい。<br />
											――――――――――――――――――――――――――――――――――――<br />
											※このメールは、送信専用メールアドレスから配信されています。<br />
											ご返信いただいてもお答えできませんので、ご了承ください。
										</td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr height="15" bgcolor="#b5c6f7"><td></td></tr>
						</table>
					</body>
					</html>
					';
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$mail2 = new Zend_Mail('UTF-8');
			$mail2->setBodyHtml($bodyHtml2);
			$mail2->addTo($customer->getEmail(),$customer->getName());
			$mail2->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
			$mail2->setSubject(Mage::helper('job')->__('【アイコニック】転職支援サービスへのお申込みを受け付けました！'));
			$checkSend2 = $mail2->send($transport);
			if($checkSend && $checkSend2){
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
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('job')->__('転職支援サービスに申し込む'));
		$this->renderLayout();
	}
	
	public function sitemapAction(){
		$this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('job')->__('サイトマップ'));
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
                            $message = Mage::helper('job')->__('メールアドレス、又は、パスワードが違います。');
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
					$message = $this->__('%sへパスワードを再発行するためのURLを送信しました。<br />メールボックスをご確認ください。', $email);
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
			if (!$this->_validateFormKey()) {
	            return $this->_redirect('*/*/request');
	        }
			if($this->getRequest()->get('email') == $this->getRequest()->get('confirm_email')){
				try{
					$requestModel = Mage::getModel('job/request');
					$requestModel->setData($this->getRequest()->getPost())->save();
					
					//send auto mail
					$baseurl = Mage::getBaseUrl();
					$logourl = $baseurl.'skin/frontend/default/iconic/images/mail-logo.png';
					$data = $this->getRequest()->getPost();
					$name = $this->getRequest()->get('name');
					$email = $this->getRequest()->get('email');
					$mail = new Zend_Mail('UTF-8');
					$transport = Mage::helper('job')->getMailConfig();
					//get general contact from config admin
					/* Sender Name */
					$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
					/* Sender Email */
					$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
					
					$bodyHtml = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
						<title>Your Message Subject or Title</title>
						<style type="text/css">
					
					
							p {margin: 1em 0;}
					
							/** Hotmail header color reset: Hotmail replaces your header color styles with a green color on H2, H3, H4, H5, and H6 tags. In this example, the color is reset to black for a non-linked header, blue for a linked header, red for an active header (limited support), and purple for a visited header (limited support).  Replace with your choice of color. The !important is really what is overriding Hotmail\'s styling. Hotmail also sets the H1 and H2 tags to the same size.
					
							Bring inline: Yes.
							**/
							h1, h2, h3, h4, h5, h6 {color: black !important;}
					
							h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
					
							h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
								color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
							 }
					
							h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
								color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
							}
					
							table td {border-collapse: collapse;}
					
							table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
							a {color: orange;}
					
					
							@media only screen and (max-device-width: 480px) {
					
								a[href^="tel"], a[href^="sms"] {
											text-decoration: none;
											color: black; /* or whatever your want */
											pointer-events: none;
											cursor: default;
										}
					
								.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
											text-decoration: default;
											color: orange !important; /* or whatever your want */
											pointer-events: auto;
											cursor: default;
										}
							}
					
							/* More Specific Targeting */
					
							@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
								/* You guessed it, ipad (tablets, smaller screens, etc) */
					
								/* Step 1a: Repeating for the iPad */
								a[href^="tel"], a[href^="sms"] {
											text-decoration: none;
											color: blue; /* or whatever your want */
											pointer-events: none;
											cursor: default;
										}
					
								.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
											text-decoration: default;
											color: orange !important;
											pointer-events: auto;
											cursor: default;
										}
							}
						</style>
					
						<!-- Targeting Windows Mobile -->
						<!--[if IEMobile 7]>
						<style type="text/css">
					
						</style>
						<![endif]-->
					
						<!-- ***********************************************
						****************************************************
						END MOBILE TARGETING
						****************************************************
						************************************************ -->
					
						<!--[if gte mso 9]>
						<style>
							/* Target Outlook 2007 and 2010 */
						</style>
						<![endif]-->
					</head>
					<body>
						<table width="600" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
							<tr height="15" bgcolor="#b5c6f7"><td></td></tr>
							<tr>
								<td>
								<table>
									<tr height="20">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="500"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr>
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td><a href="'.$baseurl.'"><img src="'.$logourl.'" /></a></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr>
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td>
											'.$data['company_name'].'<br />
											<br />
											長浜みぎわ様<br />
											<br />
											この度はアイコニックへ御社求人のご依頼を頂戴し、誠に有難うございます。<br />
											弊社は、アジアを舞台に活躍できる日本人材やアジア現地の人材に専門特化した人材紹介エージェントです。<br />
											<br />
											早速、'.$data['name'].'様からご相談頂きました今回の御社求人の採用支援にあたりまして、弊社コンサルタントより、'.$data['name'].'様へ個別に連絡を入れさせて頂きます。<br />
											<br />
											なお、弊社サービスに関し、ご不明点などございましたら、お気軽にこちらからご連絡くださいませ。<br />
											<br />
											御社がアジアを舞台に満足いく人材採用ができるよう、弊社一同、精一杯支援させて頂きます！<br />
											<br />
											<strong style="color: #4571EB;">株式会社アイコニックジャパン</strong><br />
											東京都中央区新富1丁目7番3号阪和第2別館ビル6階<br />
											電話 :  03 6222 5520   |  ウェブサイト : iconic-jp.com
										</td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="1">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td bgcolor="#457EB"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr>
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td>
											Dear Mr./Ms.'.$name.',<br />
											<br />
											Thank you very much for your interest in ICONIC JAPAN CO.,LTD<br />
											Our professional consultants will contact you soon.<br />
											<br />
											If you have any inquiry, please do not hesitate to contact us for further information from info@iconic-jp.com.<br />
											We are willing to support you anytime.<br />
											<br />
											Thanks & Best Regards,<br />
											<b style="color: #4571EB;">ICONIC JAPAN CO.,LTD.</b><br />
											<span style="color: #636466">Floor 6, Hanwa 2nd Bldg,1－7－3 Shintomi Chuo-ku, Tokyo, Japan</span><br />
											<span style="color: #636466">TEL :  03 6222 5520   |  WEB : iconic-jp.com</span><br />
										</td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
									<tr height="40">
										<td width="15" bgcolor="#b5c6f7"></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td></td>
										<td width="35" bgcolor="#FFFFFF"></td>
										<td width="15" bgcolor="#b5c6f7"></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr height="15" bgcolor="#b5c6f7"><td></td></tr>
						</table>
					</body>
					</html>
					';
					
					$mail->setBodyHtml($bodyHtml);
					$mail->addTo($email,'IconicJP');
					$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
					$mail->setSubject('【アイコニック】御社からの求人依頼を受付ました。');
					$checkSend = $mail->send($transport);
					
					$mail2 = new Zend_Mail('UTF-8');
					$data = $this->getRequest()->getPost();
					$bodyHtml2 = '<table><tbody>';
					$bodyHtml2 .= '<tr><td>Name:</td><td>'.$data["name"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Full Name:</td><td>'.$data["full_name"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Company Name:</td><td>'.$data["company_name"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Work Content:</td><td>'.$data["work_content"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Address:</td><td>'.$data["address"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Phone:</td><td>'.$data["phone"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Email:</td><td>'.$data["email"].'</td></tr>';
					$bodyHtml2 .= '<tr><td>Job Content:</td><td>'.$data["job_content"].'</td></tr>';
					$bodyHtml2 .= '</tbody></table>';
					$mail2->setBodyHtml($bodyHtml2);
					$mail2->addTo('auto_iconicjp@iconic-intl.com','IconicJP');
					$mail2->setFrom('info@iconicvn.com', Mage::helper('job')->__('IconicJP'));
					$mail2->setSubject('ICONIC-JP Employer Request - %s', $data['company_name']);
					$checkSend2 = $mail2->send($transport);
					if($checkSend && $checkSend2){
						$this->_redirect('job/success/request');
						return;
					}else{
						Mage::getSingleton('core/session')->addError($helper->__('Cannot send email.'));
					}
				}catch(Exception $e){
					Mage::getSingleton('core/session')->addError($e);
				}
			}else{
				Mage::getSingleton('core/session')->addError($helper->__('Please confirm your email.'));
			}
		}
		
		$this->renderLayout();
	}
}