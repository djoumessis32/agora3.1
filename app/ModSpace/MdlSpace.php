<?php
/*
 * Modele des espaces
 */
class MdlSpace extends MdlObject
{
	const moduleName="space";
	const objectType="space";
	const dbTable="ap_space";
	//Propriésé bdd
	const hasAccessRight=false;
	//Propriétés d'IHM
	const hasShortcut=false;
	const hasAttachedFiles=false;
	const hasNotifMail=false;
	//Champs obligatoires et de tri des résultats
	public static $requiredFields=array("name");
	public static $sortFields=array("name@@asc","name@@desc","description@@asc","description@@desc");
	//Liste des modules
	public static $moduleList=["dashboard","user","calendar","file","forum","task","link","contact","mail"];
	//Valeurs en cache (calculées qu'1 fois)
	private $_allUsersAffected=null;
	private $_spaceUsers=null;
	private $_moduleList=array();
	private $_usersAccessRight=array();

	/*
	 * SURCHARGE : Droit d'accès à un espace
	 */
	public function accessRight()
	{
		if($this->_accessRight===null){
			$this->_accessRight=parent::accessRight();//Droit par défaut
			if($this->userAccessRight(Ctrl::$curUser)>$this->_accessRight)	{$this->_accessRight=$this->userAccessRight(Ctrl::$curUser);}
		}
		return $this->_accessRight;
 	}

	/*
	 * Modules de l'espace						(ex "modules_espace()")
	 */
	public function moduleList($curUserCalendarEnabled=true)
	{
		if(empty($this->_moduleList))
		{
			//Récupère les modules de l'espace et ajoute le nom du module en "key"
			$spaceModules=Db::getTab("SELECT * FROM ap_joinSpaceModule WHERE _idSpace=".$this->_id);
			foreach($spaceModules as $tmpModule)	{$spaceModules[$tmpModule["moduleName"]]=$tmpModule;}
			//Parcourt la liste des Modules dispo
			foreach(self::availableModuleList() as $moduleName=>$tmpModule)
			{
				//"mail" désactivé pour les invites
				if($moduleName=="mail" && Ctrl::$curUser->isUser()==false)  {continue;}
				//Module est affecté à l'espace
				if(isset($spaceModules[$moduleName])){
					$tmpModule["rank"]=$spaceModules[$moduleName]["rank"];
					$tmpModule["options"]=$spaceModules[$moduleName]["options"];
					$this->_moduleList[$moduleName]=$tmpModule;
				}
				//Ajoute le "calendar" même s'il n'est pas affecté : agenda perso de l'user toujours accessible (sauf si désactivé)
				elseif($moduleName=="calendar" && Ctrl::$curUser->isUser() && Ctrl::$curUser->calendarDisabled!=1){
					$tmpModule["addedCurUserCalendar"]=true;
					$this->_moduleList[$moduleName]=$tmpModule;
				}
			}
			//Tri les résultats par "rank"
			$this->_moduleList=Tool::sortArray($this->_moduleList,"rank");
		}
		//renvoie tous les modules || renvoie tous les modules, sauf l'agenda perso
		if($curUserCalendarEnabled==true || empty($this->_moduleList["calendar"]["addedCurUserCalendar"]))	{return $this->_moduleList;}
		else{
			$curmoduleList=$this->_moduleList;
			unset($curmoduleList["calendar"]);
			return $curmoduleList;
		}
	}
	
	/*
	 * Tous les modules disponibles pour l'espace
	 */
	public static function availableModuleList()
	{
		$moduleList=[];
		foreach(self::$moduleList as $moduleName){
			$moduleList[$moduleName]=array(
				"moduleName"=>$moduleName,
				"ctrl"=>"Ctrl".ucfirst($moduleName),//controleur du module
				"url"=>($moduleName=="user"?"?ctrl=user&displayUsers=space":"?ctrl=".$moduleName),
				"label"=>Txt::trad(strtoupper($moduleName)."_headerModuleName"),
				"description"=>Txt::trad(strtoupper($moduleName)."_description_module"),
				"rank"=>100,
				"options"=>null
			);
		}
		return $moduleList;
	}

	/*
	 * Verifie si tous les utilisateurs du site sont affectes à l'espace
	 */
	public function allUsersAffected()
	{
		if($this->_allUsersAffected===null)
			{$this->_allUsersAffected=(Db::getVal("SELECT count(*) FROM ap_joinSpaceUser WHERE _idSpace=".$this->_id." AND allUsers=1")>0)  ?  true  :  false;}
		return $this->_allUsersAffected;
	}

	/*
	 * Utilisateurs affectés à l'espace							(ex "users_espace()")
	 */
	public function getUsers($return="objects")
	{
		//Initialise la liste des objets "user"
		if($this->_spaceUsers===null){
			$personSort="ORDER BY ".Ctrl::$agora->personsSort;
			$objUsers=($this->allUsersAffected())  ?  Db::getObjTab("user","SELECT * FROM ap_user ".$personSort)  :  Db::getObjTab("user","SELECT DISTINCT T1.* FROM ap_user T1, ap_joinSpaceUser T2 WHERE T1._id=T2._idUser AND T2._idSpace=".$this->_id." ".$personSort);
			$this->_spaceUsers=$objUsers;
		}
		// Retourne un tableau d'objets  OU  d'identifiants
		if($return=="objects")	{return $this->_spaceUsers;}
		else{
			$tabIds=[];
			foreach($this->_spaceUsers as $objUser)    {$tabIds[]=$objUser->_id;}
			return $tabIds;
		}
	}

