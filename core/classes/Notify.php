<?php

namespace Core;

use App\Table\PossessionEmailTable;

class Notify extends Base
{
    
    /** List of possesions retrieved from database */
    private $possessions = [];
    
    public function run()
    {
        //Get possessions list html
        $table = new PossessionEmailTable();
        $possessions_list = $table->build();
        
        //Get subject
        $subject = $this->getSubject();
        
        //Create email object instance
        //$email = new Email();

        //Send message
        foreach(RECIPIENTS as $recipient) {
            //Form unique body for each recipient
            $body = $this->getComment() .
                $this->getOtherRecipientsList($recipient) .
                "<br/><br/>Ниже список товарных знаков и патентов осортирован" . 
                "по полям 'Оплачено, до' или 'Срок действия, до' в зависимости" . 
                "от того, что наступает раньше:<br/><br/>" .
                $possessions_list;
            
            dump($subject);
            echo $body;
            break;
            
            //Sending email
            //$email->sendMail($recipient, $subject, $body);
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
        
        return "Напоминание по продлению патентов и товарных знаков (" .
            $months[ date("m") ] . " " . date("Y") . ")";
    }
    
    /**
     * Get html with information who recieve notification
     */
    private function getOtherRecipientsList($target_recipient)
    {
        //Other recipients list in html format
        $recipients_html = '';	

        //Build html - who recieve email
        foreach (RECIPIENTS as $recipient) {
            if ($target_recipient != $recipient) {
                $recipients_html .= $recipient . "<br/>";		
            }
        }

        //Only $target_recipient get this email
        if ($recipients_html == '') {
            return "Данное письмо получаете только Вы.";
        }
        
        //Other recipients also get this email
        return "Данное письмо также получают:<br/>" . $recipients_html;
    }
    
    private function getComment()
    {
        $view = new View();
        return $view->load('tables/notify/notify');
    }
}



