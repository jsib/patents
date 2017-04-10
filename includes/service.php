<?
function show($text)	//Friendly shows some text given in different formats
{
	$sapi=php_sapi_name();
	if(gettype($text)=='boolean')
	{
		if($text){$text='true';}else{$text='false';}
		if($sapi=='cli')
		{
			echo $text."\n";
		}else{
			echo $text."<br/>";
		}
	}elseif(gettype($text)=='array')
		if($sapi!='cli')
		{
			echo "<pre>";
			print_r($text);
			echo "</pre>";
		}else{
			print_r($text);
	}else{
		if($sapi=='cli')
		{
			echo $text."\n";
		}else{
			echo $text.'<br/>';
		}
	}
}

function h1($text)
{
	return "<h1 style='font-size:24pt;'>$text</h1>";
}

function h2($text)
{
    return "<h2 style='font-size:18pt;'>$text</h2>";
}
?>