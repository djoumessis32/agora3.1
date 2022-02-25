<script>
$(function(){	
	////	Logo du footer
	$("select[name='logo']").change(function(){
		$("[name='logoFile'],[name='logoUrl']").hide();
		if(this.value=="modify")	{$("[name='logoFile'],[name='logoUrl']").show();}
		else if(this.value!="")		{$("[name='logoUrl']").show();}
	});

	////	Vérif le type du fichier
	$("input[name='wallpaperFile'],input[name='logoFile']").change(function(){
		if(!find(".jpg",this.value) && !find(".jpeg",this.value) && !find(".png",this.value))
			{displayNotif("<?= Txt::trad("AGORA_erreur_wallpaper_logo") ?>");}
	});
});

////	Configuration LDAP
function displayLdapConfig()
{
	if($("#ldapConfig").css("display")=="none")		{$("#ldapConfig").fadeIn();}
	else{
		if(confirm("<?= Txt::trad("ldap_effacer_params") ?>")){
			$("#ldapConfig input,#ldapConfig select").each(function(){ $(this).val(""); });
		}
		$("#ldapConfig").fadeOut();
	}
}

////    On contrôle le formulaire
function formControl()
{
	// Contrôle du nom
	if($("[name='name']").isEmpty() || ($("[name='limite_espace_disque']").exist() && $("[name='limite_espace_disque']").isEmpty()))
		{displayNotif("<?= Txt::trad("remplir_tous_champs") ?>");  return false; }
	if($("[name='limite_espace_disque']").exist() && isNaN($("[name='limite_espace_disque']").val()))	{displayNotif("<?= Txt::trad("AGORA_espace_disque_invalide") ?>"); return false; }
	if(!confirm("<?= Txt::trad("AGORA_confirmez_modification_site") ?>"))	{return false;}
}
</script>

<style>
.vBackupForm				{text-align:center;}
.vBackupForm img			{max-height:20px;}
.vBackupForm  button		{margin:5px 10px 5px 10px; width:400px; font-size:95%;}
.vAgoraForm					{padding:10px;}
hr, .hrGradient				{margin:20px;}
img[src*='separator']		{margin:0px 5px 0px 5px;}
form .objField				{margin-bottom:15px;}/*surcharge*/
form .objField .fieldLabel	{width:35%; min-width:120px;}/*surcharge*/
input[name='logoFile']		{display:none;}
input[name='logoUrl']		{margin-top:10px; <?= (empty(Ctrl::$agora->logo)) ? "display:none;":null ?>}
#imgLogo					{max-height:45px;}
#limite_espace_disque		{width:40px;}
#ldapConfig					{margin-left:50px; margin-top:10px; <?= (empty(Ctrl::$agora->ldap_server)) ? "display:none;":null ?>}
#ldapConfig .objField		{margin:7px;}
#ldapConfig .fieldValue input	{width:50%;}
</style>


