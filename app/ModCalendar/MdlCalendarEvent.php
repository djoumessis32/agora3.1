<?php
/*
 * Modele des evenements
 */
class MdlCalendarEvent extends MdlObject
{
	const moduleName="calendar";
	const objectType="calendarEvent";
	const dbTable="ap_calendarEvent";
	const MdlObjectContainer="MdlCalendar";
	const hasAccessRight=false;
	const hasShortcut=false;
	const htmlEditorField="description";
	public static $requiredFields=array("title","dateBegin","timeBegin","dateEnd","timeEnd");
	public static $searchFields=array("title","description");
	private $_confirmedCalendars=null;
	private $_proposedCalendars=null;

	/*
	 * SURCHARGE : Constructeur
	*/
	function __construct($objIdOrValues=null)
	{
		parent::__construct($objIdOrValues);
		//Visibilité
		if(empty($this->contentVisible))	{$this->contentVisible="public";}
		//Categorie
		if(!empty($this->_idCat))	{$this->objCategory=Ctrl::getObj("calendarEventCategory",$this->_idCat);	$this->catColor=$this->objCategory->color;}
		else						{$this->objCategory=null;													$this->catColor="#444";}
		//Masque le title/description si besoin
		if($this->accessRight()<1){
			$this->title="<i>".Txt::trad("CALENDAR_evt_prive")."</i>";
			$this->description=null;
		}
	}

	/*
	 * SURCHARGE : Droit d'accès à un événement
	 * Ajoute le accessRight "0.5" qui permet juste de voir la plage horaire de l'evenement
	 */
	public function accessRight()
	{
		if($this->_accessRight===null)
		{
			//Droit par défaut
			$this->_accessRight=parent::accessRight();
			if($this->_accessRight<3)
			{
				//Droit en fonction des agendas auquels l'événement est affecté : supérieur?
				$tmpAccessRight=$tmpMaxRight=0;
				$allCalendarsFullAccess=true;
				foreach($this->affectedCalendars(true) as $objCalendar){
					if($objCalendar->accessRight()>$tmpMaxRight)	{$tmpMaxRight=$objCalendar->accessRight();}//Droit de l'agenda > droit max temporaire
					if($objCalendar->accessRight()<3)	{$allCalendarsFullAccess=false;}//L'agenda n'est pas en accès total..
				}
				if($allCalendarsFullAccess==true)								{$tmpAccessRight=3;}	//Que des agendas accessibles en écriture
				elseif($tmpMaxRight>=2)											{$tmpAccessRight=2;}	//Au moins 1 agenda accessible en écriture
				elseif($tmpMaxRight>=1 && $this->contentVisible=="public")		{$tmpAccessRight=1;}	//Au moins 1 agenda accessible en lecture/ecriture limité
				elseif($tmpMaxRight>=1 && $this->contentVisible=="public_cache"){$tmpAccessRight=0.5;}	//Au moins 1 agenda accessible en lecture/ecriture limité  :  lecture plage horaire uniquement!
				//Surcharge le droit d'accès?
				if($tmpAccessRight > $this->_accessRight)	{$this->_accessRight=$tmpAccessRight;}
			}
		}
		return $this->_accessRight;
 	}

	/*
	 * SURCHARGE : suppression d'evenement
	 */
	public function delete()
	{
		//Suppr l'evt sur un agenda spécifique
		if(Req::isParam("_idCalDeleteOn") && ($this->fullRight() || Ctrl::getObj("calendar",Req::getParam("_idCalDeleteOn"))->editRight()))
			{Db::query("DELETE FROM ap_calendarEventAffectation WHERE _idEvt=".$this->_id." AND _idCal=".(int)Req::getParam("_idCalDeleteOn"));}
		//Suppr l'evt a une date précise, pour un événement périodique (cf. "periodDateExceptions")
		elseif(Req::isParam("periodDateExceptionsAdd") && $this->fullRight())
			{Db::query("UPDATE ap_calendarEvent SET periodDateExceptions=".Db::format($this->periodDateExceptions."@@".Req::getParam("periodDateExceptionsAdd")."@@")." WHERE _id=".$this->_id);}
		//Suppression complète de l'evt
		elseif($this->fullRight())
			{Db::query("DELETE FROM ap_calendarEventAffectation WHERE _idEvt=".$this->_id);}
		//On supprime l'événement s'il est affecté à aucun agenda
		if(Db::getVal("SELECT count(*) FROM ap_calendarEventAffectation WHERE _idEvt=".$this->_id)==0)  {parent::delete();}
	}

