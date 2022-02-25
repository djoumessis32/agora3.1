<?php
/*
 * Modele des agendas
 */
class MdlCalendar extends MdlObject
{
	//Propriétés principales
	const moduleName="calendar";
	const objectType="calendar";
	const dbTable="ap_calendar";
	const MdlObjectContent="MdlCalendarEvent";
	//Propriétés d'IHM
	const hasShortcut=false;
	const hasNotifMail=false;
	//Droit de supprimer l'agenda perso. True si suppression de l'user correspondant
	public static $persoCalendarDeleteRight=false;
	//Champs obligatoires, Champs de recherche et Champs de tri d'affichage
	public static $requiredFields=array("title");
	public static $searchFields=array("title","description");
	//Valeurs en cache (calculées qu'1 fois)
	private static $_visibleCalendars=null;
	private static $_visiblePersoCalendars=null;
	private static $_displayedCalendars=null;
	private static $_affectationCalendars=null;


	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct($objIdOrValues=null)
	{
		parent::__construct($objIdOrValues);
		//Libellé de l'agenda perso = nom de l'utilisateur correspondant
		if($this->type=="user")	{$this->title=$this->displayAutor();}
		//Plage horaire de l'agenda
		if(empty($this->timeSlot)){
			$this->timeSlotBegin=8;
			$this->timeSlotEnd=20;
		}else{
			$tmpTimeSlot=explode("-",$this->timeSlot);
			$this->timeSlotBegin=$tmpTimeSlot[0];
			$this->timeSlotEnd=$tmpTimeSlot[1];
		}
	}

	/*
	 * Verifie si c'est l'agenda perso de l'user courant
	 */
	function isMyPerso()
	{
		return ($this->type=="user" && $this->isAutor());
	}

	/*
	 * SURCHARGE : Privilège de droits d'accès pour l'admin d'espace : pas sur les agendas persos
	 */
	public function accessRightAdminSpacePrivilege()
	{
		return ($this->type=="user");
	}

	/*
	 * SURCHARGE : Droit d'ajouter du contenu dans l'objet conteneur. Pour les "guests" : uniquement des propositions d'evt
	 */
	public function editContentRight()
	{
		return (Ctrl::$curUser->isUser())  ?  parent::editContentRight()  :  false;
	}

