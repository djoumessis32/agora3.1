<?php
/*
 * Modele des Contact
 */
class MdlContact extends MdlPerson
{
	const moduleName="contact";
	const objectType="contact";
	const dbTable="ap_contact";
	const MdlObjectContainer="MdlContactFolder";
	const isFolderContent=true;
	//Champs obligatoires et de tri des résultats
	public static $requiredFields=array("name");
	public static $sortFields=array("name@@asc","name@@desc","firstName@@asc","firstName@@desc","civility@@asc","civility@@desc","postalCode@@asc","postalCode@@desc","city@@asc","city@@desc","country@@asc","country@@desc","function@@asc","function@@desc","companyOrganization@@asc","companyOrganization@@desc","_idUser@@asc","_idUser@@desc","dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc");

	/*
	 * Photo d'un contact
	 */
	public function pathImgThumb()
	{
		return PATH_MOD_CONTACT.$this->_id."_thumb.jpg";
	}

	/*
	 * SURCHARGE : Supprime un contact
	 */
	public function delete()
	{
		if($this->deleteRight()){
			if(is_file($this->pathImgThumb()))	{unlink($this->pathImgThumb());}
			parent::delete();
		}
	}

	/*
	 * VUE : Surcharge du menu contextuel
	 */
	public function menuContext($options=null)
	{
		//"Créer un utilisateur sur cet espace" : admin général uniquement!
		if(Ctrl::$curUser->isAdminGeneral())
		{
			$options["specificOptions"][]=array(
				"actionJs"=>"contactAddUser('".$this->_targetObjId."')",
				"iconSrc"=>"app/img/plus.png",
				"label"=>Txt::trad("CONTACT_creer_user"),
				"tooltip"=>Txt::trad("CONTACT_creer_user_infos"),
				"inMainMenu"=>true
			);
			return parent::menuContext($options);
		}
	}
}