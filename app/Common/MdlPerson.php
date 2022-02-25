<?php
/*
 * Modele des personnes : utilisateurs & contacts
 */
class MdlPerson extends MdlObject
{
	//Propriétés de base
	const isSelectable=true;
	//Champs obligatoires, de recherche et de tri des résultats
	public static $displayModeOptions=array("block","line");
	public static $requiredFields=array("name","firstName","login");
	public static $searchFields=array("name","firstName","adress","postalCode","city","country","skills","hobbies","function","companyOrganization","comment");
	//Formats .csv  (champ agora=>champ specifique)
	public static $csvFormats=array(
		//AGORA
		"csv_agora"=>array(
			"delimiter"=>";",
			"enclosure"=>'"',
			"fieldKeys"=>array("civility","name","firstName","companyOrganization","function","adress","postalCode","city","country","telephone","telmobile","fax","mail","website","skills","hobbies","comment","login","password")
		),
		//GMAIL
		"csv_gmail"=>array(
			"delimiter"=>",",
			"enclosure"=>"",
			"fieldKeys"=>array(
				"firstName"=>"Given Name",
				"name"=>"Family Name",
				"mail"=>"E-mail 1 - Value",
				"fax"=>"Fax",
				"telmobile"=>"Phone 1 - Value",
				"website"=>"Site Web",
				"function"=>"Fonction",
				"companyOrganization"=>"Société",
				"adress"=>"Address 1 - Street",
				"city"=>"Address 1 - City",
				"postalCode"=>"Address 1 - Postal Code",
				"country"=>"Address 1 - Country",
				"comment"=>"Notes",
				"comment"=>"Commentaires"
			)
		),
		//OUTLOOK
		"csv_outlook"=>array(
			"delimiter"=>",",
			"enclosure"=>'"',
			"fieldKeys"=>array(
				"firstName"=>"Prénom",
				"name"=>"Nom",
				"companyOrganization"=>"Société",
				"function"=>"Fonction",
				"adress"=>"Rue (domicile)",
				"city"=>"Ville (domicile)",
				"adress"=>"Code postal (domicile)",
				"country"=>"Pays (domicile)",
				"fax"=>"Fax (domicile)",
				"telephone"=>"Téléphone (domicile)",
				"telmobile"=>"Tél. mobile",
				"mail"=>"Adresse mail",
				"comment"=>"Notes",
				"website"=>"Page Web"
			)
		),
		//HOTMAIL
		"csv_hotmail"=>array(
			"delimiter"=>";",
			"enclosure"=>'"',
			"fieldKeys"=>array(
				"civility"=>"Title",
				"firstName"=>"First Name",
				"Middle Name"=>"Middle Name",
				"name"=>"Last Name",
				"companyOrganization"=>"Company",
				"Department"=>"Department",
				"function"=>"Job Title",
				"adress"=>"Home Street",
				"city"=>"Home City",
				"postalCode"=>"Home Postal Code",
				"country"=>"Home Country",
				"fax"=>"Home Fax",
				"telephone"=>"Home Phone",
				"telmobile"=>"Mobile Phone",
				"mail"=>"E-mail Address",
				"hobbies"=>"Hobby",
				"comment"=>"Notes",
				"website"=>"Web Page"
			)
		),
		//THUNDERBIRD
		"csv_thunderbird"=>array(
			"delimiter"=>",",
			"enclosure"=>"",
			"fieldKeys"=>array(
					"firstName"=>"Prénom",
					"name"=>"Nom de famille",
					"mail"=>"Première adresse électronique",
					"telephone"=>"Tél. personnel",
					"fax"=>"Fax",
					"telmobile"=>"Portable",
					"adress"=>"Adresse privée",
					"city"=>"Ville",
					"country"=>"Pays/État",
					"postalCode"=>"Code postal",
					"function"=>"Profession",
					"companyOrganization"=>"Société",
					"website"=>"Page Web 1",
					"comment"=>"Notes"
			)
		)
	);

	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct($objIdOrValues=null)
	{
		parent::__construct($objIdOrValues);
		//Tri en fonction du parametrage general (inverse le tri par défaut?)
		if(Ctrl::$agora->personsSort=="firstName" && strstr(static::$sortFields[0],"firstName")==false){
			foreach(static::$sortFields as $fieldKey=>$fieldVal)
				{static::$sortFields[$fieldKey]=(strstr($fieldVal,"firstName"))  ?  str_replace("firstName","name",$fieldVal)  :  str_replace("name","firstName",$fieldVal);}
		}
		self::$sortFields=array("firstName@@asc","firstName@@desc","civility@@asc","civility@@desc");
	}

