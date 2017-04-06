<?php

use Core\Route;

//Include main config file
require_once('../app/config.php');

require_once(ROOT_PATH . "includes/service.php");
require_once(ROOT_PATH . "includes/uris.php");
require_once(ROOT_PATH . "includes/files.php");
require_once(ROOT_PATH . "includes/auth.php");

//Require shortcuts for classes functions
require_once(CORE_PATH . "shortcuts.php");

//Require debug before autoloader to handle autoloader errors correctly
require_once(CLASSES_PATH . "Debug.php");

//Set error handler
set_error_handler(array('Debug', 'handleErrors'));

//Начинаем сессию и задаем ее время жизни
session_start();
session_set_cookie_params(10800);

//Require autoloader class
require_once(CLASSES_PATH."Autoloader.php");

//Set classes autoloader
spl_autoload_register('Autoloader::load');

//Create instance of route object
$route = new Route();

//Include routes, must be after autoloader
require_once(ROOT_PATH . 'app/routes.php');

$route->build();

//Start cookie session and define is user authenticated
//Auth::init();

echo $route->startController();
exit;

//Если вход не выполнен
if(!isset($_SESSION['user'])){
	echo login_form();
	exit;
}


//Выводим ссылку для добавления патента
//Добавить строку/столбец
//if(get_user_group($_SESSION['user'])=="writer"){
//	if(@$_GET['regime']=='write'){
//		$html_right = "<h3 style='margin-top:30px;'>Действия</h3>"."<a href='".uri_make(array('regime'=>'write', 'action'=>'add_patent'))."'>Добавить патент</a><br/><br/>";
//	}
//}
//Форма
//if(@$_GET['regime']!='write'){
//	$html.="";
//}else{
//	if(get_user_group($_SESSION['user'])=="writer"){
//		$form_action=uri_make('action', 'save_patent');
//	}else{
//		$form_action=uri_make();
//	}
//	$html.="<form id='Form' action='$form_action' method='post' onsubmit='CheckForm();' style='margin:0;padding:0;'>";
//}

//Верхний счетчик
//$html.="{$table['counter']}: ".count($table['matrix']);



//Сама таблица
//$html .= $table_object->build();

//Нижний счетчик
//$html.="{$table['counter']}: ".count($table['matrix']);

//Кнопка "Сохранить"
if(get_user_group($_SESSION['user'])=="writer"){
	if(@$_GET['regime']!='write'){
		$html.="";
	}else{
		$html.="<br/><br/><input type='submit' value='Сохранить'></form>";
	}
}

//Закрываем область с данными
//$html.="</div>";
//
////Показываем меню управления
//$html.="<div id='manage_menu'>";
//$html.=$html_right;
//$html.="</div>";

//Подключаем и выводим нижнюю часть HTML кода страницы

echo $html;
?>