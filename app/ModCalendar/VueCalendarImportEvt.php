<script type="text/javascript">
lightboxWidth("800px");//Resize

////	Init la page
$(function(){
	//Switch de la sélection
	$("img[src*='switch.png']").on("click",function(){
		$(":checkbox[id^='boxEvent']").each(function(){
			$(this).prop("checked",!$(this).prop("checked")).trigger("change");
		});
	});
});

////	Contrôle du formulaire
function formControl()
{
	//Fichier Import au format csv
	if($("input[name='importFile']").exist()){
		if($("input[name='importFile']").isEmpty())						{displayNotif("<?= Txt::trad("specifier_fichier") ?>");	return false;}
		else if(extension($("input[name='importFile']").val())!="ics")	{displayNotif("<?= Txt::trad("extension_fichier") ?> ICS");	return false;}
	}
}
</script>

<style>
.fancyboxContent	{padding:0px; margin:0px;}
form				{text-align:center;}
td					{text-align:left; vertical-align:top; padding:5px;}
.vTableHeader td	{background:#ddd; text-align:center;}
td:nth-child(1)		{width:30px;}
td:nth-child(2)		{width:100px;}
td:nth-child(3)		{width:150px;}
.vTableEvt:hover	{background:#eee;}
td img				{vertical-align:middle;}
.eventDescription	{font-weight:normal;}
</style>

<div class="fancyboxContent">
	<div class="lightboxTitle"><?= Txt::trad("CALENDAR_importer_ical") ?></div>

	<form action="index.php" method="post" enctype="multipart/form-data" onsubmit="return formControl()">
		<!--SELECTION DU FICHIER D'IMPORT-->
		<?php if(empty($eventList)){ ?><input type="file" name="importFile"><?php } ?>

		<!--EVENEMENTS A IMPORTER-->
		<?php if(!empty($eventList)){ ?>
			<table>
				<!--HEADER-->
				<tr class="vTableHeader">
					<td title="<?= Txt::trad("inverser_selection") ?>"><img src="app/img/switch.png" class="sLink"></td>
					<td><?= Txt::trad("CALENDAR_importer_ical_etat") ?></td>
					<td><?= Txt::trad("debut")." - ".Txt::trad("fin") ?></td>
					<td><?= Txt::trad("title") ?></td>
					<td><?= Txt::trad("description") ?></td>
				</tr>
				<!--LISTE D'EVENEMENTS-->
				<?php foreach($eventList as $cptEvt=>$tmpEvt){ ?>
					<tr class="vTableEvt">
						<td><input type="checkbox" name="eventList[<?= $cptEvt ?>][checked]" value="1" id="boxEvent<?= $cptEvt ?>"></td>
						<td><?= ($tmpEvt["isPresent"]==true)  ?  "<img src='app/img/dotR.png'> ".Txt::trad("CALENDAR_importer_ical_deja_present")  :  "<img src='app/img/dotG.png'> ".Txt::trad("CALENDAR_importer_ical_a_importer") ?></td>
						<td><?= Txt::displayDate($tmpEvt["dbDateBegin"],"normal",$tmpEvt["dbDateEnd"]) ?><input type="hidden" name="eventList[<?= $cptEvt ?>][dateBegin]" value="<?= $tmpEvt["dbDateBegin"] ?>"><input type="hidden" name="eventList[<?= $cptEvt ?>][dateEnd]" value="<?= $tmpEvt["dbDateEnd"] ?>"></td>
						<td><label for="boxEvent<?= $cptEvt ?>"><?= $tmpEvt["SUMMARY"] ?></label> <input type="hidden" name="eventList[<?= $cptEvt ?>][title]" value="<?= $tmpEvt["SUMMARY"] ?>"></td>
						<td class="eventDescription"><label for="boxEvent<?= $cptEvt ?>"><?= $tmpEvt["DESCRIPTION"] ?></label> <input type="hidden" name="eventList[<?= $cptEvt ?>][description]" value="<?= $tmpEvt["DESCRIPTION"] ?>"></td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>

		<!--VALIDATION DU FORM-->
		<?= Txt::formValidate() ?>
	</form>
</div>