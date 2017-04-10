<?
//Производит замены в шаблоне
function template_get($template_name, $replacements=array())
{
    if(template_exists($template_name)){
        //Получаем текст из файла
        $text=file_easy_read($_SERVER['DOCUMENT_ROOT']."/templates/$template_name.php");

        //Делаем замены, если массив замен не пуст
        if(count($replacements)>0){
            //Проходим все замены одна за другой
            foreach($replacements as $match=>$replacement)
            {
                //Заменяем все вхождения, соответствующие '{$match}' на '$replacement'
                $text=str_replace("{".$match."}",$replacement,$text);
            }
        }
        return $text;
    }else{
        echo "Файл шаблона '$template_name' не существует.";
    }
}

//Проверяет, существует ли файл шаблона
function template_exists($template_name){
    return file_exists($_SERVER['DOCUMENT_ROOT']."/templates/$template_name.php");
}
?>