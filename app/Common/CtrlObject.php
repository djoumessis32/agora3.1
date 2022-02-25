<?php
/*
 * Menus & actions sur les objets
 */
class CtrlObject extends Ctrl
{
	/* Inexistant dans ce contexte: pas un module à part entière*/
	public static function actionDefault(){}

	/*
	 * ACTION : Affiche les logs d'un objet
	 */
	public static function actionLogs()
	{
		if(Req::isParam("targetObjId")){
			$curObj=self::getTargetObj();
			if($curObj->editRight()){
				$vDatas["logsList"]=Db::getTab("SELECT action, _idUser, UNIX_TIMESTAMP(date) as date, comment FROM ap_log WHERE objectType='".$curObj::objectType."' AND _idObject=".$curObj->_id." ORDER BY date");
				static::displayPage(Req::commonPath."VueObjLogs.php",$vDatas);
			}
		}
	}

	/*
	 * ACTION : Telecharge un fichier joint
	 */
	public static function actionDownloadAttachedFile()
	{
		$curFile=MdlObject::getAttachedFile(Req::getParam("_id"));
		if(is_file($curFile["path"]) && $curFile["containerObj"]->readRight())
			{File::download($curFile["name"],$curFile["path"]);}
	}

	/*
	 * ACTION : Affiche un fichier joint
	 */
	 public static function actionDisplayAttachedFile()
	{
		$curFile=MdlObject::getAttachedFile(Req::getParam("_id"));
		if(is_file($curFile["path"]) && $curFile["containerObj"]->readRight())
			{File::display($curFile["path"]);}
	 }

	/*
	 * ACTION : Supprime un fichier joint
	 */
	public static function actionDeleteAttachedFile()
	{
		$curFile=MdlObject::getAttachedFile(Req::getParam("_id"));
		if(is_file($curFile["path"]) && $curFile["containerObj"]->editRight()){
			$deleteResult=$curFile["containerObj"]->deleteAttachedFile($curFile);
			if($deleteResult==true)  {echo "true";}
		}
	}

	/*
	 * ACTION : Supprime un objet / des objets sélectionnés
	 */
	public static function actionDelete()
	{
		$updateDatasFolderSize=$containerUrl=null;
		foreach(self::getTargetObjects() as $tmpObj){
			if(empty($containerUrl))	{$containerUrl=$tmpObj->getUrl("container");}
			if($tmpObj::moduleName=="file" && $updateDatasFolderSize==null)  {$updateDatasFolderSize=true;}
			$tmpObj->delete();
		}
		if($updateDatasFolderSize==true)	{File::datasFolderSize(true);}//Maj en session
		self::redir($containerUrl);//Redirection sur la page du conteneur de l'objet supprimé
	}

	/*
	 * ACTION : Deplace les objets sélectionnés dans un autre dossier
	 */
	public static function actionFolderMove()
	{
		//Validation du formulaire
		if(Req::isParam("formValidate") && Req::isParam("newFolderId")){
			foreach(self::getTargetObjects() as $tmpObj)    {$tmpObj->folderMove(Req::getParam("newFolderId"));}
			static::lightboxClose();
		}
		//Affiche le menu de déplacement de dossier
		self::menuFolderTree("move");
	}

	/*
	 * VUE : Menu d'arborescence de dossiers
	 * $action=="nav": redirection vers un dossier  || $action=="move": selectionne le dossier pour déplacer un element dans l'arbo.
	 */
	public static function menuFolderTree($context="nav")
	{
		$vDatas["context"]=$context;
		$vDatas["folderTree"]=Ctrl::getObj(self::$curContainer->getType(),1)->folderTree();//arborescence depuis le dossier racine
		$vueFolderTree="VueMenuFolderTree.php";
		if($context=="move")	{static::displayPage(Req::commonPath.$vueFolderTree,$vDatas);}//Affiche directement la vue
		else					{return Ctrl::getVue(Req::commonPath.$vueFolderTree,$vDatas);}//renvoie la vue
	}

	/*
	 * VUE : Menu du Chemin d'un dossier			(ex "menu_chemin()")
	 */
	public static function menuFolderPath()
	{
		return Ctrl::getVue(Req::commonPath."VueMenuFolderPath.php");
	}

	/*
	 * ACTION VUE : Edition d'un dossier
	 */
	public static function actionFolderEdit()
	{
		////	Charge le dossier et Controle d'accès: dossier existant / nouveau dossier
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Enregistre et recharge l'objet, puis étend les droits aux sous dossiers?
			$curObj=$curObj->createUpdate("name=".Db::formatParam("name").", description=".Db::formatParam("description"));
			if(Req::isParam("extendToSubfolders")){
				foreach($curObj->folderTree("all") as $tmpObj)	{$tmpObj->setAccessRights();}
			}
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		static::displayPage(Req::commonPath."VueObjFolderEdit.php",$vDatas);
	}

