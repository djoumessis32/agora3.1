<?php
/*
 * Modele des dossiers de taches
 */
class MdlTaskFolder extends MdlObjectFolder
{
	const moduleName="task";
	const objectType="taskFolder";
	const dbTable="ap_taskFolder";
	const MdlObjectContent="MdlTask";
	const barWidth="150px";
	const barWidthBis="130px";
	
	/*
	 * SURCHARGE : Details complementaires du dossier -> synthese des "dateBeginEnd()" & "budgetEngagedAvailable()" des taches du dossier
	 */
	public function folderOtherDetails()
	{
		$textReturn=null;
		$MdlObjectContent=static::MdlObjectContent;
		$folderDetails=Db::getLine("SELECT  SUM(budgetEngaged) as budgetEngaged, SUM(budgetAvailable) as budgetAvailable,  ROUND(AVG(advancement),0) as advancementAverage,  MIN(dateBegin) as dateBegin, MAX(dateEnd) as dateEnd FROM ".$MdlObjectContent::dbTable." WHERE _idContainer=".$this->_id);
		//Synthese des "budgetEngagedAvailable()" des tâches
		if(!empty($folderDetails["budgetEngaged"]) && !empty($folderDetails["budgetAvailable"])){
			//Init les textes
			$iconEngaged=$txtEngaged=$iconAvail=$txtAvail=null;
			$percentEngaged=(!empty($folderDetails["budgetAvailable"]) && !empty($folderDetails["budgetEngaged"])) ? (($folderDetails["budgetEngaged"]/$folderDetails["budgetAvailable"]) * 100) : 0;
			if(!empty($folderDetails["budgetEngaged"]))		{$iconEngaged="<img src=\"app/img/task/budgetEngaged.png\">";	$txtEngaged=Txt::trad("TASK_budgetEngaged_total")." : ".$folderDetails["budgetEngaged"].($percentEngaged>0?" (".$percentEngaged."%)":null);}
			if(!empty($folderDetails["budgetAvailable"]))	{$iconAvail="<img src=\"app/img/task/budgetAvailable.png\">";	$txtAvail=Txt::trad("TASK_budgetAvailable_total")." : ".$folderDetails["budgetAvailable"];}
			if(!empty($iconEngaged) && !empty($iconAvail))	{$txtBar=$iconEngaged." ".$folderDetails["budgetEngaged"]." &nbsp; &nbsp; ".$iconAvail." : ".$folderDetails["budgetAvailable"];}
			elseif(!empty($iconEngaged))					{$txtBar=$iconEngaged." ".$txtEngaged;}
			elseif(!empty($iconAvail))						{$txtBar=$iconAvail." ".$txtAvail;}
			$txtTooltip=$txtEngaged."<br>".$txtAvail;
			$textReturn.=Tool::percentBar($percentEngaged, $txtBar, $txtTooltip, false, static::barWidth);
		}
		//Avancement Moyen
		if(!empty($folderDetails["advancementAverage"])){
			$txtBar="<img src='app/img/task/advancement.png'> ".$folderDetails["advancementAverage"]." %";
			$txtTooltip="<img src='app/img/task/advancement.png'> ".Txt::trad("TASK_advancementAverage")." : ".$folderDetails["advancementAverage"]." %";
			$textReturn.=Tool::percentBar((int)$folderDetails["advancementAverage"], $txtBar, $txtTooltip, false, static::barWidthBis);
		}
		//Synthese des "dateBeginEnd()" des tâches
		if(!empty($folderDetails["dateBegin"]) && !empty($folderDetails["dateEnd"])){
			$fillPercent=((time()-strtotime($folderDetails["dateBegin"])) / (strtotime($folderDetails["dateEnd"])-strtotime($folderDetails["dateBegin"]))) * 100;
			$txtBar="<img src='app/img/task/date.png'> ".Txt::displayDate($folderDetails["dateBegin"],"mini",$folderDetails["dateEnd"]);
			$txtTooltip=Txt::displayDate($folderDetails["dateBegin"],"full",$folderDetails["dateEnd"]);
			$textReturn.=Tool::percentBar($fillPercent, $txtBar, $txtTooltip, false, static::barWidth);
		}
		return $textReturn;
	}
}