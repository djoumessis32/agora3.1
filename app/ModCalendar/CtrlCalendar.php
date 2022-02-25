<?php
/*
 * Controleur du module "Calendar"
 */
class CtrlCalendar extends Ctrl
{
	const moduleName="calendar";
	public static $moduleOptions=["ajout_agenda_ressource_admin","ajout_categorie_admin"];
	public static $MdlObjects=array("MdlCalendar","MdlCalendarEvent");

	/*
	 * ACTION PAR DEFAUT
	 */
	public static function actionDefault()
	{
		////	MODE D'AFFICHAGE (day / workWeek / week / month)  &  TEMPS DE RÉFÉRENCE  &  JOURS FÉRIÉS
		$displayMode=self::prefUser("calendarDisplayMode","displayMode");
		if(empty($displayMode))	{$displayMode="month";}
		$vDatas["displayMode"]=$displayMode;
		$vDatas["curTime"]=$curTime=(Req::isParam("curTime")) ? Req::getParam("curTime") : time();
		$vDatas["celebrationDays"]=Trad::celebrationDays(date("Y",$curTime));
		////	AFFICHAGE MOIS / SEMAINE / JOUR : PREPARE LES DATES, MENUS ET LIBELLES
		if($displayMode=="month")
		{
			$vDatas["timeBegin"]=strtotime(date("Y-m",$curTime)."-01 00:00");
			$vDatas["timeEnd"]  =strtotime(date("Y-m",$curTime)."-".date("t",$curTime)." 23:59");
			$vDatas["timePrev"]=strtotime("-1 month",$curTime);
			$vDatas["timeNext"]=strtotime("+1 month",$curTime);
			$vDatas["labelPeriod"]=ucfirst(Txt::formatime("%B %Y",$curTime));//exple "mars 2016"
			$vDatas["timeDisplayedBegin"]=strtotime("-".(date("N",$vDatas["timeBegin"])-1)." days", $vDatas["timeBegin"]);	//Commence tjs l'affichage par un lundi. Exple: si le mois commence un mercredi, on enlève 2 jours pour afficher lundi et mardi du mois précédent
			$vDatas["timeDisplayedEnd"]=strtotime("+".(7-date("N",$vDatas["timeEnd"]))." days", $vDatas["timeEnd"]);		//Termine tjs l'affichage par un dimanche. Idem mais pour la fin du mois
			//Menu pour changer d'année et de mois
			$vDatas["labelPeriodMenu"]=null;
			for($tmpMonth=1; $tmpMonth<=12; $tmpMonth++){
				$tmpMonthTime=strtotime(date("Y",$curTime)."-".($tmpMonth>9?$tmpMonth:"0".$tmpMonth)."-01");
				$vDatas["labelPeriodMenu"].="<div class=\"vSelectYearsMonth ".(date("Y-m",$curTime)==date("Y-m",$tmpMonthTime)?"sLinkSelect":"sLink")."\" onClick=\"redir('?ctrl=calendar&curTime=".$tmpMonthTime."')\">".Txt::formatime("%B",$tmpMonthTime)."</div>";
			}
			$vDatas["labelPeriodMenu"].="<hr class='hrGradient'>";
			for($tmpYear=date("Y")-3; $tmpYear<=date("Y")+5; $tmpYear++){
				$tmpYearTime=strtotime($tmpYear."-".date("m",$curTime)."-01");
				$vDatas["labelPeriodMenu"].="<div class=\"vSelectYearsMonth ".(date("Y",$curTime)==$tmpYear?"sLinkSelect":"sLink")."\" onClick=\"redir('?ctrl=calendar&curTime=".$tmpYearTime."')\">".$tmpYear."</div>";
			}
		}
		elseif(preg_match("/week/i",$displayMode))
		{
			$weekTimeBegin=strtotime("-".(date("N",$curTime)-1)." days",$curTime);//lundi=0 => dimanche=6
			$weekTimeEnd=($displayMode=="week") ? strtotime("+6 days",$weekTimeBegin) : strtotime("+4 days",$weekTimeBegin);
			$vDatas["timeBegin"]=strtotime(date("Y-m-d",$weekTimeBegin)." 00:00");
			$vDatas["timeEnd"]	=strtotime(date("Y-m-d",$weekTimeEnd)." 23:59");
			$vDatas["timePrev"]=strtotime("-1 week",$curTime);
			$vDatas["timeNext"]=strtotime("+1 week",$curTime);
			$beginMonthLabel=(date("m",$weekTimeBegin)!=date("m",$weekTimeEnd)) ? Txt::formatime("%b",$weekTimeBegin) : null;
			$vDatas["labelPeriod"]=date("j",$weekTimeBegin)." ".$beginMonthLabel." - ".date("j",$weekTimeEnd)." ".Txt::formatime("%b %Y",$weekTimeEnd)." (".Txt::trad("CALENDAR_evt_semaine")." ".date("W",$weekTimeBegin).")";//exple "26 octobre - 1 novembre 2015 (semaine 44)"
		}
		elseif($displayMode=="day")
		{
			$vDatas["timeBegin"]=strtotime(date("Y-m-d",$curTime)." 00:00");
			$vDatas["timeEnd"]	=strtotime(date("Y-m-d",$curTime)." 23:59");
			$vDatas["timePrev"]=strtotime("-1 day",$curTime);
			$vDatas["timeNext"]=strtotime("+1 day",$curTime);
			$vDatas["labelPeriod"]=date("j",$curTime)." ".Txt::formatime("%b %Y",$curTime)." (".date("W",$curTime).")";//exple "26 octobre 2015 (semaine 44)"
		}
		////	LISTE LES JOURS À AFFICHER
		$vDatas["periodDays"]=[];
		if(empty($vDatas["timeDisplayedBegin"]))	{$vDatas["timeDisplayedBegin"]=$vDatas["timeBegin"];  $vDatas["timeDisplayedEnd"]=$vDatas["timeEnd"];}
		for($timeDay=$vDatas["timeDisplayedBegin"]+43200; $timeDay<=$vDatas["timeDisplayedEnd"]; $timeDay+=86400)//43200sec de décalage : heures d'été/hivers
		{
			//début et fin du jour (attention aux heures d'été/hivers)
			$tmpDay["date"]=date("Y-m-d",$timeDay);
			$tmpDay["timeBegin"]=strtotime(date("Y-m-d",$timeDay)." 00:00");
			$tmpDay["timeEnd"]=strtotime(date("Y-m-d",$timeDay)." 23:59");
			//Class css de la cellule : avant/après le mois affiché OU jour du passé OU défault
			if($displayMode=="month" && date("m",$timeDay)!=date("m",$curTime))	{$tmpDay["dayClass"]="vCalMonthOtherMonth";}
			elseif($tmpDay["timeEnd"]<time())									{$tmpDay["dayClass"]="vCalMonthOldDate";}
			else																{$tmpDay["dayClass"]=null;}
			//Url d'ajout d'evt pour le jour (vue mois) / Libelle de jour ferie / Aujourd'hui?
			$tmpDay["urlEditNew"]=MdlCalendarEvent::getUrlNew()."&newTimeBegin=".($tmpDay["timeBegin"]+43200);//12h00
			$tmpDay["celebrationDay"]=(array_key_exists(date("Y-m-d",$timeDay),$vDatas["celebrationDays"]))  ?  $vDatas["celebrationDays"][date("Y-m-d",$timeDay)]  :  null;
			$tmpDay["isTodayClass"]=(date("Y-m-d",$timeDay)==date("Y-m-d")) ? "vCalMonthToday" : null;
			//Ajoute les infos du jour
			$vDatas["periodDays"][]=$tmpDay;
		}
		////	VUE "MONTH"/"WEEK" DE CHAQUE AGENDA
		$vDatas["displayedCalendars"]=MdlCalendar::displayedCalendars();
		foreach($vDatas["displayedCalendars"] as $cptCal=>$tmpCal)
		{
			//init
			$vDatas2=$vDatas;
			$vDatas2["tmpCal"]=$tmpCal;
			$vDatas2["cptCal"]=$cptCal;
			$vDatas2["txtAddEvt"]=($displayMode=="month") ? Txt::trad("CALENDAR_ajouter_evt_jour") : Txt::trad("CALENDAR_ajouter_evt_heure");//"ajouter un événement"
			$vDatas2["txtAddEvtPropose"]=($tmpCal->editRight()==false) ? " (".Txt::trad("CALENDAR_proposer").")" : null;//"(proposition)"
			//LISTE DES EVENEMENTS DE CHAQUE JOUR
			$vDatas2["eventList"]=[];
			$tmpCal->displayedPeriodEvtList=$tmpCal->evtList($vDatas["timeDisplayedBegin"],$vDatas["timeDisplayedEnd"]);//Evenements de l'agenda, sur la période affichee
			foreach($vDatas["periodDays"] as $dayCpt=>$tmpDay)
			{
				//Ajoute la liste des evts du jour dans l'agenda courant
				$vDatas2["eventList"][$tmpDay["date"]]=[];
				$tmpCalDisplayedDayEvtList=MdlCalendar::dayEvtList($tmpCal->displayedPeriodEvtList,$tmpDay["timeBegin"],$tmpDay["timeEnd"]);//Sélectionne uniquement les evenements sur la journée
				foreach($tmpCalDisplayedDayEvtList as $tmpEvt)
				{
					//Evt hors catégorie?
					if(Req::isParam("_idCatFilter") && $tmpEvt->_idCat!=Req::getParam("_idCatFilter"))	{continue;}
					//Element pour l'affichage "semaine"/"jour"
					if($displayMode!="month")
					{
						//Menu contextuel du jour
						$tmpEvt->blockIdForMenuContextDay=$tmpEvt->blockIdForMenuContext();
						$tmpEvt->menuContextDay=$tmpEvt->menuContext(["inlineLauncher"=>true, "_idCal"=>$tmpCal->_id, "curDateTime"=>$tmpDay["timeBegin"]]);
						//Duree / Hauteur à afficher pour l'evt
						$tmpEvt->dayCpt=$dayCpt;
						$evtBeforeTmpDay=(strtotime($tmpEvt->dateBegin) < $tmpDay["timeBegin"]);//Evt commence avant le jour courant ?
						$evtAfterTmpDay=($tmpDay["timeEnd"] < strtotime($tmpEvt->dateEnd));		//Evt termine après le jour courant?
						if($evtBeforeTmpDay==true && $evtAfterTmpDay==true)	{$tmpEvt->durationMinutes=24*60;}															//Affiche toute la journée
						elseif($evtBeforeTmpDay==true)						{$tmpEvt->durationMinutes=(strtotime($tmpEvt->dateEnd)-$tmpDay["timeBegin"])/60;}			//Affiche l'evt depuis 0h00 du jour courant
						elseif($evtAfterTmpDay==true)						{$tmpEvt->durationMinutes=($tmpDay["timeEnd"]-strtotime($tmpEvt->dateBegin))/60;}			//Affiche l'evt jusqu'à 23h59 du jour courant
						else												{$tmpEvt->durationMinutes=(strtotime($tmpEvt->dateEnd)-strtotime($tmpEvt->dateBegin))/60;}	//Affichage normal (sur 1 jour)
						//Heure/Minutes de début d'affichage
						$tmpEvt->minutesFromDayBegin=(strtotime($tmpEvt->dateBegin)>$tmpDay["timeBegin"])  ?  ((strtotime($tmpEvt->dateBegin)-$tmpDay["timeBegin"])/60)  :  "pastEvt";
					}
					//AJOUTE L'EVT!
					$vDatas2["eventList"][$tmpDay["date"]][]=$tmpEvt;
				}
			}
			$calendarVue=($displayMode=="month")?"VueCalendarMonth.php":"VueCalendarWeek.php";
			$tmpCal->calendarVue=self::getVue(Req::getCurModPath().$calendarVue, $vDatas2);
		}
		////	SYNTHESE DES AGENDAS (SI + D'UN AGENDA)
		if(count($vDatas["displayedCalendars"])>1)
		{
			//Jours à afficher pour la synthese
			$vDatas["periodDaysSynthese"]=[];
			foreach($vDatas["periodDays"] as $tmpDay)
			{
				//affichage "month" & jour d'un autre mois : passe le jour
				if($displayMode=="month" && date("m",$tmpDay["timeBegin"])!=date("m",$curTime))	{continue;}
				//Evénements de chaque agenda pour le $tmpDay 
				$nbCalsOccuppied=0;
				$tmpDay["calsEvts"]=[];
				foreach($vDatas["displayedCalendars"] as $tmpCal){
					$tmpDay["calsEvts"][$tmpCal->_id]=MdlCalendar::dayEvtList($tmpCal->displayedPeriodEvtList,$tmpDay["timeBegin"],$tmpDay["timeEnd"]);
					if(!empty($tmpDay["calsEvts"][$tmpCal->_id]))	{$nbCalsOccuppied++;}
				}
				//Tooltip de synthese si au moins un agenda possède un événement à cette date
				$tmpDay["nbCalsOccuppied"]=(!empty($nbCalsOccuppied))  ?  Txt::displayDate($tmpDay["timeBegin"],"full")." :<br>".Txt::trad("CALENDAR_pourcent_agendas_occupes")." : ".$nbCalsOccuppied." / ".count($tmpDay["calsEvts"])  :  null;
				//Ajoute le jour
				$vDatas["periodDaysSynthese"][]=$tmpDay;
			}
		}
		////	LANCE L'AFFICHAGE
		static::$isMainPage=true;
		//Evt proposés et agendas visibles
		$vDatas["menuProposedEvents"]=self::menuProposedEvents();
		$vDatas["visibleCalendars"]=(Req::isParam("displayAllCals") && Ctrl::$curUser->isAdminGeneral())  ?  MdlCalendar::affectationCalendars()  :  MdlCalendar::visibleCalendars();
		static::displayPage("VueIndex.php",$vDatas);
	}

