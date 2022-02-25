<script type="text/javascript">
function captchaControl()
{
	if($(".vCaptchaText").isEmpty())  {displayNotif("<?=Txt::trad("captcha_alert_specifier") ?>");  return false;}
	var ajaxUrl="?ctrl=misc&action=CaptchaControl&captcha="+encodeURIComponent($(".vCaptchaText").val().toUpperCase());
	var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Retour Ajax obligatoire pour passer à la suite : async:false
	if(ajaxResult!="true")	{displayNotif("<?=Txt::trad("captcha_alert_erronee") ?>");  return false;}
	else					{return true;}
}
</script>

<style>
.vCaptchaReload		{cursor:pointer; width:15px; margin-right:20px;}
.vCaptchaArrow		{margin-left:10px; margin-right:10px;}
.vCaptchaText		{text-transform:uppercase; width:50px;}
</style>

<span title="<?= Txt::trad("captcha_info") ?>">
	<?= Txt::trad("captcha") ?>
	<img src="app/img/reload.png" class="vCaptchaReload" title="reload !" onclick="$('.vCaptchaImg').attr('src','?ctrl=misc&action=CaptchaImg&rand='+Math.random())">
	<img src="?ctrl=misc&action=CaptchaImg" class="vCaptchaImg">
	<img src="app/img/arrowRight.png" class="vCaptchaArrow">
	<input type="text" name="captcha" class="vCaptchaText">
</span>