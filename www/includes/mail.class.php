<?php
class mail{
	public static function send_mail($to,$subject,$body){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_VERBOSE, 0);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_USERPWD, 'api:key-4aycwdnncv5onmse529c-dkvo-7afr49');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_POST, true); 
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $params); 
		curl_setopt($ch, CURLOPT_HEADER, false); 

		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v2/pickcat.mailgun.org/messages");
		curl_setopt($ch, CURLOPT_POSTFIELDS,                
				array(  'from'      => '抓猫 <noreply@pickcat.org>',
						'to'        => $to,
						'h:Reply-To'=>  ' <noreply@pickcat.org>',
						'subject'   => $subject,
						'html'      => $body
					));
		$result = curl_exec($ch);
		curl_close($ch);
        
		return $result;
	}
	
	public static function send_activate($to,$key){
		$subject = '抓猫提醒！';
		$body = "尊敬的用户：<br>您好！<br>感谢您激活邮箱，请点击下面的链接或者复制到地址栏<br><a href='http://pickcat.sinaapp.com/user/activate_email/?key=$key'>http://pickcat.sinaapp.com/user/activate_email/?key=$key</a><br>激活邮箱后您就可以及时收到邮件提醒<br>请将本邮件地址加入白名单，以防邮件被过滤。<br><hr>系统邮件，请勿回复<br>抓猫官网<a href='http://pickcat.sinaapp.com'>http://pickcat.sinaapp.com</a>";
		return self::send_mail($to,$subject,$body);
	}
	
	public static function send_cron($to,$title,$body){
		$subject = "抓猫提醒：$title";
		$body = "尊敬的用户：<br>您好！<br>您在抓猫上设置的计划<strong>$title</strong>已经完成，以下是抓取到的上下文<br><div>$body</div><br><hr>系统邮件，请勿回复<br>抓猫官网<a href='http://pickcat.sinaapp.com'>http://pickcat.sinaapp.com</a>";
		self::send_mail($to,$subject,$body);
	}

	public static function send_register_mail(){
		self::send_mail();
	}

	public static function send_forget_mail(){
		self::send_mail();
	}
}
?>