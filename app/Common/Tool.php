<?php
/*
 * Classe "avec plein de trucs" : boite à outils
 */
class Tool
{
	// Tableau des timeszones
	public static $tabTimezones=array(
		"Kwajalein"=>"-12:00",
		"Pacific/Midway"=>"-11:00",
		"Pacific/Honolulu"=>"-10:00",
		"America/Anchorage"=>"-9:00",
		"America/Los_Angeles"=>"-8:00",
		"America/Denver"=>"-7:00",
		"America/Mexico_City"=>"-6:00",
		"America/New_York"=>"-5:00",
		"America/Guyana"=>"-4:00",
		"America/Buenos_Aires"=>"-3:00",
		"America/Sao_Paulo"=>"-3:00",
		"Atlantic/South_Georgia"=>"-2:00",
		"Atlantic/Azores"=>"-1:00",
		"Europe/London"=>"0:00",
		"Europe/Paris"=>"1:00",
		"Europe/Helsinki"=>"2:00",
		"Europe/Moscow"=>"3:00",
		"Asia/Dubai"=>"4:00",
		"Asia/Karachi"=>"5:00",
		"Asia/Dhaka"=>"6:00",
		"Asia/Jakarta"=>"7:00",
		"Asia/Hong_Kong"=>"8:00",
		"Asia/Tokyo"=>"9:00",
		"Australia/Sydney"=>"10:00",
		"Asia/Magadan"=>"11:00",
		"Pacific/Fiji"=>"12:00",
		"Pacific/Tongatapu"=>"13:00");

	/*
	 * Envoi d'un mail			(ex "envoi_mail()")
	 */
	public static function sendMail($mailsTo, $subject, $mainMessage, $options=null, $attachedFiles=null)
	{
		//Options par defaut
		$opt["noFooter"]=(stristr($options,"noFooter")) ? true : false;					//Défaut : affiche un footer dans le message
		$opt["noSendNotif"]=(stristr($options,"noSendNotif")) ? true : false;			//Défaut : affiche une notif si le message a bien été envoyé
		$opt["hideRecipients"]=(stristr($options,"hideRecipients")) ? true : false;		//Défaut : affiche les destinataires du mail
		$opt["senderNoReply"]=(stristr($options,"senderNoReply")) ? true : false;		//Défaut : affiche le nom/prénom de l'expéditeur du mail
		$opt["objectEditNotif"]=(stristr($options,"objectEditNotif")) ? true : false;	//Défaut : affiche une simple notif d'envoi
		$opt["receptionNotif"]=(stristr($options,"receptionNotif")) ? true : false;		//Défaut : pas de notification de réception du message (à l'expéditeur)

		//Charge PHPmailer
		require("app/misc/phpmailer/class.phpmailer.php");
		$mail=new PHPMailer();
		$mail->CharSet="utf-8";
		//MTA Authentification?
		//$mail->Host="localhost";$mail->Port=25;$mail->SMTPAuth=false;$mail->Username="myname@mydomaine.tld";$mail->Password="mypassword";

		//Parametrage dkim (mode test)
		if(defined("DKIM_domain") && defined("DKIM_private") && defined("DKIM_selector")){
			$mail->DKIM_domain=DKIM_domain;
			$mail->DKIM_private=DKIM_private;
			$mail->DKIM_selector=DKIM_selector;
		}

		//Expediteur
/******/$curDomain=trim($_SERVER["HTTP_HOST"],"www.");//exple:"omnispace.fr"
/******/$curDomainName=(strstr($curDomain,"."))  ?  ucfirst(substr($curDomain,0,strrpos($curDomain,".")))  :  $curDomain;//exple:"Omnispace"
		$isUserMail=(!empty(Ctrl::$curUser->mail) && method_exists(Ctrl::$curUser,"isUser"))  ?  true  :  false;
		$mail->SetFrom("noreply@".$curDomain, $curDomainName);
		if($opt["senderNoReply"]==false && $isUserMail==true){
			$mail->SetFrom("noreply@".$curDomain, $curDomainName." - ".Ctrl::$curUser->display());
			$mail->AddReplyTo(Ctrl::$curUser->mail, Ctrl::$curUser->display());
			if($opt["receptionNotif"]==true)	{$mail->ConfirmReadingTo=Ctrl::$curUser->mail;}
		}

		////	Destinataires (format text / array d'idUser)
		//Ajoute l'user courant en "AddAddress()" si "hideRecipients" (pour eviter le Spam)
		if($opt["hideRecipients"]==true && method_exists(Ctrl::$curUser,"isUser") && !empty(Ctrl::$curUser->mail))	{$mail->AddAddress(Ctrl::$curUser->mail);}
		//Ajoute chaque destinataire
		$mailsToNotif=null;
		if(is_string($mailsTo))  {$mailsTo=explode(",",trim($mailsTo,","));}
		foreach($mailsTo as $tmpDest){
			if(is_numeric($tmpDest) && method_exists(Ctrl::$curUser,"isUser"))	{$tmpDest=Ctrl::getObj("user",$tmpDest)->mail;}
			if(!empty($tmpDest)){
				$mailsToNotif.=", ".$tmpDest;
				if($opt["hideRecipients"]==true)	{$mail->AddBCC($tmpDest);}//Copie cachée (sauf wamp)
				else								{$mail->AddAddress($tmpDest);}
			}
		}

		//Sujet & message
		$mail->Subject=$subject;
/******/if($opt["noFooter"]==false && $isUserMail==true)	{$mainMessage.="<br><br>".ucfirst(Txt::trad("MAIL_envoye_par"))." ".Ctrl::$curUser->display()."<br><a href=\"".Req::getSpaceUrl()."\" target='_blank'>".Ctrl::$agora->name."</a>";}
		$mail->MsgHTML($mainMessage);

		//Fichiers joints ? ("path" & "name" & 10Mo max pour l'ensemble des fichiers)
		if(!empty($attachedFiles)){
			foreach($attachedFiles as $tmpFile){
				if(is_file($tmpFile["path"]))	{$mail->AddAttachment($tmpFile["path"],$tmpFile["name"]);}
			}
		}

		//Envoi du mail + rapport d'envoi si demande
		$isSendMail=$mail->Send();
		if($opt["noSendNotif"]==false){
			$notifMail=($opt["objectEditNotif"]==true) ? Txt::trad("MAIL_envoye_notif") : Txt::trad("MAIL_envoye");
			if($isSendMail==true)	{Ctrl::addNotif($notifMail."<br><br>".Txt::trad("MAIL_recipients")." : ".trim($mailsToNotif,","), "success");}
			else					{Ctrl::addNotif("MAIL_pas_envoye");}
		}
		return $isSendMail;
	}

