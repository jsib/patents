<?
//���������� ������� �������
$patents=array();
$table_matrix_appearance=array();
$table_matrix_columns=array();


//�������� ������� �� ���� ������
$a=db_query("SELECT `patents`.`id` as `id`,
					`patents`.`name` as `name`,
					`patents`.`certificate` as `certificate`,
					`patents`.`request` as `request`,
					`patents`.`priority` as `priority`,
					`patents`.`registration` as `registration`,
					`patents`.`paid_before` as `paid_before`,
					`patents`.`expire` as `expire`,
					`patents`.`comment` as `comment`
				FROM `patents`
				WHERE country_name='".get_country()."'");
				
while($patent=db_fetch($a))
{
    $id=$patent['id'];
    $patents[$id]['id']=$id;
    $patents[$id]['name']=$patent['name'];
	$patents[$id]['certificate']=$patent['certificate'];
	$patents[$id]['request']=$patent['request'];
	$patents[$id]['priority']=$patent['priority'];
	$patents[$id]['registration']=$patent['registration'];
	$patents[$id]['paid_before']=$patent['paid_before'];
	$patents[$id]['expire']=$patent['expire'];
	$patents[$id]['comment']=$patent['comment'];
}

//�������������� ������ ������ ��� �������
$table_matrix[0]['id']='Id �������';
$table_matrix[0]['name']='��� ��������� �����';
$table_matrix[0]['comment']='����������';
$table_matrix[0]['certificate']='� �������������';
$table_matrix[0]['request']='� ������';
$table_matrix[0]['priority']='���������';
$table_matrix[0]['registration']='�����������';
$table_matrix[0]['paid_before']='�������� ��';
$table_matrix[0]['expire']='���� ��������, ��';
if(get_user_group($_SESSION['user'])=="writer") $table_matrix[0]['delete']='';

//�������������� ��������� ������ ��� �������
if(count($patents)>0){
    foreach($patents as $id=>$patent)
    {
        $table_matrix[$id]['id']=$patent['id'];
        $table_matrix[$id]['name']=$patent['name'];
		$table_matrix[$id]['comment']=$patent['comment'];
		$table_matrix[$id]['certificate']=$patent['certificate'];
		$table_matrix[$id]['request']=$patent['request'];
		$table_matrix[$id]['priority']=$patent['priority'];
		$table_matrix[$id]['registration']=$patent['registration'];
		$table_matrix[$id]['paid_before']=$patent['paid_before'];
        $table_matrix[$id]['expire']=$patent['expire'];
		if(get_user_group($_SESSION['user'])=="writer"){
			$table_matrix[$id]['delete']="�������";
			$table_matrix_links[$id]['delete']['href']=uri_make(array('action'=>'delete_patent', 'id'=>$id));
			$table_matrix_appearance[$id]['delete']['style']="color:red;";
			$table_matrix_appearance[$id]['delete']['onclick']="if(!confirm(\"�������?\")) return false;";
		}
    }
}


//������ ������ ������ �������
$table['row_first']=$table_matrix[0];

//������ ������� �������
unset($table_matrix[0]);
$table['matrix']=$table_matrix;

$table_matrix_columns['id']['input_type']='hidden';
$table_matrix_columns['priority']['type']="date";
$table_matrix_columns['registration']['type']="date";
$table_matrix_columns['paid_before']['type']="date";
$table_matrix_columns['expire']['type']="date";

//������ �������� (������������ ������ �����)
/*$table_matrix_columns['name']['width']='260px';
$table_matrix_columns['certificate']['width']='150px';
$table_matrix_columns['request']['width']='150px';
$table_matrix_columns['priority']['width']='150px';
$table_matrix_columns['registration']['width']='150px';
$table_matrix_columns['expire']['width']='150px';*/

//������ ����� �����
$table_matrix_columns['name']['input_width']='260px';
$table_matrix_columns['certificate']['input_width']='150px';
$table_matrix_columns['request']['input_width']='150px';
$table_matrix_columns['priority']['input_width']='150px';
$table_matrix_columns['registration']['input_width']='150px';
$table_matrix_columns['expire']['input_width']='150px';

$table_rows['height']="28px";


//������ ������� �������� ���� �������
$table['matrix_appearance']=$table_matrix_appearance;

//������ ��� �������
$table['header']="�������� ����� � ������� (".$GLOBALS['countries'][get_country()]['name_rus'].")";

//������� ����� ������� (������� ����� ������ �� ���� ������� ������)
$table['border']=1;

//������ �������� ��������
$table['counter']="����������";

//�������� �������
$table['columns']=$table_matrix_columns;

//�������� �����
$table['rows']=$table_rows;

//�������� ������ ������
$table['matrix_links']=$table_matrix_links;

//������ ���������� �� ���������
$table['sort_default']='id';

//������ ����������� ���������� �� ���������
$table['sort_direction_default']='asc';
?>
