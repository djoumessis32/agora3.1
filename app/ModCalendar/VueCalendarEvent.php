<script type="text/javascript">
lightboxWidth(600);//Resize
</script>

<style>
.lightboxObjTitle	{line-height:20px;}
.vEventDescription	{margin-top:10px; font-weight:normal;}
.vEventDetails		{line-height:20px;}
.percentBar			{margin:10px 15px 0px 0px;}/*Surcharge common.css*/
</style>

<div class="fancyboxContent">

	<!--ICONE EDIT / DATE / PERIODICITE-->
	<div class="lightboxObjTitle">
		<?php if($curObj->editRight()){ ?><a href="javascript:lightboxOpen('<?= $curObj->getUrl("edit") ?>')" class="lightboxObjEditIcon" title="<?= Txt::trad("modifier") ?>"><img src="app/img/edit.png"></a><?php } ?>
		<?= Txt::displayDate($curObj->dateBegin,"full",$curObj->dateEnd) ?>
		<?= !empty($labelPeriod) ? "<br>".$labelPeriod : null ?>
	</div>
	
	<!--TITRE / DESCRIPTION / CATEGORIE-->
	<hr class="hrGradient hrMargins">
	<div class="vEventTitle">
		<?= !empty($labelCategory) ? $labelCategory." : " : null ?>
		<?= $curObj->title ?>
		<?= !empty($curObj->important) ? "<img src='app/img/important.png' title=\"".Txt::trad("important")."\"" : null?>
	</div>
	<div class="vEventDescription"><?= $curObj->description ?></div>
	
	<!--VISIBILITE-->
	<?= !empty($contentVisible) ? "<hr class='hrGradient hrMargins'>".$contentVisible : null ?>

	<!--AFFECTATIONS AUX AGENDAS-->
	<hr class="hrGradient hrMargins">
	<?= $curObj->affectedCalendarsLabel() ?>
</div>