<?php
/*
 * Controleur du module "Link"
 */
class CtrlLink extends Ctrl
{
	const moduleName="link";
	public static $folderObjectType="linkFolder";
	public static $moduleOptions=["AdminRootFolderAddContent"];
	public static $MdlObjects=array("MdlLink","MdlLinkFolder");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		$vDatas["foldersList"]=self::$curContainer->foldersList();
		$vDatas["linkList"]=Db::getObjTab("link", "SELECT * FROM ap_link WHERE ".MdlLink::sqlDisplayedObjects(self::$curContainer)." ".MdlLink::sqlSort(self::$curContainer));
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginParams=array_merge($pluginParams,array("MdlObjectFolder"=>"MdlLinkFolder"));
		$pluginsList=self::getPluginsFolders($pluginParams);
		foreach(MdlLink::getPluginObjects($pluginParams) as $tmpObj)
		{
			$tmpObj->pluginModule=self::moduleName;
			$tmpObj->pluginIcon=self::moduleName."/icon.png";
			$tmpObj->pluginLabel=(!empty($tmpObj->description)) ? Txt::reduce($tmpObj->description) : $tmpObj->adress;
			$tmpObj->pluginTitle=$tmpObj->containerObj()->folderPath("text")."<br>".$tmpObj->displayAutor(true,true);
			$tmpObj->pluginJsIcon="redir('".$tmpObj->getUrl("container")."',true);";
			$tmpObj->pluginJsLabel="window.open('".addslashes($tmpObj->adress)."');";
			$pluginsList[]=$tmpObj;
		}
		return $pluginsList;
	}

	/*
	 * ACTION : Ajout d'un lien
	 */
	public static function actionLinkEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$curObj=$curObj->createUpdate("adress=".Db::formatParam("adress").", description=".Db::formatParam("description"));
			//Notifie par mail & Ferme la page
			$curObj->sendMailNotif("<a href=\"".$curObj->adress."\" target='_blank'>".$curObj->adress."</a><br>".$curObj->description);
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueLinkEdit.php",$vDatas);
	}
}