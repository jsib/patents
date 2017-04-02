<?php

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