	/*
	 * Navigation tactile ?  (ex "is_mobile()")
	 */
	public static function tactileDevice()
	{
		return preg_match("/(android|iphone|windows phone|opera mini|blackberry|symbian|bada)/i",$_SERVER['HTTP_USER_AGENT']);
	}
	
	/*
	 * Verifie si on n'est pas avec une version obsolete d'IE  (ex "version_ie_obsolete()")
	 */
	public static function isObsoleteIE($maxObsoleteVersion=9)
	{
		return (preg_match("/MSIE [7-".$maxObsoleteVersion."]/i",$_SERVER["HTTP_USER_AGENT"]));
	}

	/*
	 * ColorPicker / Selecteur de couleurs.							(ex "select_couleur()")
	 * $bgTxtColor="background-color"/"color"
	 */
	public static function colorPicker($inputText, $inputColor, $bgTxtColor="background-color")
	{
		$colorMap=null;
		$menuContextId=uniqid(mt_rand());
		$colors=array("#9b9b9b","#cb0000","#f56b00","#ffcb2f","#ffc888","#32cb00","#00d2cb","#3166ff","#6434fc","#656565","#9a0000","#ce6301","#cd9934","#999903","#009901","#329a9d","#3531ff","#6200c9","#343434","#680100","#963400","#986536","#646809","#036400","#34696d","#00009b","#303498","#000000","#330001","#643403","#663234","#343300","#013300","#003532","#010066","#340096");
		foreach(array_reverse($colors) as $key=>$tmpColor){
			$colorMap.="<div class='colorPickerCell' style=\"background:".$tmpColor.";\" OnClick=\"$('#".$inputText."').css('".$bgTxtColor."','".$tmpColor."'); $('#".$inputColor."').val('".$tmpColor."');\">&nbsp;</div>";
			if((($key+1)%9)==0)	{$colorMap.="</div><div class='colorPickerRow'>";}
		}
		return "<div class='colorPicker menuContext' id='".$menuContextId."'>
					<div class='colorPickerTable'><div class='colorPickerRow'>".$colorMap."</div></div>
				</div>
				<img src='app/img/colorPicker.png' class='menuContextLauncher' for='".$menuContextId."'>";
	}

