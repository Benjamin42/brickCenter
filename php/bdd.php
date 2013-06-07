<?php

class SQliteDB extends SQLite3
{
  var $location="../db/dev.db";

  function __construct()
  {
    $this->open($this->location);
  }

  function addBrick($designId, $material, $imgPath) {

  }

  function searchBrick($designId, $material) {
    $query = "SELECT IMG_PATH FROM bricks WHERE DESIGN_ID = " 
      . $designId 
      . " AND MATERIAL = " 
      . $material;

    $results = $this->query($query);
    while ($row = $results->fetchArray()) {
      if ($row['IMG_PATH'] != '') {
	return $row['IMG_PATH'];
      }
    }
    return '';
  }
}

?>