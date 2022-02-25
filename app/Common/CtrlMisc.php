<?php
/*
 * Menus & actions indépendants des modules (communs)
 */
class CtrlMisc extends Ctrl
{
	/* Inexistant dans ce contexte: pas un module à part entière*/
	public static function actionDefault(){}

	/*
	 * Surcharge de l'init du controleur principal
	 */
	public static function initCtrl()
	{
		//Pas d'initialisation complete du controleur (controle de connexion, selection d'espace, etc)
		static::$initCtrlFull=false;
		//Appel le constructeur parent
		parent::initCtrl();
	}

	/*
	 * AJAX : affiche les livecounters (principal/messenger) et les messages du messenger
	 */
	public static function actionLivecounterUpdate()
	{
		//User identifié et messenger activé?
		if(self::$curUser->messengerEnabled())
		{
			//Init
			$ajaxResult=array();
			//Supprime les users déconnectés de "ap_userLivecouter" & efface les messages obsoletes
			if(empty($_SESSION["livecounterLastUpdate"])){
				Db::query("DELETE FROM ap_userLivecouter WHERE date < ".(int)(time()-LIVECOUNTER_TIMEOUT));
				Db::query("DELETE FROM ap_userMessengerMessage WHERE date < ".(int)(time()-MESSENGER_TIMEOUT));
			}
			//Update la date du livecounter de l'utilisateur courant
			$sqlValues="ipAdress='".$_SERVER["REMOTE_ADDR"]."', date='".time()."'";
			if(Db::getVal("SELECT count(*) FROM ap_userLivecouter WHERE _idUser=".self::$curUser->_id)==0)	{Db::query("INSERT INTO ap_userLivecouter SET _idUser=".self::$curUser->_id.", ".$sqlValues);}
			else																							{Db::query("UPDATE ap_userLivecouter SET ".$sqlValues." WHERE _idUser=".self::$curUser->_id);}
			//Users connectés
			$livecounterUsersConnected=self::$curUser->livecounterUsersConnected();
			//Affiche les livecounters (initialise le livecounter OU changement dans "livecounterUsersConnected")
			if(Req::isParam("initLivecounter") || $livecounterUsersConnected!=$_SESSION["livecounterUsersConnected"])
			{
				//Aucun user connecté
				if(empty($livecounterUsersConnected)){
					$ajaxResult["livecounterPrincipal"]="";
					$ajaxResult["livecounterMessenger"]="<span>".Txt::trad("HEADER_MENU_seul_utilisateur_connecte")."</span>";
				}
				//Liste des users connectés
				else
				{
					$cpt=0;
					//titles
					$ajaxResult["livecounterPrincipal"]=Txt::trad("HEADER_MENU_en_ligne")." : ";
					$ajaxResult["livecounterMessenger"]="";
					//Affiche chaque user
					foreach($livecounterUsersConnected as $tmpObj)
					{
						$cpt++;
						$userLib=(empty($tmpObj->firstName)) ? $tmpObj->name : $tmpObj->firstName;
						$userTitle=$tmpObj->display("all")." ".Txt::trad("HEADER_MENU_connecte_a")." ".strftime("%H:%M",$tmpObj->lastConnection);
						//Livecounter principal
						$ajaxResult["livecounterPrincipal"].="<label class='vLiveCounterUsers' onclick='showHideMessenger(".$tmpObj->_id.");' title=\"".$userTitle."\">".$userLib.($cpt<count($livecounterUsersConnected)?", ":"")."</label>";
						//Livecounter messenger
						$boxId="messengerUserBox".$tmpObj->_id;
						$checked=(isset($_SESSION["messengerPostUsers"]) && in_array($tmpObj->_id,$_SESSION["messengerPostUsers"])) ? "checked" : "";
						$ajaxResult["livecounterMessenger"].="<div class='vMessengerUser'>
								<div class='vMessengerUserImg'>".$tmpObj->getImg(true,true)."</div>
								<div class='vMessengerUserBox'><input type='checkbox' name='messengerPostUsers' value='".$tmpObj->_id."' id='".$boxId."' ".$checked."></div>
								<label class='vMessengerUserLabel' for='".$boxId."' title=\"".$userTitle."\">".$userLib."</label>
							</div>";
					}
				}
			}
			//Nouveau message sur le messenger?
			$sqlMessagesLastDisplay=(isset($_SESSION["messengerMessagesLastDisplay"])) ? "AND date > ".(int)$_SESSION["messengerMessagesLastDisplay"] : "";
			$messengerMessages=Db::getVal("SELECT count(*) FROM ap_userMessengerMessage WHERE _idUsers LIKE '%@".self::$curUser->_id."@%'  ".$sqlMessagesLastDisplay." ORDER BY date asc");
			if($messengerMessages>0)	{$ajaxResult["messengerNewMessages"]=true;}
			//Garde en mémoire la date du dernier Update et les derniers utilisateurs connectés
			$_SESSION["livecounterUsersConnected"]=$livecounterUsersConnected;
			$_SESSION["livecounterLastUpdate"]=time();
			//Retourne le résultat au format JSON
			echo json_encode($ajaxResult);
		}
	}

