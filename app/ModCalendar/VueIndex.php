<script>
//Init
$(function(){
	//Init le Datepicker jquery-UI
	$("#datepickerCalendar").datepicker({
		firstDay:1,
		showOtherMonths: true,
		selectOtherMonths: true
	});
	//Dimensionne chaque agenda!
	calendarDimensions();
	$(window).resize(function(){ calendarDimensions(); });
});

////	Calendar dimensions
function calendarDimensions()
{
	//Les agendas prennent toute la hauteur dispo sur la page (au minimum)
	$(".vCalendarBlock").css("height",availableContentHeight());
	$(".vCalendarVue").each(function(){
		var calObjId=$(this).attr("data-targetObjId");
		var calContentHeight=$("#blockCal"+calObjId).innerHeight() - $("#headerCal"+calObjId).outerHeight(true);
		$(this).css("height",calContentHeight+"px");
	});
	//Présentation des blocks d'événement
	$(".vCalEvtBlock").each(function(){
		var catColor=$(this).attr("data-catColor");
		$(this).css("border", "1px solid "+catColor);
		if($(this).attr("data-colorDisplay")=="background")	{$(this).addClass("vCalEvtColorBlock").removeClass("vCalEvtColorBorder").css("background",catColor);}
		else												{$(this).addClass("vCalEvtColorBorder").removeClass("vCalEvtColorBlock").css("color",catColor);}
	});
	//GÈRE L'AFFICHAGE DE LA VUE WEEK/MONTH
	calendarDimensionsVue();
	//Synthese des agendas
	if($(".vSyntheseTable").exist())
	{
		//Fixe la taille des cellule de jours
		var syntheseDayWidth=Math.round(($(".vSyntheseLineHeader").innerWidth()-$(".vSyntheseLineHeader .vSyntheseLabel").innerWidth()) / $(".vSyntheseLineHeader .vSyntheseDay").length);
		$(".vSyntheseDay").css("width",syntheseDayWidth);
		//Colors les cellules des evt de chaque agenda
		$(".vSyntheseDayEvt[data-catColor]").each(function(){
			$(this).css("background", $(this).attr("data-catColor"));
		});
	}
}

////	Sélection des agendas
function visibleCalendarSelect(selectType)
{
	//Sélecteur
	var boxSelector="[name='displayedCalendars[]']";
	if(selectType=="ressource")	{var boxSelector=boxSelector+"[data-type='ressource']";}
	else if(selectType=="user")	{var boxSelector=boxSelector+"[data-type='user']";}
	//check/décheck?
	var checkUncheck=($(boxSelector).length!=$(":checked"+boxSelector).length) ? true : false;
	$(boxSelector).prop("checked",checkUncheck).trigger("change");
}
</script>


