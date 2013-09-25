<?php

class Brick {
	public $designId;
	public $itemId;
	public $material;
	public $url;
	public $qty;
	public $tradColor;
	
	function __construct() {
		$this->qty = 0;
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
  var $location = null;

  function __construct() {
  	$this->location = APP_DIR . "db/bricks.db";
  	
    $this->open($this->location);
  }
  
  /**********************************************
  * Fonctions de gestion des couleurs des bricks
  ***********************************************/
  
	function searchBLColorIDColorWithMaterialId($materialId) {
		$query = "SELECT BLColorID, BLColorName, R_DesLab, G_DesLab, B_DesLab FROM colors WHERE materialId = '" . $materialId . "'";
		
		$results = $this->query($query);
		while ($row = $results->fetchArray()) {
			if ($row['BLColorID'] != '') {
			
				$color = new Color();
				$color->blColorId = $row['BLColorID'];
				$color->blColorName = $row['BLColorName'];
				$color->rDesLab = $row['R_DesLab'];
				$color->gDesLab = $row['G_DesLab'];
				$color->bDesLab = $row['B_DesLab'];
			
				return $color;
			}
		}
		
		return $materialId;
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
	
  /**********************************************
  * Fonctions de recherche d'une brick BrickLink avec LDDDesignId
  ***********************************************/
  
	function searchItemId($designID) {
		$query = "SELECT BLItemID FROM tLDDDesignID WHERE LDDDesignID = " . $designID;
		
		$results = $this->query($query);
		while ($row = $results->fetchArray()) {
			if ($row['BLItemID'] != '') {
				return $row['BLItemID'];
			}
		}
		
		return null;
	}
}

?>