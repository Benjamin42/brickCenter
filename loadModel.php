<?php
//é
require_once('header.php') ;


$_ = array();
foreach($_POST as $key=>$val){
  $_[$key]=htmlentities($val);
}
foreach($_GET as $key=>$val){
  $_[$key]=htmlentities($val);
}

if (array_key_exists('files', $_FILES) && $_FILES['files']['error'][0] == 0 ) {
	require_once('php/zip.class.php');
	require_once('php/function.php');

    $pic = $_FILES['files'];
    $pic['name'] = utf8_decode($pic['name'][0]);
    $pic['tmp_name'] = $pic['tmp_name'][0];
    $pic['name'] = stripslashes($pic['name']);

    if (get_extension($pic['name']) == "lxf") {

		$token = date('Ymd-His');
		$currentFolder = './'. UPLOAD_FOLDER . $token;

		if (mkdir($currentFolder)) {
	  		@chmod( $currentFolder , 0755);

	  		$destination = $currentFolder . $pic['name'];
	  		$archive = new PclZip($pic['tmp_name']);

	  		if ($archive->extract(PCLZIP_OPT_PATH, $currentFolder) == 0) {
	    		die("Error : ". $archive->errorInfo(true));
	  		}
	  
			$s = parseXml($currentFolder . "/IMAGE100.LXFML");
			
			$listBrick = array();
			$nbPiece = 0;
			foreach ($s as $tmpBrick) {
				$tradColor = extractColor($tmpBrick->material);
				
				$brick = getBricksImagePath($tmpBrick->designId, $tradColor);
				$brick->qty = $tmpBrick->qty;
				
				array_push($listBrick, $brick);
				$nbPiece += $brick->qty;
			}
			
			$tpl->assign('BRICKS_MAP', $listBrick);
			$tpl->assign('NB_BRICKS', $nbPiece);
			$tpl->assign('IMG_PATH', "../uploads/" . $token . "/IMAGE100.PNG");
			$tpl->assign('FILENAME', $pic['name']);
	  
	  	} else {
	  		$tpl->assign('ERROR', 'Erreur, impossible de cr&eacute;er le dossier');
		}
	} else {
		$tpl->assign('ERROR', 'Format différent de .lxf');
	}
} else {
	$tpl->assign('ERROR', 'Pas de fichier uploadé (hack ?)');
}
	  
	  
$view = 'loadModel';

require_once('footer.php');
?>