<?php
require_once("/home/patents.acoustic-group.net/www/includes/email.php");
require_once("/home/patents.acoustic-group.net/www/includes/tables.php");
require_once("/home/patents.acoustic-group.net/www/includes/db.php");
require_once("/home/patents.acoustic-group.net/www/includes/sort.php");
require_once("/home/patents.acoustic-group.net/www/includes/uris.php");
require_once("/home/patents.acoustic-group.net/www/includes/patents.php");

//Подключаемся к базе данных
db_connect('utf8');

//Html всей страницы
$html="";

//Список получателей
$recievers=array('ilya.domyshev@acoustic.ru', 'alex@acoustic.ru', 'andrey.patrushev@acoustic.ru');

//Получаем матрицу из базы данных
$a=db_query("SELECT *
				FROM `patents`
				WHERE
					`expire` != '1969-12-31'
				ORDER BY `expire` ASC
				");
				
$html.="<div style='font-family:Tahoma;font-size:13px;'>";
$patent_countries=load_patent_countries();

while($patent=db_fetch($a)){
	//Определение времени, оставшегося до продления патента/товарного знака
	$seconds_difference=strtotime($patent['expire'])-strtotime(date("d.m.Y")/*"15.04.2015"*/);
	$day_difference=round($seconds_difference/(60*60*24));
	//$html.=$day_difference;
	
	if($day_difference<63 && $day_difference>=31){
		$expire_color='orange';
	}elseif($day_difference<31){
		$expire_color='red';
	}else{
		$expire_color='green';
	}
	
	if(strlen($patent['name'])>400){$hvost="...";}else{$hvost="";}
	$html.="<br/>Наименование: ".mb_strcut($patent['name'], 0, 400, "UTF-8").$hvost.
			"<br/>№ свидетельства: ".$patent['certificate'].
			"<br/>Страна: ".$patent_countries[$patent['country_name']]['name_rus'].
			"<br/>Срок действия, до: <span style='color:$expire_color'>".date("d.m.Y", strtotime($patent['expire']))."</span><br/><br/>";
			
}

$html.="</div>";

//Массив с месяцами
$Month_r = array( 
	"01" => "январь", 
	"02" => "февраль", 
	"03" => "март", 
	"04" => "апрель", 
	"05" => "май", 
	"06" => "июнь", 
	"07" => "июль", 
	"08" => "август", 
	"09" => "сентябрь", 
	"10" => "октябрь", 
	"11" => "ноябрь", 
	"12" => "декабрь"); 
	
$topic="Напоминание по продлению патентов и товарных знаков (".$Month_r[date("m")].")";

//Комментарий к сообщению
$letter_comment =	"Здравствуйте!<br/><br/>
					Данное письмо содержит список патентов, отсортированных по дате истечения.<br/>
					Чем раньше истекает патент, тем выше он в списке.<br/>
					Кроме того, если до срока истечения остается меньше двух месяцев, то дата истечения помечается оранжевым цветом. Если меньше одного месяца, то красным.<br/>
					Управление списком доступно по адресу http://patents.acoustic-group.net.<br/>
					Для получения доступа или включения/отключения данной рассылки обратитесь, пожалуйста, к системному администратору.<br/><br/>";
		


//Рассылаем сообщение по получателям
foreach($recievers as $reciever){
	$who_recieve_html="";	
	//Формируем текст, кто еще получит сообщение
	foreach($recievers as $recieverWHO){
		if($reciever!=$recieverWHO){
			$who_recieve_html.=$recieverWHO."<br/>";		
		}
	}
	
	//Подключаем текст, кто еще получает сообщение
	if($who_recieve_html==""){
		$who_recieve_html="Данное письмо получаете только Вы.<br/>";
	}else{
		$who_recieve_html="Данное письмо также получают:<br/>".$who_recieve_html;
	}
	$who_recieve_html=$who_recieve_html."<br/>"."Список патентов:";
	
	//Отправка
	my_send_mail($reciever, $topic, $letter_comment.$who_recieve_html.$html);
}
?>
