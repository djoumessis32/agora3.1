<script type="text/javascript">
lightboxWidth(650);//Resize

////	Init la page
$(function(){
	////	Masque les heures si une date n'est pas sélectionnée
	if($(".dateBegin").isEmpty())	{$(".timeBegin").hide();}
	if($(".dateEnd").isEmpty())		{$(".timeEnd").hide();}
	////	Donne une valeur aux inputs "select"
	$("[name='advancement']").val("<?= $curObj->advancement ?>");
	$("[name='priority']").val("<?= $curObj->priority ?>");
	////	Change de priorité : modif l'icone
	$("[name='priority']").change(function(){
		var imgPriority="app/img/task/priority"+$(this).val()+".png";
		$("img[src*='priority']").attr("src",imgPriority);
	});
	////	Affiche le block des responsables s'il y en a de sélectionnés
	if($(":checked[name='responsiblePersons[]']").length>0)	{$("#divResponsiblePersons").show();}
});
</script>

<style>
hr						{margin:3px;}
[name='title']			{width:80%; margin-right:10px;}
#blockDescription		{margin-top:20px; <?= empty($curObj->description)?"display:none;":null ?>}
[name='description']	{width:100%; height:70px; <?= empty($curObj->description)?"display:none;":null ?>}
.taskOption				{display:inline-block; margin:10px 5px 10px 5px;}
img[src*='arrowRight']	{margin-left:5px; margin-right:5px;}
[name='budgetAvailable']{width:110px;}
[name='budgetEngaged']	{width:100px;}
[name='humanDayCharge']	{width:130px;}
img[src*='user']		{height:20px;}
#divResponsiblePersons	{display:none; margin-top:10px; max-height:200px; overflow:auto;}
.divResponsible			{display:inline-block; float:left; width:33%; text-align:left;}
.divResponsibleTable	{display:table; width:100%;}
.divResponsibleCell		{display:table-cell; vertical-align:middle;}
.divResponsibleCell:first-child	{width:20px;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">
	<!--TITRE & DESCRIPTION (EDITOR)-->
	<input type="text" name="title" value="<?= $curObj->title ?>" placeholder="<?= Txt::trad("title") ?>">
	<img src="app/img/description.png" class="sLink" title="<?= Txt::trad("description") ?>" onclick="$('#blockDescription').slideToggle(200)">
	<div id="blockDescription">
		<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
	</div>

	<!--OPTIONS-->
	<fieldset class="fieldsetCenter fieldsetMarginTop sBlock">
		<!--PRIORITE-->
		<div class="taskOption">
			<?= Txt::trad("TASK_priority") ?>
			<select name="priority">
				<option value=""></option>
				<?php for($i=1;$i<=4;$i++)  {echo "<option value='".$i."'>".Txt::trad("TASK_priority".$i)."</option>";} ?>
			</select>
			<img src="app/img/task/priority<?= $curObj->priority ?>.png">
		</div>
		<!--DATE DEBUT & FIN-->
		<div class="taskOption">
			<input type="text" name="dateBegin" class="dateBegin" value="<?= Txt::formatDate($curObj->dateBegin,"dbDatetime","inputDate") ?>" placeholder="<?= Txt::trad("debut") ?>" title="<?= Txt::trad("debut") ?>">
			<input type="text" name="timeBegin" class="timeBegin" value="<?= Txt::formatDate($curObj->dateBegin,"dbDatetime","inputHM",true) ?>" placeholder="H:m">
			<img src="app/img/arrowRight.png">
			<input type="text" name="dateEnd" class="dateEnd" value="<?= Txt::formatDate($curObj->dateEnd,"dbDatetime","inputDate") ?>" placeholder="<?= Txt::trad("fin") ?>" title="<?= Txt::trad("fin") ?>">
			<input type="text" name="timeEnd" class="timeEnd" value="<?= Txt::formatDate($curObj->dateEnd,"dbDatetime","inputHM",true) ?>" placeholder="H:m">
		</div>
		<!--AVANCEMENT-->
		<div class="taskOption">
			<?= Txt::trad("TASK_advancement") ?>
			<select name="advancement">
				<option value=""></option>
				<?php for($i=0;$i<=100;$i+=10)  {echo "<option value='".$i."'>".$i." %</option>";} ?>
			</select>
		</div>
		<hr class="hrGradient">
		<!--BUDGET ENGAGE-->
		<div class="taskOption">
			<img src="app/img/task/budgetAvailable.png">
			<input type="text" name="budgetAvailable" value="<?= $curObj->budgetAvailable ?>" class="integerValue" placeholder="<?= txt::trad("TASK_budgetAvailable") ?>" title="<?= txt::trad("TASK_budgetAvailable") ?>">
		</div>
		<!--BUDGET GLOBAL-->
		<div class="taskOption">
			<img src="app/img/task/budgetEngaged.png">
			<input type="text" name="budgetEngaged" value="<?= $curObj->budgetEngaged ?>" class="integerValue" placeholder="<?= txt::trad("TASK_budgetEngaged") ?>" title="<?= txt::trad("TASK_budgetEngaged") ?>">
		</div>
		<!--CHARGE JOUR/HOMME-->
		<div class="taskOption">
			<img src="app/img/task/humanDayCharge.png">
			<input type="text" name="humanDayCharge" value="<?= $curObj->humanDayCharge ?>" class="integerValue" placeholder="<?= txt::trad("TASK_humanDayCharge") ?>" title="<?= txt::trad("TASK_humanDayCharge_info") ?>">
		</div>
		<!--RESPONSABLES-->
		<div class="taskOption labelMargin sLink" onclick="$('#divResponsiblePersons').slideToggle(200);">
			<img src="app/img/user/icon.png"> <?= txt::trad("TASK_responsiblePersons") ?> <img src="app/img/developp.png">
		</div>
		<div id="divResponsiblePersons">
			<?php foreach(Ctrl::$curSpace->getUsers() as $tmpUser){ ?>
			<div class="divResponsible">
				<div class="divResponsibleTable">
					<div class="divResponsibleCell"><input type="checkbox" name="responsiblePersons[]" value="<?= $tmpUser->_id ?>" id="responsiblePerson<?= $tmpUser->_id ?>"  <?= in_array($tmpUser->_id,Txt::txt2tab($curObj->responsiblePersons))?"checked":null ?> ></div>
					<div class="divResponsibleCell"><label for="responsiblePerson<?= $tmpUser->_id ?>"><?= $tmpUser->display() ?></label></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</fieldset>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>