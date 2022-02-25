<script>
////	LARGEUR DE LA TIMELINE (GANTT)
$(window).on("resize",function(){
	if($(".vTimelineBlock").exist())
	{
		$(".vTimelineBlock").css("display","none");
		var timelineBlockWidth=$(".pageFullContent").width();
		$(".vTimelineBlock").outerWidth(timelineBlockWidth).css("display","inline-block");
		setTimeout(function(){ setObjBlockWidth(); },100);//Relance l'affichage des blocks
	}
});
$(function(){ $(window).trigger("resize"); });
</script>

<style>
/* BLOCKS DE CONTENU */
.objDBlock									{max-width:350px; min-width:180px; height:80px;}/*cf. "setObjBlockWidth()"*/
.objDBlock .objTask .objLabelMain			{display:table-cell; height:100%; text-align:center; vertical-align:middle;}
.objDBlock .objTask .objLabelDetails		{display:table-row; text-align:right;}
.objDBlock .objTask .objLabelDetails img	{max-height:20px; margin:0px 3px 2px 5px;}
.objDLine .objTask .objLabelDetails > img	{max-height:20px;}
.objDLine .percentBar						{margin-left:10px;}
.objTask .objLabelMain a					{display:block; padding:5px;}
.vIconPriority								{text-align:center;}
/*BLOCK TIMELINE*/
.vTimelineBlock						{margin-top:30px; margin-bottom:20px; overflow-x:auto;}
.vTimelineBlock > table				{border-collapse:collapse;}
.vTimelineMonths, .vTimelineDays, .vTimelineTaskDays	{vertical-align:middle; width:18px; min-width:18px;}
.vTimelineTitle						{padding:2px; white-space:nowrap; vertical-align:middle;}
.vTimelineTitle img[src*='edit']	{max-height:15px}
.vTimelineDays						{padding:2px;}
.vTimelineTaskDays					{padding:0px;}
.vTimelineDays						{font-weight:normal; font-size:85%;}
.vTimelineLeftBorder				{border-left:#ddd solid 1px;}
.vTimelineBlock .percentBarContent	{text-align:left; cursor:pointer;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock"><?= CtrlObject::menuFolderTree() ?></div>
		<div class="sBlock">
			<?php if(Ctrl::$curContainer->editContentRight()){ ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlTask::getUrlNew() ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("TASK_ajouter_tache") ?></div></div>
				<?php if(Ctrl::$curContainer->addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=object&action=FolderEdit&targetObjId=<?= Ctrl::$curContainer->getType()."&_idContainer=".Ctrl::$curContainer->_id ?>')"><div class="moduleMenuIcon"><img src="app/img/folderAdd.png"></div><div class="moduleMenuTxt"><?= Txt::trad("ajouter_dossier") ?></div></div><?php } ?>
				<hr>
			<?php } ?>
			<!--FILTRE PAR PRIORITE-->
			<div class="moduleMenuLine sLink">
				<div class="moduleMenuIcon vIconPriority"><img src="app/img/task/priority<?= Req::getParam("filterPriority") ?>.png"></div>
				<div class="moduleMenuTxt">
					<div class="menuContext sBlock" id="vMenuPriority">
						<?php for($tmpPriority=0; $tmpPriority<=4; $tmpPriority++){
							if($tmpPriority==0)  {$tmpPriority=null;}
							echo "<div class='menuContextLine ".($tmpPriority==Req::getParam("filterPriority")?'sLinkSelect':'sLink')."' onclick=\"redir('".Tool::getParamsUrl("filterPriority")."&filterPriority=".$tmpPriority."')\"><div class='menuContextIcon'><img src='app/img/task/priority".$tmpPriority.".png'></div><div class='menuContextTxt'>".(empty($tmpPriority)?Txt::trad("tout_afficher"):Txt::trad("TASK_priority".$tmpPriority))."</div></div>";
						} ?>
					</div>
					<span class="sLink menuContextLauncher" for="vMenuPriority"><?= Txt::trad("TASK_priority")." ".(Req::getParam("filterPriority")>=1?Txt::trad("TASK_priority".Req::getParam("filterPriority")):null) ?></span>
				</div>
			</div>
			<?= MdlTask::menuSelectObjects().MdlTask::menuDisplayMode().MdlTask::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= Ctrl::$curContainer->folderContentDescription() ?></div></div>
		</div>
	</div>
	<div class="pageFullContent">
		<!--CHEMIN DU DOSSIER & LISTE DES DOSSIERS & LISTE DES TACHES-->
		<?= CtrlObject::menuFolderPath().$foldersList ?>
		<?php foreach($tasksList as $tmpTask){ ?>
			<div class="sBlock objScrollContent <?= (MdlTask::getDisplayMode()=="line"?"objDLine":"objDBlock") ?>" <?= $tmpTask->blockIdForMenuContext() ?>>
				<?= $tmpTask->menuContext(); ?>
				<div class="objTable objTask">
					<div class="objLabelMain"><a href="javascript:lightboxOpen('<?= $tmpTask->getUrl("vue") ?>')"><?= $tmpTask->priority()."&nbsp; ".$tmpTask->title ?></a></div>
					<div class="objLabelDetails"><?= $tmpTask->responsiblePersons().$tmpTask->budgetEngagedAvailable().$tmpTask->advancementHumanDayCharge().$tmpTask->dateBeginEnd() ?></div>
					<div class="objLabelAutor"><?= $tmpTask->displayAutor() ?></div>
					<div class="objLabelDate"><?= $tmpTask->displayDate(true,"date") ?></div>
				</div>
			</div>
		<?php } ?>
		<!--AUCUN CONTENU-->
		<?php if(empty($foldersList) && empty($tasksList)){ ?><div class="pageEmptyContent"><?= Txt::trad("TASK_aucune_tache") ?></div><?php } ?>

		<!--TIMELINE (GANTT)-->
		<?php if(!empty($timelineBegin)){ ?>
		<div class="sBlock vTimelineBlock objDMain">
			<table>
			<?php
			//HEADER MOIS
			echo "<tr>
					<td class='vTimelineTitle'>&nbsp;</td>";
					foreach($timelineDays as $tmpDay)	{echo ($tmpDay["newMonthLabel"]) ? "<td colspan='".$tmpDay["newMonthColspan"]."' class='vTimelineMonths vTimelineLeftBorder'>".$tmpDay["newMonthLabel"]."</td>" : null;}
			echo "</tr>";
			//HEADER JOUR
			echo "<tr>
					<td class='vTimelineTitle'>&nbsp;</td>";
					foreach($timelineDays as $tmpDay)	{echo "<td class='vTimelineDays ".$tmpDay["classBorderLeft"]."'>".$tmpDay["dayLabel"]."</td>";}
			echo "</tr>";
			//TIMELINE DE CHAQUE TACHE
			foreach($timelineTasks as $tmpTask)
			{
				$taskDateBegin=date("Y-m-d",$tmpTask->timeBegin);
				echo "<tr class='sTableRow'>
						<td class='vTimelineTitle'><a href=\"javascript:lightboxOpen('".$tmpTask->getUrl("vue")."')\" title=\"".$tmpTask->title."\">".Txt::reduce($tmpTask->title,40)."</a></td>";
						//Affiche les jours de la timeline
						foreach($timelineDays as $tmpDay)
						{
							//Affiche la cellule du jour OU cellule de la tache si : 1er jour de la tache OU jour précédant la tache OU jour suivant la tache
							if($taskDateBegin==$tmpDay["curDate"] || $tmpDay["timeBegin"]<$tmpTask->timeBegin || $tmpTask->timeEnd<$tmpDay["timeBegin"])
								{echo "<td class=\"vTimelineTaskDays ".$tmpDay["classBorderLeft"]."\" ".($taskDateBegin==$tmpDay["curDate"]?"colspan='".$tmpTask->timelineColspan."'":null)." >".($taskDateBegin==$tmpDay["curDate"]?$tmpTask->timelineBeginEnd():'&nbsp;')."</td>";}
						}
				echo "</tr>";
			}
			?>
			</table>
		</div>
		<?php } ?>
	</div>
</div>