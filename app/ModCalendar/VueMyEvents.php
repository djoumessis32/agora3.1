<script type="text/javascript">
lightboxWidth(650);//Resize
</script>

<style>
.vEventLine		{display:table; width:100%; margin:5px;}
.vEventDate		{display:table-cell; width:200px;}
.vEventTitle	{display:table-cell;}
.vEventOptions	{display:table-cell; width:50px;}
</style>

<div class="fancyboxContent">
	<div class="lightboxObjTitle"><?= Txt::trad("CALENDAR_evt_proprio") ?></div>
	<hr class="hrGradient hrMargins">
	<!--LISTE DES EVT-->
	<?php foreach($myEvents as $tmpEvent){ ?>
	<div class="vEventLine sTableRow" title="<?= Txt::displayDate($tmpEvent->dateBegin,"full",$tmpEvent->dateEnd) ?><br><?= $tmpEvent->description ?>">
		<div class="vEventDate"><?= Txt::displayDate($tmpEvent->dateBegin,"normal",$tmpEvent->dateEnd) ?></div>
		<div class="vEventTitle"><?= $tmpEvent->title ?></div>
		<div class="vEventOptions">
			<img src="app/img/edit.png" class="sLink" onclick="lightboxOpen('<?= $tmpEvent->getUrl("edit") ?>')">
			<img src="app/img/delete.png" class="sLink" onclick="if(confirm('<?= Txt::trad("confirmDelete",true) ?>')) {lightboxClose(true,'<?= $tmpEvent->getUrl("delete") ?>');}">
		</div>
	</div>
	<?php } ?>
	<!--AUCUN EVT-->
	<?php if(empty($myEvents)){echo "<h3>".Txt::trad("CALENDAR_aucun_evt")."</h3>";} ?>
</div>