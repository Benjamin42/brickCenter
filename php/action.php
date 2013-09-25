<?php

session_start();
require_once('config.php');
require_once('function.php');

$javascript =  array();
$javascript['succes'] = false;

$_ = array();
foreach($_POST as $key=>$val) {
  $_[$key]=htmlentities($val);
}

foreach($_GET as $key=>$val) {
  $_[$key]=htmlentities($val);
}


if (isset($_['action'])) {

  switch ($_['action']) {
  
	  case 'findOtherImage':
		if (isset($_['designId']) && isset($_['material'])) {
			
			$javascript['succes'] = true;
			
			$brick = findOtherImage($_['designId'], $_['material']);
			
			$javascript['url'] = $brick->url;
			$javascript['material'] = $brick->material;
			$javascript['designId'] = $brick->designId;
		
		} else {
			$javascript['status'] = "Problème de paramètres.";
		}  
	  break;

  }

}

echo (isset($javascript) ? json_encode($javascript) : '');

?>