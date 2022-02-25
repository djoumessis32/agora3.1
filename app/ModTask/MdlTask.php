<?php
/*
 * Modele des taches
 */
class MdlTask extends MdlObject
{
	private $_isDelayed=null;
	const moduleName="task";
	const objectType="task";
	const dbTable="ap_task";
	const MdlObjectContainer="MdlTaskFolder";
	const isFolderContent=true;
	const isSelectable=true;
	const htmlEditorField="description";
	const barWidth="160px";
	const barWidthBis="120px";
	public static $displayModeOptions=array("line","block");
	public static $requiredFields=array("title");
	public static $searchFields=array("title","description");
	public static $sortFields=array("dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","title@@asc","title@@desc","description@@asc","description@@desc","priority@@asc","priority@@desc","advancement@@asc","advancement@@desc","dateBegin@@asc","dateBegin@@desc","dateEnd@@asc","dateEnd@@desc");

	/*
	 * icone & label "Priority"
	 */
	public function priority()
	{
		if(!empty($this->priority))
			{return "<img src=\"app/img/task/priority".$this->priority.".png\" class='cursorHelp' title=\"".Txt::trad("TASK_priority")." ".Txt::trad("TASK_priority".$this->priority)."\">";}
	}

	/*
	 * Tache en retard : date de fin passée et tache inachevée (advancement < 100%)
	 */
	public function isDelayed($displayLabel=false)
	{
		if($this->_isDelayed===null){
			$this->_isDelayed=(!empty($this->advancement) && !empty($this->dateEnd) && strtotime($this->dateEnd)<time() && (int)$this->advancement<100) ? true : false;
		}
		if($displayLabel==true && $this->_isDelayed==true)	{return Txt::trad("TASK_advancement_retard")." <img src='app/img/important.png' style='height:20px'>";}
		else												{return $this->_isDelayed;}
	}

	/*
	 * Pourcentage d'avencement en %
	 */
	public function fillPercent()
	{
		if(!empty($this->dateEnd) && $this->dateBegin!=$this->dateEnd){
			$timeBegin=strtotime($this->dateBegin);
			$timeEnd=strtotime($this->dateEnd);
			return floor(100 * ((time()-$timeBegin) / ($timeEnd-$timeBegin)));
		}
	}

	/*
	 * icone & label "dateBegin" & "dateEnd"			(ex "tache_debut_fin()")
	 */
	public function dateBeginEnd($percentBar=null)
	{
		if(!empty($this->dateBegin) || !empty($this->dateEnd))
		{
			//Affichage : Icone + tooltip / Barre détaillée
			if($percentBar==null && MdlTask::getDisplayMode()=="block")	{return "<img src='app/img/task/date.png' class='cursorHelp' title=\"".Txt::displayDate($this->dateBegin,"full",$this->dateEnd)."\">";}
			else{
				$txtBar="<img src='app/img/task/date.png'> ".(empty($this->dateEnd)?Txt::displayDate($this->dateBegin,"full"):Txt::displayDate($this->dateBegin,"normal",$this->dateEnd));
				$txtTooltip=Txt::displayDate($this->dateBegin,"full",$this->dateEnd)." <br>".$this->isDelayed(true);
				return Tool::percentBar($this->fillPercent(), $txtBar, $txtTooltip, $this->isDelayed(), static::barWidth);
			}
		}
	}

	/*
	 * Barre de la "timeline"
	 */
	public function timelineBeginEnd()
	{
		if(!empty($this->dateBegin) || !empty($this->dateEnd)){
			$txtBar=null;
			$txtTooltip=$this->title."<br>".Txt::displayDate($this->dateBegin,"full",$this->dateEnd);
			if(!empty($this->advancement)){
				$txtBar.="<img src='app/img/task/advancement".($this->isDelayed()?"Delayed":null).".png'> ".$this->advancement."%";
				$txtTooltip.="<br><img src='app/img/task/advancement".($this->isDelayed()?"Delayed":null).".png'> ".Txt::trad("TASK_advancement")." : ".$this->advancement." % <br>".$this->isDelayed(true);
			}
			return "<a href=\"javascript:lightboxOpen('".$this->getUrl("vue")."')\">".Tool::percentBar($this->fillPercent(), $txtBar, $txtTooltip, $this->isDelayed())."</a>";
		}
	}

