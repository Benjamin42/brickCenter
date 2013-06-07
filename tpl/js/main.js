//é
var blockNewFolder = false;
var pendingTask = false;
$(function() {
	
	
	$(document).ajaxStop(function() {
		$('.preloader').hide();
	    });
	$(document).ajaxStart(function() {
  		$('.preloader').show();
	    });


	$('.tooltips').poshytip({
		className: 'tooltip',
		    showTimeout: 0,
		    alignTo: 'target',
		    alignX: 'center',
		    offsetY: 10,
		    allowTipHover: false
		    });


	$(function () {
		$('#uploadButton').fileupload({
			dataType: 'json',
			    autoUpload: true,
			    dropZone : '#uploadButton',
			    maxFileSize: 5000000,
			    sequentialUploads: true,
			    add: function (e, data) {
			    pendingTask = true;
			    data.submit();
			},
			    progress: function (e, data) {
			    var progress = parseInt(data.loaded / data.total * 100, 10);
			    $.data(data.files[0]).find('.progress').width(progress+'%');
			},
			    success: function (result, textStatus, jqXHR) {
			    showInfo(result);
			}
		    });
	    });


	var templateInfo = '<div class="row">'
	    + '<div class="span4">'
	    + '		<div align="center">'
	    + '			<img />' 
	    + '		</div>'
	    + '</div>'
	    + '<div class="span8">' 
	    + '		<table width="100%">'
	    + '			<tr>'
	    + '				<td width="25%"><span class="filename">Fichier : </span></td>'
	    + '				<td><span class="filename" id="filename"></span></td>'
	    + '			</tr>'
	    + '			<tr>'
	    + '				<td><span class="filename">Nombre de pièces : </span></td>'
	    + '				<td><span class="filename" id="nbBricks"></span></td>'
	    + '			</tr>'
	    + '		</table>'
	    + '</div>'
	    + '<br/>'
	    + '<div id="table" class="span9" align="center"></div>';


	function showInfo(result) {
	    var preview = $(templateInfo), 
		image = $('img', preview),
		fileName = $('#filename', preview);
		nbBricks = $('#nbBricks', preview);
		table = $('#table', preview);

	    var size = 128;
	    image.attr('width', size);
	    image.attr('height', size);
	    image.attr('src', result.img);

	    fileName.html(result.filename);
		nbBricks.html(result.qtyTotal);
		
		table.html(result.html);
		
	    $('#idDivInfo').html(preview);
	}

	
	function showMessage(msg){
		message.html(msg);
	}
	
	checkVersion();

});




function checkPendingTask(){
	if(pendingTask){
		alert('Certaines tâches sont encore en cours d\'execution, vous risquez de perdre des données.');
	}
}

