<?php

namespace Core;

use App\Table\PossessionEmailTable;

class Notify extends Base
{
    /**
     * List, who recieve notification
     */
    const RECIPIENTS = [
        'pochta2id@gmail.com'
        //'ilya.domyshev@acoustic.ru',
        //'alex@acoustic.ru',
        //'andrey.patrushev@acoustic.ru'
    ];
    
    /**
     * List of possesions retrieved from database
     */
    private $possessions = [];
    
    public function run()
    {
        //
        $table = new PossessionEmailTable();
        $table->build();
        
        //Create email object instance
        //$email = new Email();

        //Send message
        foreach( RECIEVERS as $recipient ) {
            $who_recieve_html="";	

            //Build text - who recieve email
            foreach (RECIEVERS as $recieverWHO) {
                if ($recipient!=$recieverWHO) {
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
            //$email->sendMail($reciever, $topic, $letter_comment . $who_recieve_html . $body);

            \dump($topic, '$topic');
            \dump($body, '$body');
        }        
    }
    
    /**
     * Build text for email's subject
     */
    private function getSubject()
    {
        $months = [
            "01" => "январь", "02" => "февраль", "03" => "март",
            "04" => "апрель", "05" => "май", "06" => "июнь", 
            "07" => "июль", "08" => "август", "09" => "сентябрь", 
            "10" => "октябрь", "11" => "ноябрь", "12" => "декабрь"
        ]; 
        
        $subject = "Напоминание по продлению патентов и товарных знаков (" . $months[ date("m") ] . ")";
    }
    
}



