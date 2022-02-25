<link rel="stylesheet" type="text/css" href="app/js/datatables/css/jquery.dataTables.min.css">
<script type="text/javascript" src="app/js/datatables/jquery.dataTables.min.js"></script>

<script>
////	PARAMETRAGE DE DataTables
$(function(){
	//Construction du tableau de donnees
	oTable=$("#tableLogs").dataTable({
        "iDisplayLength": 50,		//nb de lignes par page par défaut
        "aLengthMenu": [50,200,1000],//menu d'affichage du nb de lignes par page
        "aaSorting": [[0,"desc"]],	//indique sur quelle colonne se fait le tri par défaut
        "oLanguage":{				//Traduction diverses dans le menu
            "sLengthMenu": "_MENU_ logs",											//Menu select du nb de lignes par page
            "sZeroRecords": "<?= Txt::trad("LOG_no_logs") ?>",						//"aucun logs"
            "sInfo": "total : _TOTAL_ logs",										//Nb total de logs
            "sInfoEmpty": "<?= Txt::trad("LOG_no_logs") ?>",						//"aucun logs"
            "sInfoFiltered": "(<?= Txt::trad("LOG_filtre_a_partir") ?> _MAX_ logs)",// Ajouté si on filtre les infos dans une table (pour donner une idée de la force du filtrage)
            "sSearch":"<img src='app/img/search.png'>",															//champs "search"
			"oPaginate":{
				"sPrevious": "<img src='app/img/navPrevious.png'>",
				"sNext": "<img src='app/img/navNext.png'>"
			}
        }
    });
	//Ajoute le placeholder du champs "search"
	$(".dataTables_filter input").attr("placeholder","<?= Txt::trad("LOG_chercher") ?>");
	//Filtre sur le input text et "select" du footer
	$("tfoot input, tfoot select").on("keyup change",function(){
		oTable.fnFilter($(this).val(), this.parentNode.cellIndex);
	});
});
</script>

<style>
.pageCenterContent	{padding:10px;}
thead th			{text-align:left;}
#tableLogs			{font-size:95%;}
#tableLogs td		{text-align:left; padding:3px;}
#tableLogs th		{text-align:left; padding:8px; padding-left:3px;}
#logsDownload		{padding:5px; text-align:center;}
tfoot select, tfoot input	{width:100px;}
[name=search_ip]			{width:60px;}
[name=search_comment]		{width:300px;}
.dataTables_filter input	{width:100px;}/*champ "recherche"*/
.dataTables_filter img		{max-height:18px;}/*champ "recherche"*/
</style>

<div class="pageCenter">
	<div class="pageCenterContent sBlock">
		<!--TABLEAU DES LOGS-->
		<table id="tableLogs" class="display">
			<thead>
				<tr>
					<th><?= Txt::trad("LOG_date_heure") ?></th>
					<th><?= Txt::trad("LOG_utilisateur") ?></th>
					<th><?= Txt::trad("LOG_ipAdress") ?></th>
					<th><?= Txt::trad("LOG_espace") ?></th>
					<th><?= Txt::trad("LOG_module") ?></th>
					<th><?= Txt::trad("LOG_action") ?></th>
					<th><?= Txt::trad("LOG_objectType") ?></th>
					<th><?= Txt::trad("LOG_comment") ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				////	AFFICHAGE DES LOGS
				foreach($logList as $logTmp)
				{
					echo "<tr>
							<td>".substr($logTmp["date"],0,16)."</td>
							<td>".$logTmp["userName"]."</td>
							<td>".$logTmp["ip"]."</td>
							<td>".$logTmp["spaceName"]."</td>
							<td>".$logTmp["moduleName"]."</td>
							<td>".$logTmp["action"]."</td>
							<td>".$logTmp["objectType"]."</td>
							<td>".$logTmp["comment"]."</td>
						  </tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="search_date" placeholder="<?= Txt::trad("LOG_filtre")." ".Txt::trad("LOG_date_heure") ?>" class="searchInit"></th>
					<th><input type="text" name="search_user" placeholder="<?= Txt::trad("LOG_filtre")." ".Txt::trad("LOG_utilisateur") ?>" class="searchInit"></th>
					<th><input type="text" name="search_ip" placeholder="<?= Txt::trad("LOG_filtre")." ".Txt::trad("LOG_ipAdress") ?>" class="searchInit"></th>
					<th><?= CtrlLog::fieldFilterSelect(Txt::trad("LOG_espace"),"S.name") ?></th>
					<th><?= CtrlLog::fieldFilterSelect(Txt::trad("LOG_module"),"moduleName") ?></th>
					<th><?= CtrlLog::fieldFilterSelect(Txt::trad("LOG_action"),"action") ?></th>
					<th><input type="text" name="search_objectType" placeholder="<?= Txt::trad("LOG_filtre")." ".Txt::trad("LOG_objectType") ?>" class="searchInit"></th>
					<th><input type="text" name="search_comment" placeholder="<?= Txt::trad("LOG_filtre")." ".Txt::trad("LOG_comment") ?>" class="searchInit"></th>
				</tr>
			</tfoot>
		</table>

		<!--TELECHARGEMENT DES LOGS-->
		<div id="logsDownload" class="sLink" onClick="redir('?ctrl=log&action=logsDownload');">
			<img src="app/img/download.png"> <?= Txt::trad("telecharger") ?>
		</div>
	</div>
</div>