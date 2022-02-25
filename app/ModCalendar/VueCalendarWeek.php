<?php if($cptCal==0){ ?>
<script>
////	Gère l'affichage de la vue "week" (cf. "CalendarDimensions()")
function calendarDimensionsVue()
{
	////	Largeur / Hauteur des block scroller des agendas
	$(".vCalWeekScroller").css("display","none");//masque temporairement le scroller pour récupérer la vraie hauteur de "vCalWeek" 
	weekScrollerWidth=$(".vCalWeek").innerWidth();
	weekScrollerHeight=$(".vCalWeek").innerHeight() - $(".vCalWeekHeader").outerHeight(true);
	$(".vCalWeekScroller").css("width",weekScrollerWidth).css("height",weekScrollerHeight-1).css("display","block");

	////	Lance l'affichage de chaque agenda
	$(".vCalWeekScroller").each(function(){
		calWeekScrollerTmpId=this.id;
		////	Hauteur des lignes d'heure (le créneau horaire de l'agenda doit prendre la hauteur du "weekScrollerHeight")
		var calWeekLineHeight=Math.round(weekScrollerHeight / parseInt($(this).attr("data-timeSlotDuration")));//"timeSlotDuration" = nb d'heures
		if(calWeekLineHeight<30)	{calWeekLineHeight=30;}//30px minimum pour les lignes d'heure
		$(".vCalWeekLine,.vCalWeekHourQuarts").css("height",calWeekLineHeight);

		////	Hauteur/Largeur (..avec marges) des jours
		cellDayHeight=$(".vCalWeekLine:first-child .vCalWeekHour").outerHeight(true);
		cellDayWidth=Math.round(($(this).innerWidth() - $(".vCalWeekHours").innerWidth()) / $("#"+calWeekScrollerTmpId+" .vCalWeekLine:first-child .vCalWeekHour").length);
		$(".vCalWeekDayLabel,.vCalWeekHour").css("width",cellDayWidth+"px");

		////	Affichage des blocks d'événement
		hearlierEvtTop=null;
		$(".vCalEvtBlock").each(function(){
			var curDaySelector=".vCalWeekLine:first-child [data-dayCpt='"+$(this).attr("data-dayCpt")+"']";
			//Position left et largeur
			var cellDayPosition=$(curDaySelector).position();
			$(this).outerWidth($(curDaySelector).innerWidth()-2).css("margin-left",cellDayPosition.left);
			//Position top
			var minutesFromDayBegin=$(this).attr("data-minutesFromDayBegin");
			var evtTop=(minutesFromDayBegin!="pastEvt")  ?  Math.round((cellDayHeight/60) * parseInt(minutesFromDayBegin))  :  0;
			$(this).css("margin-top",evtTop);
			//Hauteur de l'evt (minimum 20px)
			var durationMinutes=parseInt($(this).attr("data-durationMinutes"));
			if(durationMinutes>0)	{var evtHeight=Math.round((cellDayHeight/60) * durationMinutes) -3;}//enlève la marge et shadow du block evt
			else					{var evtHeight=Math.round(cellDayHeight/2) -3;}//idem
			$(this).outerHeight(evtHeight);
			//Hauteur de l'evt est inférieur à 30px : 40 carac max
			if(evtHeight<30){
				var evtTitleTmp=$(this).find(".vCalEvtTitle").html();
				if(evtTitleTmp.length>60)	{$(this).find(".vCalEvtTitle").html(evtTitleTmp.substr(0,40)+"...");}
			}
			//Première position des evts de la semaine (evt le plus tôt de la semaine)
			if(minutesFromDayBegin!="pastEvt" && (hearlierEvtTop===null || evtTop<hearlierEvtTop))	{hearlierEvtTop=evtTop;}
		});

		////	Place l'agenda (scroll) au début de la plage horaire OU sur l'événement le plus tôt de la semaine
		var scrollTopTimeSlot=cellDayHeight * parseInt($(this).attr("data-timeSlotBegin"));
		var scrollTopCalWeek=(hearlierEvtTop!==null && hearlierEvtTop<scrollTopTimeSlot)  ?  hearlierEvtTop  :  scrollTopTimeSlot;
		$(this).scrollTop(scrollTopCalWeek);

		////	Sélection de créneau horaire pour l'ajout d'un evt
		//Init la sélection & Détecte le Mousedown/Mouseup
		isMouseDown=selectedDate=timeSelectBegin=timeSelectEnd=null;
		$(".vCalWeekHourQuartCell").on("mousedown",function(){ isMouseDown=true; });
		$(document).mouseup(function(){ initTimeslotSelection(); });
		//Update le créneau horaire au survol de "vCalWeekHourQuartCell" (mousemove, pas mouseover!)
		$("#"+calWeekScrollerTmpId+" .vCalWeekHourQuartCell").on("mousemove",function(){
			//Sélectionne le Timeslot, si on est sur le même jour
			if(isMouseDown==true && (selectedDate==null || selectedDate==$(this).attr("data-selectedDate")))
			{
				//Init "timeSelectBegin" & "timeSelectBegin" & Sélection du jour
				selectedDate=$(this).attr("data-selectedDate");
				var timeCellBegin=parseInt($(this).attr("data-newTimeBegin"));
				var timeCellEnd=timeCellBegin+900;
				if(timeSelectBegin==null)	{timeSelectBegin=timeCellBegin;}
				timeSelectEnd=timeCellEnd;
				//Ajoute la classe aux cellules sélectionnées : parcourt les cellules entre "timeSelectBegin" et "timeSelectEnd"
				$("#"+calWeekScrollerTmpId+" .vCalWeekHourQuartCell[data-selectedDate='"+selectedDate+"']").each(function(){
					var timeCellBegin=parseInt($(this).attr("data-newTimeBegin"));
					(timeSelectBegin<=timeCellBegin && timeCellBegin<timeSelectEnd)  ?  $(this).addClass("vCalWeekHourQuartSelect")  :  $(this).removeClass("vCalWeekHourQuartSelect");
				});
			}
		});
		//Sélection terminée!
		$("#"+calWeekScrollerTmpId+" .vCalWeekHourQuartCell").on("mouseup",function(){
			//Sélection pas encore initialisé (si ya click direct sur une plage horaire)
			if(timeSelectBegin==null || timeSelectEnd==null)	{timeSelectBegin=timeSelectEnd=parseInt($(this).attr("data-newTimeBegin"));}
			//lance la page d'édition d'un evt & réinitialise la sélection
			lightboxOpen("<?= MdlCalendarEvent::getUrlNew() ?>&_idCal=<?= $tmpCal->_id ?>&newTimeBegin="+timeSelectBegin+"&newTimeEnd="+timeSelectEnd);
			initTimeslotSelection();
		});
		//Fonction pour réinitialiser la sélection
		function initTimeslotSelection(){
			isMouseDown=selectedDate=timeSelectBegin=timeSelectEnd=null;
			$(".vCalWeekHourQuartCell").removeClass("vCalWeekHourQuartSelect");
		}
	});
}
</script>


<style>
.vCalWeek						{display:table; width:100%; height:100%; -moz-user-select:none; -webkit-user-select:none; -ms-user-select:none;}
.vCalWeekScroller				{overflow-y:scroll; overflow-x:hidden; position:absolute;}
.vCalWeekHeader, .vCalWeekTable	{display:table; width:100%;}
.vCalWeekHeaderLine				{display:table-row; height:20px;}
.vCalWeekHeaderScrollerMargin	{width:10px;}/*largeur de la barre de défilement*/
.vCalWeekDayLabel				{text-align:center; vertical-align:bottom; <?= $displayMode=="day"?"visibility:hidden;":null ?>}
.vCalWeekDayLabelToday			{color:#c00;}
.vCalWeekHours, .vCalWeekDayLabel, .vCalWeekHour	{display:table-cell;}
.vCalWeekLine					{display:table-row;}
.vCalWeekLine .vCalWeekHours	{border-top:solid 1px #eee;}
.vCalWeekLine .vCalWeekHour		{background:#fff; border-top:solid 1px #fff; border-bottom:solid 1px #ddd; border-left:solid 1px #ccc;}
.vCalWeekHours					{color:#555; width:35px;}
.vCalWeekLine .vCalWeekHours	{text-align:center; background:#ddd;}
.vCalWeekHourOutTimeslot		{background:#f7f7f7;}
.vCalWeekHourQuarts				{display:table; width:100%;}
.vCalWeekHourQuartLine			{display:table-row;}
.vCalWeekHourQuartCell			{display:table-cell; height:25%; cursor:default;}
.vCalWeekHourQuartCell:hover 	{background:#aaa;}
.vCalWeekHourQuartOld			{background:#eee;}
.vCalWeekHourQuartCurrent		{background:#eee; border-bottom:solid 1px #c00;}
.vCalWeekHourQuartSelect		{background:#999;}
.vCalEvtBlock					{position:absolute;}

/* IMPRESSION */
@media print {
	.vCalWeekHour	{border-top:solid 2px #ccc; border-left:solid 2px #ccc; border-bottom:0px; border-right:0px;}
}
</style>
<?php } ?>


<div class="vCalWeek">
	<!--HEADER DES JOURS : FIXE-->
	<div class="vCalWeekHeader">
		<div class="vCalWeekHeaderLine">
			<div class="vCalWeekHours">&nbsp;</div>
			<?php foreach($periodDays as $tmpDay){ ?>
				<div class="vCalWeekDayLabel <?= date("y-m-d",$tmpDay["timeBegin"])==date("y-m-d")?"vCalWeekDayLabelToday":null ?>">
					<?= Txt::trad("jour_".date("N",$tmpDay["timeBegin"]))." ".date("j/m",$tmpDay["timeBegin"]) ?>
					<?= !empty($tmpDay["celebrationDay"]) ? "<img src='app/img/calendar/celebrationDay.png' title=\"".$tmpDay["celebrationDay"]."\">" : null ?>
				</div>
			<?php } ?>
			<div class="vCalWeekHeaderScrollerMargin">&nbsp;</div>
		</div>
	</div>

	<!--PARTIR SCROLLABLE DE L'AGENDA : EVENEMENTS & GRILLE DES HEURES/MINUTES-->
	<div class="vCalWeekScroller" id="calWeekScroller<?= $tmpCal->_targetObjId ?>" data-timeSlotBegin="<?= $tmpCal->timeSlotBegin ?>" data-timeSlotDuration="<?= round($tmpCal->timeSlotEnd-$tmpCal->timeSlotBegin) ?>">

		<!--EVENEMENTS DE L'AGENDA (POSITION ABSOLUE), POUR CHAQUE JOUR-->
		<?php
		foreach($eventList as $tmpDateEvts){
			foreach($tmpDateEvts as $tmpEvt){
		?>
		<div class="vCalEvtBlock sBlock" data-dayCpt="<?= $tmpEvt->dayCpt ?>" data-minutesFromDayBegin="<?= $tmpEvt->minutesFromDayBegin ?>" data-durationMinutes="<?= $tmpEvt->durationMinutes ?>" data-catColor="<?= $tmpEvt->catColor ?>" data-colorDisplay="<?= $tmpCal->evtColorDisplay ?>" <?= $tmpEvt->blockIdForMenuContextDay ?> data-onclickJs="lightboxOpen('<?= $tmpEvt->getUrl("vue") ?>')">
			<?= $tmpEvt->menuContextDay ?>
			<span class="vCalEvtLabel">
				<?= Txt::displayDate($tmpEvt->dateBegin,"mini",$tmpEvt->dateEnd) ?>
				<span class="vCalEvtTitle"><?= $tmpEvt->title ?><?= (!empty($tmpEvt->important)) ? "<img src='app/img/important.png'>" : null ?></span>
			</span>
		</div>
		<?php }}  ?>
	
		<!--GRILLE DES HEURES/MINUTES-->
		<div class="vCalWeekTable">
			<?php for($H=0; $H<=23; $H++){
				$tmpHourClass=($H<$tmpCal->timeSlotBegin || $H>$tmpCal->timeSlotEnd || $H==12 || $H==13) ? "vCalWeekHourOutTimeslot" : null;//créneau hors du "Timeslot" de l'agenda?
			?>
			<!--LIGNE DES HEURES-->
			<div class="vCalWeekLine">
				<div class="vCalWeekHours"><?= $H ?>:00</div>
				<?php foreach($periodDays as $dayCpt=>$tmpDay){ ?>
				<!--AFFICHE CHAQUE HEURE DE CHAQUE JOUR-->
				<div class="vCalWeekHour" data-dayCpt="<?= $dayCpt ?>">
					<div class="vCalWeekHourQuarts">
						<!--DIVISE LES CELLULES EN QUARTS D'HEURES-->
						<?php for($quarter=0; $quarter<=3; $quarter++){
							$timeQuarterBegin=$tmpDay["timeBegin"]+(3600*$H)+(900*$quarter);//Time du début du quart d'heure
							$timeQuarterEnd=$timeQuarterBegin+900;							//Time de la fin "	"
							if(time()<$timeQuarterBegin)									{$quarterClass=$tmpHourClass;}				//quart d'heure futur (fonction du timeslot)
							elseif($timeQuarterBegin<=time() && time()<=$timeQuarterEnd)	{$quarterClass="vCalWeekHourQuartCurrent";}	//	" courant
							else															{$quarterClass="vCalWeekHourQuartOld";}		//	" passée
							$tmpNewDateTitle=$txtAddEvt." ".date("H:i",$timeQuarterBegin).$txtAddEvtPropose;
							$tmpNewDate=date("Ymd",$timeQuarterBegin);
						?>
							<div class="vCalWeekHourQuartLine">
								<div class="vCalWeekHourQuartCell <?= $quarterClass ?>" title="<?= $tmpNewDateTitle ?>" data-newTimeBegin="<?= $timeQuarterBegin ?>" data-selectedDate="<?= $tmpNewDate ?>">&nbsp;</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>