<?php
require_once('config.php');
require_once('KLogger.php');
require_once('bdd.php');
require_once('simple_html_dom.php');

/******************
* Fonction utiles
*******************/

function logInfo($txt) {
  $log = new KLogger('./log', KLogger::DEBUG);
  $log->logInfo($txt);
}

function get_extension($file_name){
  $ext = explode('.', $file_name);
  $ext = (count($ext) == 1 ? null : array_pop($ext));
  return strtolower($ext);
}

/****************************************
* Fonctions de parsing du fichier .LXFML
*****************************************/

function parseXml($filename) {
	logInfo("fichier a parser = " . $filename);
	$document_xml = new DomDocument();
	$document_xml->load($filename);
	$elements = $document_xml->getElementsByTagName('LXFML');
	
	$tree = $elements->item(0); // On récupère le noeud LXFML
	
	$s = array();
	$s = parseChilds($tree, $s);

	return $s;
}

function parseChilds($node, $s) {
  $childs = $node->childNodes;
	
  foreach($childs as $child) {
    if($child->hasChildNodes() == true) {
      $s = parseNode($node, $s);
      $s = parseChilds($child, $s);
    }
  }
  return $s;
}


function parseNode($node, $s) {
	$nom = $node->nodeName;
	
	if ($nom == 'Part') {
		$designId = $node->attributes->getNamedItem('designID')->nodeValue;
		$material = $node->attributes->getNamedItem('materials')->nodeValue;
		
		logInfo("nom : " . $nom);
		logInfo("\tdesignId : " . $designId);
		logInfo("\tmaterial : " . $material);
		
		$key = $designId . "-" . $material;
		$brick = null;
		
		if (isset($s[$key])) {
			$brick = $s[$key];
			$brick->qty = $brick->qty + 1;
		} else {
			$brick = new Brick();
			$brick->designId = $designId;
			$brick->url = "./tpl/img/bricks/" . $designId . ".PNG";
			
			$brick->itemId = getItemIdBrick($designId);
			
			$brick->material = $material;
			$brick->tradColor = getTradColor($material);
			
			$brick->qty = 1;
	
			$s[$key] = $brick;
		}
	}
	return $s;
}

/**********************************************************
* Fonction de traduction LDDDesignID -> ItemID
***********************************************************/

function getItemIdBrick($designId) {
  	$db = new SQliteDB();

  	$itemID = $db->searchItemId($designId);

  	return $itemID;
}

/**********************************************************
* Fonction de traduction des couleurs
***********************************************************/

function getTradColor($materialId) {
  	$db = new SQliteDB();
  	
  	$colors = explode(",", $materialId);

	$color = -1;
	foreach($colors as $colorTmp) {
		if ($colorTmp > $color) {
			$color = $colorTmp;
		}
	}

  	return $db->searchBLColorIDColorWithMaterialId($color);
}

/*************************************
* Fonctions de gestion des couleurs
**************************************/

function createSelectList() {
	$db = new SQliteDB();
	$list = $db->searchAllColors();
	
	$html = "<select>";
	
	foreach ($list as $color) {
		$html .= "<option value='" . $color->blColorId . "' style='background-color: rgb(" . $color->rDesLab . ", " . $color->gDesLab . ", " . $color->bDesLab . ")'>" . $color->blColorName . "</option>";
	}
	
	$html .= "</select>";
	return $html;
}
?>