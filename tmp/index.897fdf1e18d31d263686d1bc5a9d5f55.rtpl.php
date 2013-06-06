<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>


<div id="dropbox">

  <div class="fileBloc tooltips" title="Faites glisser des fichiers sur la zone ou cliquez sur celle ci pour envoyer des fichiers">
    <div id="dropZone">
      <input id="uploadButton" type="file" size="1" name="files[]" data-url="./php/action.php?action=upload" multiple>
    </div>
    <div class="clear"></div>
  </div>

  <div id="idDivInfo"></div>
</div>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>