<div class="pageCenter">
	<div class="pageCenterContent">
		<form class="sBlock vBackupForm" action="index.php" method="post">
			<input type="hidden" name="ctrl" value="agora">
			<input type="hidden" name="action" value="getBackup">
			<button type="submit" name="typeBackup" value="all" <?= $alertMessageBigSav ?>><img src="app/img/download.png"> <?= Txt::trad("AGORA_sav") ?><img src="app/img/disk.png"><img src="app/img/folderSmall.png"></button>
			<button type="submit" name="typeBackup" value="db"><img src="app/img/download.png"> <?= Txt::trad("AGORA_sav_bdd") ?><img src="app/img/disk.png"></button>
		</form>
		<br>
		<form class="sBlock vAgoraForm" action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">
			<!--INFOS PRINCIPALES-->
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_versions") ?></div>
				<div class="fieldValue">
					Agora-Project <?= Ctrl::$agora->version_agora ?> - <?= Txt::trad("AGORA_version_agora_maj")." : ".Txt::displayDate(Ctrl::$agora->dateUpdateDb,"dateMini") ?>
					<img src="app/img/separator.png"> PHP <?= str_replace(strstr(phpversion(),"+deb"),null,phpversion()); ?>
					<img src="app/img/separator.png"> MySQL <?= str_replace(strstr(Db::dbVersion(),"+deb"),null,Db::dbVersion()); ?>
					<?php if(!function_exists("mail")){ ?><br><br><span title="<?= Txt::trad("AGORA_fonction_mail_infos") ?>"><img src="app/img/delete.png"> &nbsp; <?= Txt::trad("AGORA_fonction_mail_desactive") ?></span><?php } ?>
					<?php if(!function_exists("imagecreatetruecolor")){ ?><br><br><span><img src="app/img/delete.png"> &nbsp; <?= Txt::trad("AGORA_fonction_image_desactive") ?></span><?php } ?>
				</div>
			</div>
			<hr class="hrGradient">
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_name") ?></div>
				<div class="fieldValue"><input type="text" name="name" value="<?= Ctrl::$agora->name ?>"></div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("description") ?></div>
				<div class="fieldValue"><input type="text" name="description" value="<?= Ctrl::$agora->description ?>"></div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><abbr title="<?= Txt::trad("AGORA_footerHtml_info") ?>"><?= Txt::trad("AGORA_footerHtml") ?></abbr></div>
				<div class="fieldValue"><textarea name="footerHtml"><?= Ctrl::$agora->footerHtml ?></textarea></div>
			</div>
			<!--INTERFACE DE L'ESPACE-->
			<hr>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_skin") ?></div>
				<div class="fieldValue">
					<select name="skin">
						<option value="white"><?= Txt::trad("AGORA_blanc") ?></option>
						<option value="black" <?= Ctrl::$agora->skin=="black"?"selected":null ?>><?= Txt::trad("AGORA_noir") ?></option>
					</select>
				</div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("wallpaper") ?></div>
				<div class="fieldValue"><?= CtrlMisc::menuWallpaper(Ctrl::$agora->wallpaper) ?></div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_logo_footer") ?></div>
				<div class="fieldValue">
					<img src="<?= CtrlMisc::pathfooterLogo() ?>" id="imgLogo">
					<select name="logo">
						<?php if(!empty(Ctrl::$agora->logo)){ ?><option value="<?= Ctrl::$agora->logo ?>"><?= Txt::trad("garder") ?></option><?php } ?>
						<option value=""><?= Txt::trad("par_defaut") ?></option>
						<option value="modify"><?= Txt::trad("modifier") ?></option>
					</select>
					<input type="file" name="logoFile">
					<input type="text" name="logoUrl" value="<?= Ctrl::$agora->logoUrl ?>" placeholder="<?= Txt::trad("AGORA_logo_footer_url") ?>">
				</div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_moduleLabelDisplay") ?></div>
				<div class="fieldValue">
					<select name="moduleLabelDisplay">
						<option value=""><?= Txt::trad("AGORA_moduleLabelDisplay_masquer") ?></option>
						<option value="icones" <?= Ctrl::$agora->moduleLabelDisplay=="icones"?"selected":null ?>><?= Txt::trad("AGORA_moduleLabelDisplay_icones") ?></option>
						<option value="page" <?= Ctrl::$agora->moduleLabelDisplay=="page"?"selected":null ?>><?= Txt::trad("AGORA_moduleLabelDisplay_page") ?></option>
					</select>
				</div>
			</div>
			<!--DETAILS DE PARAMETRAGE-->
			<hr>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_lang") ?></div>
				<div class="fieldValue"><?= Txt::menuTrad("agora",Ctrl::$agora->lang) ?></div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_timezone") ?></div>
				<div class="fieldValue">
					<select name="timezone">
						<?php foreach(Tool::$tabTimezones as $tmpLabel=>$timezone)  {echo "<option value=\"".$timezone."\" ".($timezone==Tool::$tabTimezones[Ctrl::$curTimezone]?'selected':null).">[gmt ".($timezone>0?"+":"").$timezone."] ".$tmpLabel."</option>";}?>
					</select>
				</div>
			</div>
			<?php if(!defined("HOST_DOMAINE")){ ?>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_limite_espace_disque") ?></div>
				<div class="fieldValue"><input type="text" name="limite_espace_disque" id="limite_espace_disque" value="<?= round((limite_espace_disque/File::sizeGo),2) ?>"> <?= Txt::trad("giga_octet")?></div>
			</div>
			<?php } ?>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_logsTimeOut") ?></div>
				<div class="fieldValue">
					<select name="logsTimeOut">
						<?php foreach($logsTimeOut as $tmpTime)  {echo "<option value='".$tmpTime."' ".($tmpTime==Ctrl::$agora->logsTimeOut?"selected":null).">".$tmpTime."</option>";} ?>
					</select>
					<?= Txt::trad("jours") ?>
				</div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_personsSort") ?></div>
				<div class="fieldValue">
					<select name="personsSort">
						<option value="firstName"><?= Txt::trad("firstName") ?></option>
						<option value="name" <?= Ctrl::$agora->personsSort=="name"?"selected":null ?>><?= Txt::trad("name") ?></option>
					</select>
				</div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><?= Txt::trad("AGORA_messengerDisabled") ?></div>
				<div class="fieldValue">
					<select name="messengerDisabled">
						<option value=""><?= Txt::trad("oui") ?></option>
						<option value="1" <?= !empty(Ctrl::$agora->messengerDisabled)?"selected":null ?>><?= Txt::trad("non") ?></option>
					</select>
				</div>
			</div>
			<div class="objField">
				<div class="fieldLabel"><abbr title="<?= Txt::trad("AGORA_personalCalendarsDisabled_infos") ?>"><?= Txt::trad("AGORA_personalCalendarsDisabled") ?></abbr></div>
				<div class="fieldValue">
					<select name="personalCalendarsDisabled">
						<option value=""><?= Txt::trad("oui") ?></option>
						<option value="1" <?= !empty(Ctrl::$agora->personalCalendarsDisabled)?"selected":null ?>><?= Txt::trad("non") ?></option>
					</select>
				</div>
			</div>
			<div>
				<div class="sLink" onclick="displayLdapConfig();"><?= Txt::trad("ldap_connexion_serveur") ?> <img src="app/img/plusSmall.png"></div>
				<!--Module de connexion LDAP désactivé?-->
				<?php if(!function_exists("ldap_connect")){ ?>
					<div class="labelInfos"><?= Txt::trad("ldap_pas_module_php") ?></div>
				<?php }else{ ?>
				<!--Parametrage LDAP-->
				<div class="objField" id="ldapConfig">
					<!--CONFIG LDAP-->
					<div class="objField">
						<div class="fieldLabel"><?= Txt::trad("ldap_server") ?></div>
						<div class="fieldValue"><input type="text" name="ldap_server" value="<?= Ctrl::$agora->ldap_server ?>"></div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><abbr title="<?= Txt::trad("ldap_server_port_infos") ?>"><?= Txt::trad("ldap_server_port") ?></abbr></div>
						<div class="fieldValue"><input type="text" name="ldap_server_port" value="<?= Ctrl::$agora->ldap_server_port ?>"></div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><abbr title="<?= Txt::trad("ldap_admin_login_infos") ?>"><?= Txt::trad("ldap_admin_login") ?></abbr></div>
						<div class="fieldValue"><input type="text" name="ldap_admin_login" value="<?= Ctrl::$agora->ldap_admin_login ?>"></div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><?= Txt::trad("ldap_admin_pass") ?></div>
						<div class="fieldValue"><input type="text" name="ldap_admin_pass" value="<?= Ctrl::$agora->ldap_admin_pass ?>"></div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><abbr title="<?= Txt::trad("ldap_base_dn_infos") ?>"><?= Txt::trad("ldap_base_dn") ?></abbr></div>
						<div class="fieldValue"><input type="text" name="ldap_base_dn" value="<?= Ctrl::$agora->ldap_base_dn ?>"></div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><abbr title="<?= Txt::trad("ldap_crea_auto_users_infos") ?>"><?= Txt::trad("ldap_crea_auto_users") ?></abbr></div>
						<div class="fieldValue">
							<select name="ldap_crea_auto_users">
								<option value="1"><?= Txt::trad("oui") ?></option>
								<option value="" <?= empty(Ctrl::$agora->ldap_crea_auto_users)?"selected":null ?>><?= Txt::trad("non") ?></option>
							</select>
						</div>
					</div>
					<div class="objField">
						<div class="fieldLabel"><img src="app/img/arrowRight.png"><?= Txt::trad("ldap_pass_cryptage") ?></div>
							<select name="ldap_pass_cryptage">
								<option value="1"><?= Txt::trad("aucun") ?></option>
								<option value="sha" <?= Ctrl::$agora->ldap_pass_cryptage=="sha"?"selected":null ?>>SHA</option>
								<option value="md5" <?= Ctrl::$agora->ldap_pass_cryptage=="md5"?"selected":null ?>>Md5</option>
							</select>
					</div>
				</div>
				<?php } ?>
			</div>
			<!--VALIDATION-->
			<?= Txt::formValidate("modifier") ?>
		</form>
	</div>
</div>