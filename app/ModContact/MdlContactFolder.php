<?php
/*
 * Modele des dossiers de contacts
 */
class MdlContactFolder extends MdlObjectFolder
{
	const moduleName="contact";
	const objectType="contactFolder";
	const dbTable="ap_contactFolder";
	const MdlObjectContent="MdlContact";
}
