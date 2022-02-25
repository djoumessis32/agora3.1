<?php
/*
 * Controleur du module des "Log"
 */
class CtrlLog extends Ctrl
{
	const moduleName="log";

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		if(Ctrl::$curUser->isAdminGeneral()==false)  {self::noAccessExit();}
		$vDatas["logList"]=self::logList();
		static::displayPage("VueIndex.php",$vDatas);
	}
	
	/*
	 * renvoie la liste des Logs de l'espace
	 */
	public static function logList()
	{
		$logList=Db::getTab("SELECT *, _idSpace as spaceName, _idUser as userName  FROM ap_log ORDER BY date desc");
		//Récupère / modifie les libellés
		foreach($logList as $tmpKey=>$tmpLog){
			$moduleTrad=strtoupper($tmpLog["moduleName"])."_headerModuleName";
			$objectTrad="OBJECT".$tmpLog["objectType"];
			if(Txt::isTrad($moduleTrad))				{$tmpLog["moduleName"]=Txt::trad($moduleTrad);}//Nom du module
			if(Txt::isTrad("LOG_".$tmpLog["action"]))	{$tmpLog["action"]=Txt::trad("LOG_".$tmpLog["action"]);}//Action du logs (si ya une trad pour l'action..)
			if(!empty($tmpLog["objectType"])){//type d'objet?
				if(stristr($tmpLog["objectType"],"folder"))	{$tmpLog["objectType"]=Txt::trad("OBJECTfolder");}
				elseif(Txt::isTrad($objectTrad))			{$tmpLog["objectType"]=Txt::trad($objectTrad);}
			}
			$tmpLog["spaceName"]=Ctrl::getObj("space",$tmpLog["spaceName"])->name;//Nom de l'espace (et non _idSpace)
			$tmpLog["userName"]=(!empty($tmpLog["userName"]))  ?  Ctrl::getObj("user",$tmpLog["userName"])->display()  :  "<i>".Txt::trad("guest")."</i>";//Nom et prenom de l'user concerné (et non _idUser)
			$logList[$tmpKey]=$tmpLog;
		}
		return $logList;
	}
	
	/*
	 * Revoie un "select" pour filter les logs en fonction d'un champ
	 */
	public static function fieldFilterSelect($fieldLabel, $fieldName)
	{
		//Récupère les options du menu
		$optionsFilter=null;
		foreach(Db::getCol("SELECT DISTINCT ".$fieldName." FROM ap_log L LEFT JOIN ap_space S ON S._id=L._idSpace WHERE ".$fieldName." is not null ORDER BY ".$fieldName." asc")  as  $tmpVal){
			if(Txt::isTrad("LOG_".$tmpVal))									{$tmpLabel=Txt::trad("LOG_".$tmpVal);}
			elseif(Txt::isTrad(strtoupper($tmpVal)."_headerModuleName"))	{$tmpLabel=Txt::trad(strtoupper($tmpVal)."_headerModuleName");}
			elseif(Txt::isTrad("OBJECT".$tmpVal))							{$tmpLabel=Txt::trad("OBJECT".$tmpVal);}
			else															{$tmpLabel=$tmpVal;}
			$optionsFilter.="<option value=\"".$tmpLabel."\">".$tmpLabel."</option>";
		}
		//renvoie le "select" du champ
		return "<select name=\"search_".$fieldLabel."\" class='searchInit'><option value=''>".Txt::trad("LOG_filtre")." ".$fieldLabel."</option>".$optionsFilter."</select>";
	}
	
	/*
	 * ACTION : TELECHARGE LES LOGS AU FORMAT CSV
	 */
	public static function actionLogsDownload()
	{
		if(Ctrl::$curUser->isAdminGeneral()==false)  {self::noAccessExit();}
		$fileContent=null;
		foreach(self::logList() as $tmpLog){
			foreach($tmpLog as $tmpLogFiled)	{$fileContent.='"'.$tmpLogFiled.'";';}
			$fileContent.="\n";
		}
		File::download(Ctrl::$agora->name." - LOGS.csv", false, $fileContent);
	}
}