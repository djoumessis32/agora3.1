<?php
/*
 * Modele des groupes d'utilisateurs
 */
class MdlUserGroup extends MdlObject
{
	const moduleName="user";
	const objectType="userGroup";
	const dbTable="ap_userGroup";
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
		//Users affectés au groupe
		$this->userIds=Txt::txt2tab($this->_idUsers);
		//Liste des users : tableau d'Id et libellé des users
		$this->usersLabel=null;
		foreach($this->userIds as $userId)	{$this->usersLabel.=Ctrl::getObj("user",$userId)->display().", ";}
		$this->usersLabel=trim($this->usersLabel,", ");
	}

	/*
	 * SURCHARGE : Droit d'accès à l'objet
	 */
	public function accessRight()
	{
		if($this->_accessRight===null){
			$this->_accessRight=parent::accessRight();
			//Ajoute l'accès en lecture si :  User courant se trouve dans le groupe  OU  l'espace du groupe fait partie des espaces de l'user (pour les affectations d'objet)
			if(empty($this->_accessRight) && (in_array(Ctrl::$curUser->_id,$this->userIds) || in_array($this->_idSpace,Ctrl::$curUser->getSpaces("ids"))))	{$this->_accessRight=1;}
		}
		return $this->_accessRight;
	}

	/*
	 * SURCHARGE : Supprime un groupe
	 */
	public function delete()
	{
		if($this->deleteRight()){
			Db::query("DELETE FROM ap_objectTarget WHERE target='G".$this->_id."'");
			parent::delete();
		}
	}

	/*
	 * Groupes d'utilisateurs (Affectés à un espace ET/OU Affectés à un utilisateur?)					(ex "groupes_users(()")
	 */
	public static function getGroups($objSpace=null, $objUser=null)
	{
		$sqlFilter=null;
		if(is_object($objSpace))	{$sqlFilter.=" AND _idSpace=".$objSpace->_id;}
		if(is_object($objUser))		{$sqlFilter.=" AND _idUsers LIKE '%@".$objUser->_id."@%'";}
		return Db::getObjTab(static::objectType, "SELECT * FROM ".self::dbTable." WHERE 1 ".$sqlFilter." ORDER BY title");
	}

	/*
	 * SURCHARGE : Droit d'ajouter un nouveau groupe
	 */
	public static function addRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled("user","ajout_utilisateurs_groupe")));
	}
}