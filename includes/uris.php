<?php
//поиск в $uri аргумента с именем $argument_name
function uri_find_argument($argument_name, $uri){
    if(preg_match("/\?$argument_name\=/", $uri) || preg_match("/\&$argument_name\=/", $uri)){
        return true;
    }else{
        return false;
    }
}

//заменяет в uri значение аргумент $argument_name на $argument_value
//если в uri не был определен аргумент $argument_name, то он будет добавлен в получаемую строку
function uri_change($argument_name, $argument_value, $uri){
    //uri содержит "?"
    if(preg_match("/\?/", $uri)){
        //uri содержит искомый аргумент
        if(uri_find_argument($argument_name, $uri)){
			//замена значения аргумента (при наличии значения)
			if($argument_value!=""){
				$uri=preg_replace("/\?$argument_name\=[^\&\?]+/", "?$argument_name=$argument_value", $uri);
				$uri=preg_replace("/\&$argument_name\=[^\&\?]+/", "&$argument_name=$argument_value", $uri);
			//удаление аргумента (при пустом значении)
			}else{
				$uri=preg_replace("/\?$argument_name\=[^\&\?]+/", "?", $uri);
				preg_replace("/\?\&/", "?", $uri); //на случай, если аргумент стоял после ? и за ним были еще аргументы (которые всегда предваряются &)
				$uri=preg_replace("/\&$argument_name\=[^\&\?]+/", "", $uri);
			}
        //uri не содержит искомого аргумента
        }else{
            //show("uri 3: ".$argument_name."::". $argument_value. "::".$uri);
            $uri="{$uri}&{$argument_name}={$argument_value}";
        }
    //uri не содержит "?", значит просто добавляем аргумент со значением
    }else{
        $uri="{$uri}?{$argument_name}={$argument_value}";
    }
    return $uri;
}

//заменяет значение аргумента в $_SERVER['URI_REQUEST'] и возвращает результат
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

function get_class_depend_on_uri($rule, $argument, $value) {
    switch($rule){
        case "=":
            if(trim($argument) == $value){
                return "not-lighted";
            }else{
                return "";
            }
        break;
        case "!=":
            if(trim($argument) != $value){
                return "not-lighted";
            }else{
                return "";
            }
        break;
    }
}
?>