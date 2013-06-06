<?php

session_start();
require_once('config.php');
require_once('function.php');

$javascript =  array();
$javascript['succes'] = false;

$_ = array();
foreach($_POST as $key=>$val){
  $_[$key]=htmlentities($val);
}
foreach($_GET as $key=>$val){
  $_[$key]=htmlentities($val);
}

if (isset($_['action'])) {

  switch ($_['action']) {
  case 'upload':
    if (array_key_exists('files', $_FILES) && $_FILES['files']['error'][0] == 0 ) {
      require_once('zip.class.php');

      $pic = $_FILES['files'];
      $pic['name'] = utf8_decode($pic['name'][0]);
      $pic['tmp_name'] = $pic['tmp_name'][0];
      $pic['name'] = stripslashes($pic['name']);

      if (get_extension($pic['name']) == "lxf") {

	$token = date('Ymd-His');
	$currentFolder = '../'. UPLOAD_FOLDER . $token;

	if(mkdir($currentFolder)) {
	  @chmod( $currentFolder , 0755);

	  $destination = $currentFolder . $pic['name'];
	  $archive = new PclZip($pic['tmp_name']);

	  if ($archive->extract(PCLZIP_OPT_PATH, $currentFolder) == 0) {
	    die("Error : ". $archive->errorInfo(true));
	  }
	  
	  // TODO : parser le fichier IMAGE100.LXFML
	  // TODO : afficher le fichier IMAGE100.PNG

	  $javascript['img'] = "/brickCenter/uploads/" . $token . "/IMAGE100.PNG";
	  $javascript['token'] = $token;
	  $javascript['status'] = "Fichier bien déposé. Cordialement Bisous++";
	  $javascript['succes'] = true;

	} else {
	  $javascript['status'] = tt('Erreur, impossible de cr&eacute;er le dossier');
	}
      } else {
	$javascript['status'] = "Format différent de .lxf";
      }

    }
    break;
  }

}

echo (isset($javascript) ? json_encode($javascript) : '');

?>