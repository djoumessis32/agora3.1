<?php
/*
 * Modele des themes de sujets
 */
class MdlForumTheme extends MdlObject
{
	const moduleName="forum";
	const objectType="forumTheme";
	const dbTable="ap_forumTheme";
	const hasAccessRight=false;
	const hasShortcut=false;
	const hasAttachedFiles=false;
	const hasNotifMail=false;
	//Champs obligatoires et de tri des résultats
	public static $requiredFields=array("title");
	public static $sortFields=array("title@asc","title@desc");

	/*
	 * SURCHARGE : Constructeur
	 */
	function __construct($objIdOrValues=null)
	{
		parent::__construct($objIdOrValues);
		//Espaces ou est visible le theme
		$this->spaceIds=Txt::txt2tab($this->_idSpaces);
		//Couleur par défaut : nouveau theme / Theme "undefined"
		if(empty($this->color))  {$this->color="#900";}
		//Id du theme pour les Urls
		$this->idThemeUrl=($this->undefinedTheme==true) ? "undefinedTheme" : $this->_id;
	}

	/*
	 * Retourne le titre du theme avec une pastille de couleur
	 */
	public function display()
	{
		if(!empty($this->title))			{return "<div class='themeColor' style=\"background:".$this->color."\">&nbsp;</div> ".$this->title;}
		elseif($this->undefinedTheme==true)	{return "<div class='themeColor' style='background:#444'>&nbsp;</div> ".Txt::trad("FORUM_sans_theme");}
	}

	/*
	 * Retourne les libellés des espaces affectés au thème
	 */
	public function spaceLabels()
	{
		if(!empty($this->spaceIds)){
			$spacesLabel=null;
			foreach($this->spaceIds as $_idSpace)	{$spacesLabel.=", ".Ctrl::getObj("space",$_idSpace)->name;}
			return trim($spacesLabel,",");
		}
	}

	/*
	 * Themes des sujets (Filtre par espace?)
	 */
	public static function getThemes($editMode=false)
	{
		$sqlFilter=($editMode==true && Ctrl::$curUser->isAdminGeneral())  ?  null  :  " AND (_idSpaces is null OR _idSpaces LIKE '%@".Ctrl::$curSpace->_id."@%')";
		return Db::getObjTab(static::objectType, "SELECT * FROM ".self::dbTable." WHERE 1 ".$sqlFilter." ORDER BY title");
	}

	/*
	 * SURCHARGE : Droit d'ajouter un nouveau theme
	 */
	public static function addRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled("forum","ajout_sujet_theme")));
	}

	/*
	 * SURCHARGE : suppression de theme
	 */
	public function delete()
	{
		if($this->deleteRight()){
			Db::query("UPDATE ap_forumSubject SET _idTheme=null WHERE _idTheme=".$this->_id);
			parent::delete();
		}
	}
}