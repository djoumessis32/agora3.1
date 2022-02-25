<div class="moduleMenuLine sLink">
	<div class="moduleMenuIcon"><img src="app/img/sort.png"></div>
	<div class="moduleMenuTxt">
		<div class="menuContext sBlock" id="vMenuSort">
			<?php
			foreach($sortFields as $tmpSort)
			{
				echo "<div class='menuContextLine ".($curSort==$tmpSort["sort"]?'sLinkSelect':'sLink')."' onclick=\"redir('".$tmpSort["url"]."')\">".
						Txt::trad("SORT_".$tmpSort["field"]).
						" <img src='app/img/sort".ucfirst($tmpSort["ascDesc"]).".png' title=\"".Txt::trad($tmpSort["ascDesc"]=="asc"?"tri_ascendant":"tri_descendant")."\">
					  </div>";
			}
			?>
		</div>
		<span class="sLink menuContextLauncher" for="vMenuSort"><?= Txt::trad("trie_par")." ".Txt::trad("SORT_".$curSortTab[0]) ?> <img src="app/img/sort<?= ucfirst($curSortTab[1])?>.png"></span>
	</div>
</div>