function generateBreadCrumb(folder){
	returned = '';
	if(folder!=null){
	var dissolvedPath = explode('/',str_replace('../','',folder));
	var path = '../'; 
	for(i=0;i<dissolvedPath.length;i++){
		if(dissolvedPath[i]!=''){
			path +=dissolvedPath[i]+'/';
			returned +='<li alt="'+addslashes(path)+'" onclick="getFiles(null,\''+addslashes(path)+'\')">'+dissolvedPath[i]+'</li>';
		}
	}

	}
	return returned;
}
	function getFiles(keywords,folder){
		$('#dropbox .preview,.tooltip').remove();

		
		if(keywords==null){keywords='';}else{keywords = "&keywords="+keywords}
		if(folder==null){folderVar='';}else{folderVar = "&folder="+folder}
		$.ajax({
		url: "php/action.php?action=getFiles"+keywords+folderVar,
		success: function(returnedValue){
		
		
		response = $.parseJSON(returnedValue);
		if(response.succes){
		
		$('.breadcrumb').html(generateBreadCrumb(response.currentFolder));
		
		t= response.status;

		for(i=0;i<t.length;i++){
   			addFile(t[i]);
		}

		$('.imageHolder,.imageHolder .addOptions li,.lien,.folderHolder').poshytip({
		className: 'tooltip',
		showTimeout: 0,
		alignTo: 'target',
		alignX: 'center',
		offsetY: 10,
		allowTipHover: false
		});
		
		
		$('.fileOption').click(function(){getFileOption(this);});

		$(".imageHolder").draggable({ revert: "invalid" });
		
		
		$( ".folderPreview" ).droppable({
			activeClass: "folderPreviewDroppableHover",
			hoverClass: "folderPreviewDroppableHover",
			drop: function( event, ui ) {
				var parent = $( ui.draggable ).parent();
				var fileUrl = $('.fileUrl',parent).html();
				var fileName = $('.fileName',parent).attr('alt');
				var folder = $('.fileUrl',this).html();
				
				$.ajax({
				  url: "php/action.php?action=moveFile",
				  data:{fileName:fileName,fileUrl:fileUrl,folder:folder},
				  success: function(response){
					var response = $.parseJSON(response);
						parent.fadeOut(300);
						tell(response.status);
				  }
				});

				
			}
		});



   	}
  }
});
	

	}
	

	function addFile(file){
			if(imageExtension[file.extension]!=null){
				ext = imageExtensionRoot+imageExtension[file.extension] ;
			}else{
				ext = imageExtensionRoot+'unknown.png';
			}
		
			if(file.type=='folder')ext = imageExtensionRoot+'folder-page.png';
			if(file.name=='..')ext = imageExtensionRoot+'folder-parent.png';



		


					if(file.type=='file'){

	var tpl = '<div  class="preview filePreview" >'+
						'<div class="fileUrl">'+stripslashes(file.url)+'</div>'+
						'<span title="'+stripslashes(file.toolTipName)+'" class="imageHolder'+(file.published?' filePublished':'')+'"><div onclick="deleteFile(this)" class="deleteFile">x</div>'+
							
							'<div onclick="focusFile(this)"  ondblclick="openFile(this)">'+

							'<img width="48px" height="48px"  src="'+ext+'"/>'+
							'<ul>'+
								'<li>Taille: '+file.size+'</li>'+
								'<li>Maj : '+file.mtimeDate+'</li>'+
								'<li>Heure: '+file.mtimeHour+'</li>'+
							
							'</ul>'+

							'</div>'+
							'<ul><li class="fileOption">+Options</li></ul>'+

							'<span ondblclick="renameFile(this)\" title="'+stripslashes(file.name)+'" alt="'+stripslashes(file.name)+'"  class="fileName">'+stripslashes(file.shortname)+'</span>'+
						'<div class="addOptions">'+
						'<ul>'+
						'<li onclick="$(\'.directLink\',$(this).parent().parent().parent()).fadeToggle(200).select();" alt="Copier le lien direct" title="Copier le lien direct" class="optionUrl"></li>'+
						//'<li alt="Envoyer par mail" title="Envoyer par mail" class="optionShare"></li>'+
						//'<li alt="Editer la source" title="Editer la source" class="optionEdit"></li>'+
						'<li onclick="zipFile(this)" alt="T&eacute;l&eacute;charger le fichier compressé" title="T&eacute;l&eacute;charger le fichier compressé" class="optionZip"></li>'+
						'<li onclick="'+(file.published?'un':'')+'publishFile(this)" title="Public/Privé" class="optionDropbox"></li>'+
						'</ul><div class="clear"></div></div>'+
						'<textarea type="text" class="directLink">'+stripslashes(file.absoluteUrl)+'</textarea>'+
						'</span>'+
						'</div>'; 


					}else if(file.type=='folder'){
						var tpl = '<div  class="preview folderPreview">'+
						'<div class="fileUrl">'+stripslashes(file.url)+'</div>'+
						'<span title="'+stripslashes(file.name)+'" class="folderHolder ">';
						if(file.name!='..') tpl += '<div onclick="deleteFile(this)" class="deleteFile">x</div>';
							tpl += '<img width="48px" height="48px" onclick="getFiles(null,$(\'.fileUrl\',$(this).parent().parent()).html())" src="'+ext+'"/>'+
							'<span ';
							if(file.name!='..') tpl +='ondblclick="renameFile(this)\"';
							tpl += ' title="'+stripslashes(file.name)+'" alt="'+stripslashes(file.name)+'"  class="fileName">'+stripslashes(file.shortname)+'</span>'+
							'</span></div>'; 
					}




		$('#dropbox').append(tpl);
	}