	/*
	 * SURCHARGE : Droit d'ajouter un agenda (partagé)
	 */
	public static function addRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled("dashboard","ajout_agenda_ressource_admin")==false));
	}
	
	/*
	 * SURCHARGE : droit de suppression d'un agenda : pas pour les agendas d'users
	 */
	public function deleteRight(){
		return ($this->type=="user" && $this::$persoCalendarDeleteRight==false)  ?  false  :  parent::deleteRight();
	}

	/*
	 * SURCHARGE : suppression d'agenda
	 */
	public function delete()
	{
		//Controle le droit d'accès
		if($this->deleteRight())
		{
			//Supprime les evenements affectés uniquement à l'agenda en question
			$eventList=Db::getCol("SELECT DISTINCT _idEvt FROM ap_calendarEventAffectation WHERE _idCal=".$this->_id." AND _idEvt NOT IN (select _idEvt from ap_calendarEventAffectation where _idCal!=".$this->_id.")");
			foreach($eventList as $_idEvt){
				$tmpEvt=Ctrl::getObj("calendarEvent",$_idEvt);
				$tmpEvt->delete();
			}
			//Supprime les jointures de l'agenda avec les evenements
			Db::query("DELETE FROM ap_calendarEventAffectation WHERE _idCal=".$this->_id);
			//Supprime enfin l'agenda
			parent::delete();
		}
	}

	/*
	 * Evenements de l'agenda
	 */
	public function evtList($periodBegin=null, $periodEnd=null, $accessRightMini=0.5, $orderByHourMinute=true, $confirmed=true, $pluginParams=null)
	{
		////	Init les sélections
		$sqlConfirmed=$sqlPeriod=$sqlPlugins=null;
		////	Evenements confirmés / pas confirmés / tout ($confirmed=="null")
		if($confirmed==true)		{$sqlConfirmed="AND confirmed=1";}
		elseif($confirmed==false)	{$sqlConfirmed="AND confirmed is NULL";}
		////	Selection d'une période (evenements périodiques OU situés dans la période).
		////	Attention! Cette liste récupère TOUT LES EVT PERIODIQUES. Il faudra donc re-filtrer cette liste avec "periodEvtList()" qui fera lui même appel à "dayEvtList()"
		if(!empty($periodBegin) && !empty($periodEnd)){
			$dateTimeBegin=Db::format(date("Y-m-d 00:00",$periodBegin));
			$dateTimeEnd=Db::format(date("Y-m-d 23:59",$periodEnd));
			$sqlPeriod="AND ( periodType is not null OR (dateBegin between ".$dateTimeBegin." and ".$dateTimeEnd.") OR (dateEnd between ".$dateTimeBegin." and ".$dateTimeEnd.") OR (DateBegin <= ".$dateTimeBegin." and DateEnd >= ".$dateTimeEnd.") )";
		}
		////	Sélection d'un plugin?
		if(!empty($pluginParams))	{$sqlPlugins="AND ".MdlCalendarEvent::sqlPluginObjects($pluginParams);}
		////	 Liste des evenements, en fonction des droits d'accès. Tri par "Heure:Minute" si affiché sur un jour (cf. evt périodiques) OU Tri par "dateBegin" si affiché une liste complete (cf. plugins)
		$sqlOrderBy=($orderByHourMinute==true)  ?  "DATE_FORMAT(dateBegin,'%H:%i') ASC"  :  "dateBegin ASC";
		$eventsObjList=Db::getObjTab("calendarEvent","SELECT * FROM ap_calendarEvent WHERE _id IN (select _idEvt from ap_calendarEventAffectation where _idCal=".$this->_id." ".$sqlConfirmed.") ".$sqlPeriod." ".$sqlPlugins." ORDER BY ".$sqlOrderBy);
		////	renvoie les evts en fonction du droit d'accès minimum 
		$evtListReturned=[];
		foreach($eventsObjList as $tmpObj){
			if($tmpObj->accessRight()>=$accessRightMini)  {$evtListReturned[]=$tmpObj;}
		}
		//renvoie les evenements
		return $evtListReturned;
	}

	/*
	 * Sélectionne les évenements d'une journée ou d'une période de qq heures, à partir d'une liste d'evt					(ex "liste_evenements()")
	 */
	public static function dayEvtList($evtList, $timeDayBegin, $timeDayEnd)
	{
		$evtListReturned=[];
		foreach($evtList as $tmpEvt)
		{
			//Clone l'evt pour chaque instance d'un événement sur plusieurs jours ou d'un événement périodique : une instance d'evt par jour
			$tmpEvt=clone $tmpEvt;
			$evtBegin=strtotime($tmpEvt->dateBegin);
			$evtEnd=strtotime($tmpEvt->dateEnd);
			//EVT SUR LA JOURNEE : Début de l'evt dans la journée || Fin de l'evt dans la journée || evt avant et après la journée
			if(($evtBegin>=$timeDayBegin && $evtBegin<=$timeDayEnd) || ($evtEnd>=$timeDayBegin && $evtEnd<=$timeDayEnd) || ($evtBegin<=$timeDayBegin && $evtEnd>=$timeDayEnd))	{$evtListReturned[]=$tmpEvt;}
			//EVT PERIODIQUE, SUR LA JOURNEE =>  evt déjà commencé  &&  (pas de fin de périodicité || fin de périodicité pas encore arrivé)  &&  (pas de date d'exception || "dateBegin" absent des dates d'exception)
			elseif(!empty($tmpEvt->periodType)  &&  $evtBegin<$timeDayBegin  &&  (empty($tmpEvt->periodDateEnd) || $timeDayEnd<=strtotime($tmpEvt->periodDateEnd." 23:59"))  &&  (empty($tmpEvt->periodDateExceptions) || !in_array(date("Y-m-d",$timeDayBegin),Txt::txt2tab($tmpEvt->periodDateExceptions))))
			{
				//L'evt périodique est présent sur le jour courant : Reformate le début/fin de l'evt pour qu'il corresponde à la date courante
				$periodValues=Txt::txt2tab($tmpEvt->periodValues);
				$dateModifiedFormat=$dateConservedFormat=null;
				if($tmpEvt->periodType=="weekDay" && in_array(date("N",$timeDayBegin),$periodValues))													{$dateModifiedFormat="Y-m-d"; $dateConservedFormat=" H:i";}//jour de semaine
				elseif($tmpEvt->periodType=="monthDay" && in_array(date("d",$timeDayBegin),$periodValues))												{$dateModifiedFormat="Y-m"; $dateConservedFormat="-d H:i";}//jour du mois
				elseif($tmpEvt->periodType=="month" && in_array(date("m",$timeDayBegin),$periodValues) && date("d",$evtBegin)==date("d",$timeDayBegin))	{$dateModifiedFormat="Y-m"; $dateConservedFormat="-d H:i";}//mois
				elseif($tmpEvt->periodType=="year" && date("m-d",$evtBegin)==date("m-d",$timeDayBegin))													{$dateModifiedFormat="Y"; $dateConservedFormat="-m-d H:i";}//année
				//Ajoute à la liste && Reformate le début/fin
				if(!empty($dateModifiedFormat) && !empty($dateConservedFormat)){
					$tmpEvt->dateBegin=date($dateModifiedFormat,$timeDayBegin).date($dateConservedFormat,$evtBegin);
					$tmpEvt->dateEnd  =date($dateModifiedFormat,$timeDayEnd).date($dateConservedFormat,$evtEnd);
					$evtListReturned[]=$tmpEvt;
				}
			}
		}
		return $evtListReturned;
	}

	/*
	 * Evenements sur une période de plusieurs jours, à partir d'une liste d'événements 		(ex "liste_evenements()")
	 */
	public static function periodEvtList($evtList, $timePeriodBegin, $timePeriodEnd)
	{
		$evtListReturned=[];
		for($timeDay=$timePeriodBegin+43200; $timeDay<=$timePeriodEnd; $timeDay+=86400){//12h de décalage (43200sec) pour prendre en compte les heures d'été/hivers
			$subPeriodBegin=strtotime(date("Y-m-d",$timeDay)." 00:00");
			$subPeriodEnd=strtotime(date("Y-m-d",$timeDay)." 23:59");
			$evtListReturned=array_merge($evtListReturned, static::dayEvtList($evtList,$subPeriodBegin,$subPeriodEnd));
		}
		return array_unique($evtListReturned,SORT_REGULAR);//"SORT_REGULAR" pour les objets
	}

	/*
	 * Droit de confirmer une proposition d'événement?
	 */
	public function confirmEventPropositionRight()
	{
		foreach(self::myCalendars() as $tmpCalendar){
			if($tmpCalendar->_id==$this->_id)	{return true;}
		}
	}

	/*
	 * Agendas visibles pour l'user courant																					(ex "$AGENDAS_AFFICHES")
	 * => agendas de ressource ET agenda persos activés qui nous sont affectés (& celui de l'user courant)
	 */
	public static function visibleCalendars()
	{
		if(self::$_visibleCalendars===null){
			$sqlDisplayedObjects=self::sqlDisplayedObjects();
			self::$_visibleCalendars=Db::getObjTab("calendar","SELECT DISTINCT * FROM ap_calendar WHERE  (type='ressource' AND ".$sqlDisplayedObjects.")  OR  (type='user' AND _idUser not in (select _id from ap_user where calendarDisabled=1) AND (_idUser=".Ctrl::$curUser->_id." or ".$sqlDisplayedObjects."))");
			self::$_visibleCalendars=self::sortCalendars(self::$_visibleCalendars);//Tri les agendas
		}
		return self::$_visibleCalendars;
	}

	/*
	 * Agendas de l'user courant : agenda perso & agendas de ressource accessible en écriture (aussi en lecture?)
	 * exple: pour le dashboard et les propositions d'evt
	 */
	public static function myCalendars()
	{
		if(self::$_visiblePersoCalendars===null)
		{
			self::$_visiblePersoCalendars=[];
			foreach(self::visibleCalendars() as $tmpCalendar){
				if($tmpCalendar->isMyPerso() || ($tmpCalendar->type=="ressource" && $tmpCalendar->fullRight()))
					{self::$_visiblePersoCalendars[]=$tmpCalendar;}
			}
		}
		return self::$_visiblePersoCalendars;
	}

	/*
	 * Agendas sur lesquels l'users courant peut affecter/proposer des événements
	 * => "visibleCalendars()" + autres agendas des "usersVisibles()"
	 */
	public static function affectationCalendars()
	{
		if(self::$_affectationCalendars===null)
		{
			self::$_affectationCalendars=self::visibleCalendars();
			//Uniquement pour les users (pas les guests)
			if(Ctrl::$curUser->isUser())
			{
				//Récupère les agendas des users de l'espace courant (pas les "usersVisibles()"..)
				$userIds="0,".implode(",",Ctrl::$curSpace->getUsers("ids"));
				$usersCalendars=Db::getObjTab("calendar","SELECT DISTINCT * FROM ap_calendar WHERE type='user' AND _idUser IN (".trim($userIds,",").") AND _idUser NOT IN (select _id from ap_user where calendarDisabled=1)");
				foreach($usersCalendars as $tmpCalendar){
					if(!in_array($tmpCalendar,self::$_affectationCalendars))	{self::$_affectationCalendars[]=$tmpCalendar;}
				}
				self::$_affectationCalendars=self::sortCalendars(self::$_affectationCalendars);//Tri les agendas
			}
		}
		return self::$_affectationCalendars;
	}

	/*
	 * Agendas Affichés actuellement
	 */
	public static function displayedCalendars()
	{
		if(self::$_displayedCalendars===null)
		{
			self::$_displayedCalendars=[];
			$visibleCalendars=self::visibleCalendars();
			$prefDisplayedCalendars=Txt::txt2tab(Ctrl::prefUser("displayedCalendars"));//Agendas affichés, enregistrés en préférence?
			//Ajoute l'agenda si :  l'agenda est dans les préférences d'affichage ou c'est mon agenda perso (agenda par défaut)
			foreach($visibleCalendars as $tmpCalendar){
				if(in_array($tmpCalendar->_id,$prefDisplayedCalendars) || empty($prefDisplayedCalendars) && $tmpCalendar->isMyPerso())	{self::$_displayedCalendars[]=$tmpCalendar;}
			}
			//Pas d'agendas affiché : prend le premier visible
			if(empty(self::$_displayedCalendars) && !empty($visibleCalendars))	{self::$_displayedCalendars[]=$visibleCalendars[0];}
		}
		return self::$_displayedCalendars;
	}

	/*
	 * Agenda courant est affiché?
	 */
	public function isDisplayed()
	{
		foreach(self::displayedCalendars() as $tmpCalendar){
			if($tmpCalendar->_id==$this->_id)	{return true;}
		}
	}

	/*
	 * Tri d'une liste d'objets calendriers : en fonction du type, puis du titre ET met l'agenda de l'user courant en 1er
	 */
	public static function sortCalendars($calendarsTab)
	{
		//Créé une liste d'agendas partagés et persos
		$myCal=$usersCals=$ressourceCals=[];
		foreach($calendarsTab as $tmpCalendar){
			if($tmpCalendar->isMyPerso())		{$myCal[]=$tmpCalendar;}
			elseif($tmpCalendar->type=="user")	{$usersCals[]=$tmpCalendar;}
			else								{$ressourceCals[]=$tmpCalendar;}
		}
		//Tri chaque tableau et renvoie l'ensemble
		usort($usersCals,["self","sortCompareCalendars"]);
		usort($ressourceCals,["self","sortCompareCalendars"]);
		return array_merge($myCal, $usersCals, $ressourceCals);
	}
	public static function sortCompareCalendars($obj1, $obj2)
	{
		return strcmp($obj1->title, $obj2->title);
	}

	/*
	 * SURCHARGE : Menu contextuel
	 */
	public function menuContext($options=null)
	{
		//Accès en édition?
		if($this->editRight())
		{
			//Export d'agenda (téléchargement / envoi par mail)
			$urlExportIcs="?ctrl=calendar&action=exportEvents&targetObjId=".$this->_targetObjId;
			$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"redir('".$urlExportIcs."')","iconSrc"=>"app/img/export.png","label"=>Txt::trad("CALENDAR_exporter_ical")];
			$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"redir('".$urlExportIcs."&sendMail=true')","iconSrc"=>"app/img/mail.png","label"=>Txt::trad("CALENDAR_exporter_ical_mail"),"tooltip"=>Txt::trad("CALENDAR_exporter_ical_mail2")."<br>".Txt::trad("envoyer_a")." ".Ctrl::$curUser->mail];
			//Import d'événements ou suppression des d'anciens événements dans l'agenda
			$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"lightboxOpen('?ctrl=calendar&action=importEvents&targetObjId=".$this->_targetObjId."')","iconSrc"=>"app/img/import.png","label"=>Txt::trad("CALENDAR_importer_ical")];
			$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"confirmRedir('".Txt::trad("CALENDAR_confirm_suppr_anciens_evt",true)."','?ctrl=calendar&action=oldEvtDelete&targetObjId=".$this->_targetObjId."')","iconSrc"=>"app/img/calendar/oldEvtDelete.png","label"=>Txt::trad("CALENDAR_suppr_anciens_evt"),"tooltip"=>Txt::trad("CALENDAR_suppr_anciens_evt_info")];
		}
		//Renvoie le menu surchargé
		return parent::menuContext($options);
	}
}