<style>
/*Menus de gauche de la page*/
.pageMenu .vDatepickerCalendarBlock		{padding:0px;}
.ui-datepicker				{width:97%; border:0px;}
#vMenuCategory				{width:150px;}
#vMenuCategory a			{display:block; padding:5px 0px 5px 0px;}
.vCategoryColorAll			{border:solid #000 2px; border-radius:20px;}
.pageMenu form				{padding:0px;}
.pageMenu .vVisibleCalendars{margin-bottom:5px; max-height:350px; overflow-y:auto;}
.vVisibleCalendarsLine		{display:table;}
.vVisibleCalendarsCell		{display:table-cell;}
.vVisibleCalendarsLine .vVisibleCalendarsCell:first-child	{width:40px;}
#vMenuVisibleCalendars div	{margin:5px;}
#calendarsDisplayMenu		{text-align:center;}
#calendarsDisplayMenu button{width:80px;}
/*Synthese de plusieurs agendas*/
.vSyntheseTable				{display:table; width:100%;}
.vSyntheseLineHeader,.vSyntheseLine,.vSyntheseLineFooter	{display:table-row;}
.vSyntheseDayCurDay			{color:#c00;}
.vSyntheseLabel				{display:table-cell; width:150px; padding:2px; padding-left:8px; line-height:12px; vertical-align:middle;}
.vSyntheseLine:hover .vSyntheseLabel	{color:#c00;}
.vSyntheseLineFooter .vSyntheseLabel	{font-style:italic;}
.vSyntheseDay				{display:table-cell; vertical-align:middle; text-align:center; height:22px;}
.vSyntheseDayEvts			{display:table; width:100%; height:100%;}
.vSyntheseDayEvt			{display:table-cell; border-left:transparent;}
.vSyntheseDayEvt:hover		{border-left:solid 1px #888;}
.vSyntheseLineFooter .vSyntheseDayEvt {cursor:help;}
.vSyntheseDayEvtTooltip		{text-align:left;}
.vSyntheseDayCal			{background:#ddd; border:dotted 1px #eee;}
.vSyntheseDayCal.vSyntheseDayCalWE	{background:#ccc;}
/*Affichage de chaque agenda*/
.vCalendarBlock				{margin-top:25px; padding:0px; min-height:300px;}
.vCalendarBlock:first-child	{margin-top:0px;}
/*Header des agendas*/
.vCalendarHeader			{display:table; width:100%; margin:0px;}
.vCalendarTitle,.vCalendarChangePeriod,.vCalendarDisplayMode	{display:table-cell; width:33%; padding:5px; vertical-align:middle;}
.vCalendarTitle				{text-transform:uppercase;}
.vCalendarTitle img			{vertical-align:top;}
.vCalendarChangePeriod		{text-align:center;}
.vCalendarDisplayMode		{text-align:right;}
img[src*='navPrevious'],img[src*='navNext']	{vertical-align:middle; margin:0px 10px 0px 10px;}
.vCalendarVue				{margin:0px; padding:0px;}
#menuContextSelectYears		{width:200px;}
.vSelectYearsMonth			{display:inline-block; width:63px;}
/*Affichage des evenements*/
.vCalEvtBlock						{padding:2px; box-shadow:1px 1px 1px #777; line-height:12px;}
.vCalEvtBlock img					{max-height:14px;}
.vCalEvtBlock .menuContextLauncher	{padding:0px 4px 2px 0px;}
.vCalEvtLabel						{cursor:pointer; font-size:85%;}
.vCalEvtTitle						{font-weight:normal;}
.vCalEvtColorBlock					{color:#fff;}
.vCalEvtColorBlock:hover			{color:#ddd;}
.vCalEvtColorBorder					{color:#000; background:#fff;}
.vCalEvtColorBorder:hover			{color:#333;}


/* IMPRESSION */
@media print {
	@page {size:landscape;}
	.vSynthese, .vCalendarDisplayMode	{display:none;}/*affiche pas la synthese des agendas, ni les menus de chaque agendas*/
	.vCalendarChangePeriod		{text-align:right;}
	.vCalendarBlock				{page-break-after:always; margin:0px; box-shadow:none;}/*saut de page, sauf pour le dernier de la liste*/
	.vCalendarBlock:last-child	{page-break-after:avoid;}
	.vCalendarHeader			{padding-bottom:15px; font-size:14px;}/*titre de l'agendaplus petit */
	.vCalEvtLabel				{color:#333;}
	.vCalEvtBlock				{box-shadow:none;}
}
</style>


<div class="pageFull">
	<div class="pageMenu">
		<!--LISTE DES AGENDAS A AFFICHER-->
		<?php if(!empty($visibleCalendars)){ ?>
		<div class="sBlock moduleMenuBlock">
			<form action="index.php" method="get">
				<div class="vVisibleCalendars">
					<?php foreach($visibleCalendars as $tmpCal){ ?>
					<div class="vVisibleCalendarsLine">
						<div class="vVisibleCalendarsCell">
							<?= $tmpCal->menuContext(["inlineLauncher"=>true]) ?>
							<input type="checkbox" name="displayedCalendars[]" value="<?= $tmpCal->_id ?>" id="displayedCal<?= $tmpCal->_targetObjId ?>" data-type="<?= $tmpCal->type ?>" <?= $tmpCal->isDisplayed()?"checked":null ?>>
						</div>
						<label class="vVisibleCalendarsCell" for="displayedCal<?= $tmpCal->_targetObjId ?>" title="<?= $tmpCal->description ?>"><?= $tmpCal->title ?></label>
					</div>
					<?php } ?>
				</div>
				<?php if(count($visibleCalendars)>1){ ?>
				<div id="calendarsDisplayMenu">
					<?= Txt::formValidate("afficher",false) ?>
					<img src="app/img/checkSwitch.png" for="vMenuVisibleCalendars" class="sLink menuContextLauncher">
					<div class="menuContext sBlock" id="vMenuVisibleCalendars">
						<div class="sLink" onclick="visibleCalendarSelect('all')"><?= Txt::trad("CALENDAR_cocher_tous_agendas") ?></div>
						<div class="sLink" onclick="visibleCalendarSelect('user')"><?= Txt::trad("CALENDAR_cocher_agendas_users") ?></div>
						<div class="sLink" onclick="visibleCalendarSelect('ressource')"><?= Txt::trad("CALENDAR_cocher_agendas_ressources") ?></div>
						<?php
						//Afficher / Masquer tous les agendas
						if(Ctrl::$curUser->isAdminGeneral()){
							if(Req::isParam("displayAllCals"))	{$displayAllCalsLabel=Txt::trad("CALENDAR_masquer_tous_agendas");	$displayAllCalsUrl="?ctrl=".Req::$curCtrl;}
							else								{$displayAllCalsLabel=Txt::trad("CALENDAR_afficher_tous_agendas");	$displayAllCalsUrl="?ctrl=".Req::$curCtrl."&displayAllCals=1";}
						?>
						<hr><div class="sLink" onclick="redir('<?= $displayAllCalsUrl ?>')"><?= $displayAllCalsLabel ?></div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</form>
		</div>
		<?php } ?>
		<!--OPTIONS DES AGENDAS-->
		<div class="sBlock vMenuOptions">
			<!--CATEGORIES D'EVT : FILTER-->
			<div class="moduleMenuLine sLink" >
				<div class="moduleMenuIcon"><img src="app/img/category.png"></div>
				<div class="moduleMenuTxt">
					<div class="menuContext sBlock" id="vMenuCategory">
						<?php foreach(MdlCalendarEventCategory::getCategories() as $tmpCategory){ ?>
							<a href="?ctrl=calendar&_idCatFilter=<?= $tmpCategory->_id ?>" class="<?= Req::getParam("_idCatFilter")==$tmpCategory->_id?'sLinkSelect':'sLink' ?>" title="<?= $tmpCategory->description ?>"><?= $tmpCategory->display() ?></a>
						<?php } ?>
						<a href="?ctrl=calendar" class="<?= Req::isParam("_idCatFilter")==false?'sLinkSelect':'sLink' ?>"><div class="categoryColor vCategoryColorAll">&nbsp;</div><?= Txt::trad("tout_afficher") ?></a>
					</div>
					<span class="sLink menuContextLauncher" for="vMenuCategory"><?= Txt::trad("CALENDAR_categorie")." : ".(Req::isParam("_idCatFilter") ? Ctrl::getObj("calendarEventCategory",Req::getParam("_idCatFilter"))->display() : Txt::trad("tout_afficher")) ?></span>
				</div>
			</div>
			<!--CATEGORIES D'EVT : EDITER-->
			<?php if(MdlCalendarEventCategory::addRight()){ ?>
			<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=calendar&action=CalendarEventCategoryEdit');">
				<div class="moduleMenuIcon"><img src="app/img/categoryEdit.png"></div>
				<div class="moduleMenuTxt"><?= Txt::trad("CALENDAR_gerer_categories") ?></div>
			</div>
			<?php } ?>
			<!--AJOUTER AGENDA PARTAGE-->
			<?php if(MdlCalendar::addRight()){ ?>
			<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlCalendar::getUrlNew() ?>');" title="<?= Txt::trad("CALENDAR_ajouter_agenda_ressource_bis") ?>">
				<div class="moduleMenuIcon"><img src="app/img/calendar/ressourceAdd.png"></div>
				<div class="moduleMenuTxt"><?= Txt::trad("CALENDAR_ajouter_agenda_ressource") ?></div>
			</div>
			<?php } ?>
			<!--EVT PROPRIO-->
			<?php if(Ctrl::$curUser->isUser()){ ?>
			<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=calendar&action=MyEvents')">
				<div class="moduleMenuIcon"><img src="app/img/calendar/myEvents.png"></div>
				<div class="moduleMenuTxt"><?= Txt::trad("CALENDAR_evt_proprio") ?></div>
			</div>
			<?php } ?>
			<!--IMPRIMER LA PAGE-->
			<div class="moduleMenuLine sLink" onclick="print();" title="<?= Txt::trad("CALENDAR_imprimer_agendas_infos") ?>">
				<div class="moduleMenuIcon"><img src="app/img/print.png"></div>
				<div class="moduleMenuTxt"><?= Txt::trad("CALENDAR_imprimer_agendas") ?></div>
			</div>
		</div>
		<!--PROPOSITIONS D'EVENEMENT-->
		<?php if(!empty($menuProposedEvents)){ ?>
		<div class="sBlock moduleMenuBlock">
			<?= $menuProposedEvents ?>
		</div>
		<?php } ?>
		<!--CALENDRIER-->
		<?php if($displayMode!="month"){ ?>
		<div class="sBlock vDatepickerCalendarBlock">
			<div id="datepickerCalendar"></div>
		</div>
		<?php } ?>
	</div>
	

	<div class="pageFullContent">
		<!--SYNTHESE DES AGENDAS ?-->
		<?php if(!empty($periodDaysSynthese)){ ?>
			<div class="vSynthese sBlock objDMain">
				<div class="vSyntheseTable">
					<!--HEADER DE LA SYNTHESE-->
					<div class="vSyntheseLineHeader">
						<div class="vSyntheseLabel">&nbsp;</div>
						<?php foreach($periodDaysSynthese as $tmpDay){ ?>
						<div class="vSyntheseDay <?= date("Y-m-d",$tmpDay["timeBegin"])==date("Y-m-d")?"vSyntheseDayCurDay":null ?>"><?= (int)date("d",$tmpDay["timeBegin"]) ?></div>
						<?php } ?>
					</div>
					<!--AFFICHE CHAQUE AGENDA-->
					<?php foreach($displayedCalendars as $tmpCal){ ?>
					<div class="vSyntheseLine">
						<div class="vSyntheseLabel sLink" onclick="toScroll('#blockCal<?= $tmpCal->_targetObjId ?>')"><?= $tmpCal->title ?></div>
						<?php foreach($periodDaysSynthese as $tmpDay){ ?>
						<div class="vSyntheseDay vSyntheseDayCal <?= date("N",$tmpDay["timeBegin"])>5?"vSyntheseDayCalWE":null ?>">
							<div class="vSyntheseDayEvts">
								<!--AFFICHE CHAQUE EVT DE L'AGENDA POUR LE JOUR COURANT-->
								<?php foreach($tmpDay["calsEvts"][$tmpCal->_id] as $tmpEvt){ ?>
								<div class="vSyntheseDayEvt sLink" onclick="lightboxOpen('<?= $tmpEvt->getUrl("vue") ?>')" data-catColor="<?= $tmpEvt->catColor ?>" title="<div class='vSyntheseDayEvtTooltip'><?= $tmpCal->title." : ".Txt::displayDate($tmpEvt->dateBegin,"full",$tmpEvt->dateEnd)."<br>".Txt::reduce($tmpEvt->title,40)."</div>" ?>">&nbsp;</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					<!--LIGNE DE SYNTHESE DES AGENDAS-->
					<div class="vSyntheseLineFooter">
						<div class="vSyntheseLabel"><?= Txt::trad("CALENDAR_synthese") ?></div>
						<?php foreach($periodDaysSynthese as $tmpDay){ ?>
						<div class="vSyntheseDay vSyntheseDayCal <?= date("N",$tmpDay["timeBegin"])>5?"vSyntheseDayCalWE":null ?>">
							<div class="vSyntheseDayEvts">
								<?php if(!empty($tmpDay["nbCalsOccuppied"])){ ?><div class="vSyntheseDayEvt" data-catColor="#777" title="<div class='vSyntheseDayEvtTooltip'><?= "".$tmpDay["nbCalsOccuppied"]."</div>" ?>">&nbsp;</div><?php } ?>	
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<!--AFFICHE CHAQUE AGENDA-->
		<?php foreach($displayedCalendars as $tmpCal){ ?>
		<div class="vCalendarBlock sBlock objDMain" id="blockCal<?= $tmpCal->_targetObjId ?>">
			<!--HEADER DU CALENDRIER-->
			<div class="vCalendarHeader" id="headerCal<?= $tmpCal->_targetObjId ?>">
				<div class="vCalendarTitle" title="<?= $tmpCal->description ?>"><?= $tmpCal->menuContext(["inlineLauncher"=>true]); ?>&nbsp;<?= $tmpCal->title ?></div>
				<div class="vCalendarChangePeriod">
					<img src="app/img/navPrevious.png" class="sLink noPrint" onclick="redir('?ctrl=calendar&curTime=<?= $timePrev ?>')" title="<?= Txt::trad("CALENDAR_periode_precedante") ?>">
					<span for="menuContextSelectYears" class="menuContextLauncher"><?= $labelPeriod ?></span>
					<?php if(!empty($labelPeriodMenu)){ ?><div class="menuContext sBlock" id="menuContextSelectYears"><?= $labelPeriodMenu ?></div><?php } ?>
					<img src="app/img/navNext.png" class="sLink noPrint" onclick="redir('?ctrl=calendar&curTime=<?= $timeNext ?>')" title="<?= Txt::trad("CALENDAR_periode_suivante") ?>">
				</div>
				<div class="vCalendarDisplayMode">
					<img src="app/img/calendar/displayToday.gif" class="sLink" onclick="redir('?ctrl=calendar&curTime=<?= time() ?>')" title="<?= Txt::trad("aff_aujourdhui") ?>">
					<img src="app/img/calendar/displayDay.gif" class="sLink" onclick="redir('?ctrl=calendar&displayMode=day')" title="<?= Txt::trad("CALENDAR_evt_jour") ?>">
					<img src="app/img/calendar/displayWorkWeek.gif" class="sLink" onclick="redir('?ctrl=calendar&displayMode=workWeek')" title="<?= Txt::trad("CALENDAR_evt_semaine_w") ?>">
					<img src="app/img/calendar/displayWeek.gif" class="sLink" onclick="redir('?ctrl=calendar&displayMode=week')" title="<?= Txt::trad("CALENDAR_evt_semaine") ?>">
					<img src="app/img/calendar/displayMonth.gif" class="sLink" onclick="redir('?ctrl=calendar&displayMode=month')" title="<?= Txt::trad("CALENDAR_evt_mois") ?>">
				</div>
			</div>
			<!--CONTENU DU CALENDRIER ("VueCalendarMonth"/"VueCalendarWeek")-->
			<div class="vCalendarVue" data-targetObjId="<?= $tmpCal->_targetObjId ?>"><?= $tmpCal->calendarVue ?></div>
		</div>
		<?php } ?>
		<!--AUCUN AGENDA-->
		<?php if(empty($displayedCalendars)){ ?><div class="pageEmptyContent"><?= Txt::trad("CALENDAR_aucun_agenda_visible") ?></div><?php } ?>
	</div>
</div>