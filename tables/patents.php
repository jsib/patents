<?
//Определяем будущие массивы
$patents=array();
$table_matrix_appearance=array();
$table_matrix_columns=array();


//Получаем матрицу из базы данных
$a=db_query("SELECT `patents`.`id` as `id`,
					`patents`.`name` as `name`,
					`patents`.`certificate` as `certificate`,
					`patents`.`request` as `request`,
					`patents`.`priority` as `priority`,
					`patents`.`registration` as `registration`,
					`patents`.`paid_before` as `paid_before`,
					`patents`.`expire` as `expire`,
					`patents`.`comment` as `comment`
				FROM `patents`
				WHERE country_name='".get_country()."'");
				
while($patent=db_fetch($a))
{
    $id=$patent['id'];
    $patents[$id]['id']=$id;
    $patents[$id]['name']=$patent['name'];
	$patents[$id]['certificate']=$patent['certificate'];
	$patents[$id]['request']=$patent['request'];
	$patents[$id]['priority']=$patent['priority'];
	$patents[$id]['registration']=$patent['registration'];
	$patents[$id]['paid_before']=$patent['paid_before'];
	$patents[$id]['expire']=$patent['expire'];
	$patents[$id]['comment']=$patent['comment'];
}

//Подготавливаем первую строку для таблицы
$table_matrix[0]['id']='Id патента';
$table_matrix[0]['name']='Имя товарного знака';
$table_matrix[0]['comment']='Примечание';
$table_matrix[0]['certificate']='№ свидетельства';
$table_matrix[0]['request']='№ заявки';
$table_matrix[0]['priority']='Приоритет';
$table_matrix[0]['registration']='Регистрация';
$table_matrix[0]['paid_before']='Оплачено до';
$table_matrix[0]['expire']='Срок действия, до';
if(get_user_group($_SESSION['user'])=="writer") $table_matrix[0]['delete']='';

//Подготавливаем остальные строки для таблицы
if(count($patents)>0){
    foreach($patents as $id=>$patent)
    {
        $table_matrix[$id]['id']=$patent['id'];
        $table_matrix[$id]['name']=$patent['name'];
		$table_matrix[$id]['comment']=$patent['comment'];
		$table_matrix[$id]['certificate']=$patent['certificate'];
		$table_matrix[$id]['request']=$patent['request'];
		$table_matrix[$id]['priority']=$patent['priority'];
		$table_matrix[$id]['registration']=$patent['registration'];
		$table_matrix[$id]['paid_before']=$patent['paid_before'];
        $table_matrix[$id]['expire']=$patent['expire'];
		if(get_user_group($_SESSION['user'])=="writer"){
			$table_matrix[$id]['delete']="Удалить";
			$table_matrix_links[$id]['delete']['href']=uri_make(array('action'=>'delete_patent', 'id'=>$id));
			$table_matrix_appearance[$id]['delete']['style']="color:red;";
			$table_matrix_appearance[$id]['delete']['onclick']="if(!confirm(\"Удалить?\")) return false;";
		}
    }
}


//Задаем первую строку таблицы
$table['row_first']=$table_matrix[0];

//Задаем матрицу таблицы
unset($table_matrix[0]);
$table['matrix']=$table_matrix;

$table_matrix_columns['id']['input_type']='hidden';
$table_matrix_columns['priority']['type']="date";
$table_matrix_columns['registration']['type']="date";
$table_matrix_columns['paid_before']['type']="date";
$table_matrix_columns['expire']['type']="date";

//Ширина столбцов (раздвигается полями ввода)
/*$table_matrix_columns['name']['width']='260px';
$table_matrix_columns['certificate']['width']='150px';
$table_matrix_columns['request']['width']='150px';
$table_matrix_columns['priority']['width']='150px';
$table_matrix_columns['registration']['width']='150px';
$table_matrix_columns['expire']['width']='150px';*/

//Ширина полей ввода
$table_matrix_columns['name']['input_width']='260px';
$table_matrix_columns['certificate']['input_width']='150px';
$table_matrix_columns['request']['input_width']='150px';
$table_matrix_columns['priority']['input_width']='150px';
$table_matrix_columns['registration']['input_width']='150px';
$table_matrix_columns['expire']['input_width']='150px';

$table_rows['height']="28px";


//Задаем матрицу внешнего вида таблицы
$table['matrix_appearance']=$table_matrix_appearance;

//Задаем имя таблицы
$table['header']="Товарные знаки и патенты (".$GLOBALS['countries'][get_country()]['name_rus'].")";

//Толщина сетки таблицы (толщина рамки всегда на один пиксель больше)
$table['border']=1;

//Задаем название счетчика
$table['counter']="Количество";

//Свойства колонок
$table['columns']=$table_matrix_columns;

//Свойства строк
$table['rows']=$table_rows;

//Содержит адреса ссылок
$table['matrix_links']=$table_matrix_links;

//Задаем сортировку по умолчанию
$table['sort_default']='id';

//Задаем направление сортировки по умолчанию
$table['sort_direction_default']='asc';
?>
