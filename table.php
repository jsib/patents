<?
require_once($_SERVER['DOCUMENT_ROOT']."/includes/service.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/tables.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/uris.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/files.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/menus.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/templates.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/patents.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/auth.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/sort.php");

//�������� ������ � ������ �� ����� �����
session_start();
session_set_cookie_params(10800);

//������������ � ���� ������
db_connect();


//���� ���� �� ��������
if(!isset($_SESSION['user'])){
	echo login_form();
	exit;
}


//������������ � ���� ������
db_connect();

//��������� ������ ����� ��������
$countries=load_patent_countries();

//Html ���� ��������
$html="";

//���������� � ������� ������� ����� HTML ���� ��������
$html.=template_get('header');

//���������� ������� ����
$html.= menu_top();

//�������� ��� ������� �� ������ ��������
$table_name=@$_GET['table_name'];

//�������� �������
if(@$_GET['action']=='delete_patent'){
	if(get_user_group($_SESSION['user'])=="writer"){
		db_query("DELETE FROM `patents` WHERE `id`=".@$_GET['id']);
		header("location: ".uri_make('action', ''));
	}
}

//���������� ������� (������ � �������)
if(@$_GET['action']=="add_patent"){
	if(get_user_group($_SESSION['user'])=="writer"){
		db_query("INSERT INTO `patents` SET `country_name`='".get_country()."'");
		header("location: ".uri_make('action', ''));
		
		/*$new_row=array();
		$new_row_number=1;
		foreach($table['row_first'] as $name=>$name_rus){
			 $new_row[$name]=" ";
		}
		array_push($table['matrix'], $new_row);*/
	}
}

//���������� �������. ����������� �� include_table().
if(@$_GET['action']=='save_patent'){
	if(get_user_group($_SESSION['user'])=="writer"){	
		$save_result=true;
		//show($_POST['Form']);
		if(isset($_POST['Form'])){
			foreach($_POST['Form'] as $row=>$columns){
				//���������� ����� ��������
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
							//$html.="<span style='color:green'>�������� ������ '{$columns['name']}'</span>";
						}else{
							//$html.="<span style='color:red'>������ ��� ���������� ������� '{$columns['name']}'</span>";
						}
					}else{
						//$html.="<span style='color:red'>������ ��� ���������� ������� '{$columns['name']}'</span>";
					}
				//���������� ��� ���������
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
							//$html.="<span style='color:green'>������� ������� ��������� '{$columns['name']}'</span>";
						}else{
							//$html.="<span style='color:red'>������ ��� ���������� ������� '{$columns['name']}'</span>";
						}
					}else{
						//$html.="<span style='color:red'>������ ��� ���������� ������� '{$columns['name']}'</span>";
					}	
				}	
			}
			//$html.="<span style='color:green'>������� ������� ��������� '{$columns['name']}'</span>";
		}
	}
}

//���������� .php ���� �������
$table=include_table($table_name);

//������� ��� �������
$html.="<br/>".h1($table['header'])."<br/>";

//��������� ������� � �������
$html.="<div id='data_area'>";

//��������� ����������
if(!$sort=get_sort()) $sort=$table['sort_default'];
if(!$sort_direction=get_sort_direction()) $sort_direction=$table['sort_direction_default'];
$table['sort']=$sort;
$table['sort_direction']=$sort_direction;

//����������
table_matrix_sort($table['matrix'], $sort, $sort_direction, $table['sort_specific']);

//������� ������ ��� ���������� �������
//�������� ������/�������
if(get_user_group($_SESSION['user'])=="writer"){
	if(@$_GET['regime']=='write'){
		$html_right.="<h3 style='margin-top:30px;'>��������</h3>"."<a href='".uri_make(array('regime'=>'write', 'action'=>'add_patent'))."'>�������� ������</a><br/><br/>";
	}
}
//�����
if(@$_GET['regime']!='write'){
	$html.="";
}else{
	if(get_user_group($_SESSION['user'])=="writer"){
		$form_action=uri_make('action', 'save_patent');
	}else{
		$form_action=uri_make();
	}
	$html.="<form id='Form' action='$form_action' method='post' onsubmit='CheckForm();' style='margin:0;padding:0;'>";
}

//������� �������
$html.="{$table['counter']}: ".count($table['matrix']);


//���� �������
$html.=get_table($table);

//������ �������
$html.="{$table['counter']}: ".count($table['matrix']);

//������ "���������"
if(get_user_group($_SESSION['user'])=="writer"){
	if(@$_GET['regime']!='write'){
		$html.="";
	}else{
		$html.="<br/><br/><input type='submit' value='���������'></form>";
	}
}

//��������� ������� � �������
$html.="</div>";

//���������� ���� ����������
$html.="<div id='manage_menu'>";
$html.=$html_right;
$html.="</div>";

//���������� � ������� ������ ����� HTML ���� ��������
$html.=template_get('footer');

echo $html;
?>