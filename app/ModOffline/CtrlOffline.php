<?php
/*
 * Controleur des pages "Offline"
 */
class CtrlOffline extends Ctrl
{
	const moduleName="offline";

	/*
	 * ACTION PAR DEFAUT : connexion à l'espace
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		//Réinitialise le password? confirme une invitation?
		MdlUser::reinitPassword();
		MdlUser::confirmInvitation();
		//Affiche la page
		$vDatas["usersInscription"]=Db::getVal("select count(*) from ap_space where usersInscription=1");
		$vDatas["objPublicSpaces"]=Db::getObjTab("space", "select * from ap_space where public=1 order by name");
		//Login par défaut
		if(Req::isParam("login"))				{$vDatas["defaultLogin"]=Req::getParam("login");}
		elseif(!empty($_COOKIE["AGORAP_LOG"]))	{$vDatas["defaultLogin"]=$_COOKIE["AGORAP_LOG"];}
		else									{$vDatas["defaultLogin"]=null;}
		static::displayPage("VueConnection.php",$vDatas);
	}

	/*
	 * ACTION : Renvoie le mot de passe oublié
	 */
	public static function actionForgotPassword()
	{
		//Formulaire validé
		if(Req::isParam("formValidate"))
		{
			$curUser=Db::getLine("SELECT * FROM ap_user WHERE mail=".Db::format(Req::getParam("mail"))." AND mail!=''");
			if(empty($curUser))    {self::addNotif("PASS_OUBLIE_mail_inexistant");}
			else{
				$_idNewPassword=uniqid();
				$confirmAdress=Req::getSpaceUrl()."/?_idUser=".$curUser["_id"]."&_idNewPassword=".$_idNewPassword;
				// Envoi du mail pour vérificaton
				$subject=Txt::trad("PASS_OUBLIE_mail_objet")." ".Ctrl::$agora->name." (".Req::getSpaceUrl(false).")";
				$mailContent=$subject."<br>".Txt::trad("PASS_OUBLIE_mail_contenu")." :&nbsp; <b>".$curUser["login"]."</b><br><br>
							 <a href=\"".$confirmAdress."\" target='_blank'><b>".Txt::trad("PASS_OUBLIE_mail_contenu_bis")."</b></a>";
				$sendMail=Tool::sendMail(Req::getParam("mail"), $subject, $mailContent);
				// On ajoute l'invitation temporaire & ferme la lightbox
				if($sendMail==true)	{Db::query("UPDATE ap_user SET _idNewPassword=".Db::format($_idNewPassword)." WHERE _id=".(int)$curUser["_id"]);}
				static::lightboxClose(false);
			}
		}
		//Affiche le formulaire
		static::displayPage("VueForgotPassword.php");
	}

	/*
	 * ACTION : Inscription d'utilisateur
	 */
	public static function actionUsersInscription()
	{
		//Nb max d'utilisateurs dépassé?
		if(MdlUser::UsersNbOk()==false)	{Ctrl::lightboxClose(false);}
		//Formulaire validé
		if(Req::isParam("formValidate")){
			Db::query("INSERT INTO ap_userInscription SET _idSpace=".Db::formatParam("_idSpace").", name=".Db::formatParam("name").", firstName=".Db::formatParam("firstName").", mail=".Db::formatParam("mail").", password=".Db::formatParam("password").", message=".Db::formatParam("message").", date=".Db::dateNow());
			self::addNotif("usersInscription_enregistre","success");
			static::lightboxClose(false);
		}
		//Affiche le formulaire
		$vDatas["objSpacesInscription"]=Db::getObjTab("space", "select * from ap_space where usersInscription=1");
		static::displayPage("VueUsersInscription.php",$vDatas);
	}

