<script type="text/javascript">
lightboxWidth(650);//Resize
</script>

<style>
li					{margin-bottom:10px;}
[id^='mailDetails']	{display:none;}
.vMailDescription	{margin-top:10px; padding:5px; border:dotted #999 1px;}
.vRecipients		{font-weight:normal; font-size:95%;}
</style>

<div class="fancyboxContent">
	<div class="lightboxObjTitle"><?= Txt::trad("MAIL_historique_mail") ?></div>
	<hr class="hrGradient hrMargins">
	<ul>
	<?php
	//LISTE DES MAILS
	foreach($mailList as $tmpMail){
		$deleteUrl="?ctrl=".Req::$curCtrl."&action=".Req::$curAction."&actionDelete=true&_idMail=".$tmpMail["_id"];
		$recipents=null;
	?>
		<li>
			<label onclick="$('#mailDetails<?= $tmpMail["_id"] ?>').slideToggle()"><?= $tmpMail["title"] ?></label>
			<img src="app/img/delete.png" onclick="confirmRedir('<?= Txt::trad("confirmDelete",true) ?>','<?= $deleteUrl ?>');" class="sLink">
			<div id="mailDetails<?= $tmpMail["_id"] ?>">
				<?= Txt::trad("MAIL_envoye_par")." ".Ctrl::getObj("user",$tmpMail["_idUser"])->display()." - ".Txt::displayDate($tmpMail["dateCrea"]) ?><br>
				<?= Txt::trad("MAIL_recipients") ?> :
				<span class="vRecipients"><?= $tmpMail["recipients"] ?></span>
				<div class="vMailDescription"><?= $tmpMail["description"] ?></div>
			</div>
		</li>
	<?php } ?>
	</ul>
	<!--AUCUN MAIL-->
	<?php if(empty($mailList)) {echo "<i>".Txt::trad("MAIL_aucun_mail")."</i>";} ?>
</div>