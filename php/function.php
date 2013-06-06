<?php
require_once('config.php');


function get_extension($file_name){
  $ext = explode('.', $file_name);
  $ext = (count($ext) == 1 ? null : array_pop($ext));
  return strtolower($ext);
}


?>