	/*
	 * Droit d'accès à l'espace d'un utilisateur
	 * retour : tableau "user=>accessRight"
	 * accessRight :  2=admin / 1=user lambda / 0=aucun accès
	 */
	public function userAccessRight($objUser)
	{
		if(empty($this->_usersAccessRight[$objUser->_id]))
		{
			if($objUser->isAdminGeneral())	{$this->_usersAccessRight[$objUser->_id]=2;}
			else{
				if($objUser->isUser())	{$curRight=Db::getVal("SELECT MAX(accessRight) FROM ap_joinSpaceUser WHERE _idSpace=".$this->_id." AND (_idUser=".(int)$objUser->_id." OR allUsers=1)");}
				if(empty($curRight))	{$curRight=Db::getVal("SELECT public FROM ap_space WHERE _id=".$this->_id);}
				$this->_usersAccessRight[$objUser->_id]=(int)$curRight;
			}
		}
		return $this->_usersAccessRight[$objUser->_id];
	}

	/*
	 * SURCHARGE : Droit d'édition d'un objet
	 */
	public function editRight()
	{
		return ($this->userAccessRight(Ctrl::$curUser)==2);
	}

	/*
	 * Droit de suppression d'un espace (pas l'espace courant)
	 */
	public function deleteRight()
	{
		return (Ctrl::$curUser->isAdminGeneral() && $this->_id!=Ctrl::$curSpace->_id);
	}

	/*
	 * Option d'un module activé pour l'espace ?				(ex "option_module()")
	 */
	public function moduleOptionEnabled($module, $optionName)
	{
		$moduleList=$this->moduleList();
		return (!empty($moduleList[$module]["options"]) && preg_match("/".$optionName."/i",$moduleList[$module]["options"]));
	}

	/*
	 * SURCHARGE : Supprime un espace définitivement!
	 */
	public function delete()
	{
		if($this->deleteRight())
		{
			//Supprime tous les objets affectés uniquement à l'espace courant
			$_idSpaceFirstOther=Db::getVal("SELECT min(_id) FROM ap_space WHERE _id!=".$this->_id);
			$foldersInOtherSpace=Db::getCol("SELECT CONCAT(objectType,'-',_idObject) FROM ap_objectTarget WHERE objectType like '%folder%' AND (_idSpace!=".$this->_id." or _idSpace is null)");
			$objectsInSpace=Db::getTab("SELECT * FROM ap_objectTarget WHERE _idSpace=".$this->_id." AND concat(objectType,_idObject) NOT IN (select concat(objectType,_idObject) from ap_objectTarget where _idSpace!=".$this->_id." or _idSpace is null) ORDER BY objectType, _idObject");
			foreach($objectsInSpace as $tmpObject)
			{
				//Charge l'objet s'il existe toujours (pas encore supprimé depuis le début de la procédure.. cf. arborescences)
				$tmpObj=Ctrl::getObj($tmpObject["objectType"],$tmpObject["_idObject"]);
				if(is_object($tmpObj) && $tmpObj->isNew()==false)
				{
					//Init
					$confirmDelete=true;
					//Dossier : supprime uniquement les dossiers dont toute l'arborescence est accessible à l'espace
					if($tmpObj::isFolder==true)
					{
						//Vérifie s'il y a des sous-dossiers affectés aussi à d'autres espaces..
						$tmpFolderTree=$tmpObj->folderTree("all");
						foreach($tmpFolderTree as $subFolder){
							if(in_array($subFolder::objectType."-".$subFolder->_id, $foldersInOtherSpace))	{$confirmDelete=false;}
						}
						//Si on ne surppime pas le dossier : on réaffecte à l'user courant sur le premier espace (exple : "_idSpace"=>"1","target"=>"U2","accessRight"=>"2")
						if($confirmDelete==false){
							Ctrl::addNotif("Folder ".$tmpObj->name." (".$tmpObj::objectType.") : not deleted!");
							foreach($tmpFolderTree as $subFolder)	{$subFolder->setAccessRights([$_idSpaceFirstOther."_U".Ctrl::$curUser->_id."_2"]);}
						}
					}
					//Agenda perso : pas de suppression, mais une réaffectation au proprio de l'agenda, sur le premier espace (exple : "_idSpace"=>"1","target"=>"U2","accessRight"=>"2")
					if($tmpObj::objectType=="calendar" && $tmpObj->type=="user"){
						$confirmDelete=false;
						$tmpObj->setAccessRights([$_idSpaceFirstOther."_U".$tmpObj->_idUser."_2"]);
					}
					//Supprime l'objet?
					if($confirmDelete==true)	{$tmpObj->delete();}
				}
			}
			//Suppr les jointures & les invitations
			Db::query("DELETE FROM ap_joinSpaceModule WHERE _idSpace=".$this->_id);
			Db::query("DELETE FROM ap_joinSpaceUser WHERE _idSpace=".$this->_id);
			Db::query("DELETE FROM ap_objectTarget WHERE _idSpace=".$this->_id);
			Db::query("DELETE FROM ap_invitation WHERE _idSpace=".$this->_id);
			//Suppr l'espace & Recalcule la taille du 'DATAS/'
			parent::delete();
			File::datasFolderSize(true);
		}
	}
}