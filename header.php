<?php
session_start();
header( 'content-type: text/html; charset=utf-8' );

require_once('php/rain.tpl.class.php');
require_once('php/config.php');
require_once('php/function.php');

// Instanciation du template
$tpl = new RainTPL();

// Definition des dossiers de template

raintpl::configure("base_url", null );
raintpl::configure("tpl_dir", './tpl/');
raintpl::configure("cache_dir", "./tmp/" );

$user = null;

$tpl->assign('DC_TITLE',DC_TITLE);//Titre du dropCenter
$tpl->assign('UPLOAD_FOLDER', UPLOAD_FOLDER); //chemin vers le dossier d'upload (ne pas oublier de mettre les droits d'écriture sur ce dossier)

?>