<?php
/*
 * Classe parente des Objects type "FOLDER"
 */
 class MdlObjectFolder extends MdlObject
{
	private $_contentDescription=null;
	const isSelectable=true;
	const isFolder=true;
	const hasAttachedFiles=false;
	const hasNotifMail=false;
	public static $displayModeOptions=array("block","line");
	public static $requiredFields=array("name");
	public static $searchFields=array("name","description");
	public static $sortFields=array("name@@asc","name@@desc","description@@asc","description@@desc","dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc");

	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct($objIdOrValues=null)
	{
		parent::__construct($objIdOrValues);
		if($this->_id==1)    {$this->name=Txt::trad("dossier_racine");}//dossier racine
	}
	
	/*
	 * SURCHARGE : droit d'accès du dossier racine (evite un calcul inutile, car géré surtout par les options "AdminRootFolderAddContent" des modules)
	 */
	public function accessRight()
	{
		return ($this->isRootFolder()) ? 2 : parent::accessRight();
	}

	/*
	 * SURCHARGE : Droit d'ajouter du contenu dans le dossier racine / un dossier lambda?
	 */
	public function editContentRight()
	{
		if($this->isRootFolder())	{return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled(static::moduleName,"AdminRootFolderAddContent")==false));}
		else						{return parent::editContentRight();}
	}

	/*
	 * Recupère le dossier conteneur du dossier courant
	 */
	public function containerObj()
	{
		if($this->_containerObj===null && $this->isRootFolder()==false && !empty($this->_idContainer)){
			$this->_containerObj=Ctrl::getObj(static::objectType, $this->_idContainer);
		}
		return $this->_containerObj;
	}

	/*
	 * Contenu d'un dossier  :  nombre d'elements + taille du dossier (module fichiers)			(ex "contenu_dossier()")
	 */
	public function folderContentDescription()
	{
		if($this->_contentDescription===null)
		{
			//Init
			$MdlObjectContent=static::MdlObjectContent;
			$this->_contentDescription="";
			$nbFolders=Db::getVal("SELECT count(*) FROM ".static::dbTable." WHERE ".self::sqlDisplayedObjects($this));
			$nbElems=Db::getVal("SELECT count(*) FROM ".$MdlObjectContent::dbTable." WHERE ".$MdlObjectContent::sqlDisplayedObjects($this));
			////	Nombre de sous-dossiers
			if(!empty($nbFolders))	{$this->_contentDescription.=$nbFolders." ".($nbFolders>1?Txt::trad("dossiers"):Txt::trad("dossier"));}
			////	Nombre d'elements dans le dossier (s'il y en a)  &&  taille des fichiers (si "fileFolder")
			if(!empty($nbElems)){
				if(!empty($this->_contentDescription))	{$this->_contentDescription.=" - ";}
				$this->_contentDescription.=$nbElems." ".Txt::trad($nbElems>1?"elements":"element");
				if(static::objectType=="fileFolder")	{$this->_contentDescription.=" : ".File::displaySize(Db::getVal("SELECT SUM(octetSize) FROM ".$MdlObjectContent::dbTable." WHERE _idContainer=".$this->_id));}
			}
			////	Aucun element..
			if(empty($this->_contentDescription))	{$this->_contentDescription="0 ".Txt::trad("element");}
		}
		return $this->_contentDescription;
	}

	/*
	 * Détails complémentaires sur le dossier => à surcharger!
	 */
	public function folderOtherDetails(){}

	/*
	 * Chemin d'un dossier (fonction récursive)											(ex "chemin()")
	 * $typeReturn= object | id | text | zip | real
	 */
	public function folderPath($typeReturn, $_objCurFolder=null, $_foldersList=array())
	{
		////	Dossier de départ & Ajoute le dossier courant
		if($_objCurFolder==null)  {$_objCurFolder=$this;}
		$_foldersList[]=$_objCurFolder;
		////	Recupère le dossier conteneur si on est pas encore à la racine (vérif que le parent existe!)
		if($_objCurFolder->isRootFolder()==false && !empty($_objCurFolder->containerObj()->_id))	{return $this->folderPath($typeReturn, $_objCurFolder->containerObj(), $_foldersList);}
		////	renvoie le résultat final si on est à la racine
		else
		{
			$_foldersList=array_reverse($_foldersList);//on commence par la racine..
			if($typeReturn=="object") 	{return $_foldersList;}
			if($typeReturn=="id"){
				$foldersIds=array();
				foreach($_foldersList as $tmpFolder)	{$foldersIds[]=$tmpFolder->_id;}
				return $foldersIds;
			}else{
				$textReturn=($typeReturn=="real") ? PATH_MOD_FILE : "";
				$imgSeparate="&nbsp;<img src='app/img/arrowRight.png'>&nbsp;";
				foreach($_foldersList as $cpt=>$objFolder){
					if($typeReturn=="text")												{$textReturn.=($cpt>0?$imgSeparate:"").$objFolder->name; }	//"Dossier racine > sous-dossier testé"
					elseif($typeReturn=="zip")											{$textReturn.=Txt::clean($objFolder->name,"download")."/";}	//"Dossier_racine/sous-dossier_teste/"
					elseif($typeReturn=="real" && $objFolder->isRootFolder()==false)	{$textReturn.=$objFolder->_id."/";}							//"DATAS/gestionnaire_fichiers/2/5/" (sans dossier racine!)
				}
				return $textReturn;
			}
		}
	}

	/*
	 * Arborescence d'objets dossiers (fonction récursive)			(ex "arborescence()")
	 */
	public function folderTree($accessRightMini=1, $_objCurFolder=null, $_treeLevel=0)
	{
		//Init
		$curFoldersList=[];
		if($_objCurFolder==null)	{$_objCurFolder=$this;}
		//Ajoute le dossier courant ?
		if($accessRightMini=="all" || $_objCurFolder->accessRight()>=$accessRightMini)
		{
			$_objCurFolder->treeLevel=$_treeLevel;
			$curFoldersList[]=$_objCurFolder;
			//Récupère récursivement les sous-dossiers (toujours triés par nom, pas avec tri courant) ?
			$sqlDisplayedObjects=($accessRightMini=="all")  ?  "_idContainer=".$_objCurFolder->_id  :  static::sqlDisplayedObjects($_objCurFolder);
			foreach(Db::getObjTab(static::objectType, "SELECT * FROM ".static::dbTable." WHERE ".$sqlDisplayedObjects." ORDER BY name ASC")  as $subFolder)
				{$curFoldersList=array_merge($curFoldersList, $this->folderTree($accessRightMini,$subFolder,$_treeLevel+1));}
		}
		//Renvoie le résultat
		return $curFoldersList;
	}

	/*
	 * Controle si un dossier se trouve dans l'arborecence du dossier courant
	 */
	public function isInFolderTree($folderId)
	{
		foreach($this->folderTree("all") as $tmpFolder){
			if($folderId==$tmpFolder->_id)  {return true;}
		}
	}

	/*
	 * VUE : Liste de dossiers à afficher
	 */
	public function foldersList($objDBlockCenter=false)
	{
		$vDatas["objDisplayClass"]=(static::getDisplayMode()=="block") ? "objDBlock" : "objDLine";//Affichage block/Ligne
		if($vDatas["objDisplayClass"]=="objDBlock" && $objDBlockCenter==true)    {$vDatas["objDisplayClass"].=" objDBlockCenter";}//Affichage block centré (ModFile)?
		$vDatas["foldersList"]=Db::getObjTab(static::objectType, "SELECT * FROM ".static::dbTable." WHERE ".static::sqlDisplayedObjects($this)." ".static::sqlSort($this));
		//Ne rien renvoyer si ya pas de de dossier!
		if(!empty($vDatas["foldersList"]))	{return Ctrl::getVue(Req::commonPath."VueObjFoldersList.php",$vDatas);}
	}

	/*
	 * SURCHARGE : Droit d'édition
	 */
	public function editRight()
	{
		return (parent::editRight() && $this->isRootFolder()==false);
	}

	/*
	 * SURCHARGE : Droit de suppression
	 */
	public function deleteRight()
	{
		return (parent::deleteRight() && $this->isRootFolder()==false);
	}

	/*
	 * SURCHARGE : Suppression d'un objet Dossier.. et son arborescence
	 */
	public function delete()
	{
		if($this->deleteRight())
		{
			////	Supprime l'arborescence du dossier ("0"=>Récupère tout!)
			foreach($this->folderTree("all") as $tmpFolder)
			{
				//Supprime les fichiers du dossier courant
				$MdlObjectContent=static::MdlObjectContent;
				$filesList=Db::getObjTab($MdlObjectContent::objectType, "SELECT * FROM ".$MdlObjectContent::dbTable." WHERE _idContainer=".$tmpFolder->_id);
				foreach($filesList as $tmpFile)  {$tmpFile->delete();}
				//Supprime le dossier.. sauf le dossier courant : supprimé à la fin
				if($tmpFolder->_id!=$this->_id)  {$tmpFolder->delete();}
			}
			////	Supprime le dossier courant
			if(static::objectType=="fileFolder"){
				$tmpFolderPath=$this->folderPath("real");
				if($tmpFolderPath!=PATH_MOD_FILE && is_dir($tmpFolderPath))  {File::rm($tmpFolderPath);}
			}
			parent::delete();
		}
	}
}