<?php
/*
 * Controleur du module "File"
 */
class CtrlFile extends Ctrl
{
	const moduleName="file";
	public static $folderObjectType="fileFolder";
	public static $moduleOptions=["AdminRootFolderAddContent"];
	public static $MdlObjects=array("MdlFile","MdlFileFolder");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		////	Verif l'accès en écriture & Occupation d'espace disque
		if(Ctrl::$curUser->isAdminGeneral())
		{
			//Verif l'accès en écriture
			if(!is_writable(Ctrl::$curContainer->folderPath("real")))
				{Ctrl::addNotif(Txt::trad("FILE_ajouter_fichier_alert")." (fileFolderId=".Ctrl::$curContainer->_id.")", "warning");}
			//Occupation d'espace disque
			$folderSize=File::folderSize(PATH_MOD_FILE);
			$fillRatePercent=ceil(($folderSize/limite_espace_disque)*100);
			$txtBar=Txt::trad("espace_disque_utilise")." : ".$fillRatePercent."%";
			$txtTooltip=Txt::trad("espace_disque_utilise_mod_fichier")." : ".File::displaySize($folderSize)." ".Txt::trad("de")." ".File::displaySize(limite_espace_disque);
			$vDatas["fillRateLevel"]=ceil($fillRatePercent/33);
			$fillRateLevelHight=($fillRatePercent>70) ? true : false;
			$vDatas["fillRateBar"]=Tool::percentBar($fillRatePercent, $txtBar, $txtTooltip, $fillRateLevelHight);
		}
		////	Dossiers & Fichiers
		$vDatas["foldersList"]=self::$curContainer->foldersList(true);
		$vDatas["filesList"]=Db::getObjTab("file", "SELECT * FROM ap_file WHERE ".MdlFile::sqlDisplayedObjects(self::$curContainer)." ".MdlFile::sqlSort(self::$curContainer));
		foreach($vDatas["filesList"] as $fileKey=>$tmpFile)
		{
			//Type du fichier
			if(File::controlType("imageBrowser",$tmpFile->name))	{$tmpFile->fileType="image";}
			elseif(File::controlType("pdf",$tmpFile->name))			{$tmpFile->fileType="pdf";}
			elseif(File::controlType("mp3",$tmpFile->name))			{$tmpFile->fileType="mp3";}
			elseif(File::controlType("videoPlayer",$tmpFile->name))	{$tmpFile->fileType="video";}
			else													{$tmpFile->fileType="misc";}
			//Lien de l'icone du fichier : lightbox / lecteur / telechargement direct
			if($tmpFile->fileType=="image")									{$tmpFile->iconHref="href=\"".$tmpFile->urlDownloadDisplay("display")."\" rel='lightboxGallery'";}
			elseif($tmpFile->fileType=="pdf" || $tmpFile->fileType=="mp3")	{$tmpFile->iconHref="href=\"javascript:lightboxOpen('".$tmpFile->urlDownloadDisplay("display")."');\" rel='lightboxGallery'";}
			elseif($tmpFile->fileType=="video")								{$tmpFile->iconHref="href=\"javascript:lightboxOpen('".$tmpFile->filePath()."');\" rel='lightboxGallery'";}
			else															{$tmpFile->iconHref="href=\"".$tmpFile->urlDownloadDisplay()."\" target='_blank'";}//telechargement direct
			//Tooltips et description
			$tmpFile->tooltip=Txt::trad("telecharger")."<br>".$tmpFile->name;
			$tmpFile->iconTooltip=$tmpFile->name." - ".File::displaySize($tmpFile->octetSize);
			if(!empty($tmpFile->description))	{$tmpFile->iconTooltip.="<br>".$tmpFile->description;}
			//Definition en pixel d'une image (ajoute au tooltip)  &&  Class "thumbLandscape"/"thumbPortrait" d'une vignette (image/pdf)
			if($tmpFile->hasThumb())
			{
				$imgWidth=$imgHeight=null;
				if($tmpFile->fileType=="image"){
					list($imgWidth,$imgHeight)=getimagesize($tmpFile->filePath());
					$tmpFile->iconTooltip.="<br>".$imgWidth." x ".$imgHeight." ".Txt::trad("pixels");
				}
				$tmpFile->thumbClass=($imgWidth>$imgHeight) ? "hasThumb thumbLandscape" : "hasThumb thumbPortrait";
				if($tmpFile->fileType=="pdf")	{$tmpFile->thumbClass.=" thumbPdf";}
			}
			//Ajoute le fichier
			$vDatas["filesList"][$fileKey]=$tmpFile;
		}
		////	Affiche la vue
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginParams=array_merge($pluginParams,array("MdlObjectFolder"=>"MdlFileFolder"));
		$pluginsList=self::getPluginsFolders($pluginParams);
		foreach(MdlFile::getPluginObjects($pluginParams) as $tmpObj)
		{
			$tmpObj->pluginModule=self::moduleName;
			$tmpObj->pluginIcon=self::moduleName."/icon.png";
			$tmpObj->pluginLabel=$tmpObj->name;
			$tmpObj->pluginTitle=$tmpObj->containerObj()->folderPath("text")."<br>".$tmpObj->displayAutor(true,true);
			$tmpObj->pluginJsIcon="redir('".$tmpObj->getUrl("container")."',true);";
			$tmpObj->pluginJsLabel="redir('".$tmpObj->urlDownloadDisplay("container")."',true);";
			$pluginsList[]=$tmpObj;
		}
		return $pluginsList;
	}

	/*
	 * ACTION : Affichage/Download un fichier
	 */
	public static function actionGetFile()
	{
		if(Req::isParam("targetObjId"))
		{
			$curFile=self::getTargetObj();
			if($curFile->readRight())
			{
				//Affichage/Download du fichier
				if(Req::isParam("display"))    {File::display($curFile->filePath());}
				else{
					Ctrl::addLog("consult2",$curFile);
					$curVersion=$curFile->getVersion(Req::getParam("dateCrea"));
					File::download($curVersion["name"], $curFile->filePath(Req::getParam("dateCrea")));
				}
			}
		}
	}

	/*
	 * ACTION : Download d'une archive zip (dossier / elements sélectionnés)
	 */
	public static function actionDownloadArchive()
	{
		$archiveSize=0;
		$filesList=array();
		////	Ajoute à l'archive les dossiers sélectionnés
		foreach(self::getTargetObjects("fileFolder") as $curFolder)
		{
			$archiveSize+=File::folderSize($curFolder->folderPath("real"));
			$archiveName=$curFolder->containerObj()->name;
			$containerFolderPathZip=$curFolder->containerObj()->folderPath("zip");
			if($curFolder->readRight())
			{
				//Parcourt chaque dossier de l'arborescence & Ajoute chaque fichier
				foreach($curFolder->folderTree() as $tmpFolder)
				{
					$folderPathZip=substr($tmpFolder->folderPath("zip"),strlen($containerFolderPathZip));//On part du chemin du dossier courant de la page ("racine/dossier/sous-dossier" -> "sous-dossier")
					$folderFiles=Db::getObjTab("file","SELECT * FROM ap_file WHERE _idContainer=".$tmpFolder->_id);
					if(empty($folderFiles))  {$filesList[]=array("emptyFolderZipPath"=>$folderPathZip);}
					else{
						foreach($folderFiles as $tmpFile)	{$filesList[]=array("realPath"=>$tmpFile->filePath(),"zipPath"=>$folderPathZip.Txt::clean($tmpFile->name,"download"));}
					}
				}
			}
		}
		////	Ajoute à l'archive les fichiers sélectionnés
		foreach(self::getTargetObjects("file") as $curFile)
		{
			$archiveSize+=$curFile->octetSize;
			$archiveName=$curFile->containerObj()->name;
			if($curFile->readRight()){
				$filesList[]=array("realPath"=>$curFile->filePath(),"zipPath"=>$curFile->name);
				Ctrl::addLog("consult2",$curFile);
			}
		}
		////	Controle la taille de l'archive et l'envoie
		if(!empty($filesList)){
			File::archiveSizeControl($archiveSize);
			File::downloadArchive($filesList,$archiveName.".zip");
		}
	}

	/*
	 * VUE : Modif d'un fichier
	 */
	public static function actionFileEdit()
	{
		////	Charge le fichier
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Enregistre & recharge le fichier + update la dernière version
			$fileName=Req::getParam("name").Req::getParam("dotExtension");
			$curObj=$curObj->createUpdate("name=".Db::format($fileName).", description=".Db::formatParam("description"));
			$lastVersion=$curObj->getVersion();
			Db::Query("UPDATE ap_fileVersion SET name=".Db::format($fileName).", description=".Db::formatParam("description")." WHERE _idFile=".$lastVersion["_idFile"]." AND dateCrea=".Db::format($lastVersion["dateCrea"]));
			//Modif contenu du fichier texte/html
			if(Req::isParam("fileContent") && Req::getParam("fileContent")!=Req::getParam("fileContentOld")){
				$folderPath=$curObj->containerObj()->folderPath("real");
				$newFileRealName=$curObj->_id."_".time().Req::getParam("dotExtension");
				$fp=fopen($folderPath.$newFileRealName, "w");
				fwrite($fp, stripslashes(Req::getParam("fileContent")));//au cas ou "magic_quote_gpc" est activé..
				fclose($fp);
				Db::query("INSERT INTO ap_fileVersion SET _idFile=".$curObj->_id.", name=".Db::formatParam("name").", realName=".Db::format($newFileRealName).", description=".Db::formatParam("description").", octetSize=".(int)filesize($folderPath.$newFileRealName).", dateCrea=".Db::dateNow().", _idUser=".Ctrl::$curUser->_id);
			}
			//Notifie par mail & Ferme la page
			$notifMail=$curObj->name;
			if(!empty($curObj->description))	{$notifMail .= "<br>".$curObj->description;}
			$curObj->sendMailNotif($notifMail);
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		//Fichier directement éditable (text/html) ?
		if(File::controlType("text",$curObj->name) || File::controlType("html",$curObj->name)){
			$vDatas["fileContent"]=implode("",file($curObj->filePath()));
			if(File::controlType("html",$curObj->name))	{$vDatas["initHtmlEditor"]=true;}
		}
		static::displayPage("VueFileEdit.php",$vDatas);
	}

	/*
	 * VUE : Ajout de fichiers
	 */
	public static function actionAddEditFiles()
	{
		////	Charge l'objet && Affiche uniquement les "Notif" du "menuEditValidate();" si nouvelle version du fichier
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		if(Req::isParam("_idContainer")==false)	{$curObj->onlyNotifMenu=true;}
		////	Controle la taille du DATAS ET L'accès physique au dossier
		if(File::datasFolderSize() > limite_espace_disque)					{Ctrl::noAccessExit(Txt::trad("MSG_NOTIF_espace_disque"));}
		elseif(!is_dir($curObj->containerObj()->folderPath("real")))		{Ctrl::noAccessExit(Txt::trad("MSG_NOTIF_acces_fichier_dossier")." : ".$curObj->containerObj()->name);}
		elseif(!is_writable($curObj->containerObj()->folderPath("real")))	{Ctrl::noAccessExit(Txt::trad("MSG_NOTIF_chmod_DATAS"));}
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Init
			$newFiles=$newFilesAdded=$notifFilesLabels=$notifFilesAttachements=array();
			$tmpDatasFolderSize=File::datasFolderSize();
			////	PRÉPARE LES FICHIERS ENVOYÉS AVEC $_FILE ("addVersion" OU plupload désactivé)
			if(!empty($_FILES))
			{
				foreach($_FILES as $fileKey=>$tmpFile){
					if($tmpFile["error"]==1 || $tmpFile["error"]==2)	{Ctrl::addNotif(Txt::trad("FILE_erreur_taille_fichier")." : ".$tmpFile["name"]);}
					elseif($tmpFile["error"]==0){
						$newFiles[]=array("tmpPath"=>$tmpFile["tmp_name"],"name"=>$tmpFile["name"]);
						if(Req::isParam("addVersion") && File::extension($curObj->name)!=File::extension($tmpFile["name"]))//Notif de changement de type de fichier
							{Ctrl::addNotif(Txt::trad("MSG_NOTIF_type_version")." : ".File::extension($tmpFile["name"])." -> ".File::extension($tmpFile["name"]));}
					}
				}
			}
			////	PRÉPARE LE/LES FICHIERS ENVOYÉS AVEC "PLUPLOAD"
			if(Req::isParam("tmpFolderName"))
			{
				$tmpDirPath=sys_get_temp_dir()."/".Req::getParam("tmpFolderName")."/";
				if(is_dir($tmpDirPath)){
					foreach(scandir($tmpDirPath) as $tmpFileName){
						$tmpFilePath=$tmpDirPath.$tmpFileName;
						if(is_file($tmpFilePath))	{$newFiles[]=array("tmpPath"=>$tmpFilePath,"name"=>$tmpFileName);}
					}
				}
			}
			////	AJOUTE LE/LES FICHIERS
			foreach($newFiles as $fileKey=>$tmpFile)
			{
				////	Controles
				//Fichier interdit : affiche une notification et passe au fichier suivant
				if(File::controlType("forbidden",$tmpFile["name"]))   {Ctrl::addNotif(Txt::trad("MSG_NOTIF_type_interdit")." : ".$tmpFile["name"]);  continue;}
				//Pas assez d'espace disque: sort de la boucle.. sinon incrémente l'espace disque total et ajoute le fichier courant à $newFilesAdded
				$fileSize=filesize($tmpFile["tmpPath"]);
				if(($tmpDatasFolderSize+$fileSize) > limite_espace_disque)	{break;}
				else														{$tmpDatasFolderSize+=$fileSize;  $newFilesAdded[]=$tmpFile;}
				//Si un autre fichier existe déjà avec le meme nom : Affiche une notif
				if(Db::getVal("SELECT count(*) FROM ap_file WHERE _idContainer=".(int)$curObj->_idContainer." AND _id!=".$curObj->_id." AND name=".Db::format($tmpFile["name"]))>0)
					{Ctrl::addNotif(Txt::trad("MSG_NOTIF_fileName")." :<br><br>".$tmpFile["name"]);}
				////	Charge le fichier, enregistre ses propriétés et recharge l'objet
				$tmpObj=Ctrl::getTargetObj();//nouveau fichier (create) OU nouvelle version du fichier (update)
				$tmpObj=$tmpObj->createUpdate("name=".Db::format($tmpFile["name"]).", description=".Db::formatParam("description").", octetSize=".Db::format($fileSize));
				////	Ajoute la version du fichier
				$sqlVersionFileName=$tmpObj->_id."_".time().".".File::extension($tmpFile["name"]);
				Db::query("INSERT INTO ap_fileVersion SET _idFile=".$tmpObj->_id.", name=".Db::format($tmpFile["name"]).", realName=".Db::format($sqlVersionFileName).", octetSize=".Db::format($fileSize).", description=".Db::formatParam("description").", dateCrea=".Db::dateNow().", _idUser=".Ctrl::$curUser->_id.", guest=".Db::formatParam("guest"));
				copy($tmpFile["tmpPath"], $tmpObj->filePath());//copie dans le dossier final (après avoir enregistré la version en Bdd!!)
				File::setChmod($tmpObj->filePath());
				//Creation de vignette ET/OU ImageResize?
				$tmpObj->createThumb();
				if(File::controlType("imageResize",$tmpFile["name"]) && Req::isParam("imageResize"))
				{
					File::imageResize($tmpObj->filePath(),$tmpObj->filePath(),Req::getParam("resizeSize"));
					clearstatcache();//Maj du cache
					$newFileSize=(int)filesize($tmpObj->filePath());
					Db::query("UPDATE ap_file SET octetSize=".$newFileSize." WHERE _id=".$tmpObj->_id);
					Db::query("UPDATE ap_fileVersion SET octetSize=".$newFileSize." WHERE _idFile=".$tmpObj->_id." AND realName=".Db::format($sqlVersionFileName));
				}
				////	Prepare la notif mail (10 premiers fichiers)
				$maxNbFiles=10;
				$fileCpt=$fileKey+1;
				if($fileCpt<=$maxNbFiles)
				{
					$tmpFileNotif=$tmpObj->name;
					if(!empty($tmpObj->description))    {$tmpFileNotif.="<br>".$tmpObj->description;}//description?
					$notifFilesLabels[]=$tmpFileNotif;
					if(Req::isParam("notifMailAddFiles"))    {$notifFilesAttachements[]=array("path"=>$tmpObj->filePath(),"name"=>$tmpObj->name);}//joint le fichier à la notif?
				}
				elseif($fileCpt==($maxNbFiles+1))    {$notifFilesLabels[]="[...+ ".(count($newFiles)-$maxNbFiles)." ".Txt::trad("OBJECTfile")."]";}//Sinon affiche "[..+ x fichiers]"
			}
			////	Notifie par mail?  Supprime le dossier temporaire?  Ferme la page
			if(!empty($newFilesAdded))	{$curObj->sendMailNotif(implode("<br><br>",$notifFilesLabels), $notifFilesAttachements);}
			if(!empty($tmpDirPath) && is_dir($tmpDirPath))	{File::rm($tmpDirPath);}
			File::datasFolderSize(true);//Maj en session
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		$vDatas["tmpFolderName"]=uniqid(mt_rand());
		static::displayPage("VueAddEditFiles.php",$vDatas);
	}

	/*
	 * ACTION : Upload d'un fichier temporaire via Plupload
	 */
	public static function actionUploadTmpFile()
	{
		if(Req::isParam("tmpFolderName") && !empty($_FILES))
		{
			//Init/Crée le dossier temporaire
			$tmpDirPath=sys_get_temp_dir()."/".Req::getParam("tmpFolderName")."/";
			if(!is_dir($tmpDirPath))	{mkdir($tmpDirPath);}
			//Vérifie l'accès au dossier temporaire && y place chaque fichier correctement uploadé
			if(is_writable($tmpDirPath)){
				foreach($_FILES as $tmpFile){
					if($tmpFile["error"]==0)	{move_uploaded_file($tmpFile["tmp_name"], $tmpDirPath.$tmpFile["name"]);}
				}
			}
		}
	}

	/*
	 * VUE : Versions d'un fichier
	 */
	public static function actionFileVersions()
	{
		$curObj=self::getTargetObj();
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueFileVersions.php",$vDatas);
	}
	
	/*
	 * ACTION : Suppresion d'un version de fichier
	 */
	public static function actionDeleteFileVersion()
	{
		$curObj=self::getTargetObj();
		$curObj->delete(Req::getParam("dateCrea"));
		static::lightboxClose();
	}
}