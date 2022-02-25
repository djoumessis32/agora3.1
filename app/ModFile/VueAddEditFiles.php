<!--CHARGE PLUPLOAD -->
<script type="text/javascript" src="app/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="app/js/plupload/i18n/<?= Txt::trad("UPLOADER") ?>.js"></script>
<script type="text/javascript" src="app/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<link rel="stylesheet" href="app/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />


<script type="text/javascript">
////	Resize
lightboxWidth(550);

////	Init
$(function(){
	////	PLUPLOAD
	$("#uploader").plupload({
		url: "?ctrl=file&action=UploadTmpFile&tmpFolderName=<?= $tmpFolderName ?>",
		runtimes: "html5,html4",
		dragdrop: true,
		max_file_size: "<?= (int)ini_get("upload_max_filesize") ?>mb",
		max_file_count:<?= Ctrl::$curUser->isUser()?"200":"5" ?>,
		unique_names : true,
		views:{thumbs:true,list:true,active:"thumbs"},
		init:{
			//Fonction à l'ajout de fichiers
			FilesAdded:function(up,files){
				//Affiche l'option "redimension d'image" OU Affiche les fichiers au format "liste"
				for(i=0; i<files.length; i++){
					var fileExt=extension(files[i].name);
					if(fileExt=="jpg" || fileExt=="jpeg" || fileExt=="png")	{$(".imageResize").fadeIn();}
					else													{$("label[for$='_view_list']").trigger("click");}
				}
				//Masque le "drop text" & resize le fancybox
				$(".plupload_droptext").css("display","none");
				parent.$.fancybox.update();
			}
		}
	});
	//Bouton "selectionner les fichiers" de Plupload : Ajoute la taille Max des fichiers
	$(".plupload_add").attr("title","<?= File::displaySize(File::uploadMaxFilesize()) ?> Max. <?= Txt::trad("FILE_ajout_multiple_info") ?>");
	
	////	Nouvelle version de fichier : affiche un message si le nom du fichier est différent
	$("[name='addVersionFile']").on("change",function(){
		var oldName=$("[name='curFileName']").val();
		var newName=$("[name='addVersionFile']").val().split('\\').pop();
		if(oldName!=newName)	{$("#notifDifferentName").fadeIn();}
	});
});

////	Contrôle du formulaire
function formControl()
{
	//Nouvelle version de fichier
	if($("[name='addVersion']").exist())
	{
		//Fichier sélectionné
		if($("[name='addVersionFile']").isEmpty())	{displayNotif("<?= Txt::trad("FILE_selectionner_fichier"); ?>");  return false;}
		//Controle final (champs obligatoires, etc)}
		return finalFormControl();
	}
	//Ajout de fichier : controle Plupload
	else if($("#uploader").exist())
	{
		//Selectionner au moins un fichier
		if($("#uploader").plupload("getFiles").length==0)	{displayNotif("<?= Txt::trad("FILE_selectionner_fichier") ?>");  return false;}
		//Si le controle global est OK : lance l'upload.. qui validera ensuite le formulaire
		if(finalFormControl()){
			$("#uploader").on("complete",function(){ $("#filesForm")[0].submit(); });
			$("#uploader").plupload("start");
		}
		//C'est l'uploader qui valide le formulaire (mettre en dernier)
		return false;
	}
}
</script>


<style>
#notifDifferentName			{display:none;}
.uploadOptions				{margin-top:8px; text-align:right;}
textarea[name='description']{display:none;}
.imageResize				{display:none;}
/*Modifs de Plupload (masque les titres et le bouton de lancement de l'upload)*/
.plupload_logo,.plupload_header_title,.plupload_header_text,.plupload_start	{display:none;}
.plupload_header_content	{height:30px;}
.plupload_view_list .plupload_content,.plupload_view_thumbs .plupload_content	{top:30px;}	/*cf. "plupload_header_content" à 30px, au lieu de 57px*/
.plupload_view_switch		{position:absolute; top:2px; right:10px;}						/*IDEM*/
.plupload_droptext 			{color:#aaa;}
.plupload_add				{width:260px;}
</style>


<form action="index.php" method="post" onsubmit="return formControl()" id="filesForm" enctype="multipart/form-data">

	<?php if(Req::isParam("addVersion")){ ?>
		<!--AJOUT DE VERSION DE FICHIER-->
		<input type="hidden" name="addVersion" value="true">
		<input type="hidden" name="curFileName" value="<?= $curObj->name ?>">
		<div class="labelInfos" id="notifDifferentName"><?= Txt::trad("FILE_updatedName") ?></div>
		<input type="file" name="addVersionFile" title="<?= File::displaySize(File::uploadMaxFilesize()) ?> Max">
	<?php }else{ ?>
		<!--AJOUT DE FICHIERS VIA PLUPLOAD (input "file" remplacé par plupload)-->
		<div id="uploader"><input type="file" name="newFile" title="<?= File::displaySize(File::uploadMaxFilesize()) ?> Max"></div>
		<input type="hidden" name="tmpFolderName" value="<?= $tmpFolderName ?>">
	<?php } ?>

	<!--imageResize-->
	<div class="uploadOptions imageResize">
		<input type="checkbox" name="imageResize" id="imageResizeBox" value="1" checked> 
		<label for="imageResizeBox"><?= Txt::trad("FILE_optimiser_images") ?></label>
		<select name="resizeSize">
			<?php foreach(array("1280","1600","2048") as $sizeMax)  {echo "<option value='".$sizeMax."'>".$sizeMax." ".Txt::trad("pixels")." Max</option>";} ?>
		</select>
	</div>
	<!--description-->
	<div class="uploadOptions">
		<div class="sLink" onclick="$('textarea[name=description]').slideToggle(200);"><?= Txt::trad("description") ?> <img src="app/img/description.png"></div>
		<textarea name="description" placeholder="<?= Txt::trad("description") ?>"></textarea>
	</div>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>