<?php

use Core\Controller;
use App\Table\PatentTable;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->listAction('russia', 'patents');
    }
    
    public function listAction($country, $object)
    {
        $table = new PatentTable();
        $table->country = $country;
        return $table->build();
    }
    
    public function deleteAction()
    {
	if(get_user_group($_SESSION['user'])=="writer"){
            db_query("DELETE FROM `patents` WHERE `id`=".@$_GET['id']);
            header("location: ".uri_make('action', ''));
	}
    }
    
    public function addAction($country, $object)
    {
        //Check if user has rights for this action
        if( !$this->auth->userHasRight('edit') ) {
            error("You don't have permissions");
            return;
        }
        
        //Check user input
        if ( $object !== 'patent' && $object !== 'trademark' ) {
            error("Error");
            return;
        }
        
        //Query database
        $this->db->prepare("
            INSERT INTO
                `" . $object. "s`
            SET
                `country_name`=?
        ")
            ->bindParam('s', $country)
            ->exec();
        
        //Redirect to list of items
        header('Location: ' . '/' . $country . '/' . $object . '/');
    }
    
    //Сохранение таблицы. Выполняется до include_table().
    public function save()
    {
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
}
