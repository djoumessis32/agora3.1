<?php if($cptCal==0){ ?>
<style>
.vCalMonth							{display:table; width:100%; height:100%; -moz-user-select:none; -webkit-user-select:none; -ms-user-select:none;}
.vCalMonthHeaderLine, .vCalMonthDaysLine	{display:table-row;}
.vCalMonthHeaderLine				{height:15px;}
.vCalMonthWeekNb					{display:table-cell; color:#999; vertical-align:middle; width:20px;}
.vCalMonthWeekNb:hover				{background:#e9e9e9;}
.vCalMonthDaysLine .vCalMonthWeekNb	{border-right:solid 1px #ddd;}
.vCalMonthDayLabel, .vCalMonthDay	{display:table-cell; vertical-align:top;}
.vCalMonthDayLabel					{text-align:center; border-bottom:solid #eee 1px;}
.vCalMonthDay						{background:#fff; border-width:2px 1px 1px 2px; border-color:#fff #ccc #ddd #fff; border-style:solid;}
.vCalMonthOtherMonth				{background:#e9e9e9;}/*cf. $tmpDay["dayClass"]*/
.vCalMonthOtherMonth .vCalMonthDayCellLabel	{color:#aaa;}
.vCalMonthOldDate					{background:#f9f9f9;}/*idem*/
.vCalMonthDayCellLabel				{color:#222; padding:3px; text-align:right; vertical-align:top; border-bottom:dotted #eee 1px;}
.vCalMonthDayCellLabel:hover		{color:#222; background:#ddd;}
.vCalMonthDayCellLabel img			{display:none; margin-left:5px;}
.vCalMonthDayCellLabel:hover img	{display:inline;}
.vCalMonthCelebrationDay			{float:left; color:#070; font-style:italic;}
.vCalMonthToday						{color:#d00; font-style:italic; background:#f9f9f9; border-bottom:dotted #ccc 1px;}

/* IMPRESSION */
@media print {
	.vCalMonth		{height:72%;}
	.vCalMonthDay	{font-size:10px;}
	.vCalMonthOtherMonth .vCalMonthDayCellLabel	{color:#ddd;}
}
</style>

<script>
////	Gère l'affichage de la vue "month" (cf. "CalendarDimensions()")
function calendarDimensionsVue()
{
	//largeur des jours
	var dayWidth=Math.round(($(".vCalMonth").innerWidth() - $(".vCalMonthWeekNb").innerWidth()) / 7);
	$(".vCalMonthDayLabel,.vCalMonthDay").css("width",dayWidth+"px");
	//hauteur des jours
	var dayHeightPercent=Math.round(100 / ($(".vCalMonth .vCalMonthDaysLine").length / $(".vCalMonth").length));
	$(".vCalMonthDay").css("height",dayHeightPercent+"%");
	//Redimentionne "vCalendarBlock" si la hauteur réelle est supérieure à "availableContentHeight()"
	$(".vCalendarBlock").each(function(){
		var realHeight=$(this).find(".vCalendarHeader").height() + $(this).find(".vCalMonth").height() -2;
		if($(this).innerHeight()<realHeight)	{$(this).css("height",realHeight);}
	});
}
</script>
<?php } ?>


<div class="vCalMonth">
	<!--HEADER DES JOURS-->
	<div class="vCalMonthHeaderLine">
		<div class="vCalMonthWeekNb">&nbsp;</div>
		<?php for($cmpDay=1; $cmpDay<=7; $cmpDay++){ ?>
			<div class="vCalMonthDayLabel"><?= Txt::trad("jour_".$cmpDay) ?></div>
		<?php } ?>
	</div>

	<!--JOURS DU MOIS-->
	<?php foreach($periodDays as $tmpDay){ ?>
		<!--LIGNE DE LA SEMAINE + Numéro de semaine-->
		<?php if(date("N",$tmpDay["timeBegin"])==1){ ?>
		<div class="vCalMonthDaysLine">
			<div class="vCalMonthWeekNb sLink" onClick="redir('?ctrl=calendar&displayMode=week&curTime=<?= $tmpDay["timeBegin"] ?>')" title="<?= Txt::trad("CALENDAR_voir_num_semaine")." ".date("W",$tmpDay["timeBegin"]) ?>"><?= date("W",$tmpDay["timeBegin"]) ?></div>
		<?php } ?>
			<!--CELLULE DU JOUR-->
			<div class="vCalMonthDay <?= $tmpDay["dayClass"] ?>">
				<div class="vCalMonthDayCellLabel sLink <?= $tmpDay["isTodayClass"]?>" onClick="lightboxOpen('<?= $tmpDay["urlEditNew"] ?>&_idCal=<?= $tmpCal->_id ?>')" title="<?= $txtAddEvt.$txtAddEvtPropose ?>">
					<div class="vCalMonthCelebrationDay"><?= $tmpDay["celebrationDay"] ?></div>
					<?= date("j",$tmpDay["timeBegin"]) ?><img src="app/img/plusSmall2.png">
				</div>
				<!--EVENEMENTS DU JOUR-->
				<?php foreach($eventList[$tmpDay["date"]] as $tmpEvt){ ?>
				<div class="vCalEvtBlock sBlock" <?= 'data-catColor="'.$tmpEvt->catColor.'" data-colorDisplay="'.$tmpCal->evtColorDisplay.'" '.$tmpEvt->blockIdForMenuContext() ?> data-onclickJs="lightboxOpen('<?= $tmpEvt->getUrl("vue") ?>')">
					<?= $tmpEvt->menuContext(["inlineLauncher"=>true, "_idCal"=>$tmpCal->_id, "curDateTime"=>$tmpDay["timeBegin"]]); ?>
					<span class="vCalEvtLabel">
						<?= Txt::displayDate($tmpEvt->dateBegin,"mini",$tmpEvt->dateEnd) ?>
						<span class="vCalEvtTitle"><?= Txt::reduce($tmpEvt->title,65) ?><?= (!empty($tmpEvt->important)) ? "<img src='app/img/important.png'>" : null ?></span>
					</span>
				</div>
				<?php } ?>
			</div>
		<!--LIGNE DE LA SEMAINE : FIN-->
		<?php if(date("N",$tmpDay["timeBegin"])==7){?></div><?php } ?>
	<?php } ?>
</div>