	/*
	 * ACTION : Install de l'Agora
	 */
	public static function actionInstall()
	{
		//init & Install authorisé? & chmod sur le dossier DATAS
		static::$isMainPage=true;
		self::installControl();
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Modif le fichier "config.inc.php"
			File::setChmod(PATH_DATAS);
			$AGORA_SALT=Txt::idUniq();
			$spaceDiskLimit=File::getBytesSize(Req::getParam("spaceDiskLimit")."go");
			File::updateConfigFile(["AGORA_SALT"=>$AGORA_SALT, "db_host"=>Req::getParam("db_host"), "db_login"=>Req::getParam("db_login"), "db_password"=>Req::getParam("db_password"), "db_name"=>Req::getParam("db_name"), "limite_nb_users"=>"10000", "limite_espace_disque"=>$spaceDiskLimit]);
			//Controle l'accès au sgbd & Créé la Bdd?
			$controlConnect=self::installSgbdControl(Req::getParam("db_host"), Req::getParam("db_login"), Req::getParam("db_password"), Req::getParam("db_name"));
			if($controlConnect!=true && $controlConnect!="errorConnectDb")	{self::noAccessExit();}
			elseif($controlConnect=="errorConnectDb"){
				$objPDO=new PDO("mysql:host=".Req::getParam("db_host"),Req::getParam("db_login"),Req::getParam("db_password"));
				$objPDO->query("CREATE DATABASE `".Req::getParam("db_name")."` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;");
			}
			//Se connecte au sgbd & Importe la Bdd!
			$objPDO=new PDO("mysql:host=".Req::getParam("db_host").";dbname=".Req::getParam("db_name").";charset=utf8;", Req::getParam("db_login"), Req::getParam("db_password"));
			$dbFile="app/ModOffline/db.sql";
			$handle=fopen($dbFile,"r");
			foreach(explode(";",fread($handle,filesize($dbFile))) as $tmpQuery){
				if(strlen($tmpQuery)>5)  {$objPDO->query($tmpQuery);}
			}
			////	Ne pas utiliser le "Db::format()" car instancie un "new PDO()" !!
			//Ajoute le paramétrage général, le premier espace, les jointures "espace">"modules", la jointure "espace">"utilisateurs"
			$objPDO->query("INSERT INTO ap_agora SET name='Agora-Project', description=".$objPDO->quote(Txt::trad("INSTALL_spaceDescription")).", timezone=".$objPDO->quote(Req::getParam("timezone")).", lang=".$objPDO->quote(Req::getParam("lang")).", dateUpdateDb=".Db::dateNow().", version_agora='".VERSION_AGORA."', personsSort='firstName', logsTimeOut='30'");
			$objPDO->query("INSERT INTO ap_space SET _id=1, name=".$objPDO->quote(Req::getParam("spaceName")).", description=".$objPDO->quote(Req::getParam("spaceDescription")).", public=".(Req::getParam("spacePublic")==1?"'1'":"NULL").", usersInvitation=1");
			$objPDO->query("INSERT INTO ap_joinSpaceModule (_idSpace,moduleName,rank) VALUES (1,'dashboard',1), (1,'file',2), (1,'calendar',3), (1,'forum',4), (1,'contact',5), (1,'link',6), (1,'task',7), (1,'user',8), (1,'mail',9)");
			$objPDO->query("INSERT INTO ap_joinSpaceUser SET _idSpace=1, allUsers=1, accessRight=1");
			//Ajoute l'utilisateur (admin général) et le messenger
			$password=MdlUser::passwordSha1(Req::getParam("adminPassword"),$AGORA_SALT);
			$objPDO->query("INSERT INTO ap_user SET _id=1, login=".$objPDO->quote(Req::getParam("adminLogin")).", password=".$objPDO->quote($password).", name=".$objPDO->quote(Req::getParam("adminName")).", firstName=".$objPDO->quote(Req::getParam("adminFirstName")).", mail=".$objPDO->quote(Req::getParam("adminMail")).", generalAdmin=1");
			$objPDO->query("INSERT INTO ap_userMessenger SET _idUserMessenger=1, allUsers=1");
			//Ajoute une première actualité
			$newsDescription="<div style='font-size:150%;margin:30px 0px 30px 0px;font-weight:bold;' align='center'>Bienvenue sur votre nouvel espace !</div><a href=\"javascript:lightboxOpen('?ctrl=user&action=SendInvitation')\"><i><b>Cliquez ici pour inviter des personnes à vous y rejoindre</b></i></a>";
			$objPDO->query("INSERT INTO ap_dashboardNews SET _id=1, description=".$objPDO->quote($newsDescription).", dateCrea=".Db::dateNow().", _idUser=1");
			//Ajoute les agendas et categories d'agenda
			$objPDO->query("INSERT INTO ap_calendar (_id,type,evtColorDisplay,_idUser,title) VALUES (1,'ressource','background',1,".$objPDO->quote(Req::getParam("spaceName"))."), (2,'user','background',1,NULL)");
			$objPDO->query("INSERT INTO ap_calendarEventCategory (_id,color,title) VALUES (1,'#770000','rendez-vous'), (2,'#000077','réunion'), (3,'#dd7700','congés'), (4,'#007700','personnel')");
			//Ajoute les dossiers
			$objPDO->query("INSERT INTO ap_contactFolder SET _id=1, _idContainer=0");
			$objPDO->query("INSERT INTO ap_fileFolder SET _id=1, _idContainer=0");
			$objPDO->query("INSERT INTO ap_linkFolder SET _id=1, _idContainer=0");
			$objPDO->query("INSERT INTO ap_taskFolder SET _id=1, _idContainer=0");
			//Ajoute les jointures "objet">"espace"
			$objPDO->query("INSERT INTO ap_objectTarget (objectType,_idObject,_idSpace,target,accessRight) VALUES 
				('dashboardNews',1,1,'spaceUsers',1),
				('calendar',1,1,'spaceUsers',1.5),
				('fileFolder',1,1,'spaceUsers',2),
				('contactFolder',1,1,'spaceUsers',2),
				('taskFolder',1,1,'spaceUsers',2),
				('linkFolder',1,1,'spaceUsers',2)");
			//REDIRECTION
			self::addNotif("INSTALL_install_ok");
			self::redir("?disconnect=1");
		}
		//Charge la langue & Affiche le formulaire
		Txt::loadTrads(Req::getParam("tradInstall"));
		static::displayPage("VueInstall.php");
	}

