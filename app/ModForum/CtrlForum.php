<?php
/*
 * Controleur du module "Forum"
 */
class CtrlForum extends Ctrl
{
	const moduleName="forum";
	public static $moduleOptions=["ajout_sujet_admin","ajout_sujet_theme"];
	public static $MdlObjects=array("MdlForumSubject","MdlForumMessage");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		//Init
		$vDatas["themeList"]=MdlForumTheme::getThemes();
		////	AFFICHAGE D'UN SUJET & SES MESSAGES(?)
		$curSubject=Ctrl::getTargetObj();
		if(is_object($curSubject) && $curSubject::objectType=="forumSubject")
		{
			$curSubject->curUserConsultLastMessageMaj();//Met à jour si besoin la consultation du dernier message
			$vDatas["displayForum"]="subjectMessages";
			$vDatas["displayMode"]=MdlForumSubject::getDisplayMode();
			$vDatas["curSubject"]=$curSubject;
			$vDatas["messagesList"]=$curSubject->getMessages($vDatas["displayMode"]);
			$vDatas["messagesNb"]=count($vDatas["messagesList"]);
			$vDatas["labelQuoteAnswer"]=($vDatas["displayMode"]=="line")  ?  Txt::trad("FORUM_citer") : Txt::trad("FORUM_repondre");
			$vDatas["labelQuoteAnswerTitle"]=($vDatas["displayMode"]=="line")  ?  Txt::trad("FORUM_message_citer") : Txt::trad("FORUM_message_repondre");
		}
		////	AFFICHAGE DES THÈMES DE SUJET
		elseif(!empty($vDatas["themeList"]) && Req::isParam("_idTheme")==false)
		{
			$vDatas["displayForum"]="theme";
			$vDatas["themeEditButton"]=MdlForumTheme::addRight();
			$vDatas["displayPageCenter"]=(MdlForumTheme::addRight()==false);
			$vDatas["themeList"][]=new MdlForumTheme(["undefinedTheme"=>true]);//ajoute le pseudo theme "sans theme"
			foreach($vDatas["themeList"] as $tmpKey=>$tmpTheme)
			{
				//Nombre de sujets & Objet du dernier sujet
				$sqlThemeFilter=(!empty($tmpTheme->_id)) ? "_idTheme=".$tmpTheme->_id : "_idTheme is NULL";//Theme normal / "sans theme"
				$tmpTheme->subjectList=Db::getObjTab("forumSubject", "SELECT * FROM ap_forumSubject WHERE ".MdlForumSubject::sqlDisplayedObjects()." AND ".$sqlThemeFilter." ORDER BY dateCrea desc");
				$tmpTheme->subjectsNb=count($tmpTheme->subjectList);
				if($tmpTheme->undefinedTheme==true && empty($tmpTheme->subjectsNb))	{unset($vDatas["themeList"][$tmpKey]);}//Enleve le theme "sans theme" s'il n'y a aucun sujet correspondant..
				elseif($tmpTheme->subjectsNb>0)										{$tmpTheme->subjectLast=reset($tmpTheme->subjectList);}//reset: premier sujet de la liste (le + récent)
				//Nombre de messages & Objet du dernier message : tous sujets confondus!
				$tmpTheme->messagesNb=$timeMessageLast=0;
				foreach($tmpTheme->subjectList as $tmpSubject){
					$messageList=$tmpSubject->getMessages();
					$tmpTheme->messagesNb+=count($messageList);
					$messageLast=reset($messageList);//reset: premier message de la liste (le + récent)
					if(count($messageList)>0 && $timeMessageLast<strtotime($messageLast->dateCrea))  {$tmpTheme->messageLast=$messageLast;}
				}
			}
		}
		////	AFFICHAGE DES SUJETS (D'UN THEME SPECIFIQUE?)
		else
		{
			$vDatas["displayForum"]="subjects";
			$vDatas["themeEditButton"]=(empty($vDatas["themeList"]) && MdlForumTheme::addRight());
			//Liste les sujets
			if(Req::getParam("_idTheme")=="undefinedTheme")	{$sqlThemeFilter="AND (_idTheme is NULL or _idTheme=0)";}		//sujets "sans theme"
			elseif(Req::isParam("_idTheme"))				{$sqlThemeFilter="AND _idTheme=".Db::formatParam("_idTheme");}	//sujets d'un theme précis
			else											{$sqlThemeFilter=null;}											//tout les sujets
			$sqlDisplayedSubjects="SELECT * FROM ".MdlForumSubject::dbTable." WHERE ".MdlForumSubject::sqlDisplayedObjects()." ".$sqlThemeFilter." ".MdlForumSubject::sqlSort();
			$vDatas["subjectsDisplayed"]=Db::getObjTab("forumSubject", $sqlDisplayedSubjects." ".MdlForumSubject::sqlPagination());
			$vDatas["subjectsTotalNb"]=count(Db::getTab($sqlDisplayedSubjects));
			//Pour chaque sujet : Nombre de messages & Objet dernier message
			foreach($vDatas["subjectsDisplayed"] as $tmpSubject){
				$messageList=$tmpSubject->getMessages();
				$tmpSubject->messagesNb=count($messageList);
				if(!empty($tmpSubject->messagesNb))  {$tmpSubject->messageLast=reset($messageList);}//reset: premier message de la liste (le + récent)
			}
		}
		////	THEME COURANT POUR LE MENU PATH
		if($vDatas["displayForum"]!="theme" && !empty($vDatas["themeList"])){
			if(Req::getParam("_idTheme")=="undefinedTheme" || (is_object($curSubject) && empty($curSubject->_idTheme)))	{$vDatas["curTheme"]=new MdlForumTheme(["undefinedTheme"=>true]);}
			elseif(is_object($curSubject) && !empty($curSubject->_idTheme))												{$vDatas["curTheme"]=self::getObj("forumTheme",$curSubject->_idTheme);}
			elseif(Req::getParam("_idTheme"))																			{$vDatas["curTheme"]=self::getObj("forumTheme",Req::getParam("_idTheme"));}
		}
		////	AFFICHAGE
		static::$isMainPage=true;
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginsList=array();
		//Sujets
		foreach(MdlForumSubject::getPluginObjects($pluginParams) as $objSubject)
		{
			$objSubject->pluginModule=self::moduleName;
			$objSubject->pluginIcon=self::moduleName."/icon.png";
			$objSubject->pluginLabel=(!empty($objSubject->title)) ? $objSubject->title : Txt::reduce($objSubject->description);
			$objSubject->pluginTitle=$objSubject->displayAutor(true,true);
			$objSubject->pluginJsIcon="redir('".$objSubject->getUrl()."',true);";
			$objSubject->pluginJsLabel=$objSubject->pluginJsIcon;
			$pluginsList[]=$objSubject;
		}
		//messages
		if($pluginParams["type"]!="shortcut")
		{
			foreach(MdlForumMessage::getPluginObjects($pluginParams) as $objMessage)
			{
				$objMessage->pluginIcon=self::moduleName."/icon.png";
				$objMessage->pluginLabel=(!empty($objMessage->title)) ? $objMessage->title : Txt::reduce($objMessage->description);
				$objMessage->pluginTitle=$objMessage->displayAutor(true,true);
				$objMessage->pluginJsIcon="redir('".$objMessage->getUrl("container")."',true);";
				$objMessage->pluginJsLabel=$objMessage->pluginJsIcon;
				$pluginsList[]=$objMessage;
			}
		}
		return $pluginsList;
	}

	/*
	 * AJAX : Active/désactive les notifications des messages par mail
	 */
	public static function actionNotifyLastMessage()
	{
		$curSubject=Ctrl::getTargetObj();
		if($curSubject->readRight()){
			$usersNotifyLastMessage=Txt::txt2tab($curSubject->usersNotifyLastMessage);
			if($curSubject->curUserNotifyLastMessage())		{$usersNotifyLastMessage=array_diff($usersNotifyLastMessage,[Ctrl::$curUser->_id]);		echo "removeUser";}
			else											{$usersNotifyLastMessage[]=Ctrl::$curUser->_id;											echo "addUser";}
			Db::query("UPDATE ap_forumSubject SET usersNotifyLastMessage=".Db::formatTab2txt($usersNotifyLastMessage)." WHERE _id=".$curSubject->_id);
		}
	}

	/*
	 * ACTION : Edition des themes de sujet
	 */
	public static function actionForumThemeEdit()
	{
		////	Droit d'ajouter un theme?
		if(MdlForumTheme::addRight()==false)  {static::lightboxClose(false);}
		////	Validation de formulaire
		if(Req::isParam("formValidate")){
			$curObj=Ctrl::getTargetObj();
			$curObj->controlEdit();
			//Modif d'un theme
			$_idSpaces=(!in_array("all",Req::getParam("spaceList")))  ?  Txt::tab2txt(Req::getParam("spaceList"))  :  null;
			$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description").", color=".Db::formatParam("color").", _idSpaces=".Db::format($_idSpaces));
			//Ferme la page
			static::lightboxClose();
		}
		////	Liste des themes
		$vDatas["themesList"]=MdlForumTheme::getThemes(true);
		$vDatas["themesList"][]=New MdlForumTheme();//nouveau theme vide
		foreach($vDatas["themesList"] as $tmpKey=>$tmpTheme){
			if($tmpTheme->editRight()==false)	{unset($vDatas["themesList"][$tmpKey]);}
			else{
				$tmpTheme->tmpId=$tmpTheme->_targetObjId;
				$tmpTheme->createdBy=($tmpTheme->isNew()==false)  ?  Txt::trad("cree_par")." : ".Ctrl::getObj("user",$tmpTheme->_idUser)->display()  :  null;
			}
		}
		////	Affiche la vue
		static::displayPage("VueForumThemeEdit.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'un sujet
	 */
	public static function actionForumSubjectEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		if(MdlForumSubject::addRight()==false)   {self::noAccessExit();}
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$dateLastMessage=($curObj->isNew())  ?  ", dateLastMessage=".Db::dateNow()  :  null;//Init "dateLastMessage" pour un nouveau sujet (classement des sujets)
			$curObj=$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description","editor").", _idTheme=".Db::formatParam("_idTheme").", usersConsultLastMessage=".Db::formatTab2txt([Ctrl::$curUser->_id])." ".$dateLastMessage);
			//Notifie par mail & Ferme la page
			$curObj->sendMailNotif(Txt::reduce(strip_tags($curObj->description)));
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		if(Req::isParam("_idTheme"))	{$curObj->_idTheme=Req::getParam("_idTheme");}
		$vDatas["themesList"]=MdlForumTheme::getThemes();
		static::displayPage("VueForumSubjectEdit.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'un message
	 */
	public static function actionForumMessageEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$idMessageParent=(Req::isParam("_idMessageParent"))  ?  ", _idMessageParent=".Db::formatParam("_idMessageParent")  :  null;//Rattaché à un message parent?
			$curObj=$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description","editor").$idMessageParent);
			//MAJ "dateLastMessage" & "usersConsultLastMessage" du sujet conteneur
			Db::query("UPDATE ap_forumSubject SET dateLastMessage=".Db::dateNow().", usersConsultLastMessage=".Db::formatTab2txt([Ctrl::$curUser->_id])." WHERE _id=".$curObj->_idContainer);
			//Notifie par mail & Ferme la page
			$notifUserIds=($curObj->isNew())  ?  array_diff(Txt::txt2tab($curObj->containerObj()->usersNotifyLastMessage), [Ctrl::$curUser->_id])  :  null;//Ajoute les destinataires des notif "auto" si c'est un nouveau message (sauf pour l'auteur courant)
			$curObj->sendMailNotif(Txt::reduce(strip_tags($curObj->description)), null, null, $notifUserIds);
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		$vDatas["messageParent"]=(strlen(Req::getParam("_idMessageParent"))>0)  ?  self::getObj("forumMessage",Req::getParam("_idMessageParent"))  :  null;
		static::displayPage("VueForumMessageEdit.php",$vDatas);
	}
	
	/*
	 * Image de l'auteur du sujet/message
	 */
	public static function autorImg($_idUser)
	{
		if(!empty($_idUser))	{return self::getObj("user",$_idUser)->getImg(true);}
	}
}