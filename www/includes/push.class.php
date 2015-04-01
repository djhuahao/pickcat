<?php
include(INCLUDES_PATH.'baiduapi/Channel.class.php');
push::$push = new Channel ( baidu_apiKey, baidu_secretKey ) ;
class push{
	public static $push;
	public static function push_message($user_id,$title,$desc){
		//推送消息到某个user，设置push_type = 1; 
		//推送消息到一个tag中的全部user，设置push_type = 2;
		//推送消息到该app中的全部user，设置push_type = 3;
		$push_type = 1; //推送单播消息
		$optional[Channel::USER_ID] = $user_id; //如果推送单播消息，需要指定user
		//optional[Channel::TAG_NAME] = "xxxx";  //如果推送tag消息，需要指定tag_name

		//指定发到android设备
		$optional[Channel::DEVICE_TYPE] = 3;
		//指定消息类型为通知
		$optional[Channel::MESSAGE_TYPE] = 1;
		//通知类型的内容必须按指定内容发送，示例如下：
		$message = "{ 
				'title': '$title',
				'description': '$desc',
				'notification_basic_style':7,
				'open_type':2,
				'url':'http://pickcat.sinaapp.com'
			}";
		
		$message_key = "msg_key";
		$ret = self::$push->pushMessage ( $push_type, $message, $message_key, $optional ) ;
	}
}
?>