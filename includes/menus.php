<?
function menu_top(){
    //����������� �� ������� �� 'menus' ��� ������ ����, ������������� ���������� 'top'
    $items_query=db_query("SELECT `href`, `text`, `label` FROM `menu_items` WHERE `area`='top' ORDER BY `order` ASC");

    $html_items="";
    $number_item=1;

    //������ ���� �������
    if(db_count($items_query)>0){
        //���������� ������ ����
        while($item=db_fetch($items_query)){
            $html_items.="<a href='{$item['href']}' class='".get_class_depend_on_uri("=", 'country', $item['label'])."'>{$item['text']}</a>";
            $html_items.="<span class='divider'></span>";
            if($number_item % 7 == 0) $html_items.="<br/>";
            $number_item++;
        }
    //����� ������ ���� �� �������
    }else{
        $html_items="���  ������� ��� �������� ����.";
    }
    return template_get('menus/menu_top', array('html_items'=>$html_items, 'login'=>$_SESSION['user']));
}?>