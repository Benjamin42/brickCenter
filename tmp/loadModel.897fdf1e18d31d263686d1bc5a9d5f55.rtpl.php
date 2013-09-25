<?php if(!class_exists('raintpl')){exit;}?><div class="row">
	<div class="span4">
			<div align="center">
				<img src="./tpl/<?php echo $IMG_PATH;?>" />
			</div>
	</div>
	<div class="span8">
		<table width="100%">
			<tr valign="middle">
				<td width="40%"><span class="filename">File Name : </span></td>
				<td><span class="filename" id="filename"><?php echo $FILENAME;?></span></td>
			</tr>
			<tr>
				<td><span class="filename">Bricks Quantity : </span></td>
				<td><span class="filename" id="idNbPiece"><?php echo $NB_BRICKS;?></span></td>
			</tr>
			<tr>
				<td>Model Quantity : </td>
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
			
			<!--
			<tr>
			  <td>
			    <a class="btn btn-primary" onclick="generateXmlPopup();">Generate XML in text <i class="icon-pencil icon-white"></i></a>
			  </td>
			  <td>
			    <textarea id="xmlTextId" ></textarea>
			  </td>
			</tr>
			-->
			
			<tr>
				<td colspan="2">
					<a class="btn btn-success" onclick="generateXmlBrickLink();">Generate XML in BrickLink <i class="icon-share-alt icon-white"></i></a>					
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
			<th>Picture</th>
			<th>ItemId</th>
			<th>Material</th>
			<th>Quantity</th>
		</tr>
		<?php $id=$this->var['id']=0;?>

		<?php $counter1=-1; if( isset($BRICKS_MAP) && is_array($BRICKS_MAP) && sizeof($BRICKS_MAP) ) foreach( $BRICKS_MAP as $key1 => $value1 ){ $counter1++; ?>

			<tr>
				
				<td><img id='idImg<?php echo $id;?>' src='<?php echo $value1->url;?>' style='background-color : rgb(<?php echo $value1->tradColor->rDesLab;?>,<?php echo $value1->tradColor->gDesLab;?>,<?php echo $value1->tradColor->bDesLab;?>);' /></td>
				<td id='idItemId<?php echo $id;?>'><?php echo $value1->itemId;?></td>
				<td><?php echo $value1->tradColor->blColorName;?><input type="hidden" id='idMaterial<?php echo $id;?>' value="<?php echo $value1->tradColor->blColorId;?>" /></td>
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