	/*
	 * AJAX : recupère les messages du messenger
	 */
	public static function actionMessengerGetMessages()
	{
		//User identifié et messenger activé?
		if(self::$curUser->messengerEnabled())
		{
			$messengerMessages="";
			$dbMessages=Db::getTab("SELECT DISTINCT T1.*, T2.* FROM ap_userMessengerMessage T1, ap_user T2 WHERE T1._idUser=T2._id AND T1._idUsers LIKE '%@".self::$curUser->_id."@%' AND T1.date > ".(int)(time()-MESSENGER_TIMEOUT)." ORDER BY T1.date asc");
			foreach($dbMessages as $tmpMessage)
			{
				$usersTab=Txt::txt2tab($tmpMessage["_idUsers"]);
				$userTmp=self::getObj("user",$tmpMessage["_idUser"]);
				$titleMessageTo=$userTmp->getImg()." ".$userTmp->display()." : ";
				foreach($usersTab as $recipientId){
					if($recipientId!=$tmpMessage["_idUser"])	{$titleMessageTo.=self::getObj("user",$recipientId)->display().", ";}
				}
				$title="<div class='vMessengerMessageTitle'>".Txt::trad("HEADER_MENU_envoye_a")." ".strftime("%H:%M",$tmpMessage["date"])."</div><div class='vMessengerMessageTitle'>".trim($titleMessageTo,", ")."</div>";
				$usersAsterisk=(count($usersTab)>2)?"*":"";
				$messengerMessages.="<div class='vMessengerMessageLine' title=\"".$title."\">
										<div class='vMessengerMessageUser'>".$usersAsterisk.(empty($tmpMessage["firstName"])?$tmpMessage["name"]:$tmpMessage["firstName"])."</div>
										<div class='vMessengerMessageContent' style='color:".$tmpMessage["color"]."'>".$tmpMessage["message"]."</div>
									  </div>";
			}
			$_SESSION["messengerMessagesLastDisplay"]=time();
			echo $messengerMessages;
		}
	}

	/*
	 * AJAX : Post d'un message sur le messenger
	 */
	public static function actionMessengerPostMessage()
	{
		//User identifié et messenger activé?
		if(self::$curUser->messengerEnabled())
		{
			$usersIds=Req::getParam("messengerPostUsers");
			$usersIds[]=self::$curUser->_id;
			Db::query("INSERT INTO ap_userMessengerMessage SET _idUser=".self::$curUser->_id.", _idUsers=".Db::formatTab2txt($usersIds).", message=".Db::formatParam("message").", color=".Db::formatParam("color").", date='".time()."'");
			$_SESSION["messengerPostColor"]=Req::getParam("color");
			$_SESSION["messengerPostUsers"]=$usersIds;
		}
	}

