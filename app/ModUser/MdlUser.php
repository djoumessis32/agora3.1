<?php
/*
 * Modele des utilisateurs
 */
class MdlUser extends MdlPerson
{
	//Propriétés de base
	const moduleName="user";
	const objectType="user";
	const dbTable="ap_user";
	//Propriésé bdd
	const hasAccessRight=false;
	//Propriétés d'IHM
	const hasShortcut=false;
	const hasAttachedFiles=false;
	const hasNotifMail=false;
	const nbObjectsByPage=50;
	//Valeurs en cache (calculées qu'une fois)
	private $_userSpaces=null;
	private $_isAdminCurSpace=null;
	private $_usersVisibles=null;
	private $_messengerEnabled=null;
	//Champs obligatoires et de tri des résultats
	public static $requiredFields=array("name","login");
	public static $sortFields=array("name@@asc","name@@desc","firstName@@asc","firstName@@desc","civility@@asc","civility@@desc","postalCode@@asc","postalCode@@desc","city@@asc","city@@desc","country@@asc","country@@desc","function@@asc","function@@desc","companyOrganization@@asc","companyOrganization@@desc");

	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct($objIdOrValues=null)
	{
		if($objIdOrValues=="isGuest")	{$objIdOrValues=array("calendarDisabled"=>1,"isGuest"=>true);}//guest
		parent::__construct($objIdOrValues);
	}

	/*
	 * Photo d'un utilisateur
	 */
	public function pathImgThumb()
	{
		return PATH_MOD_USER.$this->_id."_thumb.jpg";
	}
	
	/*
	 * SURCHARGE VERIF : User courant est l'auteur de l'objet ?  (ex "is_auteur()")
	 */
	public function isAutor(){
		return ($this->_id==Ctrl::$curUser->_id && Ctrl::$curUser->isUser());
	}
	/*
	 * Verifie s'il s'agit d'un utilisateur.. et non un invité
	 */
	public function isUser()
	{
		return (!empty($this->_id));
	}
	/*
	 * Verifie s'il s'agit d'un administrateur général
	 */
	public function isAdminGeneral()
	{
		return (!empty($this->generalAdmin));
	}

	/*
	 * Administrateur de l'espace courant?
	 */
	public function isAdminCurSpace()
	{
		if($this->_isAdminCurSpace===null)	{$this->_isAdminCurSpace=(Ctrl::$curSpace->userAccessRight($this)==2) ? true : false;}
		return $this->_isAdminCurSpace;
	}

	/*
	 * SURCHARGE : Droit d'accès à l'objet						(cf. ex "controle_affichage_utilisateur()")
	 */
	public function accessRight()
	{
		if($this->_accessRight===null){
			$this->_accessRight=parent::accessRight();//Droit par défaut
			//Pas d'accès : Ajoute l'accès en lecture si l'user en question fait partie des "usersVisibles" de l'user courant
			if(empty($this->_accessRight)){
				foreach(Ctrl::$curUser->usersVisibles() as $tmpUser)	{ if($this->_id==$tmpUser->_id){$this->_accessRight=1;} }
			}
		}
		return $this->_accessRight;
	}

	/*
	 * SURCHARGE : Droit d'édition
	 */
	public function editRight()
	{
		return ($this->accessRight()==3);
	}

	/*
	 * SURCHARGE : Droit de suppression
	 */
	public function deleteRight()
	{
		//Accès total  &&  Autre user que celui en cours  &&  Pas dernier adminGeneral
		if(parent::fullRight() && $this->_id!=Ctrl::$curUser->_id){
			if($this->isAdminGeneral()==false || Db::getVal("SELECT count(*) FROM ap_user WHERE generalAdmin=1")>1)  {return true;}
		}
	}

