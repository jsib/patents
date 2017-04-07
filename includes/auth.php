<?php
//Генерирует хэш на основе логина и пароля
function generate_hash($user, $password){
	$hash=sha1(md5($user)."+++".$password);
	return $hash;
}

//Проверяет, указаны ли верные данные для входа
function check_login(){
	$user=db_easy("SELECT `name`, `password_hash` FROM `users` WHERE `name`='".mysql_real_escape_string(@$_POST['user'])."'");
	if(generate_hash($user['name'], @$_POST['password'])==$user['password_hash']){
		return true;
	}else{
		return false;
	}
}

//Генерирует HTML формы входа
function login_form($message=''){
	$html = "";
	$html.=template_get('header');
	$html.="<div style='width:100%;height:100%' align='center'>";
	$html.="<form action='/?action=login' method='post' style='margin-top:25%;width:300px;height:300px;'>";
	$html.="Введите логин и пароль<br/>";
	$html.=$message;
	$html.="<input type='text' name='user'/><br/>";
	$html.="<input type='password' name='password'/><br/>";
	$html.="<input type='submit' value='Войти'/>";
	$html.="</form>";
	$html.="</div>";
	$html.=template_get('footer');
	return $html;
}

?>