function zipFile(element){
	var parent = $(element).parent().parent().parent().parent();
	var file =$('.fileUrl',parent).html();

		$.ajax({
  url: "php/action.php?action=zipFile",
  data:{file:file},
  success: function(response){
	var response = $.parseJSON(response);
	if(response.succes==true){
		window.location= './'+response.status;
	}else{
		tell(response.status);
	}
  }
});
}


function publishFile(element){
	var parent = $(element).parent().parent().parent().parent();
	var file =$('.fileUrl',parent).html();
	
		$.ajax({
  url: "php/action.php?action=publishFile",
  data:{file:file},
  success: function(response){
	var response = $.parseJSON(response);
	if(response.succes==true){

		$(element).attr('onclick','unpublishFile(this);');
		$(element).parent().parent().parent().addClass('filePublished');

		tell(response.status);
	}else{
		tell(response.status);
	}
  }
});
}

function unpublishFile(element){
	var parent = $(element).parent().parent().parent().parent();
	var file =$('.fileUrl',parent).html();
	
		$.ajax({
  url: "php/action.php?action=unpublishFile",
  data:{file:file},
  success: function(response){
	var response = $.parseJSON(response);
	if(response.succes==true){
		$(element).parent().parent().parent().removeClass('filePublished');
		$(element).attr('onclick','publishFile(this);');
		tell(response.status);
	}else{
		tell(response.status);
	}
  }
});
}



function addFolder(){

	if(!blockNewFolder){
		blockNewFolder = true;
	$('.newFolder').hide();
	$.ajax({
		async:false,
  url: "php/action.php?action=addFolder",
  data:{name:$('input[name="folderName"]').val()},
  success: function(response){
	var response = $.parseJSON(response);
	if(response.succes==true){
		getFiles(null,'//CURRENT');

	}else{
		tell(response.status,0);
	}
  }
});

	$('.newFolder').fadeIn(300);
	blockNewFolder = false;
	}
}


function deleteUser(message,id){
	if(confirm(message))window.location="php/action.php?action=deleteUser&user="+id;
}



function tell(message,time){
	var fix = false;
	if(time==null)time = 3000;
	if(time==0)fix = true;
	var options = {
	position: 'top-right',
        timeout: time,
        sticky: fix
	};
	TINYPOP.show(message,options);
}




function openFile(element){
	var parent = $(element).parent().parent();
	var file = $('.fileUrl',parent).html();

	window.location='./php/action.php?action=openFile&file='+file;
}

function focusFile(element){
	var parent = $(element).parent().parent();
	$('.imageHolder').css("color","#C9C9C9");
	$('.imageHolder').css("font-weight","normal");
	$('.imageHolder',parent).css("color","#ffffff");
	$('.imageHolder',parent).css("font-weight","bold");
}



