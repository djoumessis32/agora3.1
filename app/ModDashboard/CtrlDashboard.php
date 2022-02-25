<?php
/*
 * Controleur du module "Dashboard"
 */
class CtrlDashboard extends Ctrl
{
	const moduleName="dashboard";
	public static $moduleOptions=["ajout_actualite_admin"];
	public static $MdlObjects=array("MdlDashboardNews");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		static::$isMainPage=true;
		////	Init les périodes des plugins "Dashboard"
		$vDatas["pluginPeriod"]=self::prefUser("pluginPeriod",null,"otherPeriod");
		if(empty($vDatas["pluginPeriod"]))	{$vDatas["pluginPeriod"]="week";}
		//depuis la dernière connexion
		$timeConnectBegin=(!empty(self::$curUser->previousConnection)) ? (int)self::$curUser->previousConnection : time();
		$timeConnectEnd=time();
		$vDatas["pluginConnectLabel"]=strftime("%A %d %B %Y",$timeConnectBegin)." > ".Txt::trad("aujourdhui");
		//jour
		$timeDayBegin=strtotime(date("Y-m-d 00:00:00"));
		$timeDayEnd=strtotime(date("Y-m-d 23:59:59"));
		$vDatas["pluginDayLabel"]=strftime("%A %d %B");
		//cette semaine
		$weekDay=str_replace("0","7",date("w")); //Jour de la semaine (1=lundi,7=dimanche)
		$timeWeekBegin=strtotime(date("Y-m-d 00:00:00"))-(86400*($weekDay-1));
		$timeWeekEnd=$timeWeekBegin+(86400*7)-1;
		$vDatas["pluginWeekLabel"]=strftime("%A %d %B",$timeWeekBegin)." > ".strftime("%A %d %B",$timeWeekEnd);
		//ce mois
		$timeMonthBegin=strtotime(date("Y-m-01 00:00:00"));
		$timeMonthEnd=strtotime(date("Y-m-t 23:59:59"));//nb de jours dans le mois
		$vDatas["pluginMonthLabel"]=strftime("%A %d %B",$timeMonthBegin)." > ".strftime("%A %d %B",$timeMonthEnd);
		//autre période
		$timeOtherBegin=(Req::isParam("pluginBegin")) ? strtotime(Txt::formatDate(Req::getParam("pluginBegin"),"inputDate","dbDate")." 00:00") : null;
		$timeOtherEnd=(Req::isParam("pluginEnd")) ? strtotime(Txt::formatDate(Req::getParam("pluginEnd"),"inputDate","dbDate")." 23:59") : null;
		//Dates de début / fin de période de la sélection
		if($vDatas["pluginPeriod"]=="connect")		{$vDatas["pluginTimeBegin"]=$timeConnectBegin;	$vDatas["pluginTimeEnd"]=$timeConnectEnd;	}
		elseif($vDatas["pluginPeriod"]=="day")		{$vDatas["pluginTimeBegin"]=$timeDayBegin;		$vDatas["pluginTimeEnd"]=$timeDayEnd;}
		elseif($vDatas["pluginPeriod"]=="week")		{$vDatas["pluginTimeBegin"]=$timeWeekBegin;		$vDatas["pluginTimeEnd"]=$timeWeekEnd;}
		elseif($vDatas["pluginPeriod"]=="month")	{$vDatas["pluginTimeBegin"]=$timeMonthBegin;	$vDatas["pluginTimeEnd"]=$timeMonthEnd;}
		else										{$vDatas["pluginTimeBegin"]=$timeOtherBegin;	$vDatas["pluginTimeEnd"]=$timeOtherEnd;}
		////	Details de la periode des plugins
		$vDatas["pluginPeriodTitle"]=Txt::trad("DASHBOARD_new_elems_info").strftime("%A %d %B",$vDatas["pluginTimeBegin"])." &nbsp;".Txt::trad("et")."&nbsp; ".strftime("%A %d %B",$vDatas["pluginTimeEnd"]);
		$vDatas["pluginPeriodTitleCurrent"]=Txt::trad("DASHBOARD_new_elems_realises_info").strftime("%A %d %B",$vDatas["pluginTimeBegin"])." &nbsp;".Txt::trad("et")."&nbsp; ".strftime("%A %d %B",$vDatas["pluginTimeEnd"]);
		////	Plugin "dashboard" de chaque module
		$vDatas["pluginsDashboard"]=$newContainers=[];
		$pluginParams=array("type"=>"dashboard", "dateTimeBegin"=>date("Y-m-d H:i",$vDatas["pluginTimeBegin"]), "dateTimeEnd"=>date("Y-m-d H:i",$vDatas["pluginTimeEnd"]));
		foreach(self::$curSpace->moduleList() as $tmpModule)
		{
			if(method_exists($tmpModule["ctrl"],"plugin"))
			{
				//Ajoute les plugins du module
				foreach($tmpModule["ctrl"]::plugin($pluginParams) as $tmpPlugin)
				{
					//Nouveau conteneur : on n'affiche pas les nouveaux elements qu'il contient (exple: pour pas afficher tous les fichiers d'un nouveau dossier)
					if(empty($tmpPlugin->pluginBlockMenu)){
						if($tmpPlugin::isContainer())	{$newContainers[]=$tmpPlugin->_targetObjId;}
						elseif($tmpPlugin::isContainersContent() && is_object($tmpPlugin->containerObj()) && in_array($tmpPlugin->containerObj()->_targetObjId,$newContainers))	{continue;}
					}
					//Ajoute le plugin
					$vDatas["pluginsDashboard"][]=$tmpPlugin;
				}
			}
		}
		////	Objets News (actualités)
		$offlineNewsCount=MdlDashboardNews::displayedNews(true,true);
		$vDatas["offlineNewsCount"]=(!empty($offlineNewsCount)) ? "(".$offlineNewsCount.")" : null;
		$vDatas["newsList"]=MdlDashboardNews::displayedNews(Req::getParam("offlineNews"));
		foreach($vDatas["newsList"] as $newsKey=>$objNews)
		{
			//Date Online/Offline en Tooltip?
			$newsTimeTooltip=null;
			if(!empty($objNews->dateOnline))	{$newsTimeTooltip.="<div>".Txt::trad("DASHBOARD_dateOnline")." ".Txt::displayDate($objNews->dateOnline,"date")."</div>";}
			if(!empty($objNews->dateOffline))	{$newsTimeTooltip.="<div>".Txt::trad("DASHBOARD_dateOffline")." ".Txt::displayDate($objNews->dateOffline,"date")."</div>";}
			// Date de création & finalisation
			$objNews->displayTime=(!empty($newsTimeTooltip)) ? "<abbr title=\"".$newsTimeTooltip."\">".Txt::displayDate($objNews->dateCrea)."</abbr>" : Txt::displayDate($objNews->dateCrea);
			$vDatas["newsList"][$newsKey]=$objNews;
		}
		////	Affiche la vue
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginsList=array();
		if($pluginParams["type"]=="search")
		{
			foreach(MdlDashboardNews::getPluginObjects($pluginParams) as $objNews)
			{
				$objNews->pluginModule=self::moduleName;
				$objNews->pluginIcon=self::moduleName."/icon.png";
				$objNews->pluginLabel="<span onclick=\"$('.pluginNews".$objNews->_id."').toggle(200);\">".Txt::reduce($objNews->description)."<span>
									  <div class='pluginNews".$objNews->_id."' style='display:none;padding:10px;'>".$objNews->description."</div>";
				$objNews->pluginTitle=$objNews->displayAutor(true,true);
				$objNews->pluginJsIcon="$('.pluginNews".$objNews->_id."').toggle(200);";
				$objNews->pluginJsLabel=null;
				$pluginsList[]=$objNews;
			}
		}
		return $pluginsList;
	}

	/*
	 * ACTION : Edition d'une actualité
	 */
	public static function actionDashboardNewsEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		if(MdlDashboardNews::addRight()==false)   {self::noAccessExit();}
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$curObj=$curObj->createUpdate("description=".Db::formatParam("description","editor").", une=".Db::formatParam("une").", offline=".Db::formatParam("offline").", dateOnline=".Db::formatParam("dateOnline","date").", dateOffline=".Db::formatParam("dateOffline","date"));
			//Notifie par mail & Ferme la page
			$curObj->sendMailNotif(Txt::reduce(strip_tags($curObj->description)));
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["objNews"]=$curObj;
		static::displayPage("VueDashboardNewsEdit.php",$vDatas);
	}
}
