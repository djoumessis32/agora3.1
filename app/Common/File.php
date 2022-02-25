<?php
/*
 * Class de gestion des fichiers
 */
class File
{
	//Tailles en octet
	const sizeGo=1073741824;
	const sizeMo=1048576;
	const sizeKo=1024;
	//Taille maxi de la totalité des fichiers attachés aux mails (10Mo)
	const mailMaxFilesSize=10485760;
	//init les types de fichiers
	private static $_fileTypes=null;

	/*
	 * Chmod sur un fichier || Chmod récursif sur un dossier
	 */
	public static function setChmod($path)
	{
		$path=trim($path,"/");
		//Chmod sur un dossier/fichier
		$chmodResult=@chmod($path,0775);
		if(is_dir($path) && $chmodResult==true){
			//chmod sur les fichiers d'un dossier
			foreach(scandir($path) as $tmpFileName){
				if($tmpFileName!='.' && $tmpFileName!='..')  {self::setChmod($path."/".$tmpFileName);}
			}
		}
	}

	/*
	 * Modif du "config.inc.php"
	 */
	public static function updateConfigFile($tabAddConst=null, $tabDeleteConst=null)
	{
		// FICHIER ACCESSIBLE EN ÉCRITURE?
		$configFilePath=PATH_DATAS."config.inc.php";
		if(!is_file($configFilePath) || !is_writable($configFilePath))    {throw new Exception("config.inc.php : the file doesn't exist or is not writable");}
		else
		{
			//Récupère le fichier sous forme de tableau
			$configTab=file($configFilePath);
			if(count($configTab)>1)
			{
				$modifiedConstants=array();
				// Parcourt chaque ligne/constante du fichier
				foreach($configTab as $lineKey => $lineValue)
				{
					// ON MODIFIE LE NOM DE LA CONSTANTE?
					if(stristr($lineValue,"limite_nb_utils")){
						$lineValue=str_replace("limite_nb_utils","limite_nb_users",$lineValue);
					}
					// SUPPRIME LA CONSTANTE COURANTE?
					if(is_array($tabDeleteConst)){
						foreach($tabDeleteConst as $constantName){
							if(!empty($constantName) && stristr($lineValue,'"'.$constantName.'"'))	{$lineValue="";}
						}
					}
					// MODIFIE LA CONSTANTE COURANTE?
					if(is_array($tabAddConst))
					{
						foreach($tabAddConst as $constantName => $constantValue)
						{
							if(stristr($lineValue,$constantName)){
								if(!preg_match("/(true|false)$/i",$lineValue))  {$constantValue="\"".$constantValue."\"";}//valeurs non booléennes : entre guillemet
								$lineValue="define(\"".$constantName."\", ".$constantValue.");\n";
								$modifiedConstants[]=$constantName;//Ajoute dans au listing.. pour la suite
							}
						}
					}
					// SUPPRIME AU BESOIN LA BALISE PHP DE FERMETURE (INUTILE ET PEUT POSER PB LORS D'AJOUT DE CONSTANTE)
					$lineValue=str_replace("?>","",$lineValue);
					// ENREGISTRE LA VALEUR FINALE DE LA LIGNE !!
					$configTab[$lineKey]=$lineValue;
				}
				//AJOUTE LES CONSTANTES DE  $tabAddConst  QUI NE SONT PAS ENCORE PRÉSENTES DANS LE FICHIER (QUI NE SONT PAS PASSES PAR L'EPREUVE DE LA MODIF)
				if(is_array($tabAddConst))
				{
					foreach($tabAddConst as $constantName => $constantValue)
					{
						//contante pas modifiée? -> on l'ajoute au fichier!
						if(!in_array($constantName,$modifiedConstants)){
							if(!preg_match("/(true|false)$/i",$lineValue))  {$constantValue="\"".$constantValue."\"";}//valeurs non booléennes : entre guillemet
							$configTab[]="define(\"".$constantName."\", ".$constantValue.");\n";
						}
					}
				}

				// ON REMPLACE LE FICHIER !
				$fileContent=implode("", $configTab);
				$fp=fopen($configFilePath, "w");
				fwrite($fp, $fileContent);
				fclose($fp);
			}
		}
	}
	
	/*
	 * Extension du fichier (sans le point!)
	 */
	public static function extension($fileName)
	{
		return strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
	}

