<?php
//Implement connection to database
function db_connect($encoding='cp1251')
{
    if(!mysql_connect("localhost", "ilya", "local")){
        trigger_error("Connection to database failed, error: ".mysql_error(), E_USER_ERROR);
        exit();
    }
    if(!mysql_select_db("patents")){
        trigger_error("Changing database failed, error: ".mysql_error(), E_USER_ERROR);
        exit();
    }

	//Устанавливаем кодировку для работы с базой
	mysql_query("SET NAMES '$encoding'");
	mysql_query("SET CHARACTER SET '$encoding'");
	mysql_query("SET SESSION collation_connection = '{$encoding}_general_ci'");
		
}

function db_query($question)	//Wrapper for mysql_query
{
    $debug=debug_backtrace();
    if($q=mysql_query($question))
    {
        return $q;
    }else{
        trigger_error("Ошибка в запросе к базе данных mysql_query(\"$question\"). Запрос вызван из файла {$debug[1]['file']} line {$debug[1]['line']}. Ошибка сгенерирована функцией 'trigger_error'", E_USER_ERROR);
    }
}

function db_fetch($query)	//Wrapper for mysql_fetch_array
{
    return mysql_fetch_array($query);
}

function db_count($query)	//Wrapper for mysql_num_rows
{
    return mysql_num_rows($query);
}

function db_result($query='notdefined')
{
    if($query=='notdefined')
    {
        return mysql_affected_rows();
    }else{
        return mysql_affected_rows($query);
    }
}

//Easy implement a query to database and return result immediately (e.g. query + fetch = both in one)
function db_easy($question, $file='', $line='')
{
    if($a=db_query($question, $file, $line))
    {
        return db_fetch($a);
    }else{
        return false;
    }
}

function db_short_easy($question, $file='', $line='')
{
    if($a=db_query($question, $file, $line))
    {
        $result=db_fetch($a);
        return $result[0];
    }else{
        return false;
    }
}

//Простой подсчет количества возвращаемых результатов поиска по базе
function db_easy_count($question){
    return mysql_num_rows(db_query($question));
}

function db_easy_result($question)
{
    if($a=db_query($question))
    {
        return db_result($a);
    }else{
        return false;
    }
}
?>