	/*
	 * AJAX : Controle si un autre objet fichier/dossier porte le même nom (dans le même dossier parent)
	 */
	public static function actionControlDuplicateName()
	{
		$isDuplicate="false";
		if(Req::isParam(["targetObjId","targetObjIdContainer","controledName"]))
		{
			//Charge l'objet conteneur et courant
			$curObj=Ctrl::getTargetObj();
			$curObjContainer=Ctrl::getTargetObj(Req::getParam("targetObjIdContainer"));
			//Recherche les doublons affectés à l'espace courant, dans le dossier courant.. en excluant l'objet courant, si déjà créé
			$sqlSpaceObjects="AND _id IN (select _idObject as _id from ap_objectTarget where objectType='".$curObj::objectType."' and _idSpace=".Ctrl::$curSpace->_id.")";
			$sqlExcludeCurObj=(!empty($curObj->_id))  ?  "AND _id!=".$curObj->_id  :  null;
			$nbDuplicate=Db::getVal("SELECT count(*) FROM ".$curObj::dbTable." WHERE _idContainer=".$curObjContainer->_id." ".$sqlSpaceObjects." ".$sqlExcludeCurObj." AND name=".Db::formatParam("controledName"));
			if($nbDuplicate>0)   {$isDuplicate="true";}
		}
		echo $isDuplicate;
	}

	/*
	 * AJAX : Controle si tous les sous-dossiers peuvent être supprimés
	 */
	public static function actionSubFoldersDeleteControl()
	{
		$curFolder=Ctrl::getTargetObj();
		$folderTreeFull=$curFolder->FolderTree("all");
		$folderTreeReadAccess=$curFolder->FolderTree(2);
		echo (count($folderTreeFull)==count($folderTreeReadAccess)) ? "true" : "false";
	}

	/*
	 * ACTION : formulaire de recherche
	 */
	public static function actionSearch()
	{
		$vDatas=array();
		//Champs de recherche de tous les objets
		$vDatas["searchFields"]=array();
		foreach(self::$curSpace->moduleList() as $tmpModule)
		{
			if(isset($tmpModule["ctrl"]::$MdlObjects))
			{
				//Pour chaque objet du module, on liste les champs de recherche : modifie le "title" et le "checked"
				foreach($tmpModule["ctrl"]::$MdlObjects as $tmpMdlObject)
				{
					foreach($tmpMdlObject::$searchFields as $tmpField)
					{
						$vDatas["searchFields"][$tmpField]["checked"]=(!Req::isParam("searchFields") || in_array($tmpField,Req::getParam("searchFields"))) ? "checked" : "";
						if(empty($vDatas["searchFields"][$tmpField]["title"]))	{$vDatas["searchFields"][$tmpField]["title"]="";}
						$folderInTitle=preg_match("/".Txt::trad("objectFolder")."/i",$vDatas["searchFields"][$tmpField]["title"]);
						if($tmpMdlObject::isFolder==true && $folderInTitle==false)	{$vDatas["searchFields"][$tmpField]["title"].="- ".Txt::trad("OBJECTfolder")."<br>";}
						elseif($tmpMdlObject::isFolder==false)						{$vDatas["searchFields"][$tmpField]["title"].="- ".Txt::trad("OBJECT".$tmpMdlObject::objectType)."<br>";}
					}
				}
			}
		}
		//Resultat de recherche
		if(Req::isParam("formValidate"))
		{
			//prépare la recherche
			$pluginParams=array(
				"type"=>"search",
				"searchText"=>Txt::clean(Req::getParam("searchText"),"normal"," "),
				"searchMode"=>Req::getParam("searchMode"),
				"searchFields"=>Req::getParam("searchFields"),
				"creationDate"=>Req::getParam("creationDate"),
				"searchModules"=>Req::getParam("searchModules")
			);
			//récupère les résultats!
			$vDatas["pluginsSearchResult"]=array();
			foreach(self::$curSpace->moduleList() as $tmpModule){
				if(in_array($tmpModule["ctrl"]::moduleName,Req::getParam("searchModules")) && method_exists($tmpModule["ctrl"],"plugin")){
					$vDatas["pluginsSearchResult"]=array_merge($vDatas["pluginsSearchResult"],$tmpModule["ctrl"]::plugin($pluginParams));
				}
			}
		}
		static::displayPage(Req::commonPath."VueSearch.php",$vDatas);
	}
}