	/*
	 * Affiche le nom/prénom  (ex "auteur()")
	 * $display : firstNameAndName / all / firstName
	 */
	public function display($display="firstNameAndName")
	{
		if(!empty($this->firstName) || !empty($this->name)){
			if($display=="firstName" && !empty($this->firstName))	{return $this->firstName;}
			elseif($display=="all")									{return $this->civility." ".$this->firstName." ".$this->name;}
			else													{return $this->firstName." ".$this->name;}
		}
		elseif($this->_id>0)	{return "<i>".Txt::trad("inconnu")."</i>";}
	}

	/*
	 * Possède une adresse : city + (adress / postalCode)
	 */
	public function hasAdress()
	{
		return (!empty($this->city) && (!empty($this->adress) || !empty($this->postalCode)));
	}

	/*
	 * Affiche les infos sur la personne
	 * $displayMode : block / line / profile / edit
	 */
	public function getFields($displayMode)
	{
		$details=null;
		//Affichage en page principale (display block/line)
		if($displayMode=="block" || $displayMode=="line")
		{
			$details.=	$this->getField("companyOrganization",$displayMode).
						$this->getField("function",$displayMode).
						$this->getField("mail",$displayMode).
						$this->getField("fullAdress",$displayMode);
		}
		//Affichage du profil (vue / édition)
		elseif($displayMode=="profile" || $displayMode=="edit")
		{
			if($displayMode=="edit")	{$details.=$this->getField("civility",$displayMode).$this->getField("name",$displayMode).$this->getField("firstName",$displayMode)."<hr class='hrGradient'>";}
			$details.=	$this->getField("mail",$displayMode).
						$this->getField("telmobile",$displayMode).
						$this->getField("telephone",$displayMode).
						$this->getField("fax",$displayMode).
						$this->getField("website",$displayMode).
						($displayMode=="edit"?"<hr class='hrGradient'>":null).
						$this->getField("adress",$displayMode).
						$this->getField("postalCode",$displayMode).
						$this->getField("city",$displayMode).
						$this->getField("country",$displayMode).
						($displayMode=="edit"?"<hr class='hrGradient'>":null).
						$this->getField("skills",$displayMode).
						$this->getField("hobbies",$displayMode).
						$this->getField("function",$displayMode).
						$this->getField("companyOrganization",$displayMode).
						$this->getField("comment",$displayMode);
		}
		return ($displayMode=="line")  ?  substr($details,0,strrpos($details,"<img src='app/img/separator.png'>"))  :  $details;//Si besoin, enleve le dernier séparateur (mais pas de "trim()"!)
	}

	/*
	 * Affiche une info sur la personne
	 */
	public function getField($field, $displayMode)
	{
		$detail=null;
		//Adresse complete (avec carte) / Adresse web /Champ unique
		if($field=="fullAdress" && $this->hasAdress())								{$detail="<a href=\"javascript:lightboxOpen('?ctrl=misc&action=PersonsMap&targetObjects[".static::objectType."]=".$this->_id."');\" title=\"".Txt::trad("localiser_carte")."\">".$this->adress." ".$this->postalCode." ".$this->city." <img src='app/img/map.png'></a>";}
		elseif($field=="website" && !empty($this->$field) && $displayMode!="edit")	{$detail="<a href=\"".(!strstr($this->$field,"://")?"http://":null).$this->$field."\" target='_blank'>".$this->$field."</a>";}
		else																		{$detail=$this->$field;}
		//"Habille" le champ en fonction du Type d'affichage
		if(!empty($detail) || $displayMode=="edit")
		{
			if($displayMode=="block")		{return "<div class='personDetail'>".$detail."</div>";}
			elseif($displayMode=="line")	{return "<div class='personDetail'>".$detail."</div> &nbsp;<img src='app/img/separator.png'>&nbsp; ";}
			elseif($displayMode=="profile" || $displayMode=="edit"){
				if($displayMode=="edit")	{$detail=($field=="comment")  ?  "<textarea name='".$field."'>".strip_tags($detail)."</textarea>"  :  "<input type='text' name='".$field."' value=\"".strip_tags($detail)."\">";}
				return "<div class='objField'><div class='fieldLabel'><img src='app/img/person/".$field.".png'> ".Txt::trad($field)."</div><div class='fieldValue'>".$detail."</div></div>";
			}
		}
	}