	/*
	 * Tableau des types de fichiers
	 */
	public static function fileTypes($typeKey)
	{
		if(static::$_fileTypes===null)
		{
			//Types de fichiers en fonction de leur extension
			static::$_fileTypes=array(
				"image"=>array("jpg","jpeg","jpe","png","gif","bmp","wbmp","tif","tiff","psd","eps","ai","ps","svg"),
				"imageBrowser"=>array("jpg","jpeg","png","gif"),
				"imageResize"=>array("jpg","jpeg","png","gif"),//idem
				"word"=>array("doc","docx","docm","dotx","dotm"),
				"excel"=>array("xls","xlsx","xlsm","xltx","xltm"),
				"powerpoint"=>array("ppt","pptx","pptm","potx","potm","pps","ppsx"),
				"ootext"=>array("odt","ott","sxw","stw"),
				"oocalc"=>array("ods","ots","sxc","stc"),
				"oopresent"=>array("odp","otp","sxi","sti"),
				"archive"=>array("zip","rar","7z","gz","tgz","gz","tar","ace","cab","iso","jar","nrg"),
				"pdf"=>array("pdf"),
				"text"=>array("text","txt","rtf"),
				"mp3"=>array("mp3"),
				"audio"=>array("mp3","flac","oga","wma","wav","cda","ra","ram","aac","m4r","mid","aif"),
				"flash"=>array("swf"),
				"html"=>array("htm","html"),
				"web"=>array("htm","html","php","phtml","asp","aspx","jsp","js","css"),
				"autocad"=>array("dwg","dxf"),
				"videoPlayer"=>array("mp4","webm","ogg","mkv","flv"),
				"video"=>array("mp4","webm","ogg","mkv","flv","avi","qt","mov","wmv","mpg"),
				"executable"=>array("exe","bat","dat","dll","msi"),
				"forbidden"=>array("htaccess","sh","so","bin","cgi","rpm","deb","php","phtml","php3","php4","php5","js")
			);
			//Fichier joint pouvant être intégré dans une description (ou autre)
			static::$_fileTypes["attachedFileInsert"]=array_merge(static::$_fileTypes["imageBrowser"],static::$_fileTypes["videoPlayer"],static::$_fileTypes["mp3"],static::$_fileTypes["flash"]);
			//Fichier pouvant être ouvert dans le navigateur
			static::$_fileTypes["filesBrowser"]=array_merge(static::$_fileTypes["imageBrowser"],static::$_fileTypes["videoPlayer"],static::$_fileTypes["mp3"],static::$_fileTypes["flash"],static::$_fileTypes["text"],static::$_fileTypes["html"],static::$_fileTypes["pdf"]);
		}
		//renvoie les fichiers correspondant aux types
		return (array_key_exists($typeKey,static::$_fileTypes))  ?  static::$_fileTypes[$typeKey]  :  array();
	}

	/*
	 * Controle le type de fichier en fonction de son extension			(ex "controle_fichier()")
	 */
	public static function controlType($typeKey, $fileName)
	{
		return in_array(self::extension($fileName), self::fileTypes($typeKey));
	}

	/*
	 * Afficher un player Audio/Video/Flash
	 */
	public static function getMediaPlayer($filePath)
	{
		$defaultMessage="Your browser does not support HTML5: thanks for updating it.<br>Votre navigateur ne prend pas en charge HTML5: merci de le mettre à jour.";
		if(self::controlType("videoPlayer",$filePath))	{return "<video controls><source src='".$filePath."' type='video/".self::extension($filePath)."'>".$defaultMessage."</video>";}
		elseif(self::controlType("mp3",$filePath))		{return "<audio controls><source src='".$filePath."' type='audio/mpeg'>".$defaultMessage."</audio>";}
		elseif(self::controlType("flash",$filePath))	{return "<object type='application/x-shockwave-flash' data='".$filePath."'><param name='movie' value='".$filePath."'></object>";}
	}