	/*
	 * Barre de pourcentage			(ex "status_bar()"))
	 */
	public static function percentBar($fillPercent, $txtBar, $txtTooltip, $orangeBar=false, $barWidth=null, $barHeight=null)
	{
		//init
		if(empty($barWidth))	{$barWidth="100%";}
		if(empty($barHeight))	{$barHeight="20px";}
		if($fillPercent>100)	{$fillPercent=100;}
		//Couleur de barre de remplissage
		if($orangeBar==true)		{$percentBarImg="percentBarO";}//avancement retard (ou autre) : barre orange
		elseif($fillPercent==100)	{$percentBarImg="percentBarB";}//terminé à 100% : bleu
		else						{$percentBarImg="percentBarG";}//en dessous de 100% : vert
		//renvoie la percentbar
		return "<div class='percentBar' style='width:".$barWidth.";height:".$barHeight.";' title=\"".$txtTooltip."\">
					<div class='percentBarContent' style='background-image:url(app/img/".$percentBarImg.".png);background-size:".(int)$fillPercent."% 100%;'>".$txtBar."</div>
				</div>";
	}

	/*
	 * Url filtré des parametres passés en Get					(ex "variables_get()")
	 */
	public static function getParamsUrl($paramsExclude=null)
	{
		//Init
		$getParamsUrl=array();
		$paramsExclude=(!empty($paramsExclude)) ? explode(",",$paramsExclude) : array();
		//Filtre les parametres passés en Get
		parse_str($_SERVER["QUERY_STRING"],$getParams);//$getParams est retourné par "parse_str()"
		foreach($getParams as $paramKey=>$paramVal){
			if(!in_array($paramKey,$paramsExclude))  {$getParamsUrl[$paramKey]=$paramVal;}
		}
		//Renvoie l'url à partir du tableau
		return "?".http_build_query($getParamsUrl);
	}

	/*
	 * Tri un tableau multidimentionnel																(ex "array_multi_sort()")
	 */
	public static function sortArray($sortedArray, $sortedField, $ascDesc="asc", $fixFirstLine=false)
	{
		// Créé un tableau temporaire avec juste la cle du tableau principal et le champ à trier
		$keyFirstResult=null;
		$tmpArray=$returnArray=array();
		foreach($sortedArray as $key=>$value){
			if($fixFirstLine==true && empty($keyFirstResult))	{$keyFirstResult=$key;}//Retient le premier resultat
			else												{$tmpArray[$key]=$value[$sortedField];}
		}
		// Tri ascendant ou descendant (avec maintient des index)
		($ascDesc=="asc")  ?  asort($tmpArray)  :  arsort($tmpArray);
		// Rajoute si besoin le premier résultat (cf. ci-dessus)
		if(isset($keyFirstResult))	{$returnArray[$keyFirstResult]=$sortedArray[$keyFirstResult];}
		// Reconstruit le tableau multidimensionnel à partir du tableau temporaire trié
		foreach($tmpArray as $key=>$value)	{$returnArray[$key]=$sortedArray[$key];}
		// Retourne le tableau trié
		return $returnArray;
	}

	/*
	 * Recherche une valeure dans un tableau multidimentionnel							(ex "array_multi_search()")
	 */
	public static function arraySearch($curTable, $searchValue)
	{
		if(is_array($curTable)){
			//Dans le tableau courant
			if(in_array($searchValue,$curTable))  {return true;}
			//Dans un sous-tableaux ? (recherche récursive)
			foreach($curTable as $tableElem){
				if(is_array($tableElem) && self::arraySearch($tableElem,$searchValue))	{return true;}
			}
			//Sinon Recherche infructueuse
			return false;
		}
	}

	/*
	 * Enlève une valeure d'un tableau
	 */
	public static function arrayUnsetVal($delVal, $array)
	{
		$keyVal=array_search($delVal,$array);
		if($keyVal!==false)	{unset($array[$keyVal]);}
		return $array;
	}

	/*
	 * Verifie si on est sur un environnement Windows
	 */
	public static function winEnv()
	{
		return (strtoupper(substr(PHP_OS,0,3))=="WIN");
	}

	/*
	 * Verifie si on est sur un environnement Linux
	 */
	public static function linuxEnv()
	{
		return preg_match("/linux/i",PHP_OS);
	}
}