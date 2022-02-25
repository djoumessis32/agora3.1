<?php
/*
 * Controleur du module "User"
 */
class CtrlUser extends Ctrl
{
	const moduleName="user";
	public static $moduleOptions=["ajout_utilisateurs_groupe"];
	public static $MdlObjects=array("MdlUser");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		//Affichage des utilisateurs : "space" / "all"
		if(Req::isParam("displayUsers"))	{$_SESSION["displayUsers"]=(Req::getParam("displayUsers")=="all" && self::$curUser->isAdminGeneral()) ? "all" : "space";}
		//Filtre Alphabet : avec la première lettre du nom
		$vDatas["alphabetList"]=Db::getCol("SELECT DISTINCT UPPER(LEFT(name,1)) FROM ".MdlUser::dbTable." WHERE ".MdlUser::sqlDisplayedObjects()." ORDER BY name");
		$sqlAlphabetFilter=(Req::isParam("alphabet")) ? "AND name LIKE '".Req::getParam("alphabet")."%'" : null;
		//Utilisateurs et menus
		$sqlDisplayedUsers="SELECT * FROM ".MdlUser::dbTable." WHERE ".MdlUser::sqlDisplayedObjects()." ".$sqlAlphabetFilter." ".MdlUser::sqlSort();
		$vDatas["displayedUsers"]=Db::getObjTab("user", $sqlDisplayedUsers." ".MdlUser::sqlPagination());
		$vDatas["displayedUsersTotalNb"]=count(Db::getTab($sqlDisplayedUsers));
		$vDatas["displayedUsersAllAffected"]=(Ctrl::$curUser->isAdminCurSpace() && Ctrl::$curSpace->allUsersAffected())  ?  Txt::trad("USER_tous_users_affectes")  :  null;
		$vDatas["menuDisplayUsers"]=(Ctrl::$curUser->isAdminGeneral() && ($_SESSION["displayUsers"]=="all" || count(Ctrl::$curUser->getSpaces())>1)) ? true : false;
		$vDatas["menuUsersAffectations"]=(self::$curUser->isAdminCurSpace() && self::$curSpace->allUsersAffected()==false) ? true : false;
		//Affiche la page
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginsList=array();
		if(preg_match("/(search|dashboard)/i",$pluginParams["type"]))
		{
			foreach(MdlUser::getPluginObjects($pluginParams) as $tmpObj)
			{
				$tmpObj->pluginModule=self::moduleName;
				$tmpObj->pluginIcon=self::moduleName."/icon.png";
				$tmpObj->pluginLabel=$tmpObj->display("all");
				$tmpObj->pluginTitle=$tmpObj->pluginLabel;
				$tmpObj->pluginJsIcon="lightboxOpen('".$tmpObj->getUrl("vue")."');";
				$tmpObj->pluginJsLabel=$tmpObj->pluginJsIcon;
				$pluginsList[]=$tmpObj;
			}
			return $pluginsList;
		}
		return $pluginsList;
	}

	/*
	 * ACTION : utilisateur détaillé
	 */
	public static function actionUserVue()
	{
		$curObj=Ctrl::getTargetObj();
		$curObj->controlRead();
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueUser.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'un user
	 */
	public static function actionUserEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		//Nb max d'utilisateurs dépassé?
		if($curObj->isNew() && MdlUser::UsersNbOk()==false)  {static::lightboxClose(false);}
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Enregistre & recharge l'objet
			$sqlProperties="civility=".Db::formatParam("civility").", name=".Db::formatParam("name").", firstName=".Db::formatParam("firstName").", mail=".Db::formatParam("mail").", telephone=".Db::formatParam("telephone").", telmobile=".Db::formatParam("telmobile").", fax=".Db::formatParam("fax").", website=".Db::formatParam("website").", adress=".Db::formatParam("adress").", postalCode=".Db::formatParam("postalCode").", city=".Db::formatParam("city").", country=".Db::formatParam("country").", skills=".Db::formatParam("skills").", hobbies=".Db::formatParam("hobbies").", function=".Db::formatParam("function").", companyOrganization=".Db::formatParam("companyOrganization").", comment=".Db::formatParam("comment").", connectionSpace=".Db::formatParam("connectionSpace").", lang=".Db::formatParam("lang");
			if($curObj->editAdminGeneralRight())	{$sqlProperties.=", generalAdmin=".Db::formatParam("generalAdmin");}
			if(Ctrl::$curUser->isAdminGeneral())	{$sqlProperties.=", calendarDisabled=".Db::formatParam("calendarDisabled");}
			if(Req::isParam("ipControlAdresses"))	{$sqlProperties.=", ipControlAdresses=".Db::formatTab2txt(Req::getParam("ipControlAdresses"));}
			$curObj=$curObj->createUpdate($sqlProperties, Req::getParam("login"), Req::getParam("password"));//Ajoute login/password pour les controles standards
			//Objet bien créé/existant : Affectations / Images / etc
			if(is_object($curObj))
			{
				//Ajoute/supprime l'image
				$curObj->editImg();
				//Affectations aux espaces
				if(Ctrl::$curUser->isAdminGeneral())
				{
					//Réinit les droits
					Db::query("DELETE FROM ap_joinSpaceUser WHERE _idUser=".$curObj->_id);
					//Attribue les affectations
					if(Req::isParam("spaceAffect")){
						foreach(Req::getParam("spaceAffect") as $curAffect){
							$curAffect=explode("_",$curAffect);//espace 5 + droit 2 : "5_2" => "[5,2]"
							Db::query("INSERT INTO ap_joinSpaceUser SET _idSpace=".$curAffect[0].", _idUser=".$curObj->_id.", accessRight=".$curAffect[1]);
						}
					}
				}
				//Affectation par défaut à l'espace courant  => si nouvel objet sans affectation définies & affichage "espace" & pour un espace dans lequel tous les users ne sont pas affectés
				if($curObj->isNew() && Req::isParam("spaceAffect")==false && $_SESSION["displayUsers"]=="space" && self::$curSpace->allUsersAffected()==false)
					{Db::query("INSERT INTO ap_joinSpaceUser SET _idSpace=".Ctrl::$curSpace->_id.", _idUser=".$curObj->_id.", accessRight=1");}
				//Notification par mail de création d'user
				if(Req::isParam("notifMail"))	{self::sendMailCoordonnates($curObj,Req::getParam("password"),"userCrea");}
			}
			//Ferme la page
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueUserEdit.php",$vDatas);
	}

	/*
	 * AJAX : Controle du login
	 */
	public static function actionControlDuplicateLogin()
	{
		$isDuplicate="true";
		if(Req::isParam(["targetObjId","controledLogin"])){
			$curObj=Ctrl::getTargetObj();
			if(Db::getVal("SELECT count(*) FROM ".MdlUser::dbTable." WHERE login=".Db::format(Req::getParam("controledLogin"))." AND _id!=".$curObj->_id)==0)
				{$isDuplicate="false";}
		}
		echo $isDuplicate;
	}

	/*
	 * ACTION : désaffectation de l'user à un espace
	 */
	public static function actionDeleteFromCurSpace()
	{
		$urlRedir=null;
		foreach(self::getTargetObjects() as $tmpObj){
			if(empty($urlRedir))	{$urlRedir=$tmpObj->getUrl();}
			{$tmpObj->deleteFromCurSpace(Ctrl::$curSpace->_id);}
		}
		self::redir($urlRedir);
	}

	/*
	 * ACTION : Parametrage du messenger d'un utilisateur
	 */
	public static function actionUserEditMessenger()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate") && Req::isParam("messengerDisplay"))
		{
			//Réinitialise
			Db::query("DELETE FROM ap_userMessenger WHERE _idUserMessenger=".$curObj->_id);
			//Affectation à tous OU à certains users?
			if(Req::getParam("messengerDisplay")=="all")	{Db::query("INSERT INTO ap_userMessenger SET _idUserMessenger=".$curObj->_id.", allUsers=1");}
			elseif(Req::getParam("messengerDisplay")=="some" && strlen(Req::getParam("messengerSomeUsers"))>0){
				foreach(Req::getParam("messengerSomeUsers") as $_idUser)	{Db::query("INSERT INTO ap_userMessenger SET _idUserMessenger=".$curObj->_id.", _idUser=".(int)$_idUser);}
			}
			//Ferme la page
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["allUsers"]=(Db::getVal("SELECT count(*) FROM ap_userMessenger WHERE _idUserMessenger=".$curObj->_id." AND allUsers=1")>0) ? true : false;
		$vDatas["someUsers"]=Db::getCol("SELECT _idUser FROM ap_userMessenger WHERE _idUserMessenger=".$curObj->_id." AND _idUser IS NOT NULL");
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueUserEditMessenger.php",$vDatas);
	}

	/*
	 * ACTION : Persons Import-Export
	 */
	public static function actionEditPersonsImportExport()
	{
		////	Nb max d'utilisateurs dépassé?
		if(MdlUser::UsersNbOk()==false)  {static::lightboxClose(false);}
		////	Validation de formulaire
		if(Req::isParam("formValidate"))
		{
			//Export de users
			if(Req::getParam("actionImportExport")=="export"){
				$userList=Db::getObjTab("user", "SELECT * FROM ".MdlUser::dbTable." WHERE ".MdlUser::sqlDisplayedObjects()." ".MdlUser::sqlSort());
				MdlUser::exportPersons($userList, Req::getParam("exportType"));
			}
			//Import de users
			elseif(Req::getParam("actionImportExport")=="import" && Req::getParam("personFields"))
			{
				$personFields=Req::getParam("personFields");
				//Créé chaque user
				foreach(Req::getParam("personsImport") as $personCpt)
				{
					$curObj=new MdlUser();
					$sqlProperties=null;
					$tmpUser=array();
					//Ajoute chaque champ du user
					foreach(Req::getParam("agoraFields") as $fieldCpt=>$curFieldName){
						$curFieldVal=(!empty($personFields[$personCpt][$fieldCpt]))  ?  $personFields[$personCpt][$fieldCpt]  :  null;
						$tmpUser[$curFieldName]=$curFieldVal;
						if(!empty($curFieldVal) && !empty($curFieldName) && !preg_match("/^(login|pass)/i",$curFieldName))
							{$sqlProperties.=$curFieldName."=".Db::format($curFieldVal).", ";}
					}
					//Password par défaut?
					if(empty($tmpUser["password"]))  {$tmpUser["password"]=Txt::idUniq();}
					//Login par défaut?
					if(empty($tmpUser["login"]) && !empty($tmpUser["mail"]))	{$tmpUser["login"]=$tmpUser["mail"];}//mail
					if(empty($tmpUser["login"]))	{$tmpUser["login"]=strtolower(substr(Txt::clean($tmpUser["firstName"],"maxi",""),0,1)).strtolower(substr(Txt::clean($tmpUser["name"],"maxi",""),0,5));}//"Gérard D'AGOBERT"=>"gdagob"
					//Enregistre l'user
					$curObj=$curObj->createUpdate($sqlProperties, $tmpUser["login"], $tmpUser["password"]);//Ajoute login/password pour les controles standards
					//Options de création
					if(is_object($curObj)){
						//Affecte aux espaces si besoin
						if(Req::isParam("spaceAffectList")){
							foreach(Req::getParam("spaceAffectList") as $_idSpace)	{Db::query("INSERT INTO ap_joinSpaceUser SET _idSpace=".(int)$_idSpace.", _idUser=".$curObj->_id.", accessRight=1");}
						}
						//Envoi une notification mail
						if(!empty($tmpUser["mail"]) && Req::isParam("notifCreaUser"))	{self::sendMailCoordonnates($curObj,$tmpUser["password"],"userCrea");}
					}
				}
				//Ferme la page
				static::lightboxClose();
			}
		}
		////	Affiche le menu d'Import/Export
		$vDatas["curObjClass"]="MdlUser";
		static::displayPage(Req::commonPath."VuePersonsImportExport.php",$vDatas);
	}

	/*
	 * ACTION : Affectation d'un user existant à l'espace courant
	 */
	public static function actionAffectUsers()
	{
		//Administrateur de l'espace courant?
		if(Ctrl::$curUser->isAdminCurSpace()==false)	{static::lightboxClose(false);}
		////	Validation de formulaire
		if(Req::isParam("formValidate"))
		{
			////	Affectation d'users
			if(Req::isParam("usersList") && count(Req::getParam("usersList"))>0)
			{
				//Affecte chaque user
				foreach(Req::getParam("usersList") as $_idUser){
					if(is_numeric($_idUser))	{Db::query("INSERT INTO ap_joinSpaceUser SET _idSpace=".Ctrl::$curSpace->_id.",  _idUser=".$_idUser.", accessRight=1");}
				}
				//Ferme la page
				static::lightboxClose();
			}
			////	Recherche d'users?
			elseif(Req::isParam("searchFields"))
			{
				//Champs de recherche
				$sqlSearch=null;
				foreach(Req::getParam("searchFields") as $fieldName=>$fieldVal){
					if(!empty($fieldVal)){
						$sqlSearch.="OR ".$fieldName." LIKE '%".Db::format($fieldVal,"noquotes")."%' ";
						$vDatas["searchFieldsValues"][$fieldName]=$fieldVal;
					}
				}
				//Liste des users
				if(!empty($sqlSearch)){
					$userIds="0,".implode(",",Ctrl::$curSpace->getUsers("ids"));
					$vDatas["usersList"]=Db::getObjTab("user", "SELECT * FROM ".MdlUser::dbTable." WHERE _id NOT IN (".trim($userIds,",").") AND (".trim($sqlSearch,"OR").")");
				}
			}
		}
		////	Formulaire
		$vDatas["searchFields"]=array("name","firstName","mail");
		static::displayPage("VueAffectUsers.php",$vDatas);
	}

	/*
	 * ACTION : Envoie les coordonnées de connexion à des utilisateurs
	 */
	public static function actionSendCoordinates()
	{
		////	Admin general uniquement
		if(Ctrl::$curUser->isAdminGeneral()==false)  {static::lightboxClose(false);}
		////	Validation de formulaire
		if(Req::isParam("formValidate") && Req::isParam("usersList"))
		{
			//Réinitialise le password pour chaque user sélectionné
			foreach(Req::getParam("usersList") as $userId){
				$curObj=Ctrl::getObj("user",$userId);
				if(is_object($curObj)){
					$newPassword=Txt::idUniq();
					$isSendmail=self::sendMailCoordonnates($curObj,$newPassword,"sendCoords");
					if($isSendmail==true)	{Db::query("UPDATE ".MdlUser::dbTable." SET password=".Db::format(MdlUser::passwordSha1($newPassword))." WHERE _id=".$curObj->_id);}
				}
			}
			//Notif d'envoi
			if($isSendmail==true)	{Ctrl::addNotif(Txt::trad("MAIL_envoye"), "success");}
			//Ferme la page
			static::lightboxClose();
		}
		////	Affichage du formulaire
		$vDatas["usersList"]=Db::getObjTab("user", "SELECT * FROM ".MdlUser::dbTable." WHERE ".MdlUser::sqlDisplayedObjects()." AND LENGTH(mail)>0 AND _id!=".Ctrl::$curUser->_id." ".MdlUser::sqlSort());
		static::displayPage("VueSendCoordinates.php",$vDatas);
	}

	/*
	 * ACTION : Envoi d'invitation
	 */
	public static function actionSendInvitation()
	{
		////	Droit d'envoyer des invitations?  Nb max d'utilisateurs dépassé?
		if(Ctrl::$curUser->sendInvitationRight()==false || MdlUser::UsersNbOk()==false)  {static::lightboxClose(false);}
		////	Suppression d'invitation
		if(Req::isParam("deleteInvitation")){
			Db::query("DELETE FROM ap_invitation WHERE _idUser=".Ctrl::$curUser->_id." AND _idInvitation=".Db::formatParam("_idInvitation"));
		}
		////	Validation de formulaire : Envoie une invitation
		if(Req::isParam("formValidate") && Req::isParam("mail"))
		{
			// Init
			$_idInvitation=Txt::idUniq(10);
			$password=Txt::idUniq();
			$confirmUrl=Req::getSpaceUrl()."/?ctrl=offline&disconnect=1&_idInvitation=".$_idInvitation."&mail=".urlencode(Req::getParam("mail"));
			// Envoi du mail d'invitation
			$subject=Txt::trad("USER_objet_mail_invitation")." ".Ctrl::$curUser->display(); // "Invitation de Jean DUPOND" && "Jean DUPOND vous invite à rejoindre l'espace Mon Espace :"
			$mainMessage="<b>".Ctrl::$curUser->display()." ".Txt::trad("USER_admin_guest_espace")." ".Ctrl::$curSpace->name." :</b>
						  <br><br>".Txt::trad("login2")." : <b>".Req::getParam("mail")."</b>
						  <br>".Txt::trad("passwordToModify")." : <b>".$password."</b>
						  <br><br><a href=\"".$confirmUrl."\" target=\"_blank\"><u><b>".Txt::trad("USER_confirmer_invitation")."</u></b></a>"; // Confirmer l'invitation ?
			if(Req::isParam("comment"))  {$mainMessage.="<br><br>".Txt::trad("comment").":<br>".Req::getParam("comment");}
			$isSendMail=Tool::sendMail(Req::getParam("mail"), $subject, $mainMessage);
			// On ajoute l'invitation temporaire
			if($isSendMail==true)	{Db::query("INSERT INTO ap_invitation SET _idInvitation=".Db::format($_idInvitation).", _idSpace=".(int)Ctrl::$curSpace->_id.", name=".Db::formatParam("name").", firstName=".Db::formatParam("firstName").", mail=".Db::formatParam("mail").", password=".Db::format($password).", dateCrea=".Db::dateNow().", _idUser=".Ctrl::$curUser->_id);}
			//Ferme la page
			static::lightboxClose();
		}
		////	On fait le ménage (suppr les invitations de + d'un an)
		Db::query("DELETE FROM ap_invitation WHERE UNIX_TIMESTAMP(dateCrea) < '".(time()-(86400*365))."'");
		////	Formulaire
		$vDatas["userFields"]=array("name","firstName","mail");
		$vDatas["invitationList"]=Db::getTab("SELECT * FROM ap_invitation WHERE _idUser=".Ctrl::$curUser->_id);
		static::displayPage("VueSendInvitation.php",$vDatas);
	}

	/*
	 * ACTION : Edition des groupes d'utilisateurs
	 */
	public static function actionUserGroupEdit()
	{
		//Droit d'ajouter un groupe?
		if(MdlUserGroup::addRight()==false)  {static::lightboxClose(false);}
		////	Validation de formulaire : edit un groupe
		if(Req::isParam("formValidate")){
			$curObj=Ctrl::getTargetObj();
			$curObj->controlEdit();
			$curObj->createUpdate("title=".Db::formatParam("title").", _idSpace=".Ctrl::$curSpace->_id.", _idUsers=".Db::formatTab2txt(Req::getParam("userList")));
			static::lightboxClose();
		}
		//Users et groupes de l'espace
		$vDatas["usersList"]=Ctrl::$curSpace->getUsers();
		$vDatas["groupList"]=MdlUserGroup::getGroups(Ctrl::$curSpace);
		$vDatas["groupList"][]=New MdlUserGroup();
		foreach($vDatas["groupList"] as $tmpKey=>$tmpGroup){
			if($tmpGroup->editRight()==false)	{unset($vDatas["groupList"][$tmpKey]);}
			else{
				$tmpGroup->tmpId=$tmpGroup->_targetObjId;
				$tmpGroup->createdBy=($tmpGroup->isNew()==false)  ?  Txt::trad("cree_par")." : ".Ctrl::getObj("user",$tmpGroup->_idUser)->display()  :  null;
			}
		}
		//Affiche la page
		static::displayPage("VueUserGroupEdit.php",$vDatas);
	}

	/*
	 * ACTION : Affiche l'inscription des utilisateurs au site 
	 */
	public static function actionRegisterUser()
	{
		//Administrateur de l'espace courant?
		if(Ctrl::$curUser->isAdminCurSpace()==false)	{static::lightboxClose(false);}
		//Validation du form
		if(Req::isParam("formValidate") && Req::isParam("inscriptionValidation"))
		{
			//Créé chaque utilisateur validé
			foreach(Req::getParam("inscriptionValidation") as $idInscription)
			{
				$tmpInscription=Db::getLine("SELECT * FROM ap_userInscription WHERE _id=".$idInscription);
				//Invalidation/Validation de l'user
				if(Req::getParam("submitAction")=="alternative"){
					$subject=$mainMessage=Txt::trad("usersInscription_invalider_mail")." ''".Ctrl::$agora->name."'' (".Req::getSpaceUrl(false).")";//"Votre compte n'a pas été validé sur ''Mon_Espace''"
					$mainMessage="<b>".$mainMessage."</b>";
					Tool::sendMail($tmpInscription["mail"], $subject, $mainMessage);
				}else{
					$curObj=new MdlUser();
					$sqlProperties="name=".Db::format($tmpInscription["name"]).", firstName=".Db::format($tmpInscription["firstName"]).", mail=".Db::format($tmpInscription["mail"]);
					$curObj=$curObj->createUpdate($sqlProperties, $tmpInscription["mail"], $tmpInscription["password"], $tmpInscription["_idSpace"]);//Ajoute login/password pour les controles standards
					if(is_object($curObj))  {self::sendMailCoordonnates($curObj,$tmpInscription["password"],"userCrea");}
				}
				//Supprime l'inscription
				Db::query("DELETE FROM ap_userInscription WHERE _id=".(int)$idInscription);
			}
			//Ferme la page
			static::lightboxClose();
		}
		//Affiche le formulaire
		$vDatas["inscriptionList"]=Db::getTab("SELECT * FROM ap_userInscription WHERE _idSpace=".Ctrl::$curSpace->_id);
		static::displayPage("VueUsersInscriptionValidate.php",$vDatas);
	}

	/*
	 * ENVOI D'UN MAIL AVEC LES COORDONNEES D'UN USER
	 */
	public static function sendMailCoordonnates($curObj, $password, $mode)
	{
		//Init
		$spaceUrl=Req::getSpaceUrl()."/?login=".$curObj->login."&disconnect=1";
		$spaceNameUrl="<a href=\"".$spaceUrl."\" target='_blank'><u><i>".Ctrl::$agora->name."</i></u></a> (".Req::getSpaceUrl(false).")";
		$message="<a href=\"".$spaceUrl."\" target='_blank'>".Txt::trad("USER_mail_infos_connexion")."</a> :";
		if($mode=="sendCoords"){
			$subject=Txt::trad("USER_mail_coordonnees")." ''".Ctrl::$agora->name."''";
			$message=Txt::trad("USER_mail_coordonnees")." ".$spaceNameUrl."<br>".$message;
		}else{
			$subject=Txt::trad("USER_mail_objet_nouvel_utilisateur")." ''".Ctrl::$agora->name."''";
			$message=Txt::trad("USER_mail_nouvel_utilisateur")." ".$spaceNameUrl."<br>".$message;
		}
		//Message
		$message.= "<br><br>".Txt::trad("login2")." : <b>".$curObj->login."</b>
					<br>".Txt::trad("passwordToModify")." : <b>".$password."</b>
					<br><br>".Txt::trad("USER_mail_infos_connexion2");
		//Envoi du message
		$options=($mode=="sendCoords") ? "noSendNotif" : null;
		return Tool::sendMail($curObj->mail, $subject, $message, $options);
	}
}