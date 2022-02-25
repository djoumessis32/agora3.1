<?php
/*
 * Classe sur le formatage de texte
 */
class Txt
{
	protected static $trad=array();
	protected static $detectEncoding=null;

	/*
	 * Charge les trads si besoin
	 */
	public static function loadTrads($specificTrad=null)
	{
		//charge les traductions?
		if(empty(self::$trad)){
			if(!empty($specificTrad))			{require_once "app/trad/".$specificTrad.".php";}		//Trad spécifique
			elseif(!empty(Ctrl::$curUser->lang)){require_once "app/trad/".Ctrl::$curUser->lang.".php";}	//Param de l'user
			elseif(!empty(Ctrl::$agora->lang))	{require_once "app/trad/".Ctrl::$agora->lang.".php";}	//Param de l'espace
			else								{require_once "app/trad/francais.php";}					//Défaut
			Trad::loadTradsLang();
		}
	}

	/*
	 * Affiche un text traduit (exple: Txt::trad('dossier_racine')")
	 */
	public static function trad($keyTrad, $addSlashes=false)
	{
		//charge les traductions?
		self::loadTrads();
		//renvoie la trad / le $keyTrad
		if(self::isTrad($keyTrad) && $addSlashes==false)	{return self::$trad[$keyTrad];}
		elseif(self::isTrad($keyTrad) && $addSlashes==true)	{return addslashes(self::$trad[$keyTrad]);}
		else												{return $keyTrad."*";}
	}

	/*
	 * Verifie si une traduction existe
	 */
	public static function isTrad($keyLang)
	{
		//charge les traductions?
		self::loadTrads();
		//renvoie le résultat
		return (isset(self::$trad[$keyLang]));
	}

	/*
	 * Texte vers tableau : @@1@@2@@3@@ => array("1","2","3")
	 */
	public static function txt2tab($text)
	{
		return (!empty($text) && !is_array($text)) ? explode("@@",trim($text,"@@")) : array();
	}
	
	/*
	 * Tableau vers texte : array("1","2","3") => @@1@@2@@3@@
	 */
	public static function tab2txt($array)
	{
		if(is_array($array)){
			$array=array_filter($array);//suppr les elements vides
			if(!empty($array))	{return "@@".implode("@@",$array)."@@";}
		}
	}

	/*
	 * Reduction d'un texte			(ex "text_reduit")
	 */
	public static function reduce($text, $maxCaracNb=200)
	{
		if(strlen($text)>$maxCaracNb)
		{
			$textDisplayed=strip_tags($text,"<img><br>");
			$maxCaracNb+=strlen($textDisplayed)-strlen(strip_tags($text));//On enleve tous les "tags" html dans la compabilisation du nb de caractères..
			$text=substr($textDisplayed,0,$maxCaracNb);//On enleve les balises (sauf <img>) puis réduit la taile du texte
			if(strrpos($text," ")>1)	{$text=substr($text,0,strrpos($text," "));}//On enlève le dernier mot tronqué auquel cas
			$text=rtrim($text,",")."...";
		}
		return $text;
	}

	/*
	 * Supprime les caracteres speciaux d'une chaine de caracteres												(ex "suppr_carac_spe()")
	 * exemple de $scope avec "L'été!":  download=>"L_été!"  mini=>"L'ete!"  normal=>"L'ete"  maxi=>"L_ete"
	 */
	public static function clean($text, $scope="normal", $replaceBy="_")
	{
		//Enleve les balide éventuelle..
		$text=strip_tags($text);
		// Remplace les caractères pour un téléchargement de fichier/dossier (ex "winFile")
		if($scope=="download")    {$text=str_replace(array('/','\\','"','\'',':','*','?','<','>','|'), $replaceBy, htmlspecialchars_decode($text));}
		// Remplace les caractères accentués et autres caractères spéciaux
		else
		{
			//Remplace les caractères accentués ou assimilés
			$text=str_replace(["á","à","â","ä"], "a", $text);
			$text=str_replace(["é","è","ê","ë"], "e", $text);
			$text=str_replace(["í","ì","î","ï"], "i", $text);
			$text=str_replace(["ó","ò","ö","ô"], "o", $text);
			$text=str_replace(["ú","ù","ü","û"], "u", $text);
			$text=str_replace("ç", "c", $text);
			$text=str_replace("ñ", "n", $text);
			//Remplace les caracteres spéciaux
			if($scope=="normal" || $scope=="maxi")
			{
				$carac_ok=($scope=="normal")  ?  array(" ","-",".","_","'","(",")","[","]")  :  array("-",".","_");
				for($i=0; $i<strlen($text); $i++){
					if(!preg_match("/[0-9a-z]/i",$text[$i]) && !in_array($text[$i],$carac_ok))	{$text[$i]=$replaceBy;}
				}
				$text=str_replace($replaceBy.$replaceBy, $replaceBy, $text);
			}
		}
		return trim($text);
	}

	/*
	 * Texte en majuscule (ex "majuscule()")
	 */
	public static function maj($text)
	{
		return strtoupper(self::clean($text,"mini"));
	}