function deleteFile(element){

	var parent = $(element).parent().parent();
	var file =$('.fileUrl',parent).html();
	 file = file;
		$.ajax({
  url: "php/action.php?action=deleteFiles",
  data:{file:file},
  success: function(response){
  var response = $.parseJSON(response);
  	tell(response.status);
	if(response.succes)$(element).parent().parent().fadeOut(300);
	
  }
});
	}

	function searchFiles(){
		var keywords = $('input[name="search"]').val();
		keywords = keywords.replace(' ',',');
		keywords = keywords.split(',');
		getFiles(keywords);
	}
	
	function renameFile(element){
		var parent = $(element).parent();
		file = $('.fileUrl',parent.parent()).html();
		value = $('.fileName',parent).attr("title");
		$('.fileName',parent).html('');
		$('.fileName',parent).append('<input type="text" value="'+value+'" class="fileNameArea">');
		pressEnter('.fileNameArea',function(){
		newValue = $('.fileNameArea',parent).val();
		
		if(newValue!=value){
		
		$.ajax({
  url: "php/action.php?action=renameFile",
  data:{file:file,newName:newValue},
  success: function(response){
  var response = $.parseJSON(response);
	if(!response.succes){
		tell(response.status);
		$('.fileName',parent).html(value);
	}else{
		$('.fileName',parent).html(newValue);
		$('.fileName',parent).attr("title",newValue);
		$('.fileName',parent).attr("alt",newValue);
		$('.fileUrl',parent.parent()).html($('.fileUrl',parent.parent()).html().replace(value,newValue));
		
		parent.poshytip('destroy');
		parent.attr("title",newValue);
		parent.poshytip({

			content:newValue,
		className: 'tooltip',
		showTimeout: 0,
		alignTo: 'target',
		alignX: 'center',
		offsetY: 10,
		allowTipHover: false


		});



	}
	
  }
});
	
	}else{
		$('.fileName',parent).html(value);
	}
		});
	}
	
	function checkVersion(){
	if(typeof(lastVersion) != 'undefined' && typeof(lastVersionNumber) != 'undefined' && typeof(lastVersionName) != 'undefined' && typeof(lastVersionUrl) != 'undefined'){
		   $.ajax({
		  url: "php/action.php?action=checkVersion",
		  success: function(response){
		  if(response<lastVersionNumber || (typeof(specialMessage)!= 'undefined' && specialMessage!='')){
		  
		  var status= 'La nouvelle version '+lastVersion+' ('+lastVersionName+' - N&deg; '+lastVersionNumber+') de DropCenter est <a target="_blank" href="'+lastVersionUrl+'">disponible ici.</a>'
			
			if(typeof(specialMessage)!= 'undefined' && specialMessage!='') status = specialMessage;
			
			$('#versionBloc').html(status);
			$('#versionBloc').fadeIn(300);
			}}});
	}
}
	
	
function pressEnter(input,func){
var  testTextBox = $(input);
        var code =null;
        testTextBox.keypress(function(e)
        {
            code= (e.keyCode ? e.keyCode : e.which);
            if (code == 13) func();
           // e.preventDefault();
        });

}  

function getFileOption(elem){
	$(".addOptions",$(elem).parent().parent()).slideToggle(200);
}

function explode(delimiter,string,limit){var emptyArray={0:''};if(arguments.length<2||typeof arguments[0]=='undefined'||typeof arguments[1]=='undefined'){return null;}
if(delimiter===''||delimiter===false||delimiter===null){return false;}
if(typeof delimiter=='function'||typeof delimiter=='object'||typeof string=='function'||typeof string=='object'){return emptyArray;}
if(delimiter===true){delimiter='1';}
if(!limit){return string.toString().split(delimiter.toString());}
var splitted=string.toString().split(delimiter.toString());var partA=splitted.splice(0,limit-1);var partB=splitted.join(delimiter.toString());partA.push(partB);return partA;}
function str_replace(search,replace,subject,count){var i=0,j=0,temp='',repl='',sl=0,fl=0,f=[].concat(search),r=[].concat(replace),s=subject,ra=Object.prototype.toString.call(r)==='[object Array]',sa=Object.prototype.toString.call(s)==='[object Array]';s=[].concat(s);if(count){this.window[count]=0;}
for(i=0,sl=s.length;i<sl;i++){if(s[i]===''){continue;}
for(j=0,fl=f.length;j<fl;j++){temp=s[i]+'';repl=ra?(r[j]!==undefined?r[j]:''):r[0];s[i]=(temp).split(f[j]).join(repl);if(count&&s[i]!==temp){this.window[count]+=(temp.length-s[i].length)/f[j].length;}}}
return sa?s:s[0];}
function addslashes(str){return(str+'').replace(/[\\"']/g,'\\$&').replace(/\u0000/g,'\\0');}
function stripslashes(str){return(str+'').replace(/\\(.?)/g,function(s,n1){switch(n1){case'\\':return'\\';case'0':return'\u0000';case'':return'';default:return n1;}});}



function array2json(arr) {
    var parts = [];
    var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

    for(var key in arr) {
    	var value = arr[key];
        if(typeof value == "object") { //Custom handling for arrays
            if(is_list) parts.push(array2json(value)); /* :RECURSION: */
            else parts[key] = array2json(value); /* :RECURSION: */
        } else {
            var str = "";
            if(!is_list) str = '"' + key + '":';

            //Custom handling for multiple data types
            if(typeof value == "number") str += value; //Numbers
            else if(value === false) str += 'false'; //The booleans
            else if(value === true) str += 'true';
            else str += '"' + value + '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

            parts.push(str);
        }
    }
    var json = parts.join(",");
    
    if(is_list) return '[' + json + ']';//Return numerical JSON
    return '{' + json + '}';//Return associative JSON
}