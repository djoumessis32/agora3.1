<script type="text/javascript">
////	Resize
lightboxWidth(650);
</script>

<style>
.vLogsRow		{display:table-row;}
.vLogsRow > div	{display:table-cell; padding:6px;}
.vLogAction		{width:130px;}
.vLogDate		{width:120px;}
.vLogUser		{width:120px;}
</style>

<div class="lightboxTitle"><?= Txt::trad("historique_element") ?></div>

<?php foreach($logsList as $tmpLog){ ?>
<div class="vLogsRow sTableRow">
	<div class="vLogAction"><img src="app/img/<?= preg_match("/(add|modif)/i",$tmpLog["action"])?"edit":"eye" ?>.png"> <?= Txt::trad("LOG_".$tmpLog["action"]) ?></div>
	<div class="vLogDate"><?= Txt::displayDate($tmpLog["date"]) ?></div>
	<div class="vLogUser"><?= Ctrl::getObj("user",$tmpLog["_idUser"])->display() ?></div>
	<div><?= $tmpLog["comment"] ?></div>
</div>
<?php } ?>