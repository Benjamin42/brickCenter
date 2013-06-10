<?php
require_once('config.php');
require_once('KLogger.php');
require_once('bdd.php');
require_once('simple_html_dom.php');

/**********************************************************
* Fonction de résolution du chemin de l'image de la brick
***********************************************************/

function getBricksImagePath($designId, $material) {
  	$db = new SQliteDB();
  
	$brick = new Brick();
	$brick->designId = $designId;
	$brick->material = $material;
  
  	$brickFunded = $db->searchBrickException($designId);

  	if ($brickFunded != null && $brickFunded->newDesignId != '') {
  		$brick->designId = $brickFunded->newDesignId;
  	}

  	$brickFunded = $db->searchBrickOtherPath($brick->designId, $material);
  	if ($brickFunded != null && $brickFunded->url != '') {
  		$brick->url = $brickFunded->url;
  		
  		if ($brickFunded->newMaterial != null) {
  			$brick->material = $brickFunded->newMaterial;
  		}
  	} else {
  		$brick->url = "http://img.bricklink.com/P/" . $brick->material . "/" . $brick->designId . ".gif";
  	}
  
  	return $brick;
}

// Fonction pour trouver une autre image (appel Ajax)
function findOtherImage($designId, $material) {
	$db = new SQliteDB();
	
	$brick = new Brick();
	$brick->designId = $designId;
	$brick->material = $material;
	
	// Essai avec l'extension .jpg
	$url = "http://img.bricklink.com/P/" . $material . "/" . $designId . ".jpg";
	
	$headers = @get_headers($url);
  	if(strpos($headers[0],'200')!==false) {
  		$db->addBrickNewPath($designId, $material, null, $url);
  		$brick->url = $url;
    	return $brick;
  	}
  		
  	// Essai en changeant de couleur (gris -> bluish grey)
  	if ($material == '10') { // Dark gray
  		$newMaterialId = '85';
  	} else if ($material == '9') { // Light gray
  		$newMaterialId = '86';
  	} else if ($material == '49') { // Very light gray
  		$newMaterialId = '99';
  	} else if ($material == '85') { // Dark Bluish gray
  		$newMaterialId = '10';
  	} else if ($material == '86') { // Light Bluish gray
  		$newMaterialId = '9';
  	} else if ($material == '99') { // Very light Bluish gray
  		$newMaterialId = '49';
  	}
  	
  	if (isset($newMaterialId)) {
  		$newUrl = tryUrls($designId, $newMaterialId);
  		if ($newUrl != '') {
			$db->addBrickNewPath($designId, $material, $newMaterialId, $newUrl);
			$brick->material = $newMaterialId;
			$brick->url = $newUrl;
			return $brick;
    	}
  	}
  	
  	$newDesignId = tryFindingDifferentDesignId($designId);
  	if ($newDesignId != '') {
  		$newUrl = tryUrls($newDesignId, $material);
  		if ($newUrl != '') {
			$db->addBrickNewDesignId($designId, $newDesignId);
			$brick->designId = $newDesignId;
			$brick->url = $newUrl;
			return $brick;
    	}
  	}
  	
  	$brick->url = "https://images3.rapidshare.com/web/img/facelift/cross-icon.png";
  	return $brick;
}

function tryUrls($designId, $material) {
	$url = "http://img.bricklink.com/P/" . $material . "/" . $designId . ".gif";
	
	$headers = @get_headers($url);
  	if(strpos($headers[0],'200')!==false) {
    	return $url;
  	}
  	
  	$url = "http://img.bricklink.com/P/" . $material . "/" . $designId . ".jpg";
	
	$headers = @get_headers($url);
  	if(strpos($headers[0],'200')!==false) {
    	return $url;
  	}
}

function tryFindingDifferentDesignId($designId) {
	$url = "http://www.bricklink.com/catalogList.asp?catType=P&catID=&itemYear=&searchName=Y&searchNo=Y&q=" . $designId . "&catLike=W";
	
	$opts = array(
              'http'=>array(
                            'method'=>"GET",
                            'user_agent'=>"Mozilla/5.0"
              )
    );
	$context = stream_context_create($opts);
	$str = file_get_contents($url, false, $context);
	$html = str_get_html($str);
	
	$arrayTitle = $html->find("font[face='Arial']");

	foreach ($arrayTitle as $element) {
		$str = $element->plaintext;
		if ( preg_match("#([0-9]*)$#i", $str, $matches) ) {
    		return($matches[1]);
    	}
	}
	
	return "";
}

/******************
* Fonction utiles
*******************/

function logInfo($txt) {
  $log = new KLogger('../log', KLogger::DEBUG);
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
		
		if (isset($s[$designId][$material])) {
			$s[$designId][$material] = $s[$designId][$material] + 1;
		} else {
			$s[$designId][$material] = 1;
		}
		
		logInfo("nom : " . $nom);
		logInfo("\tdesignId : " . $designId);
		logInfo("\tmaterial : " . $material);
		//$log->logInfo("\tqty : " . $s[$p]);
	}
	return $s;
}

/*************************************
* Fonctions de gestion des couleurs
**************************************/

function extractColor($colorInput) {
	$colors = explode(",", $colorInput);
	
	$color = -1;
	foreach($colors as $colorTmp) {
		if ($colorTmp > $color) {
			$color = $colorTmp;
		}
	}
  
	$db = new SQliteDB();
	$colorTrad = $db->searchBLColorIDColorWithMaterialId($color);
  
  	return $colorTrad;
}

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