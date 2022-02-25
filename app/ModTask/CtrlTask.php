<?php
/*
 * Controleur du module "Task"
 */
class CtrlTask extends Ctrl
{
	const moduleName="task";
	public static $folderObjectType="taskFolder";
	public static $moduleOptions=["AdminRootFolderAddContent"];
	public static $MdlObjects=array("MdlTask","MdlTaskFolder");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		$vDatas["foldersList"]=self::$curContainer->foldersList();
		$filterPriority=Req::getParam("filterPriority")>=1 ? "AND priority=".Db::formatParam("filterPriority") : null;
		$vDatas["tasksList"]=Db::getObjTab("task", "SELECT * FROM ap_task WHERE ".MdlTask::sqlDisplayedObjects(self::$curContainer)." ".$filterPriority." ".MdlTask::sqlSort(self::$curContainer));
		////	TIMELINE/GANTT
		$timelineBegin=$timelineEnd=null;
		$vDatas["timelineTasks"]=$vDatas["timelineDays"]=[];
		//Si ya des taches qui sont sur une période : détermine la période de la timeline
		foreach($vDatas["tasksList"] as $tmpTask)
		{
			//Ajoute la tache?
			if(!empty($tmpTask->dateBegin) && !empty($tmpTask->dateEnd))
			{
				//Prépare le début/fin de la timeline
				if(empty($timelineBegin) || strtotime($tmpTask->dateBegin)<$timelineBegin)	{$timelineBegin=strtotime(date("Y-m-d 00:00",strtotime($tmpTask->dateBegin)));}
				if(empty($timelineEnd) || strtotime($tmpTask->dateEnd)>$timelineEnd)		{$timelineEnd=strtotime(date("Y-m-d 23:59",strtotime($tmpTask->dateEnd)));}
				//Prépare la Tache de la timeline
				$tmpTask->timeBegin=strtotime(date("Y-m-d 00:00",strtotime($tmpTask->dateBegin)));
				$tmpTask->timeEnd=strtotime(date("Y-m-d 23:59",strtotime($tmpTask->dateEnd)));
				$tmpTask->timelineColspan=ceil(($tmpTask->timeEnd-$tmpTask->timeBegin)/86400);
				$vDatas["timelineTasks"][]=$tmpTask;
			}
		}
		//Prépare la timeline si il y en a une
		if(!empty($timelineBegin))
		{
			//Tri les tasks de la timeline par "dateBegin"
			usort($vDatas["timelineTasks"],function($objA,$objB){
				return (strtotime($objA->dateBegin)-strtotime($objB->dateBegin));
			});
			//60 jours mini pour la timeline (soit 5184000sec)
			if(($timelineEnd-$timelineBegin) < 5184000)   {$timelineEnd=$timelineBegin+5184000;}
			//Mois et Jours du header de la timeline
			$tmpMonth=null;
			for($dayTimeBegin=$timelineBegin; $dayTimeBegin<=$timelineEnd; $dayTimeBegin+=86400)
			{
				$newMonth=$tmpMonth!=date("m",$dayTimeBegin);
				$vDatas["timelineDays"][]=array(
					"curDate"=>date("Y-m-d",$dayTimeBegin),
					"timeBegin"=>$dayTimeBegin,
					"newMonthLabel"=>$newMonth==true ? ucfirst(Txt::formatime("%B %Y",$dayTimeBegin)) : null,
					"newMonthColspan"=>(date("t",$dayTimeBegin)-date("j",$dayTimeBegin)+1),
					"classBorderLeft"=>($dayTimeBegin==$timelineBegin || date("N",$dayTimeBegin)==1 || date("j",$dayTimeBegin)==1)  ?  "vTimelineLeftBorder"  :  null,//début de timeline/de mois/de semaine : affiche les pointillés
					"dayLabel"=>date("j",$dayTimeBegin)
				);
				$tmpMonth=date("m",$dayTimeBegin);
			}
		}
		////	Affiche la vue
		$vDatas["timelineBegin"]=$timelineBegin;
		$vDatas["timelineEnd"]=$timelineEnd;
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginParams=array_merge($pluginParams,array("MdlObjectFolder"=>"MdlTaskFolder"));
		$pluginsList=self::getPluginsFolders($pluginParams);
		foreach(MdlTask::getPluginObjects($pluginParams) as $tmpObj)
		{
			$tmpObj->pluginModule=self::moduleName;
			$tmpObj->pluginIcon=(isset($tmpObj->pluginIsCurrent) && $pluginParams["type"]=="dashboard") ? "newObj2.png" : "task/icon.png";
			$tmpObj->pluginLabel=(!empty($tmpObj->title)) ? $tmpObj->title : Txt::reduce($tmpObj->description);
			$tmpObj->pluginTitle=$tmpObj->containerObj()->folderPath("text")."<br>".$tmpObj->displayAutor(true,true);
			if(!empty($tmpObj->dateBegin) || !empty($tmpObj->dateEnd)){
				if(!empty($tmpObj->dateBegin))		{$displayTime=Txt::displayDate($tmpObj->dateBegin,"normal",$tmpObj->dateEnd);}
				elseif(!empty($tmpObj->dateEnd))	{$displayTime=Txt::trad("fin")." : ".Txt::displayDate($tmpObj->dateEnd,"normal");}
				$tmpObj->pluginTitle.="<br>".$displayTime;
			}			
			$tmpObj->pluginJsIcon="redir('".$tmpObj->getUrl("container")."',true);";
			$tmpObj->pluginJsLabel="lightboxOpen('".$tmpObj->getUrl("vue")."');";
			$pluginsList[]=$tmpObj;
		}
		return $pluginsList;
	}

	/*
	 * ACTION : tache détaillé
	 */
	public static function actionTaskVue()
	{
		$curObj=Ctrl::getTargetObj();
		$curObj->controlRead();
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueTask.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'une tache
	 */
	public static function actionTaskEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$dateBegin=Txt::formatDate(Req::getParam("dateBegin")." ".Req::getParam("timeBegin"), "inputDatetime", "dbDatetime");
			$dateEnd=Txt::formatDate(Req::getParam("dateEnd")." ".Req::getParam("timeEnd"), "inputDatetime", "dbDatetime");
			$curObj=$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description","editor").", dateBegin=".Db::format($dateBegin).", dateEnd=".Db::format($dateEnd).", advancement=".Db::formatParam("advancement").", priority=".Db::formatParam("priority").", budgetAvailable=".Db::formatParam("budgetAvailable").", budgetEngaged=".Db::formatParam("budgetEngaged").", humanDayCharge=".Db::formatParam("humanDayCharge","float").", responsiblePersons=".Db::formatTab2txt(Req::getParam("responsiblePersons")));
			//Notifie par mail & Ferme la page
			$curObj->sendMailNotif("<b>".$curObj->title."</b><br>".$curObj->description);
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueTaskEdit.php",$vDatas);
	}
}