	/*
	 * icone & label "Advancement" & "HumanDayCharge"
	 */
	public function advancementHumanDayCharge($percentBar=null)
	{
		//Init les textes
		$return=$iconAdv=$txtAdv=$txtAdvTooltip=$iconHum=$txtHum=null;
		if(!empty($this->advancement))		{$iconAdv="<img src='app/img/task/advancement".($this->isDelayed()?"Delayed":null).".png'>";	$txtAdv=Txt::trad("TASK_advancement")." : ".$this->advancement." %";  $txtAdvTooltip=$txtAdv." <br>".$this->isDelayed(true);}
		if(!empty($this->humanDayCharge))	{$iconHum="<img src='app/img/task/humanDayCharge.png'>";										$txtHum=Txt::trad("TASK_humanDayCharge")." : ".$this->humanDayCharge;}
		//Affichage : Icones + tooltip  /  Barre détailé
		if(!empty($iconAdv) || !empty($iconHum))
		{
			if($percentBar==null && MdlTask::getDisplayMode()=="block"){
				if(!empty($iconAdv))	{$return.="<span class='cursorHelp' title=\"".$txtAdvTooltip."\">".$iconAdv."</span>";}
				if(!empty($iconHum))	{$return.="<span class='cursorHelp' title=\"".$txtHum."\">".$iconHum."</span>";}
				return $return;
			}else{
				if(!empty($iconAdv) && !empty($iconHum)){$txtBar=$iconAdv." : ".$this->advancement."% &nbsp; &nbsp; ".$iconHum." ".$this->humanDayCharge;}
				elseif(!empty($iconAdv))				{$txtBar=$iconAdv." ".$txtAdv;}
				elseif(!empty($iconHum))				{$txtBar=$iconHum." ".$txtHum;}
				$txtTooltip=$txtAdvTooltip."<br>".$txtHum;
				return Tool::percentBar($this->advancement, $txtBar, $txtTooltip, $this->isDelayed(), static::barWidthBis);
			}
		}
	}

	/*
	 * icone & label "budgetAvailable" & "budgetAvailable"
	 */
	public function budgetEngagedAvailable($percentBar=null)
	{
		//Init les textes
		$return=$iconEngaged=$txtEngaged=$iconAvail=$txtAvail=$txtExceeded=null;
		$percentEngaged=(!empty($this->budgetAvailable) && !empty($this->budgetEngaged)) ? (($this->budgetEngaged/$this->budgetAvailable) * 100) : 0;
		if(!empty($this->budgetEngaged))	{$iconEngaged="<img src=\"app/img/task/budgetEngaged.png\">";	$txtEngaged=Txt::trad("TASK_budgetEngaged")." : ".$this->budgetEngaged.($percentEngaged>0?" (".$percentEngaged."%)":null);}
		if(!empty($this->budgetAvailable))	{$iconAvail="<img src=\"app/img/task/budgetAvailable.png\">";	$txtAvail=Txt::trad("TASK_budgetAvailable")." : ".$this->budgetAvailable;}
		if(!empty($iconEngaged) && !empty($iconAvail) && $this->budgetEngaged>$this->budgetAvailable)	{$txtExceeded="<img src='app/img/important.png' style='height:20px'> ".Txt::trad("TASK_budgetExceeded");}
		//Affichage : Icones + tooltip  /  Barre détailé
		if(!empty($iconEngaged) || !empty($iconAvail))
		{
			if($percentBar==null && MdlTask::getDisplayMode()=="block"){
				if(!empty($iconEngaged))	{$return.="<span class='cursorHelp' title=\"".$txtEngaged."\">".$iconEngaged."</span>";}
				if(!empty($iconAvail))		{$return.="<span class='cursorHelp' title=\"".$txtAvail."\">".$iconAvail."</span>";}
				return $return;
			}else{
				if(!empty($iconAvail) && !empty($iconEngaged))	{$txtBar=$iconEngaged." ".$this->budgetEngaged." &nbsp; &nbsp; ".$iconAvail." ".$this->budgetAvailable;}
				elseif(!empty($iconEngaged))					{$txtBar=$iconEngaged." ".$txtEngaged;}
				elseif(!empty($iconAvail))						{$txtBar=$iconAvail." ".$txtAvail;}
				$txtTooltip=$txtEngaged."<br>".$txtAvail."<br>".$txtExceeded;
				return Tool::percentBar($percentEngaged, $txtBar, $txtTooltip, $this->isDelayed(), static::barWidthBis);
			}
		}
	}

	/*
	 * icone & label "responsiblePersons"
	 */
	public function responsiblePersons($percentBar=null)
	{
		if(!empty($this->responsiblePersons))
		{
			//Liste des responsables
			$responsiblePersons=$responsiblePersonsFirstname=null;
			foreach(Txt::txt2tab($this->responsiblePersons) as $userId){
				$responsiblePersons.=Ctrl::getObj("user",$userId)->display().", ";
				$responsiblePersonsFirstname.=Ctrl::getObj("user",$userId)->display("firstName").", ";
			}
			//Affichage icone / barre
			$personsIcon="<img src='app/img/user/icon.png'>";
			$txtTooltip=Txt::trad("TASK_responsiblePersons")." :<br>".trim($responsiblePersons,", ");
			if($percentBar==null && MdlTask::getDisplayMode()=="block")	{return "<span class='cursorHelp' title=\"".$txtTooltip."\">".$personsIcon."</span>";}
			else{
				$txtBar=substr(Txt::trad("TASK_responsiblePersons"),0,4)." : ".trim($responsiblePersonsFirstname,", ");
				return Tool::percentBar(0, $personsIcon." ".Txt::reduce($txtBar,60), $txtTooltip, false, static::barWidth);
			}
		}
	}
}
