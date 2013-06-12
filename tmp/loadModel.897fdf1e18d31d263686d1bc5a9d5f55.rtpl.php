<?php if(!class_exists('raintpl')){exit;}?><div class="row">
	<div class="span4">
			<div align="center">
				<img src="./tpl/<?php echo $IMG_PATH;?>" />
			</div>
	</div>
	<div class="span8">
		<table width="100%">
			<tr>
				<td width="25%"><span class="filename">Fichier : </span></td>
				<td><span class="filename" id="filename"><?php echo $FILENAME;?></span></td>
			</tr>
			<tr>
				<td><span class="filename">Nombre de pièces : </span></td>
				<td><span class="filename" id="idNbPiece"><?php echo $NB_BRICKS;?></span></td>
			</tr>
			<tr>
				<td>Nombre d'exemplaires : </td>
				<td>
					<select onchange="multAllQty(this.value)" >
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>WantedList Id : </td>
				<td><input id="wantedListId" type="text" value="" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<a class="btn btn-danger" onclick="findAllRef();">Find All Ref</a>
					<a class="btn btn-success" onclick="generateXml();">Generate XML<i class="icon-arrow-down icon-white"></i></a>
				</td>
			</tr>
		</table>
	</div>
</div>
<br/>
<div id="table" class="span12" style="padding-left: 20px;">
	<?php if( isset($BRICKS_MAP) ){ ?>	  
	<table class='table'>
		<tr>
			<th>Image</th>
			<th>DesignId</th>
			<th>Material</th>
			<th>Quantité</th>
		</tr>
		<?php $id=$this->var['id']=0;?>

		<?php $counter1=-1; if( isset($BRICKS_MAP) && is_array($BRICKS_MAP) && sizeof($BRICKS_MAP) ) foreach( $BRICKS_MAP as $key1 => $value1 ){ $counter1++; ?>

			<tr>
				<td><img id='idImg<?php echo $id;?>' onerror='addButonSearchRef(this, <?php echo $value1->designId;?>, <?php echo $value1->material;?>)' src='<?php echo $value1->url;?>' /></td>
				<td id='idDesignId<?php echo $id;?>'><?php echo $value1->designId;?></td>
				<td id='idMaterial<?php echo $id;?>'><?php echo $value1->material;?></td>
				<td><span id='idQtyFinal<?php echo $id;?>'><?php echo $value1->qty;?></span><input type='hidden' id='idQtyInit<?php echo $id;?>' value='<?php echo $value1->qty;?>' /></td>
			</tr>
			<?php $id=$this->var['id']=$id+1;?>

		<?php } ?>

	</table>
	<?php } ?>

	<?php if( isset($ERROR) ){ ?>

		<h4>Des erreurs sont survenues : <?php echo $ERROR;?></h4>
	<?php } ?>

</div>

<form id="formValidateXML" action="http://www.bricklink.com/wantedXMLverify.asp" method="POST" target="_blank" >
	<input name="xmlFile" type="hidden" value="" />
</form>
