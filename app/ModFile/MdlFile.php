<?php
/*
 * Modele des fichiers
 */
class MdlFile extends MdlObject
{
	private $_versions=null;
	private $_tumbPath=null;
	private $_hasTumb=null;
	const moduleName="file";
	const objectType="file";
	const dbTable="ap_file";
	const MdlObjectContainer="MdlFileFolder";
	const isFolderContent=true;
	const isSelectable=true;
	const hasAttachedFiles=false;
	public static $displayModeOptions=array("block","line");
	public static $requiredFields=array("name");
	public static $searchFields=array("name","description");
	public static $sortFields=array("name@@asc","name@@desc","dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","extension@@asc","extension@@desc","octetSize@@asc","octetSize@@desc","downloadsNb@@desc","downloadsNb@@asc");

	/*
	 * Chemin du fichier sur le disque (dernière version / version précisé avec "date_drea")
	 */
	public function filePath($dateCrea=null)
	{
		$curVersion=$this->getVersion($dateCrea);
		return $this->containerObj()->folderPath("real").$curVersion["realName"];
	}

	/*
	 * Lien de Download/Display
	 */
	public function urlDownloadDisplay($action="download", $dateCrea=null)
	{
		$display=($action=="display") ? "&display=true&extension=.".File::extension($this->name) : null;
		if(!empty($dateCrea))	{$dateCrea="&dateCrea=".urlencode($dateCrea);}
		return "?ctrl=file&action=getFile&targetObjId=".$this->_targetObjId.$display.$dateCrea;
	}

	/*
	 * Nom d'un vignette
	 */
	public function getThumbName()
	{
		return $this->_id."_thumb.jpg";
	}

	/*
	 * Chemin de la vignette JPG d'une image ou d'un Pdf (créé ou à créer)
	 */
	public function getThumbPath()
	{
		if($this->_tumbPath===null){
			if(File::controlType("imageResize",$this->name) || (File::controlType("pdf",$this->name) && extension_loaded("imagick")))	{$this->_tumbPath=$this->containerObj()->folderPath("real").$this->getThumbName();}
			else																														{$this->_tumbPath="";}
		}
		return $this->_tumbPath;
	}

	/*
	 * Verifie s'il existe une vignette
	 */
	public function hasThumb()
	{
		if($this->_hasTumb===null)
			{$this->_hasTumb=(strlen($this->getThumbPath()) && is_file($this->getThumbPath()));}
		return $this->_hasTumb;
	}

	/*
	 * Création/Maj la vignette du fichier (Image / Pdf)
	 */
	public function createThumb()
	{
		//Fichier de moins de 10Mo?
		if(filesize($this->filePath()) < (File::sizeMo*10))
		{
			if(File::controlType("imageResize",$this->name))    {return File::imageResize($this->filePath(),$this->getThumbPath(),300,300,90);}
			elseif(File::controlType("pdf",$this->name) && extension_loaded("imagick"))
			{
				$tmpThumb=new Imagick($this->filePath()."[0]");
				$tmpThumb->writeImage($this->getThumbPath());
				$tmpThumb->clear();
				$tmpThumb->destroy();
				return File::imageResize($this->getThumbPath(),$this->getThumbPath(),300);
			}
		}
	}

	/*
	 * Récupère toutes les versions du fichier
	 */
	public function getVersions($forceUpdate=false)
	{
		if($this->_versions===null || $forceUpdate==true)    {$this->_versions=Db::getTab("SELECT * FROM ap_fileVersion WHERE _idFile=".$this->_id." ORDER BY dateCrea desc");}//"ORDER BY" pour mettre la dernière version en premier!
		return $this->_versions;
	}

	/*
	 * Récupère la dernière version du fichier / une version à une date donnée
	 */
	public function getVersion($dateCrea=null)
	{
		foreach($this->getVersions() as $tmpVersion){
			if($tmpVersion["dateCrea"]==$dateCrea || empty($dateCrea))    {return $tmpVersion;  break;}
		}
	}

	/*
	 * Menu des versions du fichier
	 */
	public function versionsMenu($displayType)
	{
		$nbVersions=count($this->getVersions());
		if($nbVersions>1){
			$nbVersionsTitle=$nbVersions." ".Txt::trad("FILE_nb_versions_fichier");
			$displayLabelIcon=($displayType=="label") ? $nbVersionsTitle : "<img src='app/img/file/versions.png'>";
			return "<span class=\"vVersionsMenu sLink\" title=\"".$nbVersionsTitle."\" onclick=\"lightboxOpen('?ctrl=file&action=FileVersions&targetObjId=".$this->_targetObjId."')\">".$displayLabelIcon."</span>";
		}
	}

