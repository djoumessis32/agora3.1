<script type="text/javascript">
////	Resize
lightboxWidth(500);

////	Contrôle du formulaire
function formControl()
{
	if(!isMail($("[name='mail']").val())){
		displayNotif("<?= Txt::trad("mail_pas_valide") ?>");
		return false;
	}
}
</script>

<style>
form			{text-align:center;padding:0px;margin:0px;}
[name='mail']	{width:200px;}
</style>

<div class="lightboxTitle"><?= Txt::trad("PASS_OUBLIE_preciser_mail") ?></div>

<form action="index.php" method="post" OnSubmit="return formControl();">
	<input type="text" name="mail">
	<?= Txt::formValidate("envoyer",false) ?>
</form>