	/*
	 * Encode une chaine en UTF-8 ?				(ex "convert_utf8()")
	 */
	public static function utf8Encode($text)
	{
		if(static::$detectEncoding===null)	{static::$detectEncoding=function_exists("mb_detect_encoding");}
		return (static::$detectEncoding==false || mb_detect_encoding($text,"UTF-8",true))  ?  $text  :  utf8_encode($text);
	}

	/*
	 * Formate une date puis encode si besoin en UTF-8				(ex "formatime()")
	 */
	public static function formatime($format, $timestamp)
	{
		return self::utf8Encode(strftime($format,$timestamp));
	}

	/*
	 * Affichage d'une date													(ex "temps()")
	 * $timeBegin & $timeEnd : Timestamp unix ou format DateTime
	 * $format => normal / full / mini / date
	 */
	public static function displayDate($timeBegin, $format="normal", $timeEnd=null)
	{
		if(!empty($timeBegin) || !empty($timeEnd))
		{
			//init
			$formatEnd="";
			if(!is_numeric($timeBegin))						{$timeBegin=strtotime($timeBegin);}
			if(!is_numeric($timeEnd) && !empty($timeEnd))	{$timeEnd=strtotime($timeEnd);}
			$imgSeparator=" &nbsp;<img src='app/img/arrowRight.png'>&nbsp;";
			// Format du mois ..et de l'année si != de l'année courante
			$formatMonthYear=($format=="full") ? "%B" : "%b";
			if(date("y",$timeBegin)!=date("y") || (!empty($timeEnd) && date("y",$timeEnd)!=date("y"))){
				$formatMonthYear.=($format=="mini" || ($format!="full" && !empty($timeEnd)))  ?  " %y"  :  " %Y";
			}
			//Format du jour et de l'heure
			$hourSeparation=self::trad("separateur_horaire");
			$formatDayOfMonth=(Tool::winEnv()) ? "%#d" : "%e"; //1-31
			$formatHM="%k".$hourSeparation."%M";
			// Jour debut!=jour fin  OU  heure debut!=heure fin
			$dayBeginEnd=(!empty($timeEnd) && date("ymd",$timeBegin)!=date("ymd",$timeEnd))  ?  true  :  false;
			$hourBeginEnd=(!empty($timeEnd) && date("H:i",$timeBegin)!=date("H:i",$timeEnd))  ?  true  :  false;

			//Format normal (menu element)
			if($format=="normal"){
				$formatDay=$formatDayOfMonth." ".$formatMonthYear;
				$formatBegin=$formatDay." ".$formatHM;								//8 fév. (15) 11h30
				if($dayBeginEnd==true)		{$formatEnd=$imgSeparator.$formatBegin;}//8 fév. (15) 11h30 > 15 mars (15) 17h30
				elseif($hourBeginEnd==true)	{$formatEnd="-".$formatHM;}				//8 fév. (15) 11h30-12h30
			}
			//Format full (detail evenement)
			if($format=="full"){
				$formatDay="%A ".$formatDayOfMonth." ".$formatMonthYear;
				$formatBegin=$formatDay." ".$formatHM;								//lundi 8 fév. (2015) 11h30
				if($dayBeginEnd==true)		{$formatEnd=$imgSeparator.$formatBegin;}//lundi 8 fév. (2015) 11h30 > mer. 15 mars (2015) 17h30
				elseif($hourBeginEnd==true)	{$formatEnd="-".$formatHM;}				//lundi 8 fév. (2015) 11h30-12h30
			}
			//Format mini (evenement dans agenda)
			elseif($format=="mini"){
				if($dayBeginEnd==true)		{$formatBegin=$formatDayOfMonth." ".$formatMonthYear;	$formatEnd=$imgSeparator.$formatBegin;}	//8 fev. (15) > 15 mars
				elseif($hourBeginEnd==true)	{$formatBegin=$formatHM;								$formatEnd="-".$formatBegin;}			//11h30-12h30
				else						{$formatBegin=$formatHM;}																		//11h30
			}
			//Format date uniquement (element affiché en mode liste)
			elseif($format=="date"){
				$formatBegin=$formatDay=$formatDayOfMonth." ".$formatMonthYear;	//8 fév. (2015)
				if($dayBeginEnd==true)	{$formatEnd=$imgSeparator.$formatBegin;}//8 fév. (2015) > 15 mars (2015)
			}
			//Format date complet
			elseif($format=="dateFull"){
				$formatBegin=$formatDay="%A ".$formatDayOfMonth." ".$formatMonthYear;	//lundi 8 fév. (2015)
				if($dayBeginEnd==true)	{$formatEnd=$imgSeparator.$formatBegin;}		//lundi 8 fév. (2015) > mer. 15 mars (2015)
			}
			//Format date mini
			elseif($format=="dateMini"){
				$formatBegin=$formatDay="%d/%m/%Y";// 8/02/2015
				if($dayBeginEnd==true)	{$formatEnd=$imgSeparator.$formatBegin;}// 8/02/2015 > 15/03/2015
			}

			//Formate une date/heure locale avec la configuration locale
			$timeTxt=null;
			if(!empty($timeBegin))	{$timeTxt=strftime($formatBegin, $timeBegin);}
			if(!empty($timeEnd))	{$timeTxt.=(empty($timeBegin)?Txt::trad("fin"):null).strftime($formatEnd, $timeEnd);}

			//Enleves les minutes "00" ("12h00" -> "12h"), puis efface les heures " 0h" (modTasks & co) 
			$timeTxt=str_replace($hourSeparation."00", $hourSeparation, $timeTxt);
			$timeTxt=str_replace(" 0".$hourSeparation, "", $timeTxt);

			//Affiche "Aujourd'hui" ?
			if(preg_match("/(normal|full|date)/i",$format) && date("Ymd")==date("Ymd",time()))
				{$timeTxt=str_replace(strftime($formatDay), self::trad("aujourdhui"), $timeTxt);}

			//On renvoie le résultat (encodé en UTF-8 ?)
			return static::utf8Encode($timeTxt);
		}
	}