	/*
	 * VUE : Surcharge du menu contextuel
	 */
	public function menuContext($options=null)
	{
		//"Télécharger le fichier (Téléchargé X fois)"
		$options["specificOptions"][]=array(
			"actionJs"=>"window.open('".$this->urlDownloadDisplay()."')",
			"iconSrc"=>"app/img/download.png",
			"label"=>Txt::trad("telecharger")." (".Txt::trad("telecharge_nb")." ".$this->downloadsNb." ".Txt::trad("telecharge_nb_bis").")"
		);
		//"X versions du fichier"
		if(count($this->getVersions())>1){
			$options["specificOptions"][]=array(
				"actionJs"=>null,
				"iconSrc"=>"app/img/file/versions.png",
				"label"=>$this->versionsMenu("label")
			);
		}
		//"Ajouter une nouvelle version"
		if($this->editRight()){
			$options["specificOptions"][]=array(
				"actionJs"=>"lightboxOpen('?ctrl=".static::moduleName."&action=AddEditFiles&addVersion=true&targetObjId=".$this->_targetObjId."')",
				"iconSrc"=>"app/img/plus.png",
				"label"=>Txt::trad("FILE_ajouter_versions_fichier")
			);
		}
		return parent::menuContext($options);
	}

	/*
	 * Image du fichier					(ex "image_fichier()")
	 */
	public function typeIcon()
	{
		$pathFileTypes="app/img/file/fileType/";
		if($this->hasThumb())																												{return $this->getThumbPath();}
		elseif(File::controlType("image",$this->name))																						{return $pathFileTypes."image.png";}
		elseif(File::controlType("pdf",$this->name))																						{return $pathFileTypes."pdf.png";}
		elseif(File::controlType("word",$this->name) || File::controlType("ootext",$this->name) || File::controlType("text",$this->name))	{return $pathFileTypes."text.png";}
		elseif(File::controlType("excel",$this->name) || File::controlType("oocalc",$this->name))											{return $pathFileTypes."calc.png";}
		elseif(File::controlType("powerpoint",$this->name) || File::controlType("oopresent",$this->name))									{return $pathFileTypes."presentation.png";}
		elseif(File::controlType("archive",$this->name))																					{return $pathFileTypes."archive.png";}
		elseif(File::controlType("audio",$this->name))																						{return $pathFileTypes."audio.png";}
		elseif(File::controlType("video",$this->name))																						{return $pathFileTypes."video.png";}
		elseif(File::controlType("executable",$this->name))																					{return $pathFileTypes."executable.png";}
		elseif(File::controlType("web",$this->name))																						{return $pathFileTypes."web.png";}
		elseif(File::controlType("autocad",$this->name))																					{return $pathFileTypes."autocad.png";}
		else																																{return $pathFileTypes."unknown.png";}
	}

	/*
	 * SURCHARGE : Supprime un fichier
	 */
	public function delete($versionDateCrea="all")
	{
		if($this->deleteRight())
		{
			////	Si on supprime la dernière version d'un fichier : update les propriétés principale du fichier (nom/taille/etc) avec celles l'avant dernière version
			$fileVersions=$this->getVersions();
			if($versionDateCrea==$fileVersions[0]["dateCrea"] && count($fileVersions)>1)
				{Db::query("UPDATE ap_file SET name=".Db::format($fileVersions[1]["name"]).", octetSize=".Db::format($fileVersions[1]["octetSize"]).", dateModif=".Db::format($fileVersions[1]["dateCrea"]).", _idUserModif=".$fileVersions[1]["_idUser"]." WHERE _id=".$this->_id);}
			////	Supprime la/les versions => disque & BDD
			$versionsDelete=Db::getTab("SELECT * FROM ap_fileVersion WHERE _idFile=".$this->_id." AND length(realName)>0 ".($versionDateCrea!="all"?"AND dateCrea=".Db::format($versionDateCrea):null));
			foreach($versionsDelete as $tmpVersion)
			{
				$tmpFilePath=$this->filePath($tmpVersion["dateCrea"]);
				if(is_file($tmpFilePath)){
					$deleteResult=File::rm($tmpFilePath);
					if($deleteResult==true)   {Db::query("DELETE FROM ap_fileVersion WHERE _idFile=".$this->_id." AND realName=".Db::format($tmpVersion["realName"]));}
				}
			}
			////	Supprime le fichier s'il ne reste plus aucune version
			if(Db::getVal("SELECT count(*) FROM ap_fileVersion WHERE _idFile=".$this->_id)==0){
				if($this->hasThumb())   {File::rm($this->getThumbPath());}
				parent::delete();
			}
			////	si besoin, recharge la liste des versions puis recréé la vignette
			else{
				$this->getVersions(true);
				$this->createThumb();
			}
		}
	}
}