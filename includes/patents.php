<?php
function load_patent_countries(){
	$q=db_query("SELECT * FROM `patent_countries`");

	while($country=db_fetch($q)){
		$countries[$country['name']]=array('name'=>$country['name'], 'name_rus'=>$country['name_rus']);
	}
	
	return $countries;

	//$countries['russia']=array('name'=>'russia', 'name_rus'=>'������');
	//$countries['ukraine']=array('name'=>'ukraine', 'name_rus'=>'�������');
	//$countries['kazahstan']=array('name'=>'kazahstan', 'name_rus'=>'���������');
	//$countries['belorussia']=array('name'=>'belorussia', 'name_rus'=>'��������');
}

function get_country(){
	$countries=$GLOBALS['countries'];
	
	if(isset($countries[@$_GET['country']])){
		$country_name=@$_GET['country'];
	}else{
		$country_name="russia";
	}
	
	return $country_name;
}
?>