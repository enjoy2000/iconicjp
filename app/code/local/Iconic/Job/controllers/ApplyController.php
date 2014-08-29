<?php
class Iconic_Job_ApplyController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){		
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
		
		$id = (int) $this->getRequest()->get('id');
		if($id <=0){
			Mage::helper('job')->redirectToSearchPage();
		}
		
		$item = Mage::getModel('job/job')->load($id);
		if(!$item->getId()){
			Mage::helper('job')->redirectToSearchPage();
		}
		$block = $this->getLayout()->getBlock('job_apply');
		//set breadcrumbs		
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('search_results', array('label'=>$helper->__('コミュニケーション・メディ'), 'title'=>$helper->__('コミュニケーション・メディ'), 'link'=>Mage::getUrl(Mage::helper('job')->getSearchUrl())));
			$breadcrumbs->addCrumb('job_apply', array('label'=>$helper->__('応募する | %s', $item->getTitle()), $helper->__('応募する | %s', $item->getTitle())));
		}	
		//set user
		$user = Mage::getSingleton('customer/session')->getCustomer();
		$block->setUser($user);
		//set title by job title
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('job')->__('Ứng tuyển').' '.$item->getTitle()); 
		//set item to block
		$block->setItem($item);
		//set other varibles from other models			
		$block->setCategory(Mage::getModel('job/category')->load($item->getCategoryId()));
		$block->setFunctionCategory(Mage::getModel('job/category')->load($item->getFunctionCategoryId()));
		$block->setLocation(Mage::getModel('job/location')->load($item->getLocationId()));
		$block->setLevel(Mage::getModel('job/level')->load($item->getJobLevel()));
		$block->setType(Mage::getModel('job/type')->load($item->getJobType()));
		
		//get jobs form same category
		$block->setJobsInCategory($item->getJobsInCategory());
		       
		$this->renderLayout();
		   
	}

	public function uploadAction(){
		error_reporting(E_ALL | E_STRICT);
		require(Mage::getBaseDir().'/UploadHandler.php');
		$upload_handler = new UploadHandler();
		$user = Mage::getSingleton('customer/session')->getCustomer();
		$upload_handler->options['upload_dir'] = Mage::getBaseDir().'/files/'.$user->getId().'/';
		$upload_handler->options['upload_url'] = Mage::getBaseUrl().'files/'.$user->getId().'/';
		$upload_handler->options['accept_file_types'] = '/\.(gif|jpe?g|png|docx?|xlsx?|pptx?|pdf|html|txt)$/i';
		$upload_handler->initialize();
	}
	
	public function sendAction(){
		$this->loadLayout();
		if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/send');
        }
		
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $session->setBeforeAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $this->_redirect('/');
            return $this;
        }
		$id = (int) $this->getRequest()->get('id');
		if($id <=0){
			Mage::helper('job')->redirectToSearchPage();
		}
		
		try{
			$user = Mage::getSingleton('customer/session')->getCustomer();
			
			$data = $this->getRequest()->getPost();
			$job = Mage::getModel('job/job')->load($id);
			$block = $this->getLayout()->getBlock('job_apply_success');
			$block->setItem($job);
			
			//set breadcrumbs		
			$helper = Mage::helper('job');
			if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
				$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム '), 'title'=>$helper->__('ホーム '), 'link'=>Mage::helper('job')->getBaseUrl()));
				$breadcrumbs->addCrumb('search_results', array('label'=>$helper->__('コミュニケーション・メディ'), 'title'=>$helper->__('コミュニケーション・メディ'), 'link'=>Mage::getUrl('job/search')));
				$breadcrumbs->addCrumb('job_apply', array('label'=>$helper->__('応募する | %s', $job->getTitle()), $helper->__('応募する | %s', $job->getTitle())));
			}	
			
			//get jobs form same category
			$block->setJobsInCategory($job->getJobsInCategory());
			
			//create email content
			$cv = implode(';',$data['filenames']);
			$mail = new Zend_Mail('UTF-8');
			foreach($data['filenames'] as $filename){
				$file = Mage::getBaseDir().'/files/'.$user->getId().'/'.$filename;
				$at = new Zend_Mime_Part(file_get_contents($file));
				$at->filename = basename($file);
				$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
				$at->encoding = Zend_Mime::ENCODING_BASE64;
				        
				$mail->addAttachment($at);
			}
			
			//check input data
			if(!$data['name'] || !$data['email']){
				Mage::getSingleton('core/session')->addError(Mage::helper('job')->__('Not enough information.'));
				$this->_redirect('job/apply', array('id'=>$data['id']));
			}
			$transport = Mage::helper('job')->getMailConfig();
			
			//get general contact from config admin
			/* Sender Name */
			$nameAdmin = Mage::getStoreConfig('trans_email/ident_general/name'); 
			/* Sender Email */
			$emailAdmin = Mage::getStoreConfig('trans_email/ident_general/email');
			
			$bodyHtml = '<table><tbody>';			
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('求人番号(Job No.)').':</td><td> '.$job->getIconicId().'</td></tr>';
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('求人タイトル (Job Title)').':</td><td> '.$job->getTitle().'</td></tr>';
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('氏名 (Name)').':</td><td> '.$data['name'].'</td></tr>';
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メールアドレス(E-mail)').':</td><td> '.$data['email'].'</td></tr>';
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('電話番号(TEL)').':</td><td> '.$data['phone'].'</td></tr>';		
			$bodyHtml .= '<tr><td>'.Mage::helper('job')->__('メッセージ(Content)').':</td><td> '.nl2br($data['message']).'</td></tr>';
			$bodyHtml .= '</tbody></table>';
			
			$mail->setBodyHtml($bodyHtml);
			$mail->addTo('auto_iconicjp@iconic-intl.com',Mage::helper('job')->__('IconicJP'));
			$mail->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
			$mail->setSubject(Mage::helper('job')->__('ICONIC-JP Apply Job No.%s - %s - %s', $job->getIconicId(), $data['name'], $user->getPic()));
			$checkSend = $mail->send($transport);
			
			$mail2 = new Zend_Mail('UTF-8');
			$baseurl = Mage::getBaseUrl();
			$logourl = $baseurl.'skin/frontend/default/iconic/images/mail-logo.png';
			$name = $user->getFirstname();
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
											こんにちは　'.$name.'様。<br />
											<br />
											このたびは、アイコニックの求人へご応募頂きまして、有難うございました！<br />
											<br />
											No. '.$job->getIconicId().' | '.$job->getTitle().'へのご応募を確かに受付ました。 応募状況の進捗があり次第、弊社コンサルタントより'.$name.'様へ直接連絡を入れさせて頂きます。<br />
											<br />
											他にもアイコニックのウェブサイトでは各種求人を毎日アップデートしておりますので、<a target="_blank" href="'.$baseurl.'">ホーム</a>に戻り、求人検索を続けてみてくださいね！<br />
											<br />
											また、転職支援サービス（無料）へのお申込みをまだされていない方は、是非、<a target="_blank" href="'.$baseurl.'submit-cv">こちら</a>からお申込みください。アジア転職のプロであるアイコニックのコンサルタントが、生活面から求人面まで、'.$name.'様のどんなお悩み・ご質問にもお答えいたします！<br />
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
											Thank you very much for your interest in iconic-jp.com<br />
											When we receive your recruitment request, our professional consulting staff will contact to you soon.<br />
											<br />
											If you have any requirements, do not hesitate to contact us for further information.<br />
											We are willing to support you anytime: info@iconic-jp.com<br />
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
			$mail2->setBodyHtml($bodyHtml2);
			$mail2->addTo($user->getEmail(),$user->getName());
			$mail2->setFrom('info@iconic-jp.com', Mage::helper('job')->__('IconicJP'));
			$mail2->setSubject(Mage::helper('job')->__('【アイコニック】%sへのご応募を受付ました。', $job->getIconicId()));
			$checkSend2 = $mail2->send($transport);
			if($checkSend && $checkSend2){
				$this->getLayout()->getBlock('head')->setTitle(Mage::helper('job')->__('申込完了致しました。 ご検討をお祈りします。'));
			}
			
			//set upload link to database
			if(strlen($cv) > 3){
				$user = Mage::getSingleton('customer/session')->getCustomer();
				$user->setUploadCv($cv)->save();
			}
			$this->renderLayout();
		}catch(Exception $e){
			//Mage::getSingleton('core/session')->addError($e);
			Mage::getSingleton('core/session')->addError(Mage::helper('job')->__('Can\'t send mail.'));
			$this->_redirect('*/apply', array('id'=> $data['id']));
		}
	}
	
}