	/*
	 * Photo d'un contact	(ex "photo_user()")
	 */
	public function getImg($openProfileVue=false, $userSmallImg=false, $onlyImgPath=false)
	{
		if(is_file($this->pathImgThumb()))	{$personImg=$this->pathImgThumb();}//Path de l'image
		elseif($userSmallImg==true)			{$personImg="app/img/user/accesUser.png";}//Image par défaut 'small'
		else								{$personImg="app/img/user/userProfile.png";}//Image par défaut
		$personImg=($onlyImgPath==true)  ?  $personImg  :  "<img src='".$personImg."' class='personPicture'>";//renvoie le path uniquement?
		$personImg=($onlyImgPath==false && $openProfileVue==true)  ?  "<a href=\"javascript:lightboxOpen('".$this->getUrl("vue")."');\">".$personImg."</a>"  :  $personImg;//Ajoute le lien vers la vue?
		return $personImg;
	}

	/*
	 * Affiche le menu de gestion de l'image
	 */
	public function displayImgMenu()
	{
		$isFile=is_file($this->pathImgThumb());
		return Txt::trad("picture")." : <select name='personImgAction' onChange=\"this.value=='change' ? \$('#addImgPerson').fadeIn() : \$('#addImgPerson').fadeOut();\">".
					"<option>".($isFile==true?Txt::trad("garder"):null)."</option>".
					($isFile==true ? "<option value='delete'>".Txt::trad("supprimer")."</option>" : null).
					"<option value='change'>".($isFile==true?Txt::trad("image_changer"):Txt::trad("ajouter"))."</option>".
				"</select>".
				"<div id='addImgPerson' class='hide'><br><input type='file' name='personImgFile'></div>";
	}

	/*
	 * Enregistre/Supprime l'image
	 */
	public function editImg()
	{
		if(Req::isParam("personImgAction"))
		{
			// Supprime
			if(Req::getParam("personImgAction")=="delete")	{unlink($this->pathImgThumb());}
			// Ajoute / change
			if(Req::getParam("personImgAction")=="change" && File::controlType("imageResize",$_FILES["personImgFile"]["name"])){
				move_uploaded_file($_FILES["personImgFile"]["tmp_name"], $this->pathImgThumb());
				File::imageResize($this->pathImgThumb(),$this->pathImgThumb(),200);
			}
		}
	}

