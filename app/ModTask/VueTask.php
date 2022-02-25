<script type="text/javascript">
lightboxWidth(600);//Resize
</script>

<style>
.vTaskDetails		{text-align:center;}
.percentBar			{margin:10px 15px 0px 0px;}/*Surcharge common.css*/
</style>

<div class="fancyboxContent">
	<div class="lightboxObjTitle">
		<?php if($curObj->editRight()){ ?><a href="javascript:lightboxOpen('<?= $curObj->getUrl("edit") ?>')" class="lightboxObjEditIcon" title="<?= Txt::trad("modifier") ?>"><img src="app/img/edit.png"></a><?php } ?>
		<?= $curObj->title ?>
	</div>
	<hr class="hrGradient hrMargins">
	<?= !empty($curObj->description) ? $curObj->description."<hr class='hrGradient hrMargins'>" : null ?>
	<div class="vTaskDetails">
		<?= $curObj->responsiblePersons(true).$curObj->budgetEngagedAvailable(true).$curObj->advancementHumanDayCharge(true).$curObj->dateBeginEnd(true) ?>
	</div>
</div>