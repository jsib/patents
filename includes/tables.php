<?
//���������� .php ����, ����������� �������
function include_table($table_name){
    //���������� .php ����
    require_once($_SERVER['DOCUMENT_ROOT']."/tables/$table_name.php");

    //���������� �������� $table �� ������������� �����
    return $table;
}

//���������� HMTL ������� �� ������� ������
function get_table($table){
    $header=$table['row_first'];
    $matrix=$table['matrix'];
    $matrix_appearance=$table['matrix_appearance'];
    $matrix_links=$table['matrix_links'];
    $sort=$table['sort'];
    $sort_direction=$table['sort_direction'];
	$border_td=$table['border'];
	if($border_td>0){$border_table=$table['border']+1;}else{$border_table=0;}
	$html.= 'border_table: '.$border_table;
	$matrix_properties=@$table['matrix_properties'];
	$row_height=@$table['rows']['height'];

    //��������� �������
    $html="<table cellspacing='0' cellpadding='1' border='$border_td' class='data' style='border:{$border_table}px solid #09F;'>";

    //��������� ������
    $html.="<tr>";

    //������ ��������� �������
    foreach($header as $column_name=>$column_user_name){
		$width=@$table['columns'][$column_name]['width'];
		if(@$table['columns'][$column_name]['input_type']!='hidden'){
			$html.="<th style='width:$width;'>".column_header($column_name, $column_user_name, $sort, $sort_direction)."</th>";
		}else{
			$html.="";
		}
    }

    //��������� ������
    $html.="</tr>";

    //��������� ������ (�� ��������)
    foreach($matrix as $row=>$columns)
    {
        //'open row'
        $html.="<tr>";

        //look over columns for current row
        foreach($columns as $column=>$value){
			$input_width=@$table['columns'][$column]['input_width'];
			if(@$_GET['regime']!="write"){
				if(@$table['columns'][$column]['input_type']!='hidden'){				
					$html.="<td style='{$matrix_appearance[$row][$column]};height:$row_height;padding-left:8px;'>";
					if(isset($matrix_links[$row][$column])) $html.="<a href='{$matrix_links[$row][$column]}' class='no_underlined'>";
					if(@$table['columns'][$column]['type']=="date"){
						if(date("d.m.Y", strtotime($value))=="31.12.1969"){
							$html.="";
						}else{
							$html.=date("d.m.Y", strtotime($value));
						}
					}else{
						$html.=$value;
					}
					if(isset($matrix_links[$row][$column])) $html.="</a>";
					$html.="</td>";
				}else{
					$html.="";
				}
			}else{
				if(@$table['columns'][$column]['input_type']!='hidden'){
					$html.="<td style='{$matrix_appearance[$row][$column]};height:$row_height;padding-left:5px;'>";
				}else{
					$html.="";
				}
				
				//��� ����� ���� "����"
				if(@$table['columns'][$column]['type']=="date"){
					if(date("d.m.Y", strtotime($value))=="31.12.1969"){
						$date_html="";
					}else{
						$date_html=date("d.m.Y", strtotime($value));
					}
					if(isset($matrix_links[$row][$column])){
						$html.="<a href='{$matrix_links[$row][$column]['href']}' style='{$matrix_appearance[$row][$column]['style']}' class='no_underlined' onclick='{$matrix_appearance[$row][$column]['onclick']}'>".$date_html."</a>";
					}else{
						$html.="<input name='Form[$row][$column]' value='".$date_html."' class='datepickerTimeField' style='margin:0;padding:0;border:0px;width:$input_width;'>";
					}
				//��� ����� ���� "Hidden"
				}elseif(@$table['columns'][$column]['input_type']=='hidden'){
					$html.="<input type='hidden' name='Form[$row][$column]' value='$value' style=''>";
				//��� ����� ���� "�����"
				}else{
					if(isset($matrix_links[$row][$column])){
						$html.="<a href='{$matrix_links[$row][$column]['href']}' style='{$matrix_appearance[$row][$column]['style']}' class='no_underlined' onclick='{$matrix_appearance[$row][$column]['onclick']}'>".$value."</a>";
					}else{
						$html.="<input type='text' name='Form[$row][$column]' value='$value' style='margin:0;padding:0;border:5px;width:$input_width;'>";
						//���������� ���������������. ����� ��� ��� �������������� ����� ��������� ������ ���� onChange=\"document.getElementById('Form').submit(); ���� ���, ��� �������� ������������� ����� ������ ���.
					}
				}
				
				if(@$table['columns'][$column]['input_type']!='hidden'){
					$html.="</td>";
				}else{
					$html.="";
				}
			}
        }

        //��������� ������
        $html.="</tr>";
    }

    //��������� �������
    $html.="</table>";

    //���������� HTML
    return $html;
}

//������� ��������� ��������� ������� � ��������
function column_header($column_name, $column_user_name, $sort, $sort_direction){
    //get new sort dirction and new opposite sort direction
    if($sort==$column_name){
        if($sort_direction=="asc"){
            $sort_direction_new="desc";
            $sort_direction_opposite_new="asc";
        }else{
            $sort_direction_new="asc";
            $sort_direction_opposite_new="desc";
        }
    }else{
        $sort_direction_new="asc";
        $sort_direction_opposite_new="asc";
    }

    //�������� �������� 'sort' � url
    $uri=uri_make('sort', $column_name);

    //�������� �������� 'sort_direction' � url
    $uri=uri_change('sort_direction', $sort_direction_new, $uri);

    //�������� HTML ������ ��� ����������
    $hrefs="<a href='".$uri."' class='sort'>".$column_user_name."</a>";

    //��������� �������������� HTML ������ �� ������� �������
    if($sort==$column_name){
        $hrefs.="<a href='".$uri."'><img src='/_content/img/".$sort_direction_opposite_new.".png' style='margin:0 0 0 3px;'></a>";
    }

    //���������� HTML
    return $hrefs;
}


?>