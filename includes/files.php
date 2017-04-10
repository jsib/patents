<?
//read from file specified by $filename and return data which was read
function file_easy_read($file_name)
{
    $handle=fopen($file_name, 'r');
    return fread($handle, filesize($file_name));
}

function file_easy_write($file_name, $text)
{
    $handle=fopen($file_name, 'w');
    return fwrite($handle, $text);
}
?>