	/*
	 * Telecharge un fichier
	 */
	public static function download($fileName, $filePath=null, $fileContent=null)
	{
		// Fichier généré à la volée ($fileContent) OU présent dans DATAS/
		if(!empty($fileContent) || is_file($filePath))
		{
			// Augmente la duree du script
			@set_time_limit(120);//disabled en safemode
			// Headers
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".Txt::clean($fileName,"download")."\"");
			header("Expires: 0");
			header("Pragma: public");
			header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
			if(!empty($filePath))    {header("Content-Length: ".filesize($filePath));}
			// Envoi direct d'un fichier (si < 20mo)  /  Envoi d'un fichier généré à la volée  /  Envoie d'un gros fichier par tranche de 2 mo (si > 20mo)
			if(!empty($filePath) && filesize($filePath)<20971520)	{readfile($filePath);}
			elseif(!empty($fileContent)){
				header('Content-Type: text/plain; charset=utf-8');
				echo $fileContent;
			}else{
				session_write_close();//permet de continuer à naviguer sur le site durant le téléchargement!
				$handle=fopen($filePath,'rb');
				while(!feof($handle)){
					print fread($handle,self::sizeMo);
					flush();//Vide les tampons de sortie
					ob_flush();//Envoie le tampon de sortie
				}
				fclose($handle);
				ob_end_clean();//Détruit les données du tampon de sortie et éteint la temporisation de sortie
			}
			exit;
		}
	}

	/*
	 * Readfile du fichier pour un affichage direct par le browser, sans mettre le chemin réel dans le html!
	 */
	public static function display($filePath)
	{
		if(is_file($filePath)){
			$extension=self::extension($filePath);
			if(self::controlType("imageBrowser",$filePath))	{$contentType="image/".$extension;}
			elseif($extension=="pdf")						{$contentType="application/pdf";}
			elseif($extension=="mp3")						{$contentType="audio/mpeg";}
			elseif($extension=="txt")						{$contentType="text/plain;";}
			elseif($extension=="rtf")						{$contentType="application/rtf;";}
			else											{$contentType="application/octet-stream";}
			header("Content-Type: ".$contentType);
			header("Content-Length: ".filesize($filePath));
			readfile($filePath);
		}
	}

	/*
	 * Taille d'un dossier, en octets  (fonction récursive. Alternative: "du -sb")					(ex "dossier_taille()")
	 */
	public static function folderSize($folderPath)
	{
		$folderSize=0;
		$folderPath=rtrim($folderPath,"/");//"trimer" uniquement la fin du chemin
		// Récupère la taille d'un dossier
		if(is_dir($folderPath))
		{
			// Parcourt le dossier courant -> récupère la taille des fichiers / lance récursivement "folderSize()"
			foreach(scandir($folderPath) as $tmpFile)
			{
				if($tmpFile!='.' && $tmpFile!='..'){
					$filePath=$folderPath."/".$tmpFile;
					if(is_file($filePath))		{$folderSize+=filesize($filePath);}
					elseif(is_dir($filePath))	{$folderSize+=self::folderSize($filePath);}
				}
			}
		}
		// Retourne le résultat
		return $folderSize;
	}

	/*
	 * Taille du PATH_DATAS (recalculé toute les 10mn)			(ex "taille_stock_fichier()")///!!!ASSOCIER A UNE ACTION : AJOUT/SUPPRESSION DE FICHIER DANS LE DATAS/
	 */
	public static function datasFolderSize($refresh=false)
	{
		$timeout=600;
		// Récupère la taille de "PATH_DATAS" (si refresh, ou pas encore définie en session, ou si valeur expiré) 
		if($refresh==true || empty($_SESSION["datasFolderSize"]) || (time()-$_SESSION["datasFolderSizeTimeout"])>$timeout){
			$_SESSION["datasFolderSize"]=self::folderSize(PATH_DATAS);
			$_SESSION["datasFolderSizeTimeout"]=time();
		}
		// retourne la valeur
		return $_SESSION["datasFolderSize"];
	}

	/*
	 * Retourne une valeur en octets, à partir d'une valeur en Go/Mo/Ko	(exple : 10Mo)											(ex "return_bytes()")
	 */
	public static function getBytesSize($sizeText)
	{
		if(preg_match("/(g|go)$/i",$sizeText))		{return str_ireplace(["go","g"],null,$sizeText) * self::sizeGo;}
		elseif(preg_match("/(m|mo)$/i",$sizeText))	{return str_ireplace(["mo","m"],null,$sizeText) * self::sizeMo;}
		elseif(preg_match("/(k|ko)$/i",$sizeText))	{return str_ireplace(["ko","k"],null,$sizeText) * self::sizeKo;}
		else										{return $sizeText;}
	}

	/*
	 * Affiche une taille (fichier/dossier) à partir d'une valeur en octets ..ou d'un texte (exple : 10Mo)						(ex "afficher_taille()")
	 */
	public static function displaySize($size, $displayLabel=true)
	{
		$bytesSize=self::getBytesSize($size);
		if($bytesSize>=self::sizeGo)		{$size=round(($bytesSize/self::sizeGo),2);		$tradLabel="giga_octet";}
		elseif($bytesSize>=self::sizeMo)	{$size=round(($bytesSize/self::sizeMo),1);		$tradLabel="mega_octet";}
		else								{$size=round(($bytesSize/self::sizeKo),0);		$tradLabel="kilo_octet";}
		return ($displayLabel==true)  ?  $size." ".Txt::trad($tradLabel)  :  $size;
	}

	/*
	 * Retourne la taille max des fichiers uploadés (en Octets)					(ex "libelle_upload_max_filesize()")
	 */
	public static function uploadMaxFilesize($message=false)
	{
		$upload_max_filesize=(int)self::getBytesSize(ini_get("upload_max_filesize"));
		if($message=="error")	{return Txt::trad("FILE_erreur_taille_fichier")." :<br>".Txt::trad("FILE_limite_chaque_fichier")." ".self::displaySize($upload_max_filesize);}
		if($message=="info")	{return Txt::trad("FILE_limite_chaque_fichier")." ".self::displaySize($upload_max_filesize);}
		else					{return $upload_max_filesize;}
	}

	/*
	 * Suppression d'un fichier/dossier sur le disque (fonction recursive)
	 */
	public static function rm($targetPath, $errorMessage=true)
	{
		//suppr le dernier "/"
		$targetPath=rtrim($targetPath,"/");
		//Verifie l'accès en écriture (avec message d'erreur au besoin?)
		if(self::isWritable($targetPath,$errorMessage))
		{
			if(is_file($targetPath))	{return unlink($targetPath);}
			elseif(is_dir($targetPath) && $targetPath!=PATH_MOD_FILE){
				foreach(scandir($targetPath) as $tmpFile){
					if($tmpFile!='.' && $tmpFile!='..')  {self::rm($targetPath."/".$tmpFile,$errorMessage);}
				}
				return rmdir($targetPath);
			}
		}
	}

	/*
	 * Verifie si un dossier ou un fichier est accessible en écriture
	 */
	public static function isWritable($targetPath, $errorMessage=true)
	{
		if(file_exists($targetPath) && is_writable($targetPath) && $targetPath!=PATH_MOD_FILE)	{return true;}
		else{
			if($errorMessage==true)  {Ctrl::addNotif(Txt::trad("MSG_NOTIF_acces_fichier_dossier")." : ".str_replace(PATH_MOD_FILE,"",$targetPath));}
			return false;
		}
	}

	/*
	 * Redimensionne une image ("imgSrc.png"= "imgDest.jpg")														(ex "reduire_image()")
	 */
	public static function imageResize($imgPathSrc, $imgPathDest, $maxWidth, $maxHeight=null, $compressionQuality=85)
	{
		// Verifs de base
		if(self::controlType("imageResize",$imgPathSrc) && function_exists("getimagesize") && is_file($imgPathSrc) && is_numeric($maxWidth))
		{
			////	Récupère la taile de l'image et vérifie l'intégrité du fichier
			$getimagesize=@getimagesize($imgPathSrc);
			if(is_array($getimagesize) && in_array($getimagesize[2],[IMAGETYPE_JPEG,IMAGETYPE_GIF,IMAGETYPE_PNG]))
			{
				//Init
				$resizeReturn=false;
				list($oldWidth,$oldHeight)=$getimagesize;
				////	Nouvelle taille de l'image, en fonction du cadre de référence
				if(empty($maxHeight))	{$maxHeight=$maxWidth;}//height=width
				if($oldWidth<$maxWidth && $oldHeight<$maxHeight)	{$newWidth=$oldWidth;	$newHeight=$oldHeight;}//conserve la taille
				elseif($oldWidth>$oldHeight)						{$newWidth=$maxWidth;	$newHeight=round(($maxWidth / $oldWidth) * $oldHeight);}//paysage
				else												{$newHeight=$maxHeight;	$newWidth=round(($maxHeight / $oldHeight) * $oldWidth);}//portrait
				////	Resize via la lib "Imagick"
				if(extension_loaded("imagick"))
				{
					$imgImagick=new Imagick($imgPathSrc);
					//Vérifie s'il faut réorienter l'image
					$imgOrientation=$imgImagick->getImageOrientation();
					if($imgOrientation==6)		{$imgRotate=90;}
					elseif($imgOrientation==8)	{$imgRotate=-90;}
					if(isset($imgRotate)){
						list($newWidth,$newHeight)=[$newHeight,$newWidth];//Switch le width et height?
						$imgImagick->rotateImage(new ImagickPixel('#000'),$imgRotate);
					}
					//Compresse && Resize && enregistre l'image
					$imgImagick->setImageCompressionQuality($compressionQuality); 
					$imgImagick->thumbnailImage($newWidth, $newHeight);
					$imgImagick->writeImage($imgPathDest);
					$resizeReturn=true;
				}
				////	Resize via la lib "GD"
				elseif(function_exists("imagecreatefromjpeg"))
				{
					// Créé une image temporaire
					$thumb=imagecreatetruecolor($newWidth,$newHeight);
					if(preg_match("/jpe?g$/i",$imgPathSrc))		{$source=imagecreatefromjpeg($imgPathSrc);}
					elseif(preg_match("/gif$/i",$imgPathSrc))	{$source=imagecreatefromgif($imgPathSrc);}
					elseif(preg_match("/png$/i",$imgPathSrc)){
						imagesavealpha($thumb,true);//conserve la transparence des .png
						$transColour=imagecolorallocatealpha($thumb, 0, 0, 0, 127);
						imagefill($thumb,0,0,$transColour);
						$source=imagecreatefrompng($imgPathSrc);
					}
					// Resize & Enregistre l'image
					if($source!=false)
					{
						imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);
						if(preg_match("/jpe?g$/i",$imgPathDest))	{imagejpeg($thumb,$imgPathDest,$compressionQuality);}
						elseif(preg_match("/gif$/i",$imgPathDest))	{imagegif($thumb,$imgPathDest);}
						elseif(preg_match("/png$/i",$imgPathDest))	{imagepng($thumb,$imgPathDest,8);}
						$resizeReturn=true;
					}
				}
				////	Chmod et retourne true
				if($resizeReturn==true){
					self::setChmod($imgPathDest);
					return true;
				}
			}
		}
	}

	/*
	 * Generer archive zip															(ex "creer_envoyer_archive()")
	 */
	public static function downloadArchive($filesList, $archiveName)
	{
		if(!empty($filesList))
		{
			//Création de l'archive
			$archiveTmpPath=sys_get_temp_dir()."/".uniqid(mt_rand()).".zip";
			$zip=new ZipArchive();
			$zip->open($archiveTmpPath, ZipArchive::CREATE);
			//Ajout de chaque fichier à l'archive (avec "realPath" & un "zipPath") ou un dossier vide (avec "emptyFolderZipPath")
			foreach($filesList as $tmpFile){
				if(isset($tmpFile["emptyFolderZipPath"]))	{$zip->addEmptyDir($tmpFile["emptyFolderZipPath"]);}
				elseif(is_file($tmpFile["realPath"]))		{$zip->addFile($tmpFile["realPath"],$tmpFile["zipPath"]);}//Problème d'encodage sous Wamp: au besoin, faire "iconv("UTF-8","CP850",$tmpFile["zipPath"])"
			}
			//Ferme l'archive, envoi le zip, puis le supprime
			$zip->close();
			self::download($archiveName, $archiveTmpPath);
			self::rm($archiveTmpPath);
		}
	}

	/*
	 * Download de grosse archive (sav & co) : controle d'horaire pour ne pas saturer le serveur				(ex "controle_big_download()")
	 */
	public static function archiveSizeControl($archiveSize)
	{
		$limitSize=(self::sizeMo*300);//300Mo max
		$limitHour=array("begin"=>9,"end"=>19);//plage horaire de pointe
		if(date("G") > $limitHour["begin"] && date("G") < $limitHour["end"] && (int)$archiveSize > $limitSize){
			Ctrl::addNotif(Txt::trad("download_alert")." (".$limitHour["begin"]."h-".$limitHour["end"]."h). ".Txt::trad("download_alert2")." : ".self::displaySize($limitSize));
			Ctrl::redir("?ctrl=".Req::$curCtrl);//ne pas mettre de "action"
		}
	}
}