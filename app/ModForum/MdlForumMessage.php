<?php
/*
 * Modele des messages du forum
 */
class MdlForumMessage extends MdlObject
{
	const moduleName="forum";
	const objectType="forumMessage";
	const dbTable="ap_forumMessage";
	const MdlObjectContainer="MdlForumSubject";
	const htmlEditorField="description";
	const hasShortcut=false;
	public static $requiredFields=array("description");
	public static $searchFields=array("title","description");
	public static $sortFields=array("dateCrea@@asc","dateCrea@@desc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","title@@asc","title@@desc","description@@asc","description@@desc");
	
	
	/*
	 * VUE : Surcharge du menu contextuel
	 */
	public function menuContext($options=null)
	{
		//Ajoute le libellé "suppr. message et messages associées"?
		if(Db::getVal("SELECT count(*) FROM ".self::dbTable." WHERE _idMessageParent=".$this->_id))
			{$options["deleteLabelConfirm"]=Txt::trad("FORUM_confirme_suppr_message");}
		return parent::menuContext($options);
	}

	/*
	 * SURCHARGE : Supprime un message
	 */
	public function delete()
	{
		if($this->deleteRight()){
			//S'il y a des sous-messages : on leur donne le meme "_idMessageParent" que le message supprimé : ils remontent d'un niveau
			Db::query("UPDATE ap_forumMessage SET _idMessageParent=".Db::format($this->_idMessageParent)." WHERE _idMessageParent=".$this->_id);
			parent::delete();
		}
	}
}