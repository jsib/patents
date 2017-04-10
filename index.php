<?
require_once($_SERVER['DOCUMENT_ROOT']."/includes/uris.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/templates.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/files.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/auth.php");

//Начинаем сессию и задаем ее время жизни
session_start();
session_set_cookie_params(10800);

//Подключаемся к базе данных
db_connect();

//Переход с index.php
$default_page="/table.php?table_name=patents&country=russia&regime=write";

if(isset($_SESSION['user'])){
	if(@$_GET['action']=="logout"){
		unset($_SESSION['user']);
		echo login_form();
	}else{
		//Если пользователь уже авторизован
		header("location: $default_page");
	}
}elseif(@$_GET['action']=='login'){
	//Или только авторизовыается
	if(check_login()){
		//И ввел верный логин и пароль
		$_SESSION['user']=@$_POST['user'];
		header("location: $default_page");
	}else{
		//Или они все таки неверны
		echo login_form("<span style='color:red'>Ошибка в логине или пароле!</span><br/>");
	}
}else{
	//Перебрасываем на форму входа в систему
	//echo generate_hash("nosova", "qwe123");
	echo login_form();
}
?>
