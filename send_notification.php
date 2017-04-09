<?php

use Core\Route;
use Core\Auth;
use Core\Uri;
use Core\Database;
use App\Entity\Country;

//Include main config file
require_once('../app/config.php');

//Require shortcuts for classes functions
require_once(CORE_PATH . "shortcuts.php");

//Require debug before autoloader to handle autoloader errors correctly
require_once(CLASSES_PATH . "Debug.php");

//Set error handler
set_error_handler(['Debug', 'handleErrors']);

//Require autoloader class
require_once(CLASSES_PATH . "Autoloader.php");

//Set classes autoloader
spl_autoload_register('Autoloader::load');

//Connect to database
$db = new Database();

//Email body
$body = '';

//Recievers list
$recievers = [
    'pochta2id@gmail.com'
    //'ilya.domyshev@acoustic.ru',
    //'alex@acoustic.ru',
    //'andrey.patrushev@acoustic.ru'
];

//Retrieve matrix from database
$result = DB::Prepare("
    SELECT 
        *
    FROM
        `patents`
    WHERE
        `expire` != '1969-12-31'
    ORDER BY
        `expire` ASC
")
    ->exec()
    ->getResult();
				
//Begin body
$body .= "<div style='font-family:Tahoma;font-size:13px;'>";

//Get instance of country object
$entity = new Country();

//Get list of countries
$countries = $entity->getList();

//Loop over list of patents
while( $patent = $result->fetch() ) {
    //Define time, leaved to patent/trademark renewal
    $seconds_difference = strtotime($patent['expire']) - strtotime(date("d.m.Y"));
    $day_difference = round($seconds_difference/(60*60*24));

    if( $day_difference < 63 && $day_difference >= 31 ) {
        $expire_color = 'orange';
    } elseif($day_difference<31) {
        $expire_color='red';
    } else {
        $expire_color='green';
    }

    if (strlen($patent['name']) > 400) {
        $hvost = "...";
    } else {
        $hvost = "";
    }

    $body .= 
        "<br/>Наименование: " . mb_strcut($patent['name'], 0, 400, "UTF-8") . $hvost .
        "<br/>№ свидетельства: " . $patent['certificate'] .
        "<br/>Страна: " . $countries[$patent['country_name']]['name_rus'] .
        "<br/>Срок действия, до: <span style='color:$expire_color'>" . date("d.m.Y", strtotime($patent['expire'])) . "</span><br/><br/>";
}

//End body
$body .= "</div>";

//Array with months
$Month_r = [
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
    "12" => "декабрь"
]; 

//Build topic
$topic = "Напоминание по продлению патентов и товарных знаков (".$Month_r[date("m")].")";

//Build comment
$letter_comment = "
    Здравствуйте!<br/><br/>
    Данное письмо содержит список патентов, отсортированных по дате истечения.<br/>
    Чем раньше истекает патент, тем выше он в списке.<br/>
    Кроме того, если до срока истечения остается меньше двух месяцев, то дата истечения помечается оранжевым цветом. Если меньше одного месяца, то красным.<br/>
    Управление списком доступно по адресу http://patents.acoustic-group.net.<br/>
    Для получения доступа или включения/отключения данной рассылки обратитесь, пожалуйста, к системному администратору.<br/><br/>
";

//Create email object instance
$email = new Email();

//Send message
foreach( $recievers as $reciever ) {
	$who_recieve_html="";	
        
	//Build text - who recieve email
	foreach ($recievers as $recieverWHO) {
            if ($reciever!=$recieverWHO) {
                $who_recieve_html .= $recieverWHO . "<br/>";		
            }
	}
	
	//Who else recieve email
	if ($who_recieve_html == "") {
            $who_recieve_html = "Данное письмо получаете только Вы.<br/>";
	}else{
            $who_recieve_html = "Данное письмо также получают:<br/>".$who_recieve_html;
	}
	$who_recieve_html = $who_recieve_html . "<br/>" . "Список патентов:";
	
	//Sending
	$email->sendMail($reciever, $topic, $letter_comment . $who_recieve_html . $body);
}
?>
