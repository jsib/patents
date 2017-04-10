<?
//���������� ������ � �������
function template_get($template_name, $replacements=array())
{
    if(template_exists($template_name)){
        //�������� ����� �� �����
        $text=file_easy_read($_SERVER['DOCUMENT_ROOT']."/templates/$template_name.php");

        //������ ������, ���� ������ ����� �� ����
        if(count($replacements)>0){
            //�������� ��� ������ ���� �� ������
            foreach($replacements as $match=>$replacement)
            {
                //�������� ��� ���������, ��������������� '{$match}' �� '$replacement'
                $text=str_replace("{".$match."}",$replacement,$text);
            }
        }
        return $text;
    }else{
        echo "���� ������� '$template_name' �� ����������.";
    }
}

//���������, ���������� �� ���� �������
function template_exists($template_name){
    return file_exists($_SERVER['DOCUMENT_ROOT']."/templates/$template_name.php");
}
?>