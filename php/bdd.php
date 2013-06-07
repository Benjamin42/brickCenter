<?php

class Brick {
	public $designId;
	public $newDesignId;
	public $material;
	public $newMaterial;
	public $url;
	
	function __construct() {
	}
}

class Color {
	public $blColorId;
	public $blColorName;
	public $rDesLab;
	public $gDesLab;
	public $bDesLab;
	
	function __construct() {
	}
}

class SQliteDB extends SQLite3
{
  var $location="../db/dev.db";

  function __construct() {
    $this->open($this->location);
  }
  
  /********************************************
  * Fonctions de gestion des bricks inconnues
  *********************************************/

  function addBrickNewPath($designId, $material, $newMaterial, $imgPath) {
	$query = "INSERT INTO bricks (ID, DESIGN_ID, MATERIAL, NEW_MATERIAL, URL) "
		. "VALUES ((select max(id) + 1 from bricks), '" . $designId . "', '" . $material . "', '" . $newMaterial . "', '" . $imgPath . "')";
	$this->query($query);
  }

  function searchBrickException($designId) {
    $query = "SELECT * FROM bricks WHERE DESIGN_ID = " . $designId . " AND MATERIAL is null";

    $results = $this->query($query);
    while ($row = $results->fetchArray()) {
    	$brick = new Brick();
    	$brick->designId = $row['DESIGN_ID'];
    	$brick->newDesignId = $row['NEW_DESIGN_ID'];
    	$brick->material = $row['MATERIAL'];
    	$brick->newMaterial = $row['NEW_MATERIAL'];
    	$brick->url = $row['URL'];
    
      	return $brick;
    }
    return null;
  }
  
  function searchBrickOtherPath($designId, $material) {
    $query = "SELECT * FROM bricks WHERE DESIGN_ID = " . $designId . " AND MATERIAL = " . $material;

    $results = $this->query($query);
    while ($row = $results->fetchArray()) {
    	$brick = new Brick();
    	$brick->designId = $row['DESIGN_ID'];
    	$brick->newDesignId = $row['NEW_DESIGN_ID'];
    	$brick->material = $row['MATERIAL'];
    	$brick->newMaterial = $row['NEW_MATERIAL'];
    	$brick->url = $row['URL'];
    
      	return $brick;
    }
    return null;
  }
  
  /**********************************************
  * Fonctions de gestion des couleurs des bricks
  ***********************************************/
  
	function searchBLColorIDColorWithMaterialId($materialId) {
		$query = "SELECT BLColorID FROM colors WHERE materialId = " . $materialId;
		
		$results = $this->query($query);
		while ($row = $results->fetchArray()) {
			if ($row['BLColorID'] != '') {
				return $row['BLColorID'];
			}
		}
		
		return '';
	}
	
	function searchAllColors() {
		$query = "SELECT * FROM colors";
		
		$list = array();
		$results = $this->query($query);
		while ($row = $results->fetchArray()) {
			$color = new Color();
			$color->blColorId = $row['BLColorID'];
			$color->blColorName = $row['BLColorName'];
			$color->rDesLab = $row['R_DesLab'];
			$color->gDesLab = $row['G_DesLab'];
			$color->bDesLab = $row['B_DesLab'];
			
			array_push($list, $color);
		}
		
		return $list;
	}
}

?>