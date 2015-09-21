create table msgs{
	'msg_id' int(10) auto_increment,
	'schema_id' int(10),
	'user_id' int(10),
	'title' varchar(255),
	'content' text,
	'status' varchar(255),
	'addtime' int(10),
	PRIMARY KEY(msg_id)
}

create table schemas{
	'schema_id' int(10) auto_increment,
	'user_id' int(10),
	'channel' int(10),
	'title' varchar(255),
	'keywords' varchar(255),
	'url' varchar(255),
	'max_num' float(10),
	'min_num' float(10),
	'auto_reactivate' varchar(255),
	'share' varchar(255),
	'status' varchar(255),
	PRIMARY KEY(schema_id)
}

create table users{
	'user_id' int(10) auto_increment,
	'username' varchar(255),
	'password' varchar(255),
	'email' varchar(255),
	'email_notify' varchar(255),
	'app_notify' varchar(255),
	'screen_name' varchar(255),
	'weibo_uid' varchar(255),
	'qq_uid' varchar(255),
	'baidu_uid' varchar(255),
	'avatar_url' varchar(255),
	PRIMARY KEY(user_id)
}