	/*
	 * PLUGINS
	 */
	public static function plugin($pluginParams)
	{
		$pluginsList=$eventList=[];
		if($pluginParams["type"]=="search" || $pluginParams["type"]=="dashboard")
		{
			//"Mes agendas" si on est sur le dashboard / "Agendas visibles" si c'est la recherche
			$visibleCalendars=($pluginParams["type"]=="dashboard") ? MdlCalendar::myCalendars() : MdlCalendar::visibleCalendars();
			if(!empty($visibleCalendars))
			{
				////	Evenements à confirmer
				if($pluginParams["type"]=="dashboard")
				{
					$menuProposedEvents=self::menuProposedEvents();
					if(!empty($menuProposedEvents)){
						$objMenuConfirm=new stdClass();
						$objMenuConfirm->pluginModule=self::moduleName;
						$objMenuConfirm->pluginBlockMenu=$menuProposedEvents;
						$pluginsList[]=$objMenuConfirm;
					}
				}
				////	Evenements de chaque agenda : événements courants
				if($pluginParams["type"]=="dashboard")
				{
					foreach($visibleCalendars as $tmpCal){
						$tmpCalEvtFullList=$tmpCal->evtList(null,null,1,false);//Evt toute période confondu, avec accessRight>=1 et pas triés par H:M
						$tmpCalEvtList=MdlCalendar::periodEvtList($tmpCalEvtFullList, strtotime($pluginParams["dateTimeBegin"]), strtotime($pluginParams["dateTimeEnd"]));
						foreach($tmpCalEvtList as $tmpObj)  {$tmpObj->pluginIsCurrent=true;  $eventList[]=$tmpObj;}
					}
				}
				////	Evenements de chaque agenda : sélection normale du plugin (date de création OU recherche)
				foreach($visibleCalendars as $tmpCal){$eventList=array_merge($eventList, $tmpCal->evtList(null,null,1,false,true,$pluginParams));}//Evt toute période confondu, avec accessRight>=1, pas triés par H:M, confirmé et filtrés avec $pluginParams
				$eventList=array_unique($eventList,SORT_REGULAR);
				////	Ajoute les plugins
				foreach($eventList as $tmpObj)
				{
					if($tmpObj->readRight())
					{
						//Agendas ou l'evenement est affecté.. et visibles par l'user courant
						$calendarsUrl=null;
						foreach($tmpObj->affectedCalendars(true) as $tmpCal){
							if(in_array($tmpCal,$visibleCalendars))  {$calendarsUrl.="&displayedCalendars[]=".$tmpCal->_id;}//(ex "date_affiche"/"affichage_demande")
						}
						//infos du plugin
						$tmpObj->pluginModule=self::moduleName;
						$tmpObj->pluginIcon=(isset($tmpObj->pluginIsCurrent) && $pluginParams["type"]=="dashboard") ? "newObj2.png" : "calendar/icon.png";
						$tmpObj->pluginLabel=Txt::displayDate($tmpObj->dateBegin,"normal",$tmpObj->dateEnd)." :<br>".$tmpObj->title;
						$tmpObj->pluginTitle=Txt::displayDate($tmpObj->dateBegin,"full",$tmpObj->dateEnd)."<hr>".$tmpObj->affectedCalendarsLabel();
						$tmpObj->pluginJsIcon="redir('?ctrl=calendar&displayType=month&datetime=".strtotime($tmpObj->dateBegin).$calendarsUrl."',true);";//"datetime" & "displayType" (month/week/day)
						$tmpObj->pluginJsLabel="lightboxOpen('".$tmpObj->getUrl("vue")."');";
						$pluginsList[]=$tmpObj;
					}
				}
			}
		}
		return $pluginsList;
	}

