<?
//����� � $uri ��������� � ������ $argument_name
function uri_find_argument($argument_name, $uri){
    if(preg_match("/\?$argument_name\=/", $uri) || preg_match("/\&$argument_name\=/", $uri)){
        return true;
    }else{
        return false;
    }
}

//�������� � uri �������� �������� $argument_name �� $argument_value
//���� � uri �� ��� ��������� �������� $argument_name, �� �� ����� �������� � ���������� ������
function uri_change($argument_name, $argument_value, $uri){
    //uri �������� "?"
    if(preg_match("/\?/", $uri)){
        //uri �������� ������� ��������
        if(uri_find_argument($argument_name, $uri)){
			//������ �������� ��������� (��� ������� ��������)
			if($argument_value!=""){
				$uri=preg_replace("/\?$argument_name\=[^\&\?]+/", "?$argument_name=$argument_value", $uri);
				$uri=preg_replace("/\&$argument_name\=[^\&\?]+/", "&$argument_name=$argument_value", $uri);
			//�������� ��������� (��� ������ ��������)
			}else{
				$uri=preg_replace("/\?$argument_name\=[^\&\?]+/", "?", $uri);
				preg_replace("/\?\&/", "?", $uri); //�� ������, ���� �������� ����� ����� ? � �� ��� ���� ��� ��������� (������� ������ ������������ &)
				$uri=preg_replace("/\&$argument_name\=[^\&\?]+/", "", $uri);
			}
        //uri �� �������� �������� ���������
        }else{
            //show("uri 3: ".$argument_name."::". $argument_value. "::".$uri);
            $uri="{$uri}&{$argument_name}={$argument_value}";
        }
    //uri �� �������� "?", ������ ������ ��������� �������� �� ���������
    }else{
        $uri="{$uri}?{$argument_name}={$argument_value}";
    }
    return $uri;
}

//�������� �������� ��������� � $_SERVER['URI_REQUEST'] � ���������� ���������
function uri_make($argument_name=false, $argument_value=""){
	if(!$argument_name){
		return $_SERVER['REQUEST_URI'];
	}else{
		if(!is_array($argument_name)){
			return uri_change($argument_name, $argument_value, $_SERVER['REQUEST_URI']);
		}else{
			$arguments=$argument_name;
			$uri=$_SERVER['REQUEST_URI'];
			foreach($arguments as $name=>$value){
				$uri=uri_change($name, $value, $uri);
			}
			return $uri;
		}
	}
}

function get_class_depend_on_uri($rule, $argument, $value){
    switch($rule){
        case "=":
            if(trim(@$_GET[$argument])==$value){
                return "not-lighted";
            }else{
                return "";
            }
        break;
        case "!=":
            if(trim(@$_GET[$argument])!=$value){
                return "not-lighted";
            }else{
                return "";
            }
        break;
    }
}
?>