	/*
	 * AJAX : Vérifie une connexion au SGBD
	 */
	public static function actionInstallVerifMysql()
	{
		//Install authorisé?
		self::installControl();
		//Vérif la connexion à Mysql, Vérif la connexion à la BDD, et Vérif si la BDD contient déjà l'appli
		echo self::installSgbdControl(Req::getParam("db_host"),Req::getParam("db_login"),Req::getParam("db_password"),Req::getParam("db_name"));
	}

	/*
	 * Vérifie la version du parser PHP et si l'appli n'est pas déjà installé en Bdd
	 */
	public static function installControl()
	{
		Req::verifPhpVersion();
		if(defined("db_host") && defined("db_login") && defined("db_password") && defined("db_name") && self::installSgbdControl(db_host,db_login,db_password,db_name)==="errorAppliInstalled")
			{self::noAccessExit();}
	}

	/*
	 * Verifie une connexion SGBD
	 */
	public static function installSgbdControl($db_host, $db_login, $db_password, $db_name)
	{
		//Connection PDO
		try{
			//Vérif la connexion à la bdd
			$objPDO=new PDO("mysql:host=".$db_host.";dbname=".$db_name.";charset=utf8;", $db_login, $db_password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
			//Vérif si l'appli est déjà installé
			$result=$objPDO->query("SHOW TABLES FROM `".$db_name."` WHERE `Tables_in_".$db_name."` LIKE 'gt_%' OR `Tables_in_".$db_name."` LIKE 'ap_%'");
			if(count($result->fetchAll(PDO::FETCH_COLUMN,0))>0)  {return "errorAppliInstalled";}//Erreur: L'application est déjà installée
		}
		//Erreur de connexion à Mysql/bdd
		catch(PDOException $exception){
			if(preg_match("/Unknown database/i",$exception->getMessage()))	{return "errorConnectDb";}				//Erreur: Database inconnue
			elseif(preg_match("/Access denied/i",$exception->getMessage()))	{return "errorConnectIdentification";}	//Erreur: Pas d'identification de l'user Mysql
			else															{return "errorConnectSGBD";}			//Erreur: Problemme d'accès au GSBD (erreur de host?)
		}
		//Pas d'erreur
		return true;
	}

	/*
	 * AJAX : Test le password de connexion à un espace public
	 */
	public static function actionPublicSpaceAccess()
	{
		$password=Db::getVal("SELECT count(*) FROM ap_space WHERE _id=".Db::formatParam("_idSpace")." AND BINARY password=".Db::formatParam("password"));//"BINARY"=>case sensitive
		echo (empty($password)) ? "false" : "true";
	}
}