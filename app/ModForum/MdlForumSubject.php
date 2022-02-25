<?php
/*
 * Modele des sujets du forum
 */
class MdlForumSubject extends MdlObject
{
	const moduleName="forum";
	const objectType="forumSubject";
	const dbTable="ap_forumSubject";
	const MdlObjectContent="MdlForumMessage";
	const htmlEditorField="description";
	const nbObjectsByPage=30;
	public static $displayModeOptions=array("arbo","line");
	public static $requiredFields=array("description");
	public static $searchFields=array("title","description");
	public static $sortFields=array("dateLastMessage@@desc","dateLastMessage@@asc","dateCrea@@desc","dateCrea@@asc","dateModif@@desc","dateModif@@asc","_idUser@@asc","_idUser@@desc","title@@asc","title@@desc","description@@asc","description@@desc");

	/*
	 * Liste des messages d'un sujet :  affiché en arborescence (récursivité)  OU  trié par date (ou autre)
	 */
	public function getMessages($displayMode="line", $_objCurMessage=null, $_treeLevel=0)
	{
		//Affichage "line" OU "arbo" => récursivité!
		if($displayMode=="line")	{return Db::getObjTab("forumMessage", "SELECT * FROM ap_forumMessage WHERE _idContainer=".$this->_id." ".MdlForumMessage::sqlSort($this));}
		else
		{
			$curMessageList=[];
			//Premier appel de "getMessage()" => Récupère les message de niveau 1  ||  Sinon => Ajoute le message courant & Récupère les sous-messages
			if(!is_object($_objCurMessage))	{$sqlIdParent="(_idMessageParent is null OR _idMessageParent=0)";}
			else{
				$sqlIdParent="_idMessageParent=".$_objCurMessage->_id;
				$_objCurMessage->treeLevel=$_treeLevel;
				$curMessageList[]=$_objCurMessage;	
			}
			//Liste des messages  : premier appel de "getMessage()" OU récupération des sous-messages
			foreach(Db::getObjTab("forumMessage", "SELECT * FROM ap_forumMessage WHERE _idContainer=".$this->_id." AND ".$sqlIdParent."  ORDER BY dateCrea ASC") as $tmpMessage)
				{$curMessageList=array_merge($curMessageList, $this->getMessages($displayMode, $tmpMessage, $_treeLevel+1));}
			//renvoie les messages
			return $curMessageList;
		}
	}
	
	/*
	 * L'User courant recoit-il des notifications à l'ajout d'un nouveau message?
	 */
	public function curUserNotifyLastMessage()
	{
		return in_array(Ctrl::$curUser->_id,Txt::txt2tab($this->usersNotifyLastMessage));
	}
	
	/*
	 * L'User courant a-t-il consulté le dernier message?
	 */
	public function curUserConsultLastMessage()
	{
		return in_array(Ctrl::$curUser->_id,Txt::txt2tab($this->usersConsultLastMessage));
	}
	
	/*
	 * L'User courant a consulté le dernier message : MAJ DB
	 */
	public function curUserConsultLastMessageMaj()
	{
		if($this->curUserConsultLastMessage()==false){
			$usersConsultLastMessage=array_merge([Ctrl::$curUser->_id], Txt::txt2tab($this->usersConsultLastMessage));
			Db::query("UPDATE ap_forumSubject SET usersConsultLastMessage=".Db::formatTab2txt($usersConsultLastMessage)." WHERE _id=".$this->_id);
		}
	}

	/*
	 * SURCHARGE : Droit d'ajouter un nouveau sujet
	 */
	public static function addRight()
	{
		return (Ctrl::$curUser->isAdminCurSpace() || (Ctrl::$curUser->isUser() && Ctrl::$curSpace->moduleOptionEnabled("forum","ajout_sujet_admin")==false));
	}

	/*
	 * SURCHARGE : Url d'accès (dans un theme?)
	 */
	public function getUrl($display=null)
	{
		//Url simple / "container"
		if($display!="container")	{return parent::getUrl($display);}
		else{
			$urlBase="?ctrl=".static::moduleName;
			if(!empty($this->_idTheme))					{return $urlBase."&_idTheme=".$this->_idTheme;}//theme précis
			elseif(count(MdlForumTheme::getThemes())>0)	{return $urlBase."&_idTheme=undefinedTheme";}//theme "sans theme"
			else										{return $urlBase;}//accueil du forum
		}
	}
}