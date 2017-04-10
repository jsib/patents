<?
require_once($_SERVER['DOCUMENT_ROOT']."/includes/service.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/tables.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/uris.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/files.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/menus.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/templates.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/patents.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/auth.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/sort.php");

//Начинаем сессию и задаем ее время жизни
session_start();
session_set_cookie_params(10800);

//Подключаемся к базе данных
db_connect();


//Если вход не выполнен
if(!isset($_SESSION['user'])){
	echo login_form();
	exit;
}


//Подключаемся к базе данных
db_connect();

//Загружаем список стран патентов
$countries=load_patent_countries();

//Html всей страницы
$html="";

//Подключаем и выводим верхнюю часть HTML кода страницы
$html.=template_get('header');

//Подключаем верхнее меню
$html.= menu_top();

//Получаем имя таблицы из строки браузера
$table_name=@$_GET['table_name'];

//Удаление патента
if(@$_GET['action']=='delete_patent'){
	if(get_user_group($_SESSION['user'])=="writer"){
		db_query("DELETE FROM `patents` WHERE `id`=".@$_GET['id']);
		header("location: ".uri_make('action', ''));
	}
}

//Добавление патента (строки в таблицу)
if(@$_GET['action']=="add_patent"){
	if(get_user_group($_SESSION['user'])=="writer"){
		db_query("INSERT INTO `patents` SET `country_name`='".get_country()."'");
		header("location: ".uri_make('action', ''));
		
		/*$new_row=array();
		$new_row_number=1;
		foreach($table['row_first'] as $name=>$name_rus){
			 $new_row[$name]=" ";
		}
		array_push($table['matrix'], $new_row);*/
	}
}

//Сохранение таблицы. Выполняется до include_table().
if(@$_GET['action']=='save_patent'){
	if(get_user_group($_SESSION['user'])=="writer"){	
		$save_result=true;
		//show($_POST['Form']);
		if(isset($_POST['Form'])){
			foreach($_POST['Form'] as $row=>$columns){
				//Добавление новых патентов
				if(trim(@$columns['id'])==""){
					if(db_query("INSERT INTO `patents` SET
									`name`='{$columns['name']}',
									`country_name`='".get_country()."',
									`certificate`='".$columns['certificate']."',
									`request`='".$columns['request']."',
									`priority`='".date("Y-m-d", strtotime($columns['priority']))."',
									`registration`='".date("Y-m-d", strtotime($columns['registration']))."',
									`expire`='".date("Y-m-d", strtotime($columns['expire']))."'
									
									
					")){
						if(db_result()){
							//$html.="<span style='color:green'>Добавлен патент '{$columns['name']}'</span>";
						}else{
							//$html.="<span style='color:red'>Ошибка при добавлении патента '{$columns['name']}'</span>";
						}
					}else{
						//$html.="<span style='color:red'>Ошибка при добавлении патента '{$columns['name']}'</span>";
					}
				//Сохранение уже имеющихся
				}else{
					if(!db_query("UPDATE `patents` SET
										`name`='{$columns['name']}',
										`comment`='{$columns['comment']}',
										`country_name`='".get_country()."',
										`certificate`='".$columns['certificate']."',
										`request`='".$columns['request']."',
										`priority`='".date("Y-m-d", strtotime($columns['priority']))."',
										`registration`='".date("Y-m-d", strtotime($columns['registration']))."',
										`paid_before`='".date("Y-m-d", strtotime($columns['paid_before']))."',
										`expire`='".date("Y-m-d", strtotime($columns['expire']))."'
									WHERE id={$columns['id']}
					")){
						if(db_result()){
							//$html.="<span style='color:green'>Таблица успешно сохранена '{$columns['name']}'</span>";
						}else{
							//$html.="<span style='color:red'>Ошибка при сохранении таблицы '{$columns['name']}'</span>";
						}
					}else{
						//$html.="<span style='color:red'>Ошибка при сохранении таблицы '{$columns['name']}'</span>";
					}	
				}	
			}
			//$html.="<span style='color:green'>Таблица успешно сохранена '{$columns['name']}'</span>";
		}
	}
}

//Подключаем .php файл таблицы
$table=include_table($table_name);

//Выводим имя таблицы
$html.="<br/>".h1($table['header'])."<br/>";

//Открываем область с данными
$html.="<div id='data_area'>";

//Параметры сортировки
if(!$sort=get_sort()) $sort=$table['sort_default'];
if(!$sort_direction=get_sort_direction()) $sort_direction=$table['sort_direction_default'];
$table['sort']=$sort;
$table['sort_direction']=$sort_direction;

//Сортировка
table_matrix_sort($table['matrix'], $sort, $sort_direction, $table['sort_specific']);

//Выводим ссылку для добавления патента
//Добавить строку/столбец
if(get_user_group($_SESSION['user'])=="writer"){
	if(@$_GET['regime']=='write'){
		$html_right.="<h3 style='margin-top:30px;'>Действия</h3>"."<a href='".uri_make(array('regime'=>'write', 'action'=>'add_patent'))."'>Добавить патент</a><br/><br/>";
	}
}
//Форма
if(@$_GET['regime']!='write'){
	$html.="";
}else{
	if(get_user_group($_SESSION['user'])=="writer"){
		$form_action=uri_make('action', 'save_patent');
	}else{
		$form_action=uri_make();
	}
	$html.="<form id='Form' action='$form_action' method='post' onsubmit='CheckForm();' style='margin:0;padding:0;'>";
}

//Верхний счетчик
$html.="{$table['counter']}: ".count($table['matrix']);


//Сама таблица
$html.=get_table($table);

//Нижний счетчик
$html.="{$table['counter']}: ".count($table['matrix']);

//Кнопка "Сохранить"
if(get_user_group($_SESSION['user'])=="writer"){
	if(@$_GET['regime']!='write'){
		$html.="";
	}else{
		$html.="<br/><br/><input type='submit' value='Сохранить'></form>";
	}
}

//Закрываем область с данными
$html.="</div>";

//Показываем меню управления
$html.="<div id='manage_menu'>";
$html.=$html_right;
$html.="</div>";

//Подключаем и выводим нижнюю часть HTML кода страницы
$html.=template_get('footer');

echo $html;
?>