	/*
	 * DROIT DE DESAFFECTATION DE L'ESPACE
	 */
	public function deleteFromCurSpaceRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() &&  Ctrl::$curSpace->allUsersAffected()==false);
	}
	
	/*
	 * Le droit "admin general" peut être édité par l'user courant?
	 */
	public function editAdminGeneralRight()
	{
		return (Ctrl::$curUser->isAdminGeneral() && Ctrl::$curUser->_id!=$this->_id);
	}

	/*
	 * Droit d'envoyer des invitations, sur un espace donné ?
	 */
	public function sendInvitationRight($objSpace=null)
	{
		if($objSpace==null)	{$objSpace=Ctrl::$curSpace;}
		return (function_exists("mail") && ($this->isAdminCurSpace() || (!empty($objSpace->usersInvitation) && $this->isUser())));
	}

	/*
	 * Livecounter et messenger actif pour l'user ?
	 */
	public function messengerEnabled()
	{
		if($this->_messengerEnabled===null)
			{$this->_messengerEnabled=($this->isUser() && empty(Ctrl::$agora->messengerDisabled) && Db::getVal("SELECT count(*) FROM ap_userMessenger WHERE _idUserMessenger=".$this->_id)>0)  ?  true  :  false;}
		return $this->_messengerEnabled;
	}

	/*
	 * Droit d'ajouter un groupe d'utilisateurs : utilisateur lambda (si option activé) ou admin de l'espace courant			(ex "droit_ajout_groupe()")
	 */
	public function addGroupRight($objSpace=null)
	{
		if($objSpace==null)	{$objSpace=Ctrl::$curSpace;}
		return ($objSpace->moduleOptionEnabled("user","ajout_utilisateurs_groupe") || $this->isAdminCurSpace());
	}

	/*
	 * STATIC SQL (SURCHARGE) : selectionne les users de l'espace / de tout le site					(ex "sql_utilisateurs_espace()", $_SESSION["displayUsers"] = $_SESSION["cfg"]["espace"]["affichage_users"])
	 */
	public static function sqlDisplayedObjects($containerObj=null, $keyId=null)
	{
		if($_SESSION["displayUsers"]=="all")	{return "1";}
		else{
			$_idUsers=array_merge([0], Ctrl::$curSpace->getUsers("ids"));//ajoute user "0" au cas ou ya personne..
			return "_id IN (".implode(',',$_idUsers).")";
		}
	}

	/*
	 * Espaces auxquels est affecté l'utilisateur					(ex "espaces_affectes_user()")
	 */
	public function getSpaces($return="objects")
	{
		//Initialise la liste des objets "space"
		if($this->_userSpaces===null)
		{
			$sqlOrderBy=(isset($_SESSION["_idSpace"]))  ?  "ORDER BY _id=".$_SESSION["_idSpace"]." DESC, name ASC"  :  "ORDER BY name ASC";
			if($this->isAdminGeneral())	{$sqlQuery="SELECT * FROM ap_space ".$sqlOrderBy;}
			else{
				$sqlUsers=($this->isUser())  ?  "T2._idUser=".$this->_id." OR T2.allUsers=1"  :  "T1.public=1";
				$sqlQuery="SELECT DISTINCT T1.* FROM ap_space T1 LEFT JOIN ap_joinSpaceUser T2 ON T1._id=T2._idSpace WHERE ".$sqlUsers." ".$sqlOrderBy;
			}
			$this->_userSpaces=Db::getObjTab("space",$sqlQuery);
		}
		// Retourne un tableau d'objets  OU  d'identifiants
		if($return=="objects")	{return $this->_userSpaces;}
		else{
			$tabIds=array();
			foreach($this->_userSpaces as $objSpace)    {$tabIds[]=$objSpace->_id;}
			return $tabIds;
		}
	}

	/*
	 * SURCHARGE : Supprime un user définitivement (Admin général uniquement!)
	 */
	public function delete()
	{
		if($this->deleteRight())
		{
			if(is_file($this->pathImgThumb()))	{unlink($this->pathImgThumb());}
			// Suppression des tables de jointures et tables annexes
			Db::query("DELETE FROM ap_joinSpaceUser			WHERE _idUser=".$this->_id);
			Db::query("DELETE FROM ap_userMessenger			WHERE _idUserMessenger=".$this->_id." OR _idUser=".$this->_id);
			Db::query("DELETE FROM ap_objectTarget			WHERE target=".Db::format("U".$this->_id));
			Db::query("DELETE FROM ap_userLivecouter		WHERE _idUser=".$this->_id);
			Db::query("DELETE FROM ap_userMessengerMessage	WHERE _idUser=".$this->_id);
			Db::query("DELETE FROM ap_userPreference		WHERE _idUser=".$this->_id);
			//Suppr l'agenda
			$objCalendar=new MdlCalendar(Db::getVal("SELECT _id FROM ap_calendar WHERE _idUser=".$this->_id." AND type='user'"));
			$objCalendar::$persoCalendarDeleteRight=true;//cf. "deleteRight()" du "MdlCalendar"
			$objCalendar->delete();
			//Suppr l'user
			parent::delete();
		}
	}

	/*
	 * SURCHARGE : désaffecte/Supprime un user d'un espace (Admin d'espace uniquement!)
	 */
	public function deleteFromCurSpace($_idSpace)
	{
		if(Ctrl::$curUser->isAdminCurSpace()){
			Db::query("DELETE FROM ap_joinSpaceUser WHERE _idUser=".$this->_id." AND _idSpace=".(int)$_idSpace);
			if(Db::getVal("SELECT count(*) FROM ap_joinSpaceUser WHERE _idSpace=".(int)$_idSpace." AND allUsers=1")>0)	{Ctrl::addNotif("USER_tous_user_affecte_espace");}
		}
	}

	/*
	 *  Users que l'utilisateur peut voir (autre que lui-même?), tous espaces confondu						(ex "users_visibles()")
	 * $options : "withCurUser", "withMail"
	 */
	public function usersVisibles($options=null)
	{
		//Initialise la liste des objets "user"
		if($this->_usersVisibles===null){
			//Espaces visibles par l'user
			$usersIds=[0];//pseudo user
			foreach($this->getSpaces() as $objSpace)	{$usersIds=array_merge($usersIds,$objSpace->getUsers("ids"));}//Users de tous les espaces de l'utilisateur courant
			$this->_usersVisibles=Db::getObjTab("user", "SELECT * FROM ap_user WHERE _id IN (".implode(",",$usersIds).") ORDER BY ".Ctrl::$agora->personsSort);
		}
		//Filtre les résultats?
		$returnedResults=[];
		foreach($this->_usersVisibles as $tmpUser){
			//Passe au suivant si :  on veut pas de l'user courant  ||  on veut uniquement les users avec mail
			if((stristr($options,"withCurUser")==false && $tmpUser->_id==Ctrl::$curUser->_id)  ||  (stristr($options,"withMail") && empty($tmpUser->mail)))  {continue;}
			$returnedResults[$tmpUser->_id]=$tmpUser;
		}
		//Retourne les résultats filtrés
		return $returnedResults;
	}

	/*
	 * Autres users que l'utilisateur peut voir sur le livecounter & messenger : deconnectes + connectés au meme moment			(ex "users_livecounter()")
	 */
	public function livecounterUsersIds()
	{
		$idsUsers=[0];//pseudo user
		foreach($this->usersVisibles() as $tmpObj)  {$idsUsers[]=$tmpObj->_id;}
		return Db::getCol("SELECT DISTINCT T1._id FROM ap_user T1, ap_userMessenger T2 WHERE T1._id=T2._idUserMessenger AND T1._id IN (".implode(",",$idsUsers).") AND (T2._idUser='".$this->_id."' OR T2.allUsers='1')");
	}

	/*
	 * Autres users que l'utilisateur peut voir sur le livecounter & messenger : connectés au meme moment						(ex "users_connectes()")
	 */
	public function livecounterUsersConnected()
	{
		$idsUsers="0,".implode(",",$this->livecounterUsersIds());
		return Db::getObjTab("user", "SELECT DISTINCT T1.* FROM ap_user T1, ap_userLivecouter T2 WHERE T1._id=T2._idUser AND T1._id IN (".trim($idsUsers,",").") AND T2.date > '".(time()-LIVECOUNTER_TIMEOUT)."'");
	}

	/*
	 * Lien vers la fiche d'un utilisateur, depend de l'user courant!				(ex "popup_user()")
	 */
	public function displayUserVue()
	{
		if(Ctrl::$curUser->isUser())    {return "onclick=\"lightboxOpen('".$this->getUrl("vue")."');\"";}
	}

	/*
	 * CurUser : réinitialisation du mot de passe
	 */
	public static function reinitPassword()
	{
		if(Req::isParam(["_idNewPassword","_idUser"]))
		{
			$tmpUser=Db::getLine("SELECT * FROM ap_user WHERE _idNewPassword=".Db::formatParam("_idNewPassword")." AND _id=".Db::formatParam("_idUser"));
			//"id_password" expiré/valide
			if(empty($tmpUser))    {Ctrl::addNotif("PASS_OUBLIE_idNewPassword_expire");}
			else{
				// On demande un nouveau mot de passe  (récup les $_GET avec $_SERVER["QUERY_STRING"])
				if(Req::isParam("newPassword")==false)    {Ctrl::addJsTrigger("lightboxPrompt(\"".Txt::trad("PASS_OUBLIE_prompt_changer_pass")."\", \"redir('?".$_SERVER["QUERY_STRING"]."&newPassword='+$('.promptInputText').val());\");");}
				// Enregistrement du nouveau mot de passe
				else{
					Db::query("UPDATE ap_user SET password=".Db::format(self::passwordSha1(Req::getParam("newPassword"))).", _idNewPassword=null WHERE _id=".(int)$tmpUser["_id"]);
					$_COOKIE["AGORAP_LOG"]=$tmpUser["login"];
					Ctrl::addNotif("PASS_OUBLIE_password_reinitialise","success");
				}
			}
		}
	}

	/*
	 * CurUser : confirm invitation
	 */
	public static function confirmInvitation()
	{
		if(Req::isParam(["_idInvitation","mail"]))
		{
			$tmpInvit=Db::getLine("SELECT * FROM ap_invitation WHERE _idInvitation=".Db::formatParam("_idInvitation")." AND mail=".Db::formatParam("mail"));
			//"_idInvitation" expiré?  /  On demande une validation du mot de passe avant création de l'user
			if(empty($tmpInvit))					{Ctrl::addNotif("USER_idInvitation_expire");}
			elseif(Req::isParam("password")==false)	{Ctrl::addJsTrigger("lightboxPrompt(\"".Txt::trad("USER_invitationConfirmPassword")."\", \"redir('?".$_SERVER["QUERY_STRING"]."&password='+$('.promptInputText').val());\", \"text\", \"".$tmpInvit["password"]."\");");}
			//Enregistrement le nouvel utilisateur avec le password confirmé
			elseif(static::UsersNbOk())
			{
				$newUser=new MdlUser();
				$sqlProperties="name=".Db::format($tmpInvit["name"]).", firstName=".Db::format($tmpInvit["firstName"]).", mail=".Db::format($tmpInvit["mail"]);
				$newUser=$newUser->createUpdate($sqlProperties, $tmpInvit["mail"], Req::getParam("password"), $tmpInvit["_idSpace"]);
				if(is_object($newUser)){
					Db::query("DELETE FROM ap_invitation WHERE _idInvitation=".Db::format($tmpInvit["_idInvitation"]));
					$_COOKIE["AGORAP_LOG"]=$tmpInvit["mail"]; //Préremplis le champ 'login'
					Ctrl::addNotif("USER_invitation_valide","success");
				}
			}
		}
	}

	/*
	 * Nombre d'utilisateurs maxi déjà atteint?		(ex "nb_users_depasse()")
	 */
	public static function UsersNbOk($addNotif=true)
	{
		if(defined("limite_nb_users") && Db::getVal("SELECT count(*) FROM ap_user") < limite_nb_users)	{return true;}
		else{
			if($addNotif==true)  {Ctrl::addNotif(Txt::trad("MSG_NOTIF_nb_users")." ".limite_nb_users);}//Message de notif?
			return false;
		}
	}

	/*
	 * SURCHARGE : Ajout/Modif d'utilisateur
	 */
	public function createUpdate($sqlProperties, $login=null, $password=null, $spaceId=null)
	{
		////	Controles : Nb d'users atteint ? Login existe déjà ?
		if(static::UsersNbOk()==false)	{return false;}
		if(Db::getVal("SELECT count(*) FROM ap_user WHERE login=".Db::format($login)." AND _id!=".$this->_id)>0)	{Ctrl::addNotif(Txt::trad("MSG_NOTIF_user_existdeja")." (login : ".$login.")");  return false;}
		////	Ajoute le login, le password? si l'agenda perso est désactivé?
		$sqlProperties=trim(trim($sqlProperties),",");
		$sqlProperties.=", login=".Db::format($login);
		if(!empty($password))	{$sqlProperties.=", password=".Db::format(MdlUser::passwordSha1($password));}
		////	Create/Update!
		$reloadedObj=parent::createUpdate($sqlProperties);
		////	Nouvel User : ajoute le parametrage du messenger, l'agenda perso, et si besoin affecte l'user à un Espace.
		if($reloadedObj->isNew()){
			Db::query("INSERT INTO ap_userMessenger SET _idUserMessenger=".$reloadedObj->_id.", allUsers=1");
			Db::query("INSERT INTO ap_calendar SET _idUser=".$reloadedObj->_id.", type='user'");//créé l'agenda, même si l'agenda est désactivé par défaut
			if(!empty($spaceId)){
				$tmpSpace=Ctrl::getObj("space",$spaceId);
				if($tmpSpace->allUsersAffected()==false)	{Db::query("INSERT INTO ap_joinSpaceUser SET _idSpace=".(int)$spaceId.", _idUser=".$reloadedObj->_id.", accessRight=1");}
			}
		}
		////	Retourne l'objet rechargé
		return $reloadedObj;
	}

	/*
	 * Crypte le password en sha1() + SALT
	 */
	public static function passwordSha1($password, $specificSalt=null)
	{
		$SALT=(!empty($specificSalt))  ?  $specificSalt  :  self::getSalt();
		return sha1($SALT.sha1($password));
	}
	
	/*
	 * Récupère le Salt
	 */
	public static function getSalt()
	{
		return (!defined("AGORA_SALT") || !AGORA_SALT)  ?  "Ag0rA-Pr0j3cT"  :  AGORA_SALT;
	}

	/*
	 * Connexion d'un user pas present sur l'agora : tente une connexion ldap pour une creation a la volee
	 */
	public static function ldapConnectCreateUser($login, $password)
	{
		$userInfos=array();
		// Creation d'user ldap autorisee ?
		if(Ctrl::$agora->ldap_crea_auto_users==1)
		{
			// Il faut au moins un espace affecte a tous les users & quota d'users pas depasse
			if(Db::getVal("SELECT count(*) FROM ap_joinSpaceUser WHERE allUsers=1")>0 && static::UsersNbOk(false)==true)
			{
				// Mot de passe crypté.. ou pas (note : certains serveurs LDAP ne fournissent pas le password, tel ActiveDirectory)
				if(Ctrl::$agora->ldap_pass_cryptage=="sha")		{$ldapPassword="{sha}".base64_encode(mhash(MHASH_SHA1,$password));}//TESTER AVEC SHA256 && SHA512 && {SHA} && {sha}
				elseif(Ctrl::$agora->ldap_pass_cryptage=="md5")	{$ldapPassword="{md5}".base64_encode(mhash(MHASH_MD5,$password));}//IDEM
				else											{$ldapPassword=$password;}
				// récupère les valeurs de l'user sur le serveur LDAP
				$usersLDAP=self::ldapSearch(true, "userConnect", "(|(uid=".$login.")(samaccountname=".$login."))");
				if(count($usersLDAP["ldapPersons"])>0)
				{
					foreach($usersLDAP["ldapPersons"] as $tmpUser)
					{
						if((!empty($tmpUser["name"]) || !empty($tmpUser["firstName"])) && !empty($tmpUser["mail"]) && !empty($tmpUser["login"]))
						{
							// Teste la connexion de l'user sur le serveur LDAP
							$userLogin="uid=".$login.",".Ctrl::$agora->ldap_base_dn;
							$userLoginAD=$login.strrchr(Ctrl::$agora->ldap_admin_login,"@");//pour 2ème test sur ActiveDirectory (exple : "monLogin@monDomaineAD")
							$ldapConnection=self::ldapConnect(null, null, $userLogin, $password, false);
							if($ldapConnection==false)	{$ldapConnection=self::ldapConnect(null, null, $userLoginAD, $password, false);}
							//  Vérifie si l'id/password du serveur LDAP est identique à celui spécifié
							$idPassOk=($tmpUser["login"]==$login && $tmpUser["password"]==$ldapPassword)  ?  true  :  false;
							// Vérifie si l'user n'a pas déjà été importé :  la connexion peut être faite par erreur avec les login/password LDAP, différent de ceux de l'agora...
							$userAgoraExist=Db::getVal("SELECT count(*) FROM ap_user WHERE login=".Db::format($tmpUser["login"])." OR mail=".Db::format($tmpUser["mail"]));
							// Créé le compte sur l'agora
							if(($ldapConnection!=false || $idPassOk==true) && empty($userAgoraExist)){
								$newUser=new MdlUser();
								$sqlProperties=null;
								foreach($tmpUser as $attributeKey=>$attributeVal){
									if(!preg_match("/(password|login)/i",$attributeKey) && !empty($attributeVal))   {$sqlProperties.=$attributeKey."=".Db::format($attributeVal).", ";}
								}
								$sqlProperties=trim($sqlProperties,", ");
								$newUser=$newUser->createUpdate($sqlProperties, $login, $password);
								$userInfos=Db::getLine("SELECT * FROM ap_user WHERE _id=".$newUser->_id);
								break;
							}
						}
					}
				}
			}
		}
		return $userInfos;
	}

	/*
	 * VUE SURCHARGE : Menu contextuel	///!!!FUSIONNER AVEC LE MENU PRINCIPAL ET SIMPLIFIER CE DERNIER
	 */
	public function menuContext($options=null)
	{
		////	INIT
		$vDatas["curObj"]=$this;
		////	EDIT PROFIL / MESSENGER
		if($this->editRight()){
			$vDatas["editObjUrl"]=$this->getUrl("edit");
			$vDatas["editMessengerObjUrl"]="?ctrl=user&action=UserEditMessenger&targetObjId=".$this->_targetObjId;
		}
		////	SUPPRESSION DE L'ESPACE
		if($this->deleteFromCurSpaceRight()){$vDatas["deleteFromCurSpaceConfirmRedir"]="confirmRedir('".Txt::trad("USER_confirm_desaffecter_utilisateur",true)."', '?ctrl=user&action=deleteFromCurSpace&targetObjects[".static::objectType."]=".$this->_id."')";}
		if($this->deleteRight())			{$vDatas["deleteConfirmRedir"]="confirmRedir('".Txt::trad("confirmDelete",true)."', '".$this->getUrl("delete")."')";}
		////	STATUT DE L'USER
		if($this->isAdminGeneral())												{$vDatas["userStatusIcon"]="adminGeneral.png";	$vDatas["userStatusLabel"]=Txt::trad("USER_adminGeneral");	$vDatas["adminIcon"]=true;}
		elseif($this->isAdminCurSpace() && $_SESSION["displayUsers"]=="space")	{$vDatas["userStatusIcon"]="adminSpace.png";	$vDatas["userStatusLabel"]=Txt::trad("USER_adminSpace");	$vDatas["adminIcon"]=true;}
		elseif($_SESSION["displayUsers"]=="space")								{$vDatas["userStatusIcon"]="accesUser.png";		$vDatas["userStatusLabel"]=Txt::trad("USER_userSpace");}
		else																	{$vDatas["userStatusIcon"]="accesUser.png";		$vDatas["userStatusLabel"]=Txt::trad("USER_user");}
		////	RENVOIE DU MENU
		return Ctrl::getVue(Req::getCurModPath()."VueUserMenuContext.php",$vDatas);
	}
}