<script type="text/javascript">
////	INIT
$(function(){
	//Init le menu de sélection de période des plugins ("News elements")
	$("#dashboardSelectPeriod").val("<?= $pluginPeriod ?>");
	//Hauteur max des news
	newsMaxHeight=1500;
	$("[id^='newsDescription']").css("max-height",newsMaxHeight);
	//News masqué en partie par l'overflow ? affiche le bouton "displayAll" avec un Timeout pour que le browser ait le tps de calculer le "scrollHeight"..
	setTimeout(function(){
		$("[id^='newsDescription']").each(function(){
			if($("#"+this.id)[0].scrollHeight > newsMaxHeight){
				var _idNews=this.id.replace("newsDescription","");
				$("#newsButtonFull"+_idNews).css("display","block");
			}
		});
	},300);
});
////	DEROULER UNE LONGUE ACTUALITE
function newsDisplayFull(_idNews)
{
	$("#newsDescription"+_idNews).css("max-height","none").css("overflow","visible");
	$("#newsButtonFull"+_idNews).css("display","none");
}
</script>


<style>
#pageMenuPluginMenu			{text-align:center;}
#pageMenuPluginMenu label	{cursor:help;}
#dashboardSelectPeriod		{margin:10px 0px 5px 0px;}
#pluginOtherPeriod			{<?= $pluginPeriod!="otherPeriod"?"display:none;":null ?>}
.vPluginModuleIcon			{margin-top:10px;}
.vPluginModuleIcon img		{float:right; margin-top:-5px; max-height:24px;}
.vPluginModuleLine			{font-size:90%;}
.vPluginModuleLine .moduleMenuIcon	{width:15px; padding-left:0px; padding-right:0px;}
.vPluginModuleLine .moduleMenuIcon img	{max-width:15px;}
.vPluginModuleLine .moduleMenuTxt		{vertical-align:top;}
.vPluginModuleLine .moduleMenuTxt img	{vertical-align:middle;}
.vNewsMenuIcon				{float:right; opacity:0.5; width:35px;}
.newsBlock					{margin-bottom:10px; width:inherit;}/*conserver "inherit" pour que le "max-width" du contenu s'applique!*/
.newsUne					{box-shadow: 4px 4px 8px #fcc;}
.newsDatetime				{float:right; padding-top:5px; padding-right:5px; text-align:right; font-style:italic;}
[id^='newsDescription']		{font-weight:normal; padding:10px; min-height:40px; overflow:hidden;}/*conserver "inherit" pour que le "max-width" du contenu s'applique!*/
[id^='newsButtonFull']		{display:none; text-align:center;}
[id^='newsButtonFull'] button	{width:100%; height:25px; border-radius:0px;}
</style>

<div class="pageCenter">
	<div class="pageMenu">
		<div class="sBlock">
			<img src="app/img/dashboard/icon.png" class="vNewsMenuIcon">
			<?php if(MdlDashboardNews::addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlDashboardNews::getUrlNew() ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("DASHBOARD_ajout_actualite") ?></div></div><?php } ?>
			<?php if(!empty($offlineNewsCount)){ ?><div class="moduleMenuLine <?= Req::getParam("offlineNews")==1?"sLinkSelect":"sLink" ?>" onclick="redir('?ctrl=dashboard&offlineNews=<?= Req::getParam("offlineNews")==1?"0":"1" ?>')"><div class="moduleMenuIcon"><img src="app/img/dashboard/offline.png"></div><div class="moduleMenuTxt"><?= Txt::trad("DASHBOARD_actualites_offline")." ".$offlineNewsCount ?></div></div><?php } ?>
			<?= MdlDashboardNews::menuSort() ?>
		</div>
		<div class="sBlock moduleMenuBlock">
			<!--PLUGIN : SELECTION DE PERIODE-->
			<div id="pageMenuPluginMenu">
				<label>
					<span title="<?= $pluginPeriodTitle ?>"><img src="app/img/newObj.png"> <?= Txt::trad("DASHBOARD_new_elems") ?></span> &nbsp; 
					<span title="<?= $pluginPeriodTitleCurrent ?>"><img src="app/img/newObj2.png"> <?= Txt::trad("DASHBOARD_new_elems_realises") ?></span>
				</label>
				<select id="dashboardSelectPeriod" onChange="if(this.value=='otherPeriod'){$('#pluginOtherPeriod').fadeIn(200);}else{redir('?ctrl=dashboard&pluginPeriod='+this.value);}">
					<?php if(Ctrl::$curUser->isUser()){ ?><option value="connect" title="<?= $pluginConnectLabel ?>">..<?= Txt::trad("DASHBOARD_plugin_connexion") ?></option><?php } ?>
					<option value="day" title="<?= $pluginDayLabel ?>">..<?= Txt::trad("DASHBOARD_plugin_jour") ?></option>
					<option value="week" title="<?= $pluginWeekLabel ?>">..<?= Txt::trad("DASHBOARD_plugin_semaine") ?></option>
					<option value="month" title="<?= $pluginMonthLabel ?>">..<?= Txt::trad("DASHBOARD_plugin_mois") ?></option>
					<option value="otherPeriod"><?= Txt::trad("DASHBOARD_autre_periode") ?></option>
				</select>
				<form action="index.php" method="post" id="pluginOtherPeriod">
					<input type="text" name="pluginBegin" class="dateBegin" value="<?= date("d/m/Y",$pluginTimeBegin) ?>"> <img src="app/img/arrowRight.png">
					<input type="text" name="pluginEnd" class="dateEnd" value="<?= date("d/m/Y",$pluginTimeEnd) ?>">
					<input type="hidden" name="pluginPeriod" value="otherPeriod">
					<?= Txt::formValidate("OK",false) ?>
				</form>
			</div>
			<?php
			////	NOUVEAUX ELEMENTS DES MODULES (cf. plugins)
			if(empty($pluginsDashboard))	{echo "<div class='pluginEmpty'>".Txt::trad("DASHBOARD_pas_nouveaux_elements")."</div>";}
			else
			{
				//Affiche chaque nouveauté
				foreach($pluginsDashboard as $tmpPlugin)
				{
					//Entête du block du module?
					if(empty($tmpModuleName) || $tmpModuleName!=$tmpPlugin->pluginModule){
						echo "<div class='vPluginModuleIcon'><hr><img src='app/img/".$tmpPlugin->pluginModule."/icon.png'></div>";
						$tmpModuleName=$tmpPlugin->pluginModule;
					}
					//Plugin Spécifique (exple: evts à confirmer) OU  Plugin "Objet"
					if(isset($tmpPlugin->pluginBlockMenu))    {echo $tmpPlugin->pluginBlockMenu;}
					else
					{
						if(!empty($tmpPlugin->pluginIsCurrent)) {$pluginIcon="newObj2.png";}   elseif(!empty($tmpPlugin->pluginIsFolder)) {$pluginIcon="folder.png";}   else {$pluginIcon="newObj.png";}
						echo "<div class='moduleMenuLine vPluginModuleLine sLink'>
									<div class='moduleMenuIcon' onclick=\"".$tmpPlugin->pluginJsIcon."\"><img src='app/img/".$pluginIcon."' class='pluginIcon'></div>
									<div class='moduleMenuTxt' title=\"".$tmpPlugin->pluginTitle."\" onclick=\"".$tmpPlugin->pluginJsLabel."\">".Txt::reduce($tmpPlugin->pluginLabel,100)."</div>
							  </div>";
					}
				}
			}
			?>
		</div>
	</div>
	<div class="pageCenterContent">
		<!--LISTE DES NEWS-->
		<?php foreach($newsList as $tmpNews){ ?>
			<div class="newsBlock sBlock <?= $tmpNews->une==1?'newsUne':null ?>" <?= $tmpNews->blockIdForMenuContext() ?>>
				<?= $tmpNews->menuContext(); ?>
				<div class="newsDatetime sLegend"><?= $tmpNews->displayTime ?> <?= $tmpNews->offline==1?"<img src='app/img/dashboard/offline.png'>":"" ?></div>
				<div id="newsDescription<?= $tmpNews->_id?>"><?= $tmpNews->description ?></div>
				<?= $tmpNews->menuAttachedFiles() ?>
				<div id="newsButtonFull<?= $tmpNews->_id?>">
					<button  class="sLinkSelect" onclick="newsDisplayFull(<?= $tmpNews->_id?>)"><?= Txt::trad("tout_afficher") ?> <img src="app/img/developp.png"></button>
				</div>
			</div>
		<?php } ?>
		<!--PAS DE NEWS-->
		<?php if(empty($newsList)){ ?><div class="pageEmptyContent"><?= Txt::trad("DASHBOARD_pas_actualites") ?></div> <?php } ?>
	</div>
</div>