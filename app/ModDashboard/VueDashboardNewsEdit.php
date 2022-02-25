<script type="text/javascript">
////	Resize
lightboxWidth(700);

////	Archive la news si ya une date de mise en ligne
$(function(){
	$(".dateBegin, .dateEnd").change(function(){
		if($(".dateBegin").isEmpty()==false && $("[name='offline']").prop("checked")==false){
			displayNotif("<?= Txt::trad("DASHBOARD_dateOnline_alerte") ?>");
			$("[name='offline']").trigger("click");
		}
	});
});
</script>

<style>
.dateBegin,.dateEnd				{width:150px;}
[name='une'],[name='offline']	{display:none;}
[for='uneCheckbox'],[name='dateOnline'],[name='dateOffline']	{margin-right:15px;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">
	<!--DESCRIPTION (EDITOR)-->
	<textarea name="description"><?= $objNews->description ?></textarea>
	<!--OPTIONS-->
	<fieldset class="fieldsetCenter fieldsetMarginTop sBlock">
		<!--A LA UNE-->
		<input type="checkbox" name="une" value="1" id="uneCheckbox" <?= $objNews->une==1?"checked":"" ?>>
		<img src="app/img/dashboard/une.png"> <label for="uneCheckbox" class="abbr" title="<?= Txt::trad("DASHBOARD_ala_une_info") ?>"><?= Txt::trad("DASHBOARD_ala_une") ?></label>
		<!--DATE ONLINE-->
		<img src="app/img/dashboard/online.png"> <input type="text" name="dateOnline" class="dateBegin" value="<?= Txt::formatDate($objNews->dateOnline,"dbDate","inputDate") ?>" placeholder="<?= Txt::trad("DASHBOARD_dateOnline") ?>" title="<?= Txt::trad("DASHBOARD_dateOnline_info") ?>">
		<!--DATE OFFLINE-->
		<img src="app/img/dashboard/offline.png"> <input type="text" name="dateOffline" class="dateEnd" value="<?= Txt::formatDate($objNews->dateOffline,"dbDate","inputDate") ?>" placeholder="<?= Txt::trad("DASHBOARD_dateOffline") ?>" title="<?= Txt::trad("DASHBOARD_dateOffline_info") ?>">
		<!--OFFLINE-->
		<input type="checkbox" name="offline" value="1" id="offlineCheckbox" <?= $objNews->offline==1?"checked":"" ?>>
		<label for="offlineCheckbox" class="abbr" title="<?= Txt::trad("DASHBOARD_offline_info") ?>"><?= Txt::trad("DASHBOARD_offline") ?></label> <img src="app/img/dashboard/offline.png"> 
	</fieldset>
	<!--MENU COMMUN-->
	<?= $objNews->menuEditValidate() ?>
</form>