	/*
	 * AJAX : Post d'un message sur le messenger
	 */
	public static function actionMessengerDownloadMessages()
	{
		//User identifié et messenger activé?
		if(self::$curUser->messengerEnabled())
		{
			$htmlReturn="";
			$messages=Db::getTab("SELECT DISTINCT T1.*, T2.* FROM ap_userMessengerMessage T1, ap_user T2 WHERE T1._idUser=T2._id AND T1._idUsers LIKE '%@".self::$curUser->_id."@%' ORDER BY  T1.date asc");
			foreach($messages as $message)
			{
				$destLib=Txt::trad("HEADER_MENU_envoye_a")." ";
				foreach(Txt::txt2tab($message["_idUsers"]) as $userId){
					if($userId!=$message["_idUser"])	{$destLib.=self::getObj("user",$userId)->display().", ";}
				}
				$htmlReturn .= "<div style='display:table;width:100%;'>
									<div style='display:table-cell;width:300px;border:solid 1px #999;padding:5px;'> ".Txt::displayDate($message["date"])." : ".$message["name"]." ".$message["firstName"]."</div>
									<div style='display:table-cell;width:300px;border:solid 1px #999;padding:5px;'>".trim($destLib,", ")."</div>
									<div style='display:table-cell;border:solid 1px #999;padding:5px;'>".$message["message"]."</div>
								</div>";
			}
			$htmlReturn="<html xmlns='http://www.w3.org/1999/xhtml'>
							<head><meta charset='utf-8'></head>
							<body>".$htmlReturn."</body>
						 </html>";
			//Telechargement
			File::download("messenger.html", null, $htmlReturn);
		}
	}

	/*
	 * AJAX : Verifie si un compte utilisateur existe dejà, avec un mail en parametre (exple:"?ctrl=misc&action=UserAccountExist&mail=test%40test.test")
	 */
	public static function actionUserAccountExist()
	{
		if(Req::isParam("mail") && Db::getVal("SELECT count(*) FROM ap_user WHERE mail=".Db::formatParam("mail")." OR login=".Db::formatParam("mail"))>0)
			{echo "true";}
	}

	/*
	 * VUE : Menu "captcha"
	 */
	public static function menuCaptcha()
	{
		return self::getVue(Req::commonPath."VueCaptcha.php");
	}

	/*
	 *  ACTION : Affiche l'image d'un menu "captcha"
	 */
	public static function actionCaptchaImg()
	{
		//Init
		$width=120;
		$height=28;
		$fontSize=21;
		$caracNb=4;
		$colorLines=array("#DD6666","#66DD66","#6666DD","#DDDD66","#DD66DD","#66DDDD","#666666");
		$colorFonts=array("#880000","#008800","#000088","#888800","#880088","#008888","#000000");
		$caracs="ABCDEFGHKMNPQRSTUVWXYZ2345689";
		//Creation de l'image
		$image=imagecreatetruecolor($width, $height);
		imagefilledrectangle($image, 0, 0, $width-1, $height-1, self::captchaColor("#FFFFFF"));
		//Dessine 20 lines en background
		for($i=0; $i < 20; $i++){
			imageline($image, mt_rand(0,$width-1), mt_rand(0,$height-1), mt_rand(0,$width-1), mt_rand(0,$height-1), self::captchaColor($colorLines[mt_rand(0,count($colorLines)-1)]));
		}
		//Dessine le texte
		$_SESSION["captcha"]="";
		$y=($height/2) + ($fontSize/2);
		for($i=0; $i < $caracNb; $i++)
		{
			// pour chaque caractere : Police + couleur + angulation
			$captcha_font="app/misc/captchaFonts/".mt_rand(1,4).".ttf";
			$color=self::captchaColor($colorFonts[mt_rand(0,count($colorFonts)-1)]);
			$angle=mt_rand(-20,20);
			// sélectionne le caractère au hazard
			$char=substr($caracs, mt_rand(0,strlen($caracs) - 1), 1);
			$x=(intval(($width/$caracNb) * $i) + ($fontSize / 2)) - 4;
			$_SESSION["captcha"] .= $char;
			imagettftext($image, $fontSize, $angle, $x, $y, $color, $captcha_font, $char);
		}
		// Captcha dans Session + affichage de l'image
		header("Content-Type: image/jpeg");
		imagejpeg($image);
	}