	/*
	 * ACTION : Evenements que l'user courant a créé
	 */
	public static function actionMyEvents()
	{
		$vDatas["myEvents"]=Db::getObjTab("calendarEvent","SELECT * FROM ap_calendarEvent WHERE _idUser=".Ctrl::$curUser->_id." ORDER BY dateBegin");
		static::displayPage("VueMyEvents.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'un agenda
	 */
	public static function actionCalendarEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		if(MdlCalendar::addRight()==false)   {self::noAccessExit();}
		////	Formulaire validé
		if(Req::isParam("formValidate")){
			//Enregistre & recharge l'objet
			$timeSlot=Req::getParam("timeSlotBegin")."-".Req::getParam("timeSlotEnd");
			$typeCalendar=$curObj->isNew() ? ", type='ressource'" : null;
			$curObj=$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description").", timeSlot=".Db::format($timeSlot).", evtColorDisplay=".Db::formatParam("evtColorDisplay").$typeCalendar);
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueCalendarEdit.php",$vDatas);
	}

	/*
	 * ACTION : Edition d'un evenement d'agenda
	 */
	public static function actionCalendarEventEdit()
	{
		//Init
		$curObj=Ctrl::getTargetObj();
		$curObj->controlEdit();
		////	Formulaire validé
		if(Req::isParam("formValidate"))
		{
			//Modifie les détails uniquement si on a les droits complets
			if($curObj->fullRight())
			{
				//Prépare les dates
				$dateBegin=Txt::formatDate(Req::getParam("dateBegin")." ".Req::getParam("timeBegin"), "inputDatetime", "dbDatetime");
				$dateEnd=Txt::formatDate(Req::getParam("dateEnd")." ".Req::getParam("timeEnd"), "inputDatetime", "dbDatetime");
				///périodicité
				$periodDateEnd=$periodValues=$periodDateExceptions=null;
				if(Req::isParam("periodType")){
					$periodDateEnd=Txt::formatDate(Req::getParam("periodDateEnd"), "inputDate", "dbDate");
					$periodValues=Txt::tab2txt(Req::getParam("periodValues_".Req::getParam("periodType")));
					if(Req::isParam("periodDateExceptions")){
						$periodDateExceptions=[];
						foreach(Req::getParam("periodDateExceptions") as $tmpDate)  {$periodDateExceptions[]=Txt::formatDate($tmpDate,"inputDate","dbDate");}
					}
				}
				//Enregistre & recharge l'objet
				$curObj=$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description","editor").", dateBegin=".Db::format($dateBegin).", dateEnd=".Db::format($dateEnd).", _idCat=".Db::formatParam("_idCat").", important=".Db::formatParam("important").", contentVisible=".Db::formatParam("contentVisible").", periodType=".Db::formatParam("periodType").", periodValues=".Db::format($periodValues).", periodDateEnd=".Db::format($periodDateEnd).", periodDateExceptions=".Db::formatTab2txt($periodDateExceptions));
			}
			//Si besoin, réinitialise les affectations aux agendas : uniquement ceux sur lesquels on peut éditer les événements
			if($curObj->isNew()==false){
				foreach(MdlCalendar::affectationCalendars() as $tmpCal){
					if(in_array($tmpCal->_id,Req::getParam("reinitAffect")))	{Db::query("DELETE FROM ap_calendarEventAffectation WHERE _idEvt=".$curObj->_id." AND _idCal=".$tmpCal->_id);}
				}
			}
			//Attribue les affectations aux agendas et propositions (uniquement ceux sur lesquels on peut éditer les événements)
			$allAffectations=$specificAffectUserIds=[];
			if(Req::isParam("affectationCalendars")){$allAffectations=Req::getParam("affectationCalendars");}
			if(Req::isParam("proposedCalendars"))	{$allAffectations=array_merge($allAffectations,Req::getParam("proposedCalendars"));}
			foreach($allAffectations as $idCal){
				$tmpCal=Ctrl::getObj("calendar",$idCal);
				if(in_array($tmpCal,MdlCalendar::affectationCalendars())){
					$confirmed=(Req::isParam("affectationCalendars") && in_array($idCal,Req::getParam("affectationCalendars")))  ?  1  :  0;
					Db::query("INSERT INTO ap_calendarEventAffectation SET _idEvt=".$curObj->_id.", _idCal=".$tmpCal->_id.", confirmed=".Db::format($confirmed));
					if($tmpCal->type=="user")	{$specificAffectUserIds[]=$tmpCal->_idUser;}
				}
			}
			// Invité : affiche un message "votre demande sera prise en compte prochainement"
			if(Ctrl::$curUser->isUser()==false)  {Ctrl::addNotif(Txt::trad("EDIT_OBJET_demandToConfirm"));}
			//Notifie par mail & Ferme la page
			$pathIcal=self::getIcalEvents([$curObj]);
			$attachedFiles=[["path"=>$pathIcal, "name"=>Txt::clean($curObj->title,"maxi")."_EXPORT.ics"]];
			$curObj->sendMailNotif("<b>".$curObj->title."</b> : ".Txt::displayDate($curObj->dateBegin,"full",$curObj->dateEnd)."<br>".$curObj->description, $attachedFiles, $specificAffectUserIds);
			if(is_file($pathIcal))	{unlink($pathIcal);}
			static::lightboxClose();
		}
		////	Affiche la vue
		$vDatas["tabPeriodValues"]=Txt::txt2tab($curObj->periodValues);
		foreach(Txt::txt2tab($curObj->periodDateExceptions) as $keyTmp=>$tmpException)	{$vDatas["periodDateExceptions"][$keyTmp+1]=Txt::formatDate($tmpException,"dbDate","inputDate");}
		// Agendas d'affectations
		$vDatas["affectationCalendars"]=MdlCalendar::affectationCalendars();
		foreach($vDatas["affectationCalendars"] as $tmpCal)
		{
			//Check affectation & proposition?
			$preselectCalendar=($curObj->isNew() && $tmpCal->_id==Req::getParam("_idCal")) ? true : false;
			$tmpCal->checkAffectation=(in_array($tmpCal,$curObj->affectedCalendars(true)) || ($preselectCalendar==true && $tmpCal->editContentRight())) ? "checked" : null;		   //check "affect"  si agenda déjà affectés OU agenda présélectionné + contenu de l'agenda éditable
			$tmpCal->checkProposed	 =(in_array($tmpCal,$curObj->affectedCalendars(false)) || ($preselectCalendar==true && $tmpCal->editContentRight()==false)) ? "checked" : null;//check "propose" si agenda déjà proposés OU agenda présélectionné + contenu de l'agenda pas éditable
			//Désactive affectation & proposition?
			$tmpCal->disableAffect=($tmpCal->editContentRight() || ($curObj->fullRight() && !empty($tmpCal->checkAffectation))) ? null : "disabled";//active "affect" si contenu de l'agenda éditable OU agenda dejà affecté + auteur de l'evt
			$tmpCal->disablePropose=($curObj->fullRight() || $tmpCal->editContentRight()) ? null : "disabled";										//active "propose" si contenu de l'agenda éditable OU auteur de l'evt
			//Réinitialise l'affectation/proposition?
			$tmpCal->reinitAffect		=((!empty($tmpCal->checkAffectation) && !empty($tmpCal->disableAffect)) || (!empty($tmpCal->checkProposed) && !empty($tmpCal->disablePropose))) ? false : true;//Réinit pas si l'evt est confirmé et bloqué / ou proposé et bloqué
			//Tooltip du label
			if(empty($tmpCal->disableAffect))		{$tmpCal->tooltip.=Txt::trad("CALENDAR_input_affecter");}	//"Ajouter l'événement [..]"
			elseif(empty($tmpCal->disablePropose))	{$tmpCal->tooltip.=Txt::trad("CALENDAR_info_proposer");}	//"Proposer l'événement uniquement [..]"
			else									{$tmpCal->tooltip.=Txt::trad("CALENDAR_info_pas_modif");}	//"Modification non autorisé [..]"
			if(!empty($tmpCal->description))	{$tmpCal->tooltip.="<br><i>".$tmpCal->description."<i>";}
		}
		//Dates d'un nouvel event		
		if($curObj->isNew()){
			if(Req::isParam("newTimeEnd"))			{$curObj->dateBegin=date("Y-m-d H:i:00",Req::getParam("newTimeBegin"));  $curObj->dateEnd=date("Y-m-d H:i:00",Req::getParam("newTimeEnd"));}
			elseif(Req::isParam("newTimeBegin"))	{$curObj->dateBegin=$curObj->dateEnd=date("Y-m-d H:i:00",Req::getParam("newTimeBegin"));}
			else									{$curObj->dateBegin=$curObj->dateEnd=date("Y-m-d H:00:00");}
		}
		$vDatas["curObj"]=$curObj;
		$vDatas["userGroups"]=MdlUserGroup::getGroups(Ctrl::$curSpace);
		static::displayPage("VueCalendarEventEdit.php",$vDatas);
	}

	/*
	 * AJAX : Control des créneaux horaires des agendas sélectionnés
	 */
	public static function actionTimeSlotBusy()
	{
		if(Req::isParam(["dateTimeBegin","dateTimeEnd","_evtId","targetObjects"]))
		{
			//Init
			$textTimeSlotBusy=null;
			$timeBegin=Txt::formatDate(Req::getParam("dateTimeBegin"),"inputDatetime","time")+1;//Décale d'une sec. pour eviter les faux positifs. Exple: créneaux 11h-12h dispo, même si 12h-13h est occupé
			$timeEnd=Txt::formatDate(Req::getParam("dateTimeEnd"),"inputDatetime","time")-1;//idem. Exple: créneaux 11h-12h dispo, même si 12h-13h est occupé
			//Vérifie le créneau horaire sur chaque agenda
			foreach(self::getTargetObjects() as $tmpCal)
			{
				$calendarBusy=$calendarBusyTimeSlots=null;
				//Verif tous les evt du calendrier, même ceux non accessible
				foreach(MdlCalendar::dayEvtList($tmpCal->evtList($timeBegin,$timeEnd),$timeBegin,$timeEnd) as $tmpEvt){//Evenements de l'agenda sur la période sélectionnée, re-filtrés pour chaque jours de la période (cf. evt périodiques)
					//Prends pas l'evt en cours d'édition..
					if($tmpEvt->_id!=Req::getParam("_evtId")){
						//Ajoute le créneau horaire de l'evt : uniquement si accessible en lecture
						if($tmpEvt->accessRight()>=0.5)  {$calendarBusyTimeSlots.=" &nbsp; &nbsp; <img src='app/img/arrowRight.png'> ".Txt::displayDate($tmpEvt->dateBegin,"normal",$tmpEvt->dateEnd)." ";}
						$calendarBusy=true;
					}
				}
				//L'agenda est occupé?
				if($calendarBusy==true)  {$textTimeSlotBusy.="<div class='vTimeSlotBusyRow'><div class='vTimeSlotBusyCell'>".$tmpCal->title."</div><div class='vTimeSlotBusyCell'>".$calendarBusyTimeSlots."</div></div>";}
			}
			//Retourne le message
			echo $textTimeSlotBusy;
		}
	}

	/*
	 * MENU : Liste des evenements à confirmer			(ex "menu_proposition_evt()")
	 */
	public static function menuProposedEvents()
	{
		$menuProposedEvents=null;
		foreach(MdlCalendar::myCalendars() as $tmpCal)
		{
			$eventsToConfirm=Db::getObjTab("calendarEvent","SELECT T1.* FROM ap_calendarEvent T1, ap_calendarEventAffectation T2 WHERE T1._id=T2._idEvt AND T2._idCal=".$tmpCal->_id." AND (T2.confirmed=0 or T2.confirmed is null)");
			if(count($eventsToConfirm)>0)
			{
				// Libelle de l'agenda
				$libConfirmEvent=($tmpCal->isMyPerso())  ?  Txt::trad("CALENDAR_evenements_proposes_mon_agenda")  :  Txt::trad("CALENDAR_evenements_proposes_pour_agenda")." <i>".$tmpCal->title."</i>";
				$menuProposedEvents.="<div class='confirmEventLib'><img src='app/img/important.png'>".$libConfirmEvent."</div>";
				// Evénements à confirmer sur l'agenda
				foreach($eventsToConfirm as $tmpObj){
					$propositionId="confirmEventProposition".$tmpObj->_id."-".$tmpCal->_id;
					$tooltip=htmlspecialchars($tmpObj->title).(!empty($tmpObj->description) ? "<hr>".Txt::reduce(strip_tags($tmpObj->description),100) : null);
					$tooltip=Txt::displayDate($tmpObj->dateBegin,"full",$tmpObj->dateEnd)."<div>".$tooltip."</div><hr>".Txt::trad("OBJECTcalendar")." : ".$tmpCal->title."<hr>".Txt::trad("CALENDAR_evenement_propose_par")." ".$tmpObj->displayAutor();
					$menuProposedEvents.="<div onclick=\"confirmEventProposition(".$tmpCal->_id.",".$tmpObj->_id.",this.id);\" title=\"".$tooltip."\" id='".$propositionId."' class='confirmEventProposition sLink'><img src='app/img/arrowRight.png'> ".$tmpObj->title."</div>";
				}
			}
		}
		return $menuProposedEvents;
	}

	/*
	 * ACTION : Validation d'événement dans un Agenda
	 */
	public static function actionConfirmEventProposition()
	{
		$curCal=Ctrl::getTargetObj();
		if($curCal->confirmEventPropositionRight()){
			if(Req::getParam("confirmed")==1)	{Db::query("UPDATE ap_calendarEventAffectation SET confirmed=1 WHERE _idEvt=".Db::formatParam("_idEvt")." AND _idCal=".$curCal->_id);}
			else								{Db::query("DELETE FROM ap_calendarEventAffectation WHERE _idEvt=".Db::formatParam("_idEvt")." AND _idCal=".$curCal->_id);}
			echo "true";
		}
	}

	/*
	 * ACTION : Détails d'un événement
	 */
	public static function actionCalendarEventVue()
	{
		$curObj=Ctrl::getTargetObj();
		$curObj->controlRead();
		// visibilite / Catégorie
		$vDatas["contentVisible"]=(preg_match("/(public_cache|prive)/i",$curObj->contentVisible))  ?  Txt::trad("CALENDAR_visibilite")." : ".($curObj->contentVisible=="public_cache"?Txt::trad("CALENDAR_visibilite_public_cache"):Txt::trad("CALENDAR_visibilite_prive"))  :  null;
		$vDatas["labelCategory"]=(!empty($curObj->objCategory))  ?  $curObj->objCategory->display()  :  null;
		//Périodicité
		$vDatas["labelPeriod"]=$periodValues=null;
		if(!empty($curObj->periodType))
		{
			//Périodicité
			$vDatas["labelPeriod"]=Txt::trad("CALENDAR_period_".$curObj->periodType);
			foreach(Txt::txt2tab($curObj->periodValues) as $tmpVal){
				if($curObj->periodType=="weekDay")		{$periodValues.=Txt::trad("jour_".$tmpVal).", ";}
				elseif($curObj->periodType=="monthDay")	{$periodValues.=$tmpVal.", ";}
				elseif($curObj->periodType=="month")	{$periodValues.=Txt::trad("mois_".$tmpVal).", ";}
			}
			if(!empty($periodValues))	{$vDatas["labelPeriod"].=" : ".trim($periodValues, ", ");}
			//Périodicité : fin
			if(!empty($curObj->periodDateEnd))	{$vDatas["labelPeriod"].=". &nbsp; ".Txt::trad("CALENDAR_periodDateEnd")." : ".Txt::displayDate($curObj->periodDateEnd,"full");}
			//Périodicité : exceptions
			if(!empty($curObj->periodDateExceptions)){
				$vDatas["labelPeriod"].="<br>".Txt::trad("CALENDAR_exception_periodicite")." : ";
				$periodDateExceptions=array_filter(Txt::txt2tab($curObj->periodDateExceptions));//"array_filter" pour enlever les valeurs vides
				foreach($periodDateExceptions as $tmpVal)	{$vDatas["labelPeriod"].=Txt::displayDate($tmpVal,"dateMini").", ";}
				$vDatas["labelPeriod"]=trim($vDatas["labelPeriod"], ", ");
			}
		}
		//Détails de l'événement
		$vDatas["curObj"]=$curObj;
		static::displayPage("VueCalendarEvent.php",$vDatas);
	}

	/*
	 * Suppression d'anciens événements d'un agenda
	 */
	public static function actionOldEvtDelete()
	{
		$curCalendar=Ctrl::getTargetObj();
		//Droit en édition : Supprime les événements de l'agenda ciblé, précédant la date d'aujourd'hui, mais uniquement sur l'agenda ciblé
		if($curCalendar->editRight()){
			$deleteDateEnd=strtotime(date("Y-m-d 00:00"));//Date précédant aujourd'hui
			//Liste les Evénement, toute période confondu, avec accessRight>=2
			foreach($curCalendar->evtList(null,null,2) as $tmpEvt){
				//Supprime les Evt dont la date de fin est passé && (sans périodicité || avec périodicité terminé)
				if(strtotime($tmpEvt->dateEnd)<$deleteDateEnd  &&  (empty($tmpEvt->periodType) || (!empty($tmpEvt->periodDateEnd) && strtotime($tmpEvt->periodDateEnd)<$deleteDateEnd)))
					{$tmpEvt->delete($curCalendar->_id);}
			}
		}
		//Redirige en page principale
		Ctrl::redir("?ctrl=".Req::$curCtrl);//ne pas mettre de "action"
	}
	
	/*
	 * ACTION : Edition des categories d'evenements
	 */
	public static function actionCalendarEventCategoryEdit()
	{
		////	Droit d'ajouter une categorie?
		if(MdlCalendarEventCategory::addRight()==false)  {static::lightboxClose(false);}
		////	Validation de formulaire
		if(Req::isParam("formValidate")){
			$curObj=Ctrl::getTargetObj();
			$curObj->controlEdit();
			//Modif d'une categorie
			$_idSpaces=(!in_array("all",Req::getParam("spaceList")))  ?  Txt::tab2txt(Req::getParam("spaceList"))  :  null;
			$curObj->createUpdate("title=".Db::formatParam("title").", description=".Db::formatParam("description").", color=".Db::formatParam("color").", _idSpaces=".Db::format($_idSpaces));
			//Ferme la page
			static::lightboxClose();
		}
		////	Liste des categories
		$vDatas["categoriesList"]=MdlCalendarEventCategory::getCategories(true);
		$vDatas["categoriesList"][]=New MdlCalendarEventCategory();//nouvelle categorie vide
		foreach($vDatas["categoriesList"] as $tmpKey=>$tmpCategory){
			if($tmpCategory->editRight()==false)	{unset($vDatas["categoriesList"][$tmpKey]);}
			else{
				$tmpCategory->tmpId=$tmpCategory->_targetObjId;
				$tmpCategory->createdBy=($tmpCategory->isNew()==false)  ?  Txt::trad("cree_par")." : ".Ctrl::getObj("user",$tmpCategory->_idUser)->display()  :  null;
			}
		}
		////	Affiche le form
		static::displayPage("VueCalendarEventCategoryEdit.php",$vDatas);
	}

	/*
	 * Création du fichier .ICAL
	 */
	public static function getIcalEvents($eventList)
	{
		//INIT
		$ical ="BEGIN:VCALENDAR\n";
		$ical.="PRODID:-//Agora-Project//".self::$agora->name."//EN\n";
		$ical.="VERSION:".VERSION_AGORA."\n";
		$ical.="CALSCALE:GREGORIAN\n";
		$ical.="METHOD:PUBLISH\n";
		//TIMEZONE
		$ical.="BEGIN:VTIMEZONE\n";
		$ical.="TZID:".self::$curTimezone."\n";
		$ical.="X-LIC-LOCATION:".self::$curTimezone."\n";
		//Daylight
		$ical.="BEGIN:DAYLIGHT\n";
		$ical.="TZOFFSETFROM:".self::icalHour()."\n";
		$ical.="TZOFFSETTO:".self::icalHour(1)."\n";
		$ical.="TZNAME:CEST\n";
		$ical.="DTSTART:19700329T020000\n";
		$ical.="RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3\n";
		$ical.="END:DAYLIGHT\n";
		//Standard
		$ical.="BEGIN:STANDARD\n";
		$ical.="TZOFFSETFROM:".self::icalHour(1)."\n";
		$ical.="TZOFFSETTO:".self::icalHour()."\n";
		$ical.="TZNAME:CET\n";
		$ical.="DTSTART:19701025T030000\n";
		$ical.="RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10\n";
		$ical.="END:STANDARD\n";
		$ical.="END:VTIMEZONE\n";
		//AJOUT DE CHAQUE EVENEMENT
		foreach($eventList as $tmpEvt)
		{
			//Infos de base
			$ical.="BEGIN:VEVENT\n";
			$ical.="CREATED:".self::icalDate($tmpEvt->dateCrea)."\n";
			$ical.="LAST-MODIFIED:".self::icalDate($tmpEvt->dateModif)."\n";
			$ical.="DTSTAMP:".self::icalDate(date("Y-m-d H:i"))."\n";
			$ical.="UID:".self::icalIdEvt($tmpEvt)."\n";
			$ical.="SUMMARY:".$tmpEvt->title."\n";
			$ical.="DTSTART;TZID=".self::icalDate($tmpEvt->dateBegin,true)."\n";//exple : "20050714T170000Z" pour 14 juillet 2005 à 17h00
			$ical.="DTEND;TZID=".self::icalDate($tmpEvt->dateEnd,true)."\n";
			// Périodicité : année / jours de la semaine / mois (non compatible .ics) / jours du mois (non compatible .ics)
			$periodDateEnd=($tmpEvt->periodDateEnd) ? ";UNTIL=".self::icalDate($tmpEvt->periodDateEnd) : null;
			if($tmpEvt->periodType=="year"){
				$ical.="RRULE:FREQ=YEARLY;INTERVAL=1".$periodDateEnd."\n";
			}elseif($tmpEvt->periodType=="weekDay"){
				$tmpEvt->periodValues=str_replace([1,2,3,4,5,6,7], ['MO','TU','WE','TH','FR','SA','SU'], $tmpEvt->periodValues);
				$ical.="RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=".implode(",",Txt::txt2tab($tmpEvt->periodValues)).$periodDateEnd."\n";
			}elseif($tmpEvt->periodType=="monthDay"){
				$tmpEvt->description.=Txt::trad("CALENDAR_periodicite")." ''".Txt::trad("CALENDAR_period_monthDay"). "'' : ";
				$tmpEvt->description.=Txt::trad("le")." ".implode(",",Txt::txt2tab($tmpEvt->periodValues))." ".Txt::trad("CALENDAR_period_month_xdumois");
				$ical.="RRULE:FREQ=MONTHLY;INTERVAL=1".$periodDateEnd."\n";
			}elseif($tmpEvt->periodType=="month"){
				$tmpEvt->description.=Txt::trad("CALENDAR_periodicite")." ''".Txt::trad("CALENDAR_period_month"). "'' : ";
				foreach(Txt::txt2tab($tmpEvt->periodValues) as $tmpMonth)	{$tmpEvt->description.=Txt::trad("mois_".$tmpMonth).", ";}
				$ical.="RRULE:FREQ=MONTHLY;INTERVAL=1".$periodDateEnd."\n";
			}
			//Description (& agendas où il est affecté) et Categorie
			if(count($tmpEvt->affectedCalendars())>0)	{$tmpEvt->description.=(!empty($tmpEvt->description)?" - ":null).Txt::reduce(str_replace("<br>"," ",$tmpEvt->affectedCalendarsLabel()));}
			$tmpEvt->description=str_replace(["\r","\n"], null, html_entity_decode(strip_tags($tmpEvt->description)));
			if(!empty($tmpEvt->description))	{$ical.="DESCRIPTION:".$tmpEvt->description."\n";}
			if(!empty($tmpEvt->_idCat))			{$ical.="CATEGORIES:".Ctrl::getObj("calendarEventCategory",$tmpEvt->_idCat)->title."\n";}
			$ical.="END:VEVENT\n";
		}
		//Fin du ical
		$ical.="END:VCALENDAR\n";
		//Enregistre dans un fichier temporaire
		$tmpFilePath=sys_get_temp_dir()."/".uniqid(mt_rand()).".ics";
		$fp=fopen($tmpFilePath, "w");
		fwrite($fp,$ical);
		fclose($fp);
		//renvoie le chemin du fichier tmp
		return $tmpFilePath;
	}

	/*
	 * Export .ical : formatage de heure/date et Identifiant unique
	 */
	public static function icalHour($timeLag=0)
	{
		// Exemple avec "-5:30"
		$hourTimezone=Tool::$tabTimezones[self::$curTimezone];
		$valueSign=(substr($hourTimezone,0,1)=="-") ? '-' : '+';				//"-"
		$hourAbsoluteVal=str_replace(['-','+'],null,substr($hourTimezone,0,-3));//"5"
		$hourAbsoluteVal+=$timeLag;												//Si $timeLag=2 -> "7"
		if($hourAbsoluteVal<10)	{$hourAbsoluteVal="0".$hourAbsoluteVal;}		//"05"
		$minutes=substr($hourTimezone,-2);										//"30"
		return $valueSign.$hourAbsoluteVal.$minutes;//Retourne "-0530"
	}
	public static function icalDate($dateTime, $timezone=false)
	{
		$dateTime=date("Ymd",strtotime($dateTime))."T".date("Hi",strtotime($dateTime))."00";//exple: "20151231T235900Z"
		return ($timezone==true) ? self::$curTimezone.":".$dateTime : str_replace("T000000Z","T235900Z",$dateTime."Z");
	}
	public static function icalIdEvt($tmpEvt)
	{
		return md5($tmpEvt->dateCrea.$tmpEvt->_id);
	}

	/*
	 * Export des événements d'un agenda
	 */
	public static function actionExportEvents()
	{
		$curCalendar=Ctrl::getTargetObj();
		//Droit en édition?
		if($curCalendar->editRight())
		{
			//Liste des evts
			$eventList=[];
			$periodBegin=time()-(86400*30);//Time - 30jours
			$periodEnd=time()+(86400*3650);//Time + 10ans
			
			$curCalEvtPeriodList=$curCalendar->evtList($periodBegin,$periodEnd);//Evenements de l'agenda, sur la période sélectionnée
			foreach(MdlCalendar::periodEvtList($curCalEvtPeriodList,$periodBegin,$periodEnd) as $tmpEvt)	{$eventList[]=$tmpEvt;}
			//Récup le fichier Ical (même si aucun agendas à afficher..)
			$pathIcal=self::getIcalEvents($eventList);
			if(is_file($pathIcal))
			{
				$fileName=Txt::clean($curCalendar->title,"maxi")."_EXPORT_".date("d-m-Y").".ics";
				//Téléchargement du fichier / Envoi le fichier par mail
				if(Req::isParam("sendMail")==false)  {File::download($fileName, $pathIcal);}
				else{
					$subject="TRUC subject";
					$mainMessage="TRUC mainMessage";
					$attachedFiles=[["path"=>$pathIcal, "name"=>$fileName]];
					Tool::sendMail(Ctrl::$curUser->mail, $subject, $mainMessage, null, $attachedFiles);
				}
				//Supprime le fichier
				unlink($pathIcal);
			}
		}
		//Redirige en page principale
		Ctrl::redir("?ctrl=".Req::$curCtrl);//ne pas mettre de "action"
	}

	/*
	 * Import d'événement (format .ical) dans un agenda
	 */
	public static function actionImportEvents()
	{
		//Charge et controle
		$curCalendar=Ctrl::getTargetObj();
		$curCalendar->controlEdit();
		////	Validation de formulaire : sélection du fichier / des evt à importer
		if(Req::isParam("formValidate"))
		{
			//Prépare le tableau d'import
			if(isset($_FILES["importFile"]) && is_file($_FILES["importFile"]["tmp_name"]))
			{
				//Récupère les événements
				require("class.iCalReader.php");
				$ical=new ical($_FILES["importFile"]["tmp_name"]);
				$vDatas["eventList"]=$ical->events();
				//Formate les imports
				foreach($vDatas["eventList"] as $cptEvt=>$tmpEvt)
				{
					//Init
					$tmpEvt["dbDateBegin"]=substr($tmpEvt["DTSTART"],0,4)."-".substr($tmpEvt["DTSTART"],4,2)."-".substr($tmpEvt["DTSTART"],6,2)." ".substr($tmpEvt["DTSTART"],9,2).":".substr($tmpEvt["DTSTART"],11,2);
					$tmpEvt["dbDateEnd"]=substr($tmpEvt["DTEND"],0,4)."-".substr($tmpEvt["DTEND"],4,2)."-".substr($tmpEvt["DTEND"],6,2)." ".substr($tmpEvt["DTEND"],9,2).":".substr($tmpEvt["DTEND"],11,2);
					$tmpEvt["DESCRIPTION"]=strip_tags(nl2br($tmpEvt["DESCRIPTION"]));
					//Etat de l'événement : importer ? dejà present (ne pas importer) ?
					$isPresent=Db::getVal("SELECT count(*) FROM ap_calendarEvent T1, ap_calendarEventAffectation T2 WHERE T1._id=T2._idEvt AND T2._idCal=".$curCalendar->_id." AND T1.title=".Db::format($tmpEvt["SUMMARY"],"editor")." AND T1.dateBegin=".Db::format($tmpEvt["dbDateBegin"])." AND T1.dateEnd=".Db::format($tmpEvt["dbDateEnd"]));
					$tmpEvt["isPresent"]=($isPresent>0) ? true : false;
					//Enregistre
					$vDatas["eventList"][$cptEvt]=$tmpEvt;
				}
			}
			//Importe les événements
			elseif(Req::isParam("eventList"))
			{
				//Import de chaque événement
				foreach(Req::getParam("eventList") as $tmpEvt)
				{
					//Import sélectionné?
					if(!empty($tmpEvt["checked"])){
						//Créé et enregistre l'événement
						$curObj=new MdlCalendarEvent();
						$curObj=$curObj->createUpdate("title=".Db::format($tmpEvt["title"]).", description=".Db::format($tmpEvt["description"]).", dateBegin=".Db::format($tmpEvt["dateBegin"]).", dateEnd=".Db::format($tmpEvt["dateEnd"]));
						//Affecte à l'agenda courant
						Db::query("INSERT INTO ap_calendarEventAffectation SET _idEvt=".$curObj->_id.", _idCal=".$curCalendar->_id.", confirmed=1");
					}
				}
				//Ferme la page
				static::lightboxClose();
			}
		}
		////	Affiche le menu d'Import/Export
		$vDatas["curCalendar"]=$curCalendar;
		static::displayPage("VueCalendarImportEvt.php",$vDatas);
	}
}