	/*
	 * Formatage d'une date
	 * Exple : "2050-12-31 12:50:00" => "31/12/2050"
	 */
	public static function formatDate($dateValue, $inFormat, $outFormat, $emptyHourNull=false)
	{
		$dateValue=trim($dateValue);
		$formatList=["dbDatetime"=>"Y-m-d H:i", "dbDate"=>"Y-m-d", "inputDatetime"=>"d/m/Y H:i", "inputDate"=>"d/m/Y", "inputHM"=>"H:i", "time"=>"U"];
		if(!empty($dateValue) && array_key_exists($inFormat,$formatList) && array_key_exists($outFormat,$formatList))
		{
			//Formate la date d'entrée
			if($inFormat=="inputDatetime" && strlen($dateValue)<16)		{$dateValue.=" 00:00";}//Ajoute les minutes/secontes si besoin, sinon $date retourne false..
			elseif($inFormat=="dbDatetime" && strlen($dateValue)>16)	{$dateValue=substr($dateValue,0,16);}//enlève les microsecondes si besoin, sinon $date retourne false..
			$date=DateTime::createFromFormat($formatList[$inFormat], $dateValue);
			//Formate la date de sortie
			if(is_object($date)){
				$return=$date->format($formatList[$outFormat]);
				if($outFormat=="inputHM" && $return=="00:00" && $emptyHourNull==true)	{$return=null;}
				return $return;
			}
		}
	}

	/*
	 * Validation de Formulaire : Inputs "hidden" de base (Ctrl, Action, formValidate) & bouton "submit"
	 */
	public static function formValidate($tradSubmit="valider", $isMainButton=true, $buttonAlternativeTrad=null)
	{
		return "<span class='".($isMainButton==true?'formValidateMain':'')."'>
					<input type='hidden' name='ctrl' value=\"".Req::$curCtrl."\">
					<input type='hidden' name='action' value=\"".Req::$curAction."\">
					<input type='hidden' name='formValidate' value='1'>
					<button type='submit' name='submitAction' value='default'>".self::trad($tradSubmit)."</button>".
					(Req::isParam("targetObjId")  ?  "<input type='hidden' name='targetObjId' value=\"".Req::getParam("targetObjId")."\">"  :  null).
					($buttonAlternativeTrad!=null  ?  " &nbsp; <button type='submit' name='submitAction' value='alternative'><i>".$buttonAlternativeTrad."</i></button>"  :  null)
				."</span>";
	}

	/*
	 * Menu de sélection de la langue
	 */
	public static function menuTrad($typeConfig, $selectedLang=null)
	{
		// Langue "francais" par défaut
		if(empty($selectedLang))	{$selectedLang="francais";}
		//Ouvre le dossier des langues & init le "Onchange"
		$onchange=($typeConfig=="install")  ?  "redir('?ctrl=".Req::$curCtrl."&action=".Req::$curAction."&tradInstall='+this.value);"  :  "$('.menuTradIcon').attr('src','app/trad/'+this.value+'.png');";
		// Affichage
		$menuLangOptions=null;
		foreach(scandir("app/trad/") as $tmpFileLang){
			if(strstr($tmpFileLang,".php")){
				$tmpLang=str_replace(".php",null,$tmpFileLang);
				$tmpLabel=($typeConfig=="user" && $tmpLang==Ctrl::$agora->lang)  ?  $tmpLang." (".Txt::trad("par_defaut").")"  :  $tmpLang;
				$menuLangOptions.= "<option value=\"".$tmpLang."\" ".($tmpLang==$selectedLang?"selected":null)."> ".$tmpLabel."</option>";
			}
		}
		return "<select name='lang' onchange=\"".$onchange."\">".$menuLangOptions."</select> &nbsp; <img src='app/trad/".$selectedLang.".png' class='menuTradIcon'>";
	}
	
	/*
	 * Créé un identifiant/password unique, avec une certaine longueur
	 */
	public static function idUniq($length=5)
	{
		return substr(md5(uniqid(mt_rand())), 0, $length);
	}
}