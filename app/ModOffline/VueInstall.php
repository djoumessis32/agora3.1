<script src="app/js/jstz.min.js"></script>
<script type="text/javascript">
////	Init la page
$(function(){
	//Init le timezone avec "jstz"
	var curTimezone=$("[data-tzName='"+jstz.determine().name()+"']").val();
	$("[name='timezone']").val(curTimezone);
});

////	CONTROLE LE FORM
function formControl()
{
	////	Vérifie que tous les champs sont remplis (sauf password, qui peut être vide)
	emptyFields=true;
	$("input,select,textarea").not("[name='db_password']").each(function(){
		if($(this).isEmpty()){
			$(this).css("box-shadow","2px 2px 6px #955");
			if(emptyFields==true)  {$(this).focus();}
			emptyFields=false;
		}
	});
	if(emptyFields==false)	{displayNotif("<?= Txt::trad("remplir_tous_champs") ?>");  return false;}
	////	Vérifie la connexion à Mysql
	var ajaxUrl="?ctrl=offline&action=InstallVerifMysql&db_host="+$("[name='db_host']").val()+"&db_login="+$("[name='db_login']").val()+"&db_password="+$("[name='db_password']").val()+"&db_name="+$("[name='db_name']").val();
	var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Attend la réponse Ajax pour passer à la suite (async:false)
	if(find("errorConnectSGBD",ajaxResult))					{displayNotif("<?= Txt::trad("INSTALL_errorConnectSGBD"); ?>");  return false;}
	else if(find("errorConnectIdentification",ajaxResult))	{displayNotif("<?= Txt::trad("INSTALL_errorConnectIdentification"); ?>");  return false;}
	else if(find("errorAppliInstalled",ajaxResult))			{displayNotif("<?= Txt::trad("INSTALL_errorAppliInstalled"); ?>");  return false;}
	else if(find("errorConnectDb",ajaxResult) && confirm("<?= Txt::trad("INSTALL_errorConnectDbConfirmInstall"); ?>")==false)	{return false;}
	////	Controle le mail &  password
	if(isMail($("[name='adminMail']").val())==false)	{displayNotif("<?= Txt::trad("mail_pas_valide"); ?>");  return false;}
	if($("[name='adminPassword']").val()!=$("[name='adminPasswordVerif']").val())	{displayNotif("<?= Txt::trad("passwordVerifError"); ?>");  return false;}
	////	Confirme l'install
	if(!confirm("<?= Txt::trad("INSTALL_confirm_install") ?>"))  {return false;}
}
</script>

<style>
.pageCenter			{padding-top:20px; padding-bottom:30px;}
.pageCenterContent	{width:600px; padding:10px; margin-top:50px;}
form				{margin-top:40px;}
.vHeader			{margin-bottom:30px;}
[src*='logo.png']	{float:right; max-height:50px;}
h3					{margin-top:20px; font-style:italic;}
#spaceDiskLimit		{width:40px;}
.vSubmitButton		{text-align:center; margin-top:30px;}
</style>

<div class="pageCenter">
	<!--CONTROLE L'ACCESS AU DOSSIER DATAS-->
	<?php if(!is_writable(PATH_DATAS)){ ?>
		<h3><img src="app/img/important.png"> <?= Txt::trad("MSG_NOTIF_chmod_DATAS") ?></h3>
	<!--FORMULAIRE D'INSTALL-->
	<?php }else{ ?>
	<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data" class="pageCenterContent sBlock">
		<!--HEADER-->
		<div class="vHeader"><img src="app/img/offline/install.png"><img src="app/img/logoMainW.png"><img src="app/img/logo.png"></div>
		<!--LANGUE-->
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("USER_langs") ?></div><div class="fieldValue"><?= Txt::menuTrad("install",Req::getParam("tradInstall")) ?></div></div>
		<!--CONFIG DB-->
		<h3><?= Txt::trad("INSTALL_connexion_bdd") ?></h3>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("INSTALL_db_host") ?></div><div class="fieldValue"><input type="text" name="db_host"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("INSTALL_db_name") ?></div><div class="fieldValue"><input type="text" name="db_name"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("INSTALL_db_login") ?></div><div class="fieldValue"><input type="text" name="db_login"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("password") ?></div><div class="fieldValue"><input type="password" name="db_password"></div></div>
		<!--ADMIN GENERAL DE L'ESPACE-->
		<h3><?= Txt::trad("INSTALL_config_admin") ?></h3>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("name") ?></div><div class="fieldValue"><input type="text" name="adminName"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("firstName") ?></div><div class="fieldValue"><input type="text" name="adminFirstName"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("login2") ?></div><div class="fieldValue"><input type="text" name="adminLogin"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("password") ?></div><div class="fieldValue"><input type="password" name="adminPassword"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("passwordVerif") ?></div><div class="fieldValue"><input type="password" name="adminPasswordVerif"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("mail") ?></div><div class="fieldValue"><input type="text" name="adminMail"></div></div>
		<!--PARAMETRAGE GENERAL DE L'ESPACE-->
		<h3><?= Txt::trad("AGORA_description_module") ?></h3>
		<div class="objField">
			<div class="fieldLabel"><?= Txt::trad("AGORA_timezone") ?></div>
			<div class="fieldValue">
				<select name="timezone">
					<?php foreach(Tool::$tabTimezones as $tzName=>$timezone)  {echo "<option value=\"".$timezone."\" data-tzName='".$tzName."'>[GMT ".($timezone>0?"+":"").$timezone."] ".$tzName."</option>";}?>
				</select>
			</div>
		</div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("AGORA_limite_espace_disque") ?></div><div class="fieldValue"><input type="text" name="spaceDiskLimit" value="10" id="spaceDiskLimit"> <?= Txt::trad("giga_octet") ?></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("AGORA_spaceName") ?></div><div class="fieldValue"><input type="text" name="spaceName"></div></div>
		<div class="objField"><div class="fieldLabel"><?= Txt::trad("description") ?></div><div class="fieldValue"><textarea name="spaceDescription"></textarea></div></div>
		<div class="objField">
			<div class="fieldLabel"><?= Txt::trad("SPACE_espace_public") ?></div>
			<select name="spacePublic">
				<option value="0"><?= Txt::trad("non") ?></option>
				<option value="1"><?= Txt::trad("oui") ?></option>
			</select>
		</div>
		<!--VALIDATION-->
		<?= Txt::formValidate() ?>
	</form>
	<?php } ?>
</div>