	/*
	 * Exporte des personnes au format spécifié
	 */
	public static function exportPersons($personObjList, $exportType)
	{
		//Init
		$fileContent=null;
		////	EXPORT CSV
		if(strstr($exportType,"csv"))
		{
			//Nom et entete du fichier
			$csv=static::$csvFormats[$exportType];
			$fileName=$exportType.".csv";
			foreach($csv["fieldKeys"] as $fieldAgora=>$fieldCsv)	{$fileContent.=$csv["enclosure"].$fieldCsv.$csv["enclosure"].$csv["delimiter"];}
			$fileContent.="\n";
			//Ajout de chaque contact
			foreach($personObjList as $tmpContact){
				foreach($csv["fieldKeys"] as $fieldAgora=>$fieldCsv){
					if($csv["enclosure"]=="'")		{$tmpContact->$fieldAgora=addslashes($tmpContact->$fieldAgora);}
					$fileContent.=(!empty($tmpContact->$fieldAgora))  ?  $csv["enclosure"].$tmpContact->$fieldAgora.$csv["enclosure"].$csv["delimiter"]  :  $csv["delimiter"];
				}
				$fileContent.="\n";
			}
		}
		////	EXPORT LDIF
		elseif($exportType=="ldif")
		{
			//Init
			$fileName="contact.ldif";
			//Ajout de chaque contact
			foreach($personObjList as $tmpContact)
			{
				$fileContent.="dn: cn=".$tmpContact->firstName." ".$tmpContact->name."\n";
				$fileContent.="objectclass: top\n";
				$fileContent.="objectclass: person\n";
				$fileContent.="objectclass: organizationalPerson\n";
				$fileContent.="cn: ".$tmpContact->firstName." ".$tmpContact->name."\n";
				$fileContent.="givenName: ".$tmpContact->firstName."\n";
				$fileContent.="sn: ".$tmpContact->name."\n";
				if(!empty($tmpContact->mail))				{$fileContent.="mail: ".$tmpContact->mail."\n";}
				if(!empty($tmpContact->telephone))			{$fileContent.="homePhone: ".$tmpContact->telephone."\n";}
				if(!empty($tmpContact->telephone))			{$fileContent.="telephonenumber: ".$tmpContact->telephone."\n";}
				if(!empty($tmpContact->fax))				{$fileContent.="fax: ".$tmpContact->fax."\n";}
				if(!empty($tmpContact->telmobile))			{$fileContent.="mobile: ".$tmpContact->telmobile."\n";}
				if(!empty($tmpContact->adress))				{$fileContent.="homeStreet: ".$tmpContact->adress."\n";}
				if(!empty($tmpContact->city))				{$fileContent.="mozillaHomeLocalityName: ".$tmpContact->city."\n";}
				if(!empty($tmpContact->postalCode))			{$fileContent.="mozillaHomePostalCode: ".$tmpContact->postalCode."\n";}
				if(!empty($tmpContact->country))				{$fileContent.="mozillaHomeCountryName: ".$tmpContact->country."\n";}
				if(!empty($tmpContact->companyOrganization)){$fileContent.="company: ".$tmpContact->companyOrganization."\n";}
				if(!empty($tmpContact->function))			{$fileContent.="title: ".$tmpContact->function."\n";}
				if(!empty($tmpContact->comment))			{$fileContent.="description: ".$tmpContact->comment."\n";}
				$fileContent.="\n";
			}
		}
		/////   LANCEMENT DU TELECHARGEMENT
		File::download($fileName, null, $fileContent);
	}