	/*
	 * Agendas (objets) où l'evenement est affecté (ex "agendas_evts()")
	 * $confirmed = true / false / "all" pour récupérer toutes les affectations
	 */
	public function affectedCalendars($confirmed=true)
	{
		if($this->_confirmedCalendars===null){
			$this->_confirmedCalendars=Db::getObjTab("calendar","SELECT * FROM ap_calendar WHERE _id in (select _idCal as _id from ap_calendarEventAffectation T2 WHERE _idEvt=".$this->_id." and confirmed=1)");
			$this->_proposedCalendars=Db::getObjTab("calendar","SELECT * FROM ap_calendar WHERE _id in (select _idCal as _id from ap_calendarEventAffectation T2 WHERE _idEvt=".$this->_id." and confirmed is null)");
		}
		if($confirmed==true)		{return $this->_confirmedCalendars;}
		elseif($confirmed==false)	{return $this->_proposedCalendars;}
		elseif($confirmed=="all")	{return array_merge($this->_confirmedCalendars,$this->_proposedCalendars);}
	}

	/*
	 * Texte des agendas où l'evenement est affecté + ceux ou il est en attente de confirmation  (ex "txt_affections_evt()")
	 */
	public function affectedCalendarsLabel()
	{
		if(Ctrl::$curUser->isUser())
		{
			$calendarsConfirmed=$calendarsUnconfirmed=null;
			foreach($this->affectedCalendars(true) as $objCalendar)		{$calendarsConfirmed.=", ".$objCalendar->title;}
			foreach($this->affectedCalendars(false) as $objCalendar)	{$calendarsUnconfirmed.=", ".$objCalendar->title;}
			if(!empty($calendarsConfirmed))		{$calendarsConfirmed=Txt::trad("CALENDAR_affectations_evt")." ".trim($calendarsConfirmed,",")."<br>";}
			if(!empty($calendarsUnconfirmed))	{$calendarsUnconfirmed=Txt::trad("CALENDAR_affectationUnconfirmed")." ".trim($calendarsUnconfirmed,",");}
			return $calendarsConfirmed.$calendarsUnconfirmed;
		}
	}

	/*
	 * SURCHARGE : Menu contextuel
	 */
	public function menuContext($options=null)
	{
		//Affectations aux agendas & séparateur
		$options["specificOptions"][]=["actionJs"=>null,"iconSrc"=>null,"label"=>$this->affectedCalendarsLabel()];
		//Evt dans plusieurs agendas ?
		if(count($this->affectedCalendars())>1)
		{
			//"supprimer" se transforme en "Supprimer dans tous les agendas ?"
			$options["deleteLabel"]=Txt::trad("CALENDAR_supprimer_evt_agendas");
			//Ajoute l'option "Supprimer dans cet agenda ?"
			if(!empty($options["_idCal"]) && Ctrl::getObj("calendar",$options["_idCal"])->editRight())
				{$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"confirmRedir('".Txt::trad("confirmDelete",true)."','".$this->getUrl("delete")."&_idCalDeleteOn=".$options["_idCal"]."')","iconSrc"=>"app/img/delete.png","label"=>Txt::trad("CALENDAR_supprimer_evt_agenda")];}
		}
		//Evt périodique : ajoute l'option "Supprimer uniquement à cette date ?"
		if(!empty($options["curDateTime"]) && !empty($this->periodType) && $this->fullRight())
			{$options["specificOptions"][]=["inMainMenu"=>true,"actionJs"=>"confirmRedir('".Txt::trad("confirmDelete",true)."','".$this->getUrl("delete")."&periodDateExceptionsAdd=".date("Y-m-d",$options["curDateTime"])."')","iconSrc"=>"app/img/delete.png","label"=>Txt::trad("CALENDAR_supprimer_evt_date")];}
		//Renvoie le menu surchargé
		return parent::menuContext($options);
	}
}