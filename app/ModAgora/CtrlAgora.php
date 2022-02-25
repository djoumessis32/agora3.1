<?php
/*
 * Controleur du module de config de l'Agora
 */
class CtrlAgora extends Ctrl
{
	const moduleName="agora";

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		//Init
		static::$isMainPage=true;
		if(Ctrl::$curUser->isAdminGeneral()==false)  {self::noAccessExit();}
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			////	Update le parametrage
			Db::query("UPDATE ap_agora SET name=".Db::formatParam("name").", description=".Db::formatParam("description").", footerHtml=".Db::formatParam("footerHtml","editor").", skin=".Db::formatParam("skin").", wallpaper=".Db::formatParam("wallpaper").", logoUrl=".Db::formatParam("logoUrl").", moduleLabelDisplay=".Db::formatParam("moduleLabelDisplay").", lang=".Db::formatParam("lang").", timezone=".Db::formatParam("timezone").", logsTimeOut=".Db::formatParam("logsTimeOut").", personsSort=".Db::formatParam("personsSort").", messengerDisabled=".Db::formatParam("messengerDisabled").", personalCalendarsDisabled=".Db::formatParam("personalCalendarsDisabled").", ldap_server=".Db::formatParam("ldap_server").", ldap_server_port=".Db::formatParam("ldap_server_port").", ldap_admin_login=".Db::formatParam("ldap_admin_login").", ldap_admin_pass=".Db::formatParam("ldap_admin_pass").", ldap_base_dn=".Db::formatParam("ldap_base_dn").", ldap_crea_auto_users=".Db::formatParam("ldap_crea_auto_users").", ldap_pass_cryptage=".Db::formatParam("ldap_pass_cryptage"));
			////	Ajoute un Wallpaper
			if(isset($_FILES["wallpaperFile"]) && File::controlType("imageResize",$_FILES["wallpaperFile"]["name"]))
			{
				$wallpaperName=Txt::clean($_FILES["wallpaperFile"]["name"]);
				$wallpaperName=str_replace(".".File::extension($wallpaperName), ".thumb.jpg", $wallpaperName);
				$wallpaperPath=PATH_WALLPAPER_CUSTOM.$wallpaperName;
				move_uploaded_file($_FILES["wallpaperFile"]["tmp_name"], $wallpaperPath);
				if($_FILES["wallpaperFile"]["size"]>409600)  {File::imageResize($wallpaperPath,$wallpaperPath,2000);}//optimise si + de 400ko
				Db::query("UPDATE ap_agora SET wallpaper=".Db::format($wallpaperName));
			}
			////	Logo du footer
			//Logo par défaut / Change de logo : réinitialise et efface l'ancien logo
			if(!strlen(Req::getParam("logo")) || Req::getParam("logo")=="modify"){
				Db::query("UPDATE ap_agora SET logo=NULL");
				if(is_file(PATH_DATAS.Ctrl::$agora->logo))	{File::rm(PATH_DATAS.Ctrl::$agora->logo);}
			}
			//Enregistre le nouveau logo
			if(isset($_FILES["logoFile"]) && File::controlType("imageResize",$_FILES["logoFile"]["name"]))
			{
				$logoFileName="logo_thumb.".File::extension($_FILES["logoFile"]["name"]);
				move_uploaded_file($_FILES["logoFile"]["tmp_name"], PATH_DATAS.$logoFileName);
				File::imageResize(PATH_DATAS.$logoFileName, PATH_DATAS.$logoFileName, 200, 80);
				Db::query("UPDATE ap_agora SET logo=".Db::format($logoFileName));
			}
			////	Test de connexion LDAP
			if(strlen(Req::getParam("ldap_server")))	{MdlPerson::ldapConnect(Req::getParam("ldap_server"),Req::getParam("ldap_server_port"),Req::getParam("ldap_admin_login"),Req::getParam("ldap_admin_pass"),true);}
			//Modifie l'espace disque
			if(!defined("HOST_DOMAINE") && Req::getParam("limite_espace_disque")>0){
				$limite_espace_disque=File::getBytesSize(Req::getParam("limite_espace_disque")."G");//exprimé en Go
				File::updateConfigFile(array("limite_espace_disque"=>$limite_espace_disque));
			}
			//Relance la page
			self::redir("?ctrl=".Req::$curCtrl);
		}
		//Supprime un wallpaper?
		if(Req::isParam("deleteCustomWallpaper")){
			$wallpaperPath=PATH_WALLPAPER_CUSTOM.Req::getParam("deleteCustomWallpaper");
			File::rm($wallpaperPath);
		}
		//Affiche la page
		$vDatas["logsTimeOut"]=array(0,15,30,60,12,360);
		$vDatas["alertMessageBigSav"]=(File::datasFolderSize()>(File::sizeMo*100))  ?  "onClick=\"alert('".addslashes(Txt::trad("AGORA_sav_alert"))."')\""  :  null;
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * RECUPERE UNE SAUVEGARDE
	 */
	public static function actionGetBackup()
	{
		//Init
		if(Ctrl::$curUser->isAdminGeneral()==false)  {self::noAccessExit();}
		$dumpPath=Db::getDump();//Dump de la bdd!
		////	Sauvegarde de tout///!!!TESTER SUR DEBIAN->CREE UN ".TAR" QUI NE PLAIT PAS A CERTAINS NAVIGATEURS..
		if(Req::getParam("typeBackup")=="all")
		{
			File::archiveSizeControl(File::datasFolderSize(true));//Controle la taille
			ini_set("max_execution_time","600");//10mn max
			$archiveName="BackupAgora_".strftime("%Y-%m-%d");
			////	Sauvegarde via le shell
			if(Tool::linuxEnv() && function_exists("shell_exec"))
			{
				$archiveTmpPath=sys_get_temp_dir()."/".uniqid(mt_rand()).".zip";
				shell_exec("cd ".PATH_DATAS."; tar -cf ".$archiveTmpPath." *");//-c=creation -f=nom du dossier source
				if(is_file($archiveTmpPath)){
					File::download($archiveName.".tar", $archiveTmpPath);
					File::rm($archiveTmpPath);
					$isArchive=true;
				}
			}
			////	Sauvegarde en php?
			if(empty($isArchive))	{File::downloadArchive(self::pathDatasFilesList(), $archiveName.".zip");}
		}
		////	Sauvegarde uniquement la Bdd
		else{
			$filesList=[ ["realPath"=>$dumpPath, "zipPath"=>str_replace(PATH_DATAS,null,$dumpPath)] ];
			File::downloadArchive($filesList, "BackupAgoraBdd_".strftime("%Y-%m-%d").".zip");
		}
	}

	/*
	 * ARBORESCENCE DU PATH_DATAS (avec "realPath" / "zipPath" / "emptyFolderZipPath". Fonction recursive!)
	 */
	public static function pathDatasFilesList($tmpPath=null)
	{
		//Init
		$filesList=[];
		if($tmpPath==null)	{$tmpPath=PATH_DATAS;}
		$tmpPath=rtrim($tmpPath,"/");//"trim" la fin du path
		//Liste les fichiers du path courant
		foreach(scandir($tmpPath) as $tmpFileName)
		{
			$tmpFileRealPath=$tmpPath."/".$tmpFileName;
			$tmpFileZipPath=str_replace(PATH_DATAS,null,$tmpFileRealPath);
			//Ajoute un fichier/dossier
			if(is_file($tmpFileRealPath))	{$filesList[]=["realPath"=>$tmpFileRealPath, "zipPath"=>$tmpFileZipPath];}
			elseif($tmpFileName!='.' && $tmpFileName!='..' && is_dir($tmpFileRealPath)){
				$filesList[]=["emptyFolderZipPath"=>$tmpFileZipPath];
				$filesList=array_merge($filesList,self::pathDatasFilesList($tmpFileRealPath));//lancement récursif
			}
		}
		// Retourne le résultat final
		return $filesList;
	}
}