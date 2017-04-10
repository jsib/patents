<?
require_once($_SERVER['DOCUMENT_ROOT']."/includes/uris.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/templates.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/files.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/auth.php");

//�������� ������ � ������ �� ����� �����
session_start();
session_set_cookie_params(10800);

//������������ � ���� ������
db_connect();

//������� � index.php
$default_page="/table.php?table_name=patents&country=russia&regime=write";

if(isset($_SESSION['user'])){
	if(@$_GET['action']=="logout"){
		unset($_SESSION['user']);
		echo login_form();
	}else{
		//���� ������������ ��� �����������
		header("location: $default_page");
	}
}elseif(@$_GET['action']=='login'){
	//��� ������ ���������������
	if(check_login()){
		//� ���� ������ ����� � ������
		$_SESSION['user']=@$_POST['user'];
		header("location: $default_page");
	}else{
		//��� ��� ��� ���� �������
		echo login_form("<span style='color:red'>������ � ������ ��� ������!</span><br/>");
	}
}else{
	//������������� �� ����� ����� � �������
	//echo generate_hash("nosova", "qwe123");
	echo login_form();
}
?>
