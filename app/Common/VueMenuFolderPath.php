<?php if(Ctrl::$curContainer->isRootFolder()==false){ ?>
<div class="folderPath sBlock noSelect">
	<img src="app/img/folderSmall.png">
	<?php
	foreach(Ctrl::$curContainer->folderPath("object") as $tmpFolder){
		echo "<a href=\"".$tmpFolder->getUrl()."\" title=\"".$tmpFolder->name."<br>".$tmpFolder->description."\">".(!empty($tmpFolder->_idContainer)?"<img src='app/img/arrowRight.png'>":null)." ".Txt::reduce($tmpFolder->name,30)."</a>";
		if($tmpFolder->_id==Ctrl::$curContainer->_id)	{echo $tmpFolder->menuContext(["inlineLauncher"=>true]);}
	}
	?>
</div>
<br>
<?php } ?>