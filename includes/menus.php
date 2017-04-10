<?
function menu_top(){
    //Запрашиваем из таблицы БД 'menus' все пункты меню, принадлежащие размещению 'top'
    $items_query=db_query("SELECT `href`, `text`, `label` FROM `menu_items` WHERE `area`='top' ORDER BY `order` ASC");

    $html_items="";
    $number_item=1;

    //Пункты меню найдены
    if(db_count($items_query)>0){
        //Перебираем пункты меню
        while($item=db_fetch($items_query)){
            $html_items.="<a href='{$item['href']}' class='".get_class_depend_on_uri("=", 'country', $item['label'])."'>{$item['text']}</a>";
            $html_items.="<span class='divider'></span>";
            if($number_item % 7 == 0) $html_items.="<br/>";
            $number_item++;
        }
    //Такие пункты меню не найдены
    }else{
        $html_items="Нет  пунктов для верхнего меню.";
    }
    return template_get('menus/menu_top', array('html_items'=>$html_items, 'login'=>$_SESSION['user']));
}?>