<?
//get value of sorting from browser
function get_sort(){
    if(isset($_GET['sort'])){
        if(preg_match("/^[A-z0-9_]{1,50}$/", $_GET['sort'])){
            return $_GET['sort'];
        }else{
            return false;
        }
    }else{
        return false;
    }
}

//get value of sorting direction from browser
function get_sort_direction(){
    if(isset($_GET['sort_direction'])){
        if(preg_match("/^[A-z0-9_]{1,20}$/", $_GET['sort_direction'])){
            return $_GET['sort_direction'];
        }else{
            return false;
        }
    }else{
        return false;
    }
}

//Сортирует матрицу по значениям желаемой колонки
function table_matrix_sort(&$matrix, $column_sort, $sort_direction, $sort_specific=array()){
    //Выполняем, только если матрица не пуста
    if(count($matrix)>0){
        //Создаем расширенную матрицу
        foreach($matrix as $key=>$columns)
        {
            $matrix_sort[$key]=$columns[$column_sort];
        }

        //Выполняем сортировку в нужном порядке
        switch ($sort_specific[$column_sort]){
            //IP-адреса
            case 'ip':
                switch ($sort_direction)
                {
                    case 'asc':
                        sort_ips($matrix_sort, $sort_direction);
                        break;
                    case 'desc':
                        sort_ips($matrix_sort, $sort_direction);
                        break;
                }
            break;

            //Обычная сортировка
            default:
                switch ($sort_direction)
                {
                    case 'asc':
                        asort($matrix_sort);
                    break;
                    case 'desc':
                        arsort($matrix_sort);
                    break;
                }
        }



        //Определяем новую матрицу из исходной за счет вспомогательной ******Старый вариант*******
        /*foreach($matrix_sort as $key=>$empt)
        {
            $matrix_new[$key]=$matrix[$key];
        }*/


        //При восходящей сортировке ставим пустые элементы наперед, при нисходящей наоборот
        foreach($matrix_sort as $key=>$value){
            if(trim($value)=="") $matrix_empty[$key]=$value;
            if(trim($value)!="") $matrix_full[$key]=$value;
        }

        //Определяем новую матрицу из исходной за счет вспомогательной
        switch($sort_direction){
            case 'asc':
                foreach((array)$matrix_full as $key=>$empty){
                    $matrix_new[$key]=$matrix[$key];
                }
                foreach((array)$matrix_empty as $key=>$empty){
                    $matrix_new[$key]=$matrix[$key];
                }
            break;
            case 'desc':
                foreach((array)$matrix_empty as $key=>$empty){
                    $matrix_new[$key]=$matrix[$key];
                }
                foreach((array)$matrix_full as $key=>$empty){
                    $matrix_new[$key]=$matrix[$key];
                }
                break;
        }


        //Заменяем исходную матрицу на новую
        $matrix=$matrix_new;
    }
}
?>