	/*
	 * Connexion a ldap
	 */
	public static function ldapConnect($ldapServer=null, $ldapServerPort=null, $ldapUserLogin=null, $ldapUserPassword=null, $displayNotif=true)
	{
		// la fonction de connexion LDAP est activée ?
		if(!function_exists("ldap_connect"))	{return false;}
		// Config
		if(empty($ldapServer))			{$ldapServer		=Ctrl::$agora->ldap_server;}
		if(empty($ldapServerPort))		{$ldapServerPort	=Ctrl::$agora->ldap_server_port;}
		if(empty($ldapUserLogin))		{$ldapUserLogin		=Ctrl::$agora->ldap_admin_login;}
		if(empty($ldapUserPassword))	{$ldapUserPassword	=Ctrl::$agora->ldap_admin_pass;}
		// Connexion au serveur LDAP
		$ldapConnection=@ldap_connect($ldapServer, $ldapServerPort);
		ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);	//Utiliser LDAP Protocol V3! (v2 par défaut)
		ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);		//Pour Active Directory
		// Identification au serveur LDAP en tant qu'admin + retourne la connexion ldap si c'est ok
		$ldapIdentification=@ldap_bind($ldapConnection, $ldapUserLogin, $ldapUserPassword);
		if($ldapIdentification==false && $displayNotif==true)	{Ctrl::addNotif("ldap_connexion_erreur");}
		return ($ldapIdentification==false) ? false : $ldapConnection;
	}

	/*
	 * RECUPERES DES PERSONNES DE L'ANNUAIRE LDAP  (exple de $searchFilter -> "(&(samaccountname=MONLOGIN)(cn=*))" )
	 */
	public static function ldapSearch($getLoginPassword=false, $searchMode="importArray", $searchFilter="(cn=*)")
	{
		$ldapConnection=self::ldapConnect();
		if($ldapConnection!=false)
		{
			// Champs Agora => Attributs LDAP correspondants (Toujours en minucule!)
			$ldapAttributes=array(
				"civility"			=>array("designation"),
				"name"				=>array("sn","name","lastname"),//Sur ActiveDirectory : "sn" est avant "name"
				"firstName"			=>array("firstname","givenname","knownas"),
				"mail"				=>array("mail"),
				"telmobile"			=>array("mobile","mobiletelephonenumber"),
				"telephone"			=>array("telephonenumber","homephone","hometelephonenumber"),
				"fax"				=>array("fax","facsimiletelephonenumber"),
				"adress"			=>array("postaladdress","homepostaladdress","streetaddress","street"),
				"postalCode"		=>array("postalcode","homepostalcode"),
				"city"				=>array("localityname","l"),
				"companyOrganization"=>array("company","department","organizationname","organizationalunitname","o","ou"),
				"function"			=>array("title","titleall"),
				"comment"			=>array("description"));
			// Champs Agora  => On ajoute l'id/password en cas d'import d'utilisateur
			if($getLoginPassword==true){
				$ldapAttributes["login"]=array("uid","samaccountname");
				$ldapAttributes["password"]=array("userpassword","password");
			}
			// Récupere les users LDAP
			$ldapSearch=@ldap_search($ldapConnection, Ctrl::$agora->ldap_base_dn, $searchFilter);
			if($ldapSearch!=false)
			{
				$searchPersons=ldap_get_entries($ldapConnection, $ldapSearch);
				if($searchPersons["count"]>0)
				{
					////	Champs Agora à utiliser
					$importedFields=array();
					foreach($searchPersons as $userAttributes){
						//Pour chaque champs de l'utilisateur importé : vérif si le champ ldap correspond à un champ Agora
						foreach($ldapAttributes as $agoraField=>$tmpLdapAttributes){
							foreach($tmpLdapAttributes as $ldapAttribute){
								if(!empty($userAttributes[$ldapAttribute][0]) && !in_array($agoraField,$importedFields))   {$importedFields[]=$agoraField;}
							}
						}
					}
					////	Attributs / valeurs de chaque contact
					$importedPersons=array();
					foreach($searchPersons as $userKey=>$userAttributes)
					{
						if(is_numeric($userKey))
						{
							$importedPerson=array();
							foreach($ldapAttributes as $agoraField=>$tmpLdapAttributes)
							{
								//Cle du tableau d'entête correspondant au champ visé (tableau d'import: numéro de colonne du champ agora || import direct : nom du champ agora)
								$fieldCpt=array_search($agoraField,$importedFields);
								$fieldKey=($searchMode=="importArray") ? $fieldCpt : $agoraField;
								// Ajoute la valeur si l'attribut ldap correspond à un champ de l'agora (..et qu'il n'a pas déjà été ajouté avec un autre attribut)
								foreach($tmpLdapAttributes as $ldapAttribute){
									if(isset($userAttributes[$ldapAttribute][0]))   {$importedPerson[$fieldKey]=$userAttributes[$ldapAttribute][0];}
								}
								//Champ non spécifié : "null"
								if(empty($importedPerson[$fieldKey]))   {$importedPerson[$fieldKey]="";}//pas de null
								//Si besoin, re-tri les champs en fonction du numéro de colonne du champ agora
								if($searchMode=="importArray")	{ksort($importedPerson);}
							}
							//Ajoute les Valeurs à l'user temporaire
							$importedPersons[]=$importedPerson;
						}
					}
					//Ferme la connexion et retourne le résultat
					ldap_close($ldapConnection);
					return array("headerFields"=>$importedFields, "ldapPersons"=>$importedPersons);
				}
			}
		}
	}
}