	/*
	 * Couleur au format hexadecimal pour un Captcha
	 */
	protected static function captchaColor($colors)
	{
		return preg_match("/^#?([\dA-F]{6})$/i",$colors,$rgb) ? hexdec($rgb[1]) : false;
	}

	/*
	 * AJAX : controle d'un captcha
	 */
	public static function actionCaptchaControl()
	{
		echo (Req::isParam("captcha") && Req::getParam("captcha")==$_SESSION["captcha"]) ? "true" : "false";
	}

	/*
	 * VUE : Renvoie l'initialisation de l'editeur TinyMCE (doit déjà y avoir un chanmp "textarea")				(ex "init_editeur_tinymce()")
	 */
	public static function initHtmlEditor($fieldName)
	{
		$vDatas=array(
			"fieldName"=>$fieldName
		);
		return self::getVue(Req::commonPath."VueHtmlEditor.php",$vDatas);
	}

	/*
	 * VUE : Affiche des personnes sur une carte (contacts/utilisateurs)
	 */
	public static function actionPersonsMap()
	{
		static::displayPage(Req::commonPath."VuePersonsMap.php");
	}

	/*
	 * VUE : menuWallpaper
	 */
	public static function menuWallpaper($curWallpaper)
	{
		//Wallpapers disponibles
		$vDatas["wallpaperList"]=array();
		$filesList=array_merge(scandir(PATH_WALLPAPER_DEFAULT),scandir(PATH_WALLPAPER_CUSTOM));
		foreach($filesList as $tmpFile){
			if($tmpFile!='.' && $tmpFile!='..' && File::controlType("imageBrowser",$tmpFile)){
				$path=(is_file(PATH_WALLPAPER_DEFAULT.$tmpFile))  ?  PATH_WALLPAPER_DEFAULT.$tmpFile  :  PATH_WALLPAPER_CUSTOM.$tmpFile;
				$value=(is_file(PATH_WALLPAPER_DEFAULT.$tmpFile))  ?  WALLPAPER_DEFAULT_PREFIX.$tmpFile  :  $tmpFile;
				$nameRacine=str_replace(File::extension($tmpFile),null,$tmpFile);
				$vDatas["wallpaperList"][]=array("path"=>$path, "value"=>$value, "name"=>$tmpFile, "nameRacine"=>$nameRacine);
			}
		}
		//Affiche le menu
		$vDatas["wallpaperList"]=Tool::sortArray($vDatas["wallpaperList"],"nameRacine");
		$vDatas["curWallpaper"]=$curWallpaper;
		return self::getVue(Req::commonPath."VueMenuWallpaper.php",$vDatas);
	}

	/*
	 * PATH D'UN WALLPAPER  (cf. Ctrl::$curSpace->wallpaper && Ctrl::$agora->wallpaper)
	 */
	public static function pathWallpaper($fileName=null)
	{
		//Récup le chemin et vérifie la présence du fichier
		if(!empty($fileName)){
			$pathWallpaper=(strstr($fileName,WALLPAPER_DEFAULT_PREFIX)) ? PATH_WALLPAPER_DEFAULT.trim($fileName,WALLPAPER_DEFAULT_PREFIX) : PATH_WALLPAPER_CUSTOM.$fileName;
			if(is_file($pathWallpaper))		{return $pathWallpaper;}
		}
		//Sinon retourne le wallpaper par défaut
		return PATH_WALLPAPER_DEFAULT."1.jpg";
	}

	/*
	 * PATH DU LOGO EN BAS DE PAGE
	 */
	public static function pathfooterLogo()
	{
		return (!empty(self::$agora->logo) && is_file(PATH_DATAS.self::$agora->logo))  ?  PATH_DATAS.self::$agora->logo  :  "app/img/logoFooter.png";
	}
}