<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="fr" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="fr" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="fr" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="fr class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="fr" class="no-js"><!--<![endif]-->
<!--é-->
  <head>
    <meta charset="utf-8" />
    <title><?php echo $DC_TITLE;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="./tpl/./favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="./tpl/./css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="./tpl/./css/styles.css" />
    <!--[if lt IE  9]>
    <script src="./tpl/./js/html5.js"></script>
    <![endif]-->
    <script src="./tpl/./js/modernizr-2.5.3.min.js"></script>
  </head>
  <body onbeforeunload ="checkPendingTask();">
    <header <?php if( $DC_LOGO!='' ){ ?>'style="background-image:url('<?php echo $DC_LOGO;?>')"'<?php } ?>>
      <section id="versionBloc"></section>
      
      <div class="preloader"> Chargement en cours...</div>
    </header>
