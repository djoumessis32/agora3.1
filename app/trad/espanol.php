<?php
/*
 * Classe de gestion d'une langue
 */
class Trad extends Txt
{
	/*
	 * Chargement les elements de traduction
	 */
	public static function loadTradsLang()
	{
		////	Header http / Editeurs Tinymce,DatePicker,etc / Dates formatées par PHP
		self::$trad["CURLANG"]="es";
		self::$trad["HEADER_HTTP"]="es";
		self::$trad["DATEPICKER"]="es";
		self::$trad["HTML_EDITOR"]="es";
		self::$trad["UPLOADER"]="es";
		setlocale(LC_TIME, "es_ES.utf8", "es_ES.UTF-8", "es_ES", "es", "spanish");

		////	Divers
		self::$trad["OK"]="OK";
		self::$trad["remplir_tous_champs"]="Gracias rellene todos los campos";
		self::$trad["elem_inaccessible"]="Elemento inaccesible";
		self::$trad["champs_obligatoire"]="Campo obligatorio";
		self::$trad["oui"]="sí";
		self::$trad["non"]="no";
		self::$trad["aucun"]="no";
		self::$trad["et"]="y";
		self::$trad["aller_page"]="Ir a la página";
		self::$trad["alphabet_filtre"]="Filtro alfabético";
		self::$trad["tout_afficher"]="Mostrar todo";
		self::$trad["important"]="importante";
		self::$trad["afficher"]="mostrar";
		self::$trad["deplacer"]="mover";
		self::$trad["garder"]="Mantengar";
		self::$trad["par_defaut"]="Por defecto";
		self::$trad["localiser_carte"]="Localizar en el mapa";
		self::$trad["bienvenue_agora"]="Bienvenido al Agora!";
		self::$trad["mail_pas_valide"]="El correo electrónico no es válida";
		self::$trad["element"]="elemento";
		self::$trad["elements"]="elementos";
		self::$trad["dossier"]="directorio";
		self::$trad["dossiers"]="directorios";
		self::$trad["dossier_parent"]="directorio superior";
		self::$trad["fermer"]="Cerrar";
		self::$trad["visible_espaces"]="Espacios en los que será visible";
		self::$trad["visible_ts_espaces"]="Visible en todos los espacios";
		self::$trad["confirmCloseLightbox"]="¿Quieres cerrar el formulario?";

		////	Menu/Menu contextuel
		self::$trad["menu"]="menu";
		self::$trad["objNew"]="Nuevo elemento";
		self::$trad["acces_perso"]="Acceso personal";
		
		////	images
		self::$trad["picture"]="foto";
		self::$trad["wallpaper"]="papel tapiz";
		self::$trad["image_changer"]="cambiar";
		self::$trad["pixels"]="píxeles";
		
		////	Connexion
		self::$trad["specifierLoginPassword"]="Gracias a especificar un nombre de usuario y contraseña";
		self::$trad["login"]="Identificador";
		self::$trad["login2"]="Identificador de conexión";
		self::$trad["placeholderLogin"]="Email / Identificador";
		self::$trad["password"]="Contraseña";
		self::$trad["passwordToModify"]="Contraseña (cambiar)";
		self::$trad["passwordVerif"]="Confirmar contraseña";
		self::$trad["passwordInfo"]="Dejar en blanco si desea mantener su contraseña";
		self::$trad["passwordVerifError"]="Su confirmación de contraseña no es válida";
		self::$trad["connexion"]="Conexión";
		self::$trad["connexion_auto"]="mantengamos el contacto";
		self::$trad["connexion_auto_info"]="Recordar mi nombre de usuario y la contraseña para una conexión automática";
		self::$trad["password_oublie"]="¿ Has olvidado tu contraseña ?";
		self::$trad["password_oublie_info"]="Enviar mi nombre de usuario y contraseña a mi dirección de correo electrónico (si se especifica)";
		self::$trad["acces_guest"]="Acceso de invitado";
		self::$trad["espace_password_erreur"]="Contraseña incorrecta";
		self::$trad["version_ie"]="Su navegador es demasiado viejo y no soporta todos los elementos de HTML : Se recomienda actualizarlo o utilizar otro navegador";
		
		////	Affichage
		self::$trad["type_affichage"]="Mostrar";
		self::$trad["type_affichage_line"]="Lista";
		self::$trad["type_affichage_block"]="Bloque";
		self::$trad["type_affichage_arbo"]="árbol";
		
		////	Sélectionner / Déselectionner tous les éléments
		self::$trad["select"]="seleccionar";
		self::$trad["select_deselect"]="seleccionar / deseleccionar";
		self::$trad["tout_selectionner"]="Seleccionar todo";
		self::$trad["inverser_selection"]="Invertir selección";
		self::$trad["suppr_elements"]="Eliminar elementos";
		self::$trad["deplacer_elements"]="Mover a otro directorio";
		self::$trad["voir_sur_carte"]="Mostrar en el mapa";
		self::$trad["selectionner_user"]="Gracias por seleccionar al menos un usuario";
		self::$trad["selectionner_2users"]="Gracias por seleccionar por lo menos dos usuarios";
		self::$trad["selectionner_espace"]="Gracias por elegir al menos un espacio";
		
		////	Temps ("de 11h à 12h", "le 25-01-2007 à 10h30", etc.)
		self::$trad["de"]="de";
		self::$trad["a"]="a";
		self::$trad["le"]="el";
		self::$trad["debut"]="inicio";
		self::$trad["fin"]="Fin";
		self::$trad["separateur_horaire"]="h";
		self::$trad["jours"]="dias";
		self::$trad["jour_1"]="lunes";
		self::$trad["jour_2"]="Martes";
		self::$trad["jour_3"]="miércoles";
		self::$trad["jour_4"]="Jueves";
		self::$trad["jour_5"]="Viernes";
		self::$trad["jour_6"]="Sábado";
		self::$trad["jour_7"]="Domingo";
		self::$trad["mois_1"]="Enero";
		self::$trad["mois_2"]="Febrero";
		self::$trad["mois_3"]="marzo";
		self::$trad["mois_4"]="Abril";
		self::$trad["mois_5"]="Mayo";
		self::$trad["mois_6"]="Junio";
		self::$trad["mois_7"]="julio";
		self::$trad["mois_8"]="agosto";
		self::$trad["mois_9"]="Septiembre";
		self::$trad["mois_10"]="octubre";
		self::$trad["mois_11"]="Noviembre";
		self::$trad["mois_12"]="Diciembre";
		self::$trad["aujourdhui"]="hoy";
		self::$trad["aff_aujourdhui"]="Ver hoy";
		self::$trad["modif_dates_debutfin"]="La fecha de fin no puede ser anterior a la fecha de inicio";
		self::$trad["dates_mauvais_format"]="La fecha debe estar en el formato dd/mm/AAAA";
		
		////	Nom & Description (pour les menus d'édition principalement)
		self::$trad["title"]="Título";
		self::$trad["name"]="Nombre";
		self::$trad["description"]="Descripción";
		self::$trad["specifyName"]="Gracias por especificar un nombre";
		
		////	Validation des formulaires
		self::$trad["ajouter"]=" Añadir";
		self::$trad["modifier"]=" Editar";
		self::$trad["modifier_et_acces"]="Editar + derechos de acceso";
		self::$trad["valider"]=" Validar";
		self::$trad["envoyer"]="Enviar";
		self::$trad["envoyer_a"]="Enviar a";
		
		////	Tri d'affichage. Tous les éléments (dossier, tâche, lien, etc...) ont par défaut une date, un auteur & une description
		self::$trad["trie_par"]="Ordenar por";
		self::$trad["SORT_dateCrea"]="fecha de creación";
		self::$trad["SORT_dateModif"]="fecha de modification";
		self::$trad["SORT_title"]="Título";
		self::$trad["SORT_description"]="Descripción";
		self::$trad["SORT__idUser"]="autor";
		self::$trad["SORT_extension"]="Tipo de archivo";
		self::$trad["SORT_octetSize"]="tamaño";
		self::$trad["SORT_downloadsNb"]="downloads";
		self::$trad["SORT_civility"]="civilidad";
		self::$trad["SORT_name"]="appelido";
		self::$trad["SORT_firstName"]="nombre";
		self::$trad["SORT_adress"]="dirección";
		self::$trad["SORT_postalCode"]="Código postal";
		self::$trad["SORT_city"]="ciudad";
		self::$trad["SORT_country"]="país";
		self::$trad["SORT_function"]="función";
		self::$trad["SORT_companyOrganization"]="compañía / organización";
		self::$trad["tri_ascendant"]="Ascendente";
		self::$trad["tri_descendant"]="Descendente";
		
		////	Options de suppression
		self::$trad["confirmDelete"]="Confirmar eliminación ?";
		self::$trad["confirmDeleteBis"]="Está seguro ?!";
		self::$trad["confirmer_suppr_dossier"]="Advertencia : algunos sub-directorios no son accessible : serán tambien eliminados !";
		self::$trad["supprimer"]="Eliminar";
		
		////	Visibilité d'un Objet : auteur et droits d'accès
		self::$trad["auteur"]="Autor";
		self::$trad["cree_par"]="creación";
		self::$trad["modif_par"]="Cambio";
		self::$trad["historique_element"]="histórico del elemento";
		self::$trad["guest"]="invitado";
		self::$trad["guests"]="invitados";
		self::$trad["tous"]="todos";
		self::$trad["inconnu"]="desconocido";
		self::$trad["lecture"]="lectura";
		self::$trad["lecture_infos"]="Acceso en lectura";
		self::$trad["ecriture_limit"]="escritura limitada";
		self::$trad["ecriture_limit_infos"]="Acceso en escritura limitada : possibilidad de añadir elementos (-ELEMENT-), sin modificar o suprimir los creados por otros usuarios";
		self::$trad["ecriture"]="escritura";
		self::$trad["ecriture_infos"]="Acceso en escritura";
		self::$trad["ecriture_infos_conteneur"]="Acceso en escritura : possibilidad de añadir, modificar o suprimir<br>todos los elementos (-ELEMENT-) del -CONTENEUR-";
		self::$trad["ecriture_auteur_admin"]="Solo el autor y los administradores pueden cambiar<br>los permisos de acceso o eliminar el -CONTENEUR-";
		self::$trad["contenu_dossier"]="contenido";
		
		////	Libellé des objets
		self::$trad["OBJECTcontainer"]="contenedor";
		self::$trad["OBJECTelement"]="elemento";
		self::$trad["OBJECTfolder"]="directorio";
		self::$trad["OBJECTdashboardNews"]="novedade";
		self::$trad["OBJECTfile"]="archivo";
		self::$trad["OBJECTcalendar"]="calendario";
		self::$trad["OBJECTcalendarEvent"]="evento";
		self::$trad["OBJECTforumSubject"]="tema";
		self::$trad["OBJECTforumMessage"]="mensaje";
		self::$trad["OBJECTcontact"]="contacto";
		self::$trad["OBJECTlink"]="favorito";
		self::$trad["OBJECTtask"]="tarea";
		self::$trad["OBJECTuser"]="usuario";
		
		////	Envoi d'un mail (nouvel utilisateur, notification de création d'objet, etc...)
		self::$trad["MAIL_noFooter"]="No firme el mensaje";
		self::$trad["MAIL_noFooter_info"]="No firme el final del mensaje con el nombre del remitentey un enlace al espacio";
		self::$trad["MAIL_hideRecipients"]="Ocultar los destinatarios";
		self::$trad["MAIL_hideRecipients_info"]="Por defecto, los destinatarios de correo electrónico aparecen en el mensaje.";
		self::$trad["MAIL_receptionNotif"]="Confirmación de entrega";
		self::$trad["MAIL_receptionNotif_info"]="Advertencia! algunos clientes de correo electrónico no soportan el recibo de entrega";
		self::$trad["MAIL_envoye_par"]="Enviado por";  // "Envoyé par" M. Trucmuche
		self::$trad["MAIL_envoye"]="El correo electrónico ha sido enviado !";
		self::$trad["MAIL_envoye_notif"]="El correo electrónico de notificación ha sido enviado !";
		self::$trad["MAIL_pas_envoye"]="El correo electrónico no se pudo enviar..."; // idem
		self::$trad["MAIL_element_cree"]="Nuevo elemento creado por";//boby
		self::$trad["MAIL_element_modifie"]="Elemento modificado por";//boby
		self::$trad["MAIL_element_sur_espace"]="en el espacio";//truc
		self::$trad["MAIL_element_acces_direct"]="Haga clic aquí para acceder al elemento en su espacio";

		////	Dossier & fichier
		self::$trad["giga_octet"]="GB";
		self::$trad["mega_octet"]="MB";
		self::$trad["kilo_octet"]="KB";
		self::$trad["dossier_racine"]="Directorio raíz";
		self::$trad["rootFolderEditInfo"]="Abra la configuración del espacio<br>para cambiar los derechos de acceso a la carpeta raíz";
		self::$trad["ajouter_dossier"]="añadir un directorio";
		self::$trad["telecharger"]="Descargar archivos";
		self::$trad["telecharge_nb"]="Descargado";
		self::$trad["telecharge_nb_bis"]="veces"; // Téléchargé 'n' fois
		self::$trad["telecharger_dossier"]="Descargar el directorio";
		self::$trad["espace_disque_utilise"]="Espacio utilizado";
		self::$trad["espace_disque_utilise_mod_fichier"]="Espacio utilizado para los Archivos";
		self::$trad["download_alert"]="La descarga de archivos de gran tamaño no están disponibles durante el día";//..(9h=>19h)
		self::$trad["download_alert2"]="Tamaño máximo del archivo durante estas horas";//..200Mo
		
		////	Infos sur une personne
		self::$trad["civility"]="Civilidad";
		self::$trad["name"]="Appelido";
		self::$trad["firstName"]="Nombre";
		self::$trad["adress"]="Dirección";
		self::$trad["postalCode"]="Código postal";
		self::$trad["city"]="Ciudad";
		self::$trad["country"]="País";
		self::$trad["telephone"]="Teléfono";
		self::$trad["telmobile"]="teléfono móvil";
		self::$trad["mail"]="Email";
		self::$trad["fax"]="Fax";
		self::$trad["website"]="Página web";
		self::$trad["skills"]="Habilidades";
		self::$trad["hobbies"]="Intereses";
		self::$trad["function"]="Función";
		self::$trad["companyOrganization"]="compañía / organización";
		self::$trad["comment"]="Comentario";
		
		////	Captcha
		self::$trad["captcha"]="Identificación Visual";
		self::$trad["captcha_info"]="Por favor, escriba los 4 caracteres para su identificación";
		self::$trad["captcha_alert_specifier"]="Por favor, especifique la identificación visual";
		self::$trad["captcha_alert_erronee"]="La identificación visual no es valida";
		
		////	Rechercher
		self::$trad["preciser_text"]="Por favor, especifique las palabras clave de al menos 3 caracteres";
		self::$trad["rechercher"]="Buscar";
		self::$trad["rechercher_dateCrea"]="Fecha de creación";
		self::$trad["rechercher_dateCrea_jour"]="menos de un día";
		self::$trad["rechercher_dateCrea_semaine"]="menos de una semana";
		self::$trad["rechercher_dateCrea_mois"]="menos de un mes";
		self::$trad["rechercher_dateCrea_annee"]="menos de un año";
		self::$trad["rechercher_espace"]="Buscar en el espacio";
		self::$trad["recherche_avancee"]= "Búsqueda avanzada";
		self::$trad["recherche_avancee_mots_certains"]= "cualquier palabra";
		self::$trad["recherche_avancee_mots_tous"]= "todas las palabras";
		self::$trad["recherche_avancee_expression_exacte"]= "frase exacta";
		self::$trad["keywords"]="Palabras clave";
		self::$trad["liste_modules"]="Módulos";
		self::$trad["liste_champs"]="Campos";
		self::$trad["liste_champs_elements"]="Elementos involucrados";
		self::$trad["aucun_resultat"]="No hay resultados";
		
		////	Gestion des inscriptions d'utilisateur
		self::$trad["usersInscription"]="registrarse en el sitio";
		self::$trad["usersInscription_info"]="crear una nueva cuenta de usuario (validado por un administrador)";
		self::$trad["usersInscription_espace"]="registrarse al espacio";
		self::$trad["usersInscription_enregistre"]="Su registro será validado tan pronto como sea posible por el administrador del espacio";
		self::$trad["usersInscription_option_espace"]="Permitir a los visitantes que se registren en el espacio";
		self::$trad["usersInscription_option_espace_info"]="El registro se encuentra en la página de inicio. Debe ser validado por el administrador del espacio.";
		self::$trad["usersInscription_validation"]="Registros";
		self::$trad["usersInscription_validation_title"]="Validar registros de usuarios al espacio";
		self::$trad["usersInscription_invalider"]="invalidar";
		self::$trad["usersInscription_invalider_mail"]="Su cuenta no ha sido validado en";
		
		////	Importer ou Exporter : Contact OU Utilisateurs
		self::$trad["exporter"]="Exportar";
		self::$trad["importer"]="Importar";
		self::$trad["import_export_user"]="usuarios";
		self::$trad["import_export_contact"]="contactos";
		self::$trad["export_format"]="formato";
		self::$trad["specifier_fichier"]="or favor, especifique un archivo";
		self::$trad["extension_fichier"]="El tipo del archivo no es válido. Debe ser de tipo";
		self::$trad["import_infos"]="Seleccione los campos (Agora) de destino con las listas desplegables de cada columna.";
		self::$trad["import_alert"]="Por favor, seleccione la columna de nombre en las listas desplegables";
		self::$trad["import_alert2"]="Por favor, seleccione al menos un contacto para importar";
		self::$trad["import_alert3"]="El campo Agora ya ha sido seleccionado en otra columna (cada campo Agora se puede seleccionar sólo una vez)";
		
		////	Connexion à un serveur LDAP
		self::$trad["ldap_connexion_serveur"]="Conexión a un servidor LDAP";
		self::$trad["ldap_server"]="dirección del servidor";
		self::$trad["ldap_server_port"]="Puerto de servidor";
		self::$trad["ldap_server_port_infos"]="''389'' de forma predeterminada";
		self::$trad["ldap_admin_login"]="Cadena de conexión para admin";
		self::$trad["ldap_admin_login_infos"]="por ejemplo ''uid=admin,ou=my_company''";
		self::$trad["ldap_admin_pass"]="Contraseña del administrador";
		self::$trad["ldap_base_dn"]="Grupo / base DN";
		self::$trad["ldap_base_dn_infos"]="Localización de los usuarios del directorio.<br> por ejemplo ''ou=users,o=my_company''";
		self::$trad["ldap_connexion_erreur"]="Error al conectar con el servidor LDAP !";
		self::$trad["ldap_crea_auto_users"]="Auto creación de usuarios después identificación";
		self::$trad["ldap_crea_auto_users_infos"]="Creación automática de un usuario si no está en el Agora, pero presente en el servidor LDAP : se le asignará a las áreas accesibles a ''todos los usuarios del sitio''.<br>De lo contrario, el usuario no se creará.";
		self::$trad["ldap_pass_cryptage"]="Contraseñas cifrado en el servidor";
		self::$trad["ldap_effacer_params"]="Eliminar configuración LDAP?";
		self::$trad["ldap_pas_module_php"]="Módulo PHP para la conexión a un servidor LDAP no está instalado!";
		
		////	Messages d'alert ou d'erreur
		self::$trad["MSG_NOTIF_identification"]="Nombre de usuario o contraseña no válida";
		self::$trad["MSG_NOTIF_dejapresent"]="Cuenta actualmente utilizada con una dirección IP diferente... (una cuenta puede ser utilizada en una sola computadora al mismo tiempo)";
		self::$trad["MSG_NOTIF_adresseip"]="La dirección IP utilizada no está permitida con esta cuenta";
		self::$trad["MSG_NOTIF_acces_aucun_espace"]="El acceso no esta actualmente permitido con este cuente : probablemente no asignado a un espacio.";
		self::$trad["MSG_NOTIF_acces_fichier_dossier"]="El archivo o directorio no está disponible";
		self::$trad["MSG_NOTIF_espace_disque"]="El espacio para almacenar sus archivos no es suficiente, no se puede añadir archivos";
		self::$trad["MSG_NOTIF_type_interdit"]="Tipo de archivo no permitido";
		self::$trad["MSG_NOTIF_type_version"]="Tipo de archivo diferente del original";
		self::$trad["MSG_NOTIF_deplacement_dossier"]="No se puede mover el directorio dentro de sí mismo..!";
		self::$trad["MSG_NOTIF_duplicateName"]="Un elemento con el mismo nombre ya existe.";
		self::$trad["MSG_NOTIF_fileName"]="Un archivo con el mismo nombre ya existe (no ha sido reemplazado)";
		self::$trad["MSG_NOTIF_chmod_DATAS"]="El directorio ''DATAS'' no es accesible por escrito. Usted necesita dar un acceso de lectura y escritura para el propietario y el grupo (''chmod 775'').";
		self::$trad["MSG_NOTIF_nb_users"]="No se puede añadir un nuevo usuario : se limita a "; // "...limité à" 10
		self::$trad["MSG_NOTIF_update"]="Actualización completada. Es recomendable reiniciar el navegador antes de volver a conectar";
		self::$trad["MSG_NOTIF_user_existdeja"]="El nombre de usuario ya existe : el usuario no se ha creado";
		
		////	header menu
		self::$trad["HEADER_MENU_espaces_dispo"]="Otros espacios disponibles";
		self::$trad["HEADER_MENU_display_elem"]="Mostrar elementos";
		self::$trad["HEADER_MENU_display_normal"]="asignado a mí";
		self::$trad["HEADER_MENU_display_title_normal"]="Es la pantalla normal / por defecto";
		self::$trad["HEADER_MENU_display_autor"]="que he creado";
		self::$trad["HEADER_MENU_display_title_autor"]="Para mostrar sólo los elementos que he creado";
		self::$trad["HEADER_MENU_display_all"]="Todos los elementos del espacio (admin)";
		self::$trad["HEADER_MENU_display_title_all"]="Para el administrador del espacio : para mostrar todos los elementos del espacio, incluso aquellos que no son asignados al administrador !";
		self::$trad["HEADER_MENU_recherche_elem"]="Búsqueda de elementos en el espacio";
		self::$trad["HEADER_MENU_documentation"]="Documentación";
		self::$trad["HEADER_MENU_sortie_agora"]="Cerrar sesión del Ágora";
		self::$trad["HEADER_MENU_shortcuts"]="Acceso directo";
		self::$trad["HEADER_MENU_seul_utilisateur_connecte"]="Actualmente sólo";
		self::$trad["HEADER_MENU_en_ligne"]="En línea";
		self::$trad["HEADER_MENU_connecte_a"]="conectado al sitio a";   // M. Bidule truc "connecté au site à" 12:45
		self::$trad["HEADER_MENU_messenger"]="Mensajería instantánea";
		self::$trad["HEADER_MENU_envoye_a"]="Enviado a";
		self::$trad["HEADER_MENU_ajouter_message"]="Añadir un mensaje";
		self::$trad["HEADER_MENU_specifier_message"]="Por favor, especifique un mensaje";
		self::$trad["HEADER_MENU_enregistrer_conversation"]="Recordar la conversación";
		
		////	Footer
		self::$trad["FOOTER_page_generee"]="página generada en";
		
		////	Password_oublie
		self::$trad["PASS_OUBLIE_preciser_mail"]="Introduzca la dirección de correo electrónico para recibir su nombre de usuario y contraseña";
		self::$trad["PASS_OUBLIE_mail_inexistant"]="El correo electrónico no está indicado en la base";
		self::$trad["PASS_OUBLIE_mail_objet"]="Conexión al espacio";
		self::$trad["PASS_OUBLIE_mail_contenu"]="Su nombre de usuario";
		self::$trad["PASS_OUBLIE_mail_contenu_bis"]="Haga clic aquí para reinicializar tu contraseña";
		self::$trad["PASS_OUBLIE_prompt_changer_pass"]="Especifique la nueva contraseña";
		self::$trad["PASS_OUBLIE_idNewPassword_expire"]="El enlace para regenerar la contraseña ha caducado .. gracias por reiniciar la procedura";
		self::$trad["PASS_OUBLIE_password_reinitialise"]="Su nueva contraseña se registró !";
		
		////	menu_edit_objet
		self::$trad["EDIT_OBJET_alert_aucune_selection"]="Debe seleccionar al menos una persona o un espacio";
		self::$trad["EDIT_OBJET_alert_pas_acces_perso"]="Usted no se ha asignado al elemento. validar todos lo mismo ?";
		self::$trad["EDIT_OBJET_alert_ecriture_obligatoire"]="Debe haber al menos una persona o un espacio asignado para escribir";
		self::$trad["EDIT_OBJET_alert_ecriture_deconseille"]="¡Advertencia! <br><br>Con acceso de escritura, TODOS los mensajes pueden ser eliminados ! <br><br>Se recomienda limitar el acceso a escritura";
		self::$trad["EDIT_OBJET_alert_guest"]="Por favor, especifique un nombre o apodo de";
		self::$trad["EDIT_OBJET_accessRight"]="Derechos de acceso";
		self::$trad["EDIT_OBJET_accessRightContent"]="Derechos de acceso al contenido";
		self::$trad["EDIT_OBJET_identification"]="Identificación";
		self::$trad["EDIT_OBJET_espace_pas_module"]="El módulo actual aún no se ha añadido a este espacio";
		self::$trad["EDIT_OBJET_allUsers"]="Todos los usuarios";
		self::$trad["EDIT_OBJET_allUsers_espaces"]="Todos los espacios";
		self::$trad["EDIT_OBJET_espace_guests"]="Invitados del espacio público";
		self::$trad["EDIT_OBJET_guest"]="Tu nombre / apodo";
		self::$trad["EDIT_OBJET_admin_espace"]="Administrador del espacio:<br>acceso de escritura a todos los elementos del espacio";
		self::$trad["EDIT_OBJET_tous_espaces"]="Mostrar todos mis espacios";
		self::$trad["EDIT_OBJET_notif_mail"]="Notificación por correo electrónico";
		self::$trad["EDIT_OBJET_notif_mail_label"]="Notificación por correo electrónico";
		self::$trad["EDIT_OBJET_notif_mail_joindre_fichiers"]="Adjuntar archivos a la notificación";
		self::$trad["EDIT_OBJET_notif_mail_info"]="Enviar notificación de creación/cambio por correo electrónico a los que tienen acceso al elemento";
		self::$trad["EDIT_OBJET_notif_mail_selection"]="Seleccionar manualmente los destinatarios de las notificaciones";
		self::$trad["EDIT_OBJET_notif_tous_users"]="Mostrar mas usuarios";
		self::$trad["EDIT_OBJET_accessRightSubFolders"]="Dar igualdad de derechos a todos los sub-directorios";
		self::$trad["EDIT_OBJET_accessRightSubFolders_info"]="Extender los derechos de acceso, a los sub-directorios que se pueden editar";
		self::$trad["EDIT_OBJET_shortcut"]="Acceso directo";
		self::$trad["EDIT_OBJET_shortcut_info"]="Mostrar un acceso directo en el menú principal";
		self::$trad["EDIT_OBJET_fichier_joint"]="Añadir archivos";
		self::$trad["EDIT_OBJET_fichier_joint_info"]="Añadir fotos, vídeos, PDF, Word.. al objeto actual.<br>Imágenes y videos se pueden integrar directamente en el editor.";
		self::$trad["EDIT_OBJET_inserer_fichier"]="Mostrar en la descripción";
		self::$trad["EDIT_OBJET_inserer_fichier_info"]="Mostrar la imagen / video / mp3... en la descripción anterior. La inserción se realiza después de la validación del formulario.";
		self::$trad["EDIT_OBJET_demandToConfirm"]="Su solicitud ha sido registrada. Se confirmó pronto.";
		
		////	Formulaire d'installation
		self::$trad["INSTALL_connexion_bdd"]="Conexión a la base de datos";
		self::$trad["INSTALL_db_host"]="Nombre del servidor host (hostname)";
		self::$trad["INSTALL_db_name"]="Nombre de la base de datos";
		self::$trad["INSTALL_db_login"]="Nombre de Usuario";
		self::$trad["INSTALL_config_admin"]="Administrador del Ágora";
		self::$trad["INSTALL_errorConnectSGBD"]="No hay conexión con la base de datos MySQL";
		self::$trad["INSTALL_errorConnectIdentification"]="No identificación con la base de datos MySQL";
		self::$trad["INSTALL_errorAppliInstalled"]="La instalación ya se ha realizado en esta base de datos. Gracias simplemente eliminar la base de datos si se debe reiniciar la instalación.";
		self::$trad["INSTALL_errorConnectDbConfirmInstall"]="La conexión a la base de datos no se ha establecido, confirmar ?";
		self::$trad["INSTALL_PhpOldVersion"]="Agora-Project requiere una versión más reciente de PHP";
		self::$trad["INSTALL_confirm_install"]="Confirmar instalación ?";
		self::$trad["INSTALL_install_ok"]="Agora-Project ha sido instalado !";
		self::$trad["INSTALL_spaceDescription"]="Espacio para el intercambio y el trabajo colaborativo";

		////	MODULE_PARAMETRAGE
		////
		self::$trad["AGORA_headerModuleName"]="Administración general";
		self::$trad["AGORA_description_module"]="Administración general";
		self::$trad["AGORA_sav"]="Copia de seguridad de la base de datos y los archivos";
		self::$trad["AGORA_sav_alert"]="La creación de la copia de seguridad puede tardar unos minutos ... y descargar una docena de minutos.";
		self::$trad["AGORA_sav_bdd"]="Copia de seguridad de la base de datos";
		self::$trad["AGORA_espace_disque_invalide"]="El límite de espacio de disco debe ser un número entero";
		self::$trad["AGORA_confirmez_modification_site"]="Confirmar los cambios ?";
		self::$trad["AGORA_name"]="Nombre del sitio";
		self::$trad["AGORA_footerHtml"]="Footer / Pie de página html";
		self::$trad["AGORA_footerHtml_info"]="Para incluir herramientas estadísticas, por ejemplo";
		self::$trad["AGORA_lang"]="Lenguaje por defecto";
		self::$trad["AGORA_timezone"]="Zona horaria";
		self::$trad["AGORA_spaceName"]="Nombre del espacio principal";
		self::$trad["AGORA_limite_espace_disque"]="Espacio de disco disponible para los archivos";
		self::$trad["AGORA_logsTimeOut"]="Periodo de validez de los Logs";
		self::$trad["AGORA_skin"]="Color de la interfaz";
		self::$trad["AGORA_noir"]="Negro";
		self::$trad["AGORA_blanc"]="Blanco";
		self::$trad["AGORA_erreur_wallpaper_logo"]="La imagen de fondo y el logotipo debe tener el formato .jpg ou .png";
		self::$trad["AGORA_suppr_wallpaper"]="Eliminar la imagen de fondo ?";
		self::$trad["AGORA_logo_footer"]="Logotipo en pie de página";
		self::$trad["AGORA_logo_footer_url"]="URL";
		self::$trad["AGORA_messengerDisabled"]="Mensajería instantánea activada";
		self::$trad["AGORA_personalCalendarsDisabled"]="Calendarios personales habilitadas por defecto";
		self::$trad["AGORA_personalCalendarsDisabled_infos"]="Agregar un calendario personal en la creación de un usuario. El calendario puede ser desactivado más tarde, cuando se cambia el perfil de usuario.";
		self::$trad["AGORA_moduleLabelDisplay"]="Nombre de los módulos en la barra de menús";
		self::$trad["AGORA_moduleLabelDisplay_masquer"]="Ocultar";
		self::$trad["AGORA_moduleLabelDisplay_icones"]="por encima de cada icono del módulo";
		self::$trad["AGORA_moduleLabelDisplay_page"]="sólo para el módulo actual";
		self::$trad["AGORA_personsSort"]="Ordenar los usuarios y contactos";
		self::$trad["AGORA_versions"]="Versiones";
		self::$trad["AGORA_version_agora_maj"]="actualización ";
		self::$trad["AGORA_fonction_mail_desactive"]="Función de PHP para enviar correos electrónicos : desactivada !";
		self::$trad["AGORA_fonction_mail_infos"]="Algunos ''Host'' desactivan la función PHP para enviar correos electrónicos, por razones de seguridad ou saturación de los servidores (SPAM)";
		self::$trad["AGORA_fonction_image_desactive"]="Función de la manipulación de imágenes y miniaturas (PHP GD2) : desactivada !";

		////	MODULE_LOG
		////
		self::$trad["LOG_headerModuleName"]="Logs";
		self::$trad["LOG_description_module"]="Logs - Registro de eventos";
		self::$trad["LOG_filtre"]="filtro";
		self::$trad["LOG_date_heure"]="Fecha / Hora";
		self::$trad["LOG_espace"]="Espacio";
		self::$trad["LOG_module"]="Módulo";
		self::$trad["LOG_objectType"]="typo de objeto";
		self::$trad["LOG_action"]="Acción";
		self::$trad["LOG_utilisateur"]="Usuario";
		self::$trad["LOG_ipAdress"]="IP";
		self::$trad["LOG_comment"]="Comentario";
		self::$trad["LOG_no_logs"]="Ningún registro";
		self::$trad["LOG_filtre_a_partir"]="filtrado de la";
		self::$trad["LOG_chercher"]="Buscar";
		self::$trad["LOG_connexion"]="Conexión";
		self::$trad["LOG_deconnexion"]="logout";
		self::$trad["LOG_consult"]="consulta";
		self::$trad["LOG_consult2"]="descarga";
		self::$trad["LOG_add"]="Añadir";
		self::$trad["LOG_delete"]="eliminar";
		self::$trad["LOG_modif"]="cambio";

		////	MODULE_ESPACE
		////
		// Menu principal
		self::$trad["SPACE_headerModuleName"]="Espacios";
		self::$trad["SPACE_description_module"]="Espacios del sitio";
		self::$trad["SPACE_description_module_infos"]="El sitio (o el espacio principal) puede ser subdivisado en varios espacios";
		// Header_menu.inc.php
		self::$trad["SPACE_gerer_espaces"]="Gestión de los spacios del sitio";
		self::$trad["SPACE_parametrage"]="Administración del espacio";
		self::$trad["SPACE_parametrage_infos"]="Administración del espacio (descripción, los módulos, los usuarios, etc)";
		// Index.php
		self::$trad["SPACE_confirm_suppr_espace"]="Confirmar eliminación ? Atención, los datos afectados a este espacio seran  definitivamente perdidas !!";
		self::$trad["SPACE_espace"]="Espacio";
		self::$trad["SPACE_espaces"]="Espacios";
		self::$trad["SPACE_definir_acces"]="Definir !";
		self::$trad["SPACE_modules"]="Módulos";
		self::$trad["SPACE_ajouter_espace"]="Añadir un espacio";
		self::$trad["SPACE_supprimer_espace"]="Eliminar el espacio?";
		self::$trad["MSG_NOTIF_suppr_espace_impossible"]="No se puede eliminar el espacio actual";
		// Espace_edit.php
		self::$trad["SPACE_gestion_acces"]="Usuarios asignados al espacio";
		self::$trad["SPACE_selectionner_module"]="Debe seleccionar al menos un módulo";
		self::$trad["SPACE_modules_espace"]="Módulos del espacio";
		self::$trad["SPACE_modules_rank"]="Mover a establecer el orden de presentación de los módulos";
		self::$trad["SPACE_espace_public"]="Espacio Público";
		self::$trad["SPACE_public_infos"]="Proporciona acceso a las personas que no tienen cuentas en el sitio : invitados. Capacidad de especificar una contraseña para proteger el acceso.";
		self::$trad["SPACE_usersInvitation"]="Los usuarios pueden enviar invitaciones por correo";
		self::$trad["SPACE_usersInvitation_infos"]="Todos los usuarios pueden enviar invitaciones por correo electrónico para unirse al espacio";
		self::$trad["SPACE_allUsers"]="Todos los usuarios del sitio";
		self::$trad["SPACE_utilisation"]=" Usuarios";
		self::$trad["SPACE_utilisation_info"]="Usuario del espacio : <br> Acceso normal al espacio";
		self::$trad["SPACE_administration"]="Administrador";
		self::$trad["SPACE_administration_info"]="Administrador del espacio : ecceso en escritura a todos los elementos del espacio + posibilidad de enviar invitaciones por correo electrónico + añadir nuevos usuarios";
		self::$trad["SPACE_creer_agenda_espace"]="Crear un calendario compartido para el espacio";
		self::$trad["SPACE_creer_agenda_espace_info"]="El calendario tendrá el mismo nombre que el espacio. Puede ser útil si los calendarios de los usuarios están desactivados.";

		////	MODULE_UTILISATEUR
		////
		// Menu principal
		self::$trad["USER_headerModuleName"]="Usuarios";
		self::$trad["USER_description_module"]="Usuarios del espacio";
		self::$trad["USER_ajout_utilisateurs_groupe"]="Los usuarios también pueden crear grupos";
		// Index.php
		self::$trad["USER_utilisateurs_site"]="Usuarios del sitio";
		self::$trad["USER_gerer_utilisateurs_site"]="Gestión de todos los usuarios";
		self::$trad["USER_utilisateurs_site_infos"]="Todos los usuarios del sitio : todas las áreas combinadas";
		self::$trad["USER_utilisateurs_espace"]="Usuarios del espacio";
		self::$trad["USER_confirm_desaffecter_utilisateur"]="Confirmar la desasignación del usuario al espacio corriente ?";
		self::$trad["USER_suppr_definitivement"]="Eliminar definitivamente";
		self::$trad["USER_desaffecter"]="Desasignar del espacio";
		self::$trad["USER_tous_user_affecte_espace"]="Todo los usuarios del sitio son asignados a este espacio : no es possible desasignar";
		self::$trad["USER_user"]="Usuario";
		self::$trad["USER_users"]="usuarios";
		self::$trad["USER_affecter_utilisateur"]="Añadir un usuario existente, a ese espacio";
		self::$trad["USER_ajouter_utilisateur"]="Añadir un usuario";
		self::$trad["USER_ajouter_utilisateur_site"]="Crear un usuario en el sitio : por defecto, asignado a ningun espacio !";
		self::$trad["USER_ajouter_utilisateur_espace"]="Crear un usuario en el espacio actual";
		self::$trad["USER_envoi_coordonnees"]="Enviar el nombre de usuario y contraseña";
		self::$trad["USER_envoi_coordonnees_info"]="Enviar a usuarios (por correo electronico) sus nombre de usuario<br> y una nueva contraseña";
		self::$trad["USER_envoi_coordonnees_info2"]="Enviar a cada nuevo usuario <br> un correo electrónico con información de acceso.";
		self::$trad["USER_envoi_coordonnees_confirm"]="Atención : las contraseñas seran reinicializadas ! confirmar ?";
		self::$trad["USER_mail_coordonnees"]="Sus datos de acceso a su espacio";
		self::$trad["USER_aucun_utilisateur"]="Ningún usuario asignado a este espacio por el momento";
		self::$trad["USER_lastConnection"]="Última conexión";
		self::$trad["USER_liste_espaces"]="Espacios del usuario";
		self::$trad["USER_aucun_espace"]="Ningún espacio";
		self::$trad["USER_adminGeneral"]="Administrador General del Sitio";
		self::$trad["USER_adminSpace"]="Administrador del espacio";
		self::$trad["USER_userSpace"]="Usuario del espacio";
		self::$trad["USER_pas_connecte"]="No está conectado";
		self::$trad["USER_modifier"]="Editar usuario";
		self::$trad["USER_modifier_mon_profil"]="Editar mi perfil";
		// Invitation.php
		self::$trad["USER_envoi_invitation"]="Invitar alguien a unirse al espacio";
		self::$trad["USER_envoi_invitation_info"]="La invitacion sera enviada por correo electronico";
		self::$trad["USER_objet_mail_invitation"]="Invitación de "; // ..Jean DUPOND
		self::$trad["USER_admin_guest_espace"]="le invita a "; // Jean DUPOND "vous invite à rejoindre l'espace" Mon Espace
		self::$trad["USER_confirmer_invitation"]="Haga clic aquí para confirmar la invitación";
		self::$trad["USER_invitation_a_confirmer"]="Invitaciones a confirmar";
		self::$trad["USER_idInvitation_expire"]="La enlace de su invitación ha caducado";
		self::$trad["USER_invitationConfirmPassword"]="Elija su contraseña y clic 'OK' para confirmar su invitación";
		self::$trad["USER_invitation_valide"]="Su invitación ha sido validado !";
		// groupes.php
		self::$trad["USER_groupe_espace"]="grupos de usuarios del espacio";
		self::$trad["USER_groupe_info"]="modificar los grupos de usuarios";
		self::$trad["USER_droit_gestion_groupes"]="Cada grupo puede ser modificado por su autor o por el administrador general";
		// Utilisateur_affecter.php
		self::$trad["USER_preciser_recherche"]="Gracias a especificar un nombre, un apellido o una dirección de correo electrónico";
		self::$trad["USER_affecter_user_confirm"]="Confirmar las asignaciónes ?";
		self::$trad["USER_rechercher_user"]="Buscar usuarios para añadirlo al espacio";
		self::$trad["USER_tous_users_affectes"]="Todos los usuarios del sitio ya están asignados a este espacio";
		self::$trad["USER_affecter_user"]="Asignar usuarios al espacio :";
		self::$trad["USER_aucun_users_recherche"]="No hay usuarios para esta búsqueda";
		// Utilisateur_edit.php & CO
		self::$trad["USER_specifyName"]="Gracias especificar un appelido";
		self::$trad["USER_specifyFirstName"]="Gracias especificar un nombre";
		self::$trad["USER_specifier_identifiant"]="Gracias especificar un identificador";
		self::$trad["USER_specifier_password"]="Gracias especificar una contraseña";
		self::$trad["USER_specifyMailAsLogin"]="Se recomienda utilizar un email como identificador de sesión. ¿Todavía validar el formulario?";
		self::$trad["USER_langs"]="Idioma";
		self::$trad["USER_agenda_perso_desactive"]="Calendario personal desactivado";
		self::$trad["USER_agenda_perso_desactive_infos"]="Por defecto, el calendar personal esta siempre accessible al usuario, incluso si el módulo Agenda del espacio no está activado";
		self::$trad["USER_connectionSpace"]="Espacio de conexión";
		self::$trad["USER_notification_mail"]="Enviar una notificación por e-mail de la creación";
		self::$trad["USER_alert_notification_mail"]="Gracias especificar una dirección de correo electrónico !";
		self::$trad["USER_adresses_ip"]="Direcciónes IP de control";
		self::$trad["USER_info_ipAdress"]="Si se especifica una (o más) direcciones IP, el usuario sólo podra conectarse si utiliza las direcciones IP especificadas";
		self::$trad["USER_identifiant_deja_present"]="El identificador especificado ya existe. ¡ Gracias a especificar otro !";
		self::$trad["USER_mail_deja_present"]="El email ya existe. ¡ Gracias a especificar otro !";
		self::$trad["USER_mail_objet_nouvel_utilisateur"]="Nueva cuenta en ";  // "...sur" l'Agora machintruc
		self::$trad["USER_mail_nouvel_utilisateur"]="Su cuenta de usuario ha sido creada en";  // idem
		self::$trad["USER_mail_infos_connexion"]="Conectar con el login y la contraseña siguientes";
		self::$trad["USER_mail_infos_connexion2"]="Gracias a mantener este correo electrónico para sus archivos.";
		// Utilisateur_Messenger.php
		self::$trad["USER_gestion_messenger_livecounter"]="Gestión de mensajería instantánea";
		self::$trad["USER_visibilite_messenger_livecounter"]="Usuarios que podran verme en línea y hablar en la mensajería instantánea";
		self::$trad["USER_aucun_utilisateur_messenger"]="No hay usuarios por el momento";
		self::$trad["USER_voir_aucun_utilisateur"]="Mensajería desactivado (todos los usuarios no pueden verme)";
		self::$trad["USER_voir_tous_utilisateur"]="Todos los usuarios pueden verme";
		self::$trad["USER_voir_certains_utilisateur"]="Algunos usuarios pueden verme";

		////	MODULE_TABLEAU BORD
		////
		// Menu principal + options du module
		self::$trad["DASHBOARD_headerModuleName"]="Noticias";
		self::$trad["DASHBOARD_description_module"]="Noticias y novedades";
		self::$trad["DASHBOARD_ajout_actualite_admin"]="Sólo el administrador puede Añadir noticias";
		// Index.php
		self::$trad["DASHBOARD_new_elems"]="novedades";
		self::$trad["DASHBOARD_new_elems_info"]="Elementos creados entre <br>";
		self::$trad["DASHBOARD_new_elems_realises"]="corrientes";
		self::$trad["DASHBOARD_new_elems_realises_info"]="Eventos y tareas que tienen lugar entre <br>";
		self::$trad["DASHBOARD_plugin_connexion"]="desde mi última conexión";
		self::$trad["DASHBOARD_plugin_jour"]="Hoy";
		self::$trad["DASHBOARD_plugin_semaine"]="esta semana";
		self::$trad["DASHBOARD_plugin_mois"]="este mes";
		self::$trad["DASHBOARD_autre_periode"]="otro período";
		self::$trad["DASHBOARD_pas_nouveaux_elements"]="No hay elementos para el periodo seleccionado";
		self::$trad["DASHBOARD_actualites"]="Noticias";
		self::$trad["DASHBOARD_actualite"]="noticia";
		self::$trad["DASHBOARD_actualites"]="noticias";
		self::$trad["DASHBOARD_ajout_actualite"]="Añadir una noticia";
		self::$trad["DASHBOARD_actualites_offline"]="Noticias archivadas";
		self::$trad["DASHBOARD_pas_actualites"]="No hay noticias";
		// Actualite_edit.php
		self::$trad["DASHBOARD_ala_une"]="Mostrar en el frente";
		self::$trad["DASHBOARD_ala_une_info"]="Resalta esta noticia";
		self::$trad["DASHBOARD_offline"]="Archivado";
		self::$trad["DASHBOARD_offline_info"]="Archive esta noticia (Offline)";
		self::$trad["DASHBOARD_dateOnline"]="En línea el";
		self::$trad["DASHBOARD_dateOnline_info"]="Establecer una fecha de línea automático (en línea). La noticia será 'archivada' 'en el ínterin";
		self::$trad["DASHBOARD_dateOnline_alerte"]="La noticia esta archivado en la expectativa de su línea automática";
		self::$trad["DASHBOARD_dateOffline"]="Archivar el";
		self::$trad["DASHBOARD_dateOffline_info"]="Fije una fecha de archivo automático (Desconectado)";

		////	MODULE_AGENDA
		////
		// Menu principal
		self::$trad["CALENDAR_headerModuleName"]="Calendarios";
		self::$trad["CALENDAR_description_module"]="Calendarios personal y calendarios compartidos";
		self::$trad["CALENDAR_ajout_agenda_ressource_admin"]="Sólo el administrador puede añadir calendarios de recursos";
		self::$trad["CALENDAR_ajout_categorie_admin"]="Sólo el administrador puede añadir categorías de eventos";
		// Index.php
		self::$trad["CALENDAR_afficher_tous_agendas"]="Ver todo los calendarios";
		self::$trad["CALENDAR_masquer_tous_agendas"]="Ocultar todo los calendarios";
		self::$trad["CALENDAR_cocher_tous_agendas"]="comprobar/disparar todos los calendarios";
		self::$trad["CALENDAR_cocher_agendas_users"]="comprobar/disparar usuarios";
		self::$trad["CALENDAR_cocher_agendas_ressources"]="comprobar/disparar los recursos";
		self::$trad["CALENDAR_imprimer_agendas"]="Imprimir el/los calendarios";
		self::$trad["CALENDAR_imprimer_agendas_infos"]="imprimir la página en modo horizontal";
		self::$trad["CALENDAR_ajouter_agenda_ressource"]="Añadir un calendario compartido";
		self::$trad["CALENDAR_ajouter_agenda_ressource_bis"]="Añadir un calendario compartido : para reservar une habitación, vehiculo, vídeo, etc.";
		self::$trad["CALENDAR_exporter_ical"]="Exportar los eventos (formato iCal)";
		self::$trad["CALENDAR_exporter_ical_mail"]="Exportar los eventos por e-mail (iCal)";
		self::$trad["CALENDAR_exporter_ical_mail2"]="Para integrar en un calendario IPHONE, ANDROID, OUTLOOK, GOOGLE CALENDAR...";
		self::$trad["CALENDAR_importer_ical"]="Importar los eventos (iCal)";
		self::$trad["CALENDAR_importer_ical_etat"]="Estado";
		self::$trad["CALENDAR_importer_ical_deja_present"]="Ya está presente";
		self::$trad["CALENDAR_importer_ical_a_importer"]="a importar";
		self::$trad["CALENDAR_suppr_anciens_evt"]="Eliminar los eventos pasados";
		self::$trad["CALENDAR_suppr_anciens_evt_info"]="Eliminar los eventos que preceden hoy";
		self::$trad["CALENDAR_confirm_suppr_anciens_evt"]="Eliminar permanentemente los eventos que preceden hoy ?";
		self::$trad["CALENDAR_ajouter_evt_heure"]="Añadir un evento a";
		self::$trad["CALENDAR_ajouter_evt_jour"]="Añadir un evento a esa fecha";
		self::$trad["CALENDAR_evt_jour"]="Día";
		self::$trad["CALENDAR_evt_semaine"]="Semana";
		self::$trad["CALENDAR_evt_semaine_w"]="Semana de trabajo";
		self::$trad["CALENDAR_evt_mois"]="Mes";
		self::$trad["CALENDAR_voir_num_semaine"]="Ver la semana n°"; //...5
		self::$trad["CALENDAR_periode_suivante"]="Período siguiente";
		self::$trad["CALENDAR_periode_precedante"]="Período anterior";
		self::$trad["CALENDAR_affectations_evt"]="Evento en el calendario de ";
		self::$trad["CALENDAR_affectationUnconfirmed"]="Pendiente de confirmación : ";
		self::$trad["CALENDAR_evenements_proposes_pour_agenda"]="Eventos propuestos para"; // "Videoprojecteur" / "salle de réunion" / etc.
		self::$trad["CALENDAR_evenements_proposes_mon_agenda"]="Eventos propuestos para mi calendario";
		self::$trad["CALENDAR_evenement_propose_par"]="Propuestos por";  // "Proposé par" M. Bidule
		self::$trad["CALENDAR_evenement_integrer"]="Integrar el evento al calendario ?";
		self::$trad["CALENDAR_evenement_pas_integrer"]="Eliminar el evento propuesto ?";
		self::$trad["CALENDAR_supprimer_evt_agenda"]="Eliminar en ese calendario ?";
		self::$trad["CALENDAR_supprimer_evt_agendas"]="Eliminar en todos los calendarios ?";
		self::$trad["CALENDAR_supprimer_evt_date"]="Eliminar sólo en esta fecha ?";
		self::$trad["CALENDAR_evt_prive"]="Évento privado";
		self::$trad["CALENDAR_evt_proprio"]="Eventos que he creado";
		self::$trad["CALENDAR_aucun_evt"]="No hay eventos";
		self::$trad["CALENDAR_proposer"]="Envíar un evento";
		self::$trad["CALENDAR_synthese"]="Síntesis de los calendarios";
		self::$trad["CALENDAR_pourcent_agendas_occupes"]="Calendarios ocupados";  // Agendas occupés : 2/5
		self::$trad["CALENDAR_aucun_agenda_visible"]="No calendario";
		// Evenement.php
		self::$trad["CALENDAR_categorie"]="Categoría";
		self::$trad["CALENDAR_visibilite"]="Visibilidad";
		self::$trad["CALENDAR_visibilite_public"]="público";
		self::$trad["CALENDAR_visibilite_public_cache"]="público, pero ocultados detalles";
		self::$trad["CALENDAR_visibilite_prive"]="privado";
		//  Agenda_edit.php
		self::$trad["CALENDAR_affichage_evt"]="Mostrar eventos";
		self::$trad["CALENDAR_affichage_evt_border"]="Frontera con el color de la categoría";
		self::$trad["CALENDAR_affichage_evt_background"]="Fondo con el color de la categoría";
		self::$trad["CALENDAR_timeSlot"]="Banda horaria";
		// Evenement_edit.php
		self::$trad["CALENDAR_periodicite"]="Evento périodico";
		self::$trad["CALENDAR_period_weekDay"]="Cada semana";
		self::$trad["CALENDAR_period_monthDay"]="Dia del mes";
		self::$trad["CALENDAR_period_month"]="Cada mes";
		self::$trad["CALENDAR_period_month_xdumois"]="del mes"; // Le 21 du mois
		self::$trad["CALENDAR_period_year"]="Cada año";
		self::$trad["CALENDAR_periodDateEnd"]="Fin de periodicidad";
		self::$trad["CALENDAR_exception_periodicite"]="Excepción de periodicidad";
		self::$trad["CALENDAR_agendas_affectations"]="Asignación a los calendarios";
		self::$trad["CALENDAR_verif_nb_agendas"]="Gracias por seleccionar por lo menos un calendario";
		self::$trad["CALENDAR_input_proposer"]="Proponer el evento al propietario del calendario";
		self::$trad["CALENDAR_input_affecter"]="Añadir el evento al calendario";
		self::$trad["CALENDAR_info_proposer"]="Proponer el evento (no tiene acceso de escritura a este calendario)";
		self::$trad["CALENDAR_info_pas_modif"]="Edición prohibida porque no tiene acceso de escritura al calendario";
		self::$trad["CALENDAR_visibilite_info"]="<u>Pública</u> : visibilidad normale.<br><br><u>Público, pero los detalles ocultados</u> : acceso en lectura sólo al calendario, solo muestra el período de tiempo del evento.<br><br><u>Privado</u> : visible sólo si se puede editar Evento.";
		self::$trad["CALENDAR_edit_limite"]="Usted no es el autor de el evento : sólo puedes editar las asignaciones a sus calendarios";
		self::$trad["CALENDAR_creneau_occupe"]="La ranura ya está ocupado en este calendario :";
		// Categories.php
		self::$trad["CALENDAR_gerer_categories"]="Administrar las categorías de eventos";
		self::$trad["CALENDAR_categories_evt"]="Categorías de eventos";
		self::$trad["CALENDAR_droit_gestion_categories"]="Cada categoría puede ser modificado por su autor o por el administrador general";

		////	MODULE_FICHIER
		////
		// Menu principal
		self::$trad["FILE_headerModuleName"]="Archivos";
		self::$trad["FILE_description_module"]="Administración de Archivos";
		self::$trad["FILE_AdminRootFolderAddContent"]="Sólo el administrador puede añadir elementos en el directorio raíz";
		// Index.php
		self::$trad["FILE_ajouter_fichier"]="Añadir archivos";
		self::$trad["FILE_ajouter_fichier_alert"]="Los directorios del servidor no son accesible en escritura !  gracias de contactar el administrador";
		self::$trad["FILE_telecharger_selection"]="Descargar selección";
		self::$trad["FILE_nb_versions_fichier"]="Archivo versiones"; // n versions du fichier
		self::$trad["FILE_ajouter_versions_fichier"]="Añadir nueva versión del archivo";
		self::$trad["FILE_aucun_fichier"]="No hay archivo en este momento";
		// fichier_edit_ajouter.php  &  Fichier_edit.php
		self::$trad["FILE_limite_chaque_fichier"]="Los archivos no deben exceder"; // ...2 Mega Octets
		self::$trad["FILE_optimiser_images"]="Limite el tamaño a "; // ..1024*768 pixels
		self::$trad["FILE_updatedName"]="El nombre del archivo será reemplazado por la nueva versión";
		self::$trad["FILE_erreur_taille_fichier"]="Archivo demasiado grande";
		self::$trad["FILE_ajout_multiple_info"]="Pulse 'Maj' o 'Ctrl' para seleccionar varios archivos";
		self::$trad["FILE_selectionner_fichier"]="Gracias por elegir al menos un archivo";
		self::$trad["FILE_contenu"]="contenido";
		// Versions_fichier.php
		self::$trad["FILE_versions_de"]="Versiones de"; // versions de fichier.gif
		self::$trad["FILE_confirmer_suppression_version"]="Confirme la eliminación de esta versión ?";

		////	MODULE_FORUM
		////
		// Menu principal
		self::$trad["FORUM_headerModuleName"]="Foro";
		self::$trad["FORUM_description_module"]="Foro";
		self::$trad["FORUM_ajout_sujet_admin"]="Sólo el administrador puede añadir sujetos";
		self::$trad["FORUM_ajout_sujet_theme"]="Los usuarios también pueden añadir temas";
		// TRI
		self::$trad["SORT_dateLastMessage"]="último mensaje";
		// Index.php & Sujet.php
		self::$trad["FORUM_sujet"]="sujeto";
		self::$trad["FORUM_sujets"]="sujetos";
		self::$trad["FORUM_message"]="mensaje";
		self::$trad["FORUM_messages"]="mensajes";
		self::$trad["FORUM_dernier_message"]="último de";
		self::$trad["FORUM_ajouter_sujet"]="añadir un sujeto";
		self::$trad["FORUM_voir_sujet"]="Ver el sujeto";
		self::$trad["FORUM_ajouter_message"]="añadir un mensaje";
		self::$trad["FORUM_repondre"]="Responder";
		self::$trad["FORUM_citer"]="Citar";
		self::$trad["FORUM_message_repondre"]="Responder a ese mensaje";
		self::$trad["FORUM_message_citer"]="Responder y citar a ese mensaje";
		self::$trad["FORUM_aucun_sujet"]="No sujeto por el momento";
		self::$trad["FORUM_aucun_message"]="No mensaje";
		self::$trad["FORUM_confirme_suppr_message"]="Confirmar la eliminación de mensajes (y sub-mensajes asociados) ?";
		self::$trad["FORUM_notifier_dernier_message"]="Notificar por e-mail";
		self::$trad["FORUM_notifier_dernier_message_info"]="Deseo recibir una notificación por correo a cada nuevo mensaje";
		// Sujet_edit.php  &  Message_edit.php
		self::$trad["FORUM_accessRightInfos"]="Para participar al sujeto, debe tener al minimo un ''acceso limitado de escritura''";
		self::$trad["FORUM_theme_espaces"]="El tema está disponible en los espacios";
		// Themes
		self::$trad["FORUM_theme_sujet"]="Temas";
		self::$trad["FORUM_accueil_forum"]="Index del foro";
		self::$trad["FORUM_sans_theme"]="Sin tema";
		self::$trad["FORUM_themes_gestion"]="Gestión de los temas";
		self::$trad["FORUM_droit_gestion_themes"]="Cada tema puede ser modificado por su autor o por el administrador general";

		////	MODULE_TACHE
		////
		// Menu principal
		self::$trad["TASK_headerModuleName"]="Tareas";
		self::$trad["TASK_description_module"]="Tareas";
		self::$trad["TASK_AdminRootFolderAddContent"]="Sólo el administrador puede añadir elementos en el directorio raíz";
		// TRI
		self::$trad["SORT_priority"]="Prioridad";
		self::$trad["SORT_advancement"]="Progreso";
		self::$trad["SORT_dateBegin"]="Fecha de inicio";
		self::$trad["SORT_dateEnd"]="Fecha de fin";
		// Index.php
		self::$trad["TASK_ajouter_tache"]="Añadir una tareas";
		self::$trad["TASK_aucune_tache"]="No hay tarea por el momento";
		self::$trad["TASK_advancement"]="Progreso";
		self::$trad["TASK_advancementAverage"]="Progreso promedio";
		self::$trad["TASK_priority"]="Prioridad";
		self::$trad["TASK_priority1"]="Baja";
		self::$trad["TASK_priority2"]="promedia";
		self::$trad["TASK_priority3"]="alta";
		self::$trad["TASK_priority4"]="Crítica";
		self::$trad["TASK_responsiblePersons"]="Responsables";
		self::$trad["TASK_budgetAvailable"]="Presupuesto disponible";
		self::$trad["TASK_budgetAvailable_total"]="Presupuesto disponible total";
		self::$trad["TASK_budgetEngaged"]="Presupuesto comprometido";
		self::$trad["TASK_budgetEngaged_total"]="Presupuesto comprometido total";
		self::$trad["TASK_humanDayCharge"]="Carga dia/hombre";
		self::$trad["TASK_humanDayCharge_info"]="Número de días de trabajo necesarios para una persona para realizar esta tarea";
		self::$trad["TASK_advancement_retard"]="Progreso retrasado";
		self::$trad["TASK_budgetExceeded"]="Presupuesto excedido";

		////	MODULE_CONTACT
		////
		// Menu principal
		self::$trad["CONTACT_headerModuleName"]="Contactos";
		self::$trad["CONTACT_description_module"]="Directorio de contactos";
		self::$trad["CONTACT_AdminRootFolderAddContent"]="Sólo el administrador puede añadir elementos en el directorio raíz";
		// Index.php
		self::$trad["CONTACT_ajouter_contact"]="Añadir un contacto";
		self::$trad["CONTACT_aucun_contact"]="No hay contacto todavía";
		self::$trad["CONTACT_creer_user"]="Crear un usuario en este espacio";
		self::$trad["CONTACT_creer_user_infos"]="Crear un usuario en este espacio con este contacto ?";
		self::$trad["CONTACT_creer_user_confirm"]="El usuario fue creado";

		////	MODULE_LIEN
		////
		// Menu principal
		self::$trad["LINK_headerModuleName"]="Favoritos";
		self::$trad["LINK_description_module"]="Favoritos";
		self::$trad["LINK_AdminRootFolderAddContent"]="Sólo el administrador puede añadir elementos en el directorio raíz";
		// Index.php
		self::$trad["LINK_ajouter_lien"]="Añadir un enlace";
		self::$trad["LINK_aucun_lien"]="No hay enlaces por el momento";
		// lien_edit.php & dossier_edit.php
		self::$trad["LINK_adress"]="Dirección web";

		////	MODULE_MAIL
		////
		// Menu principal
		self::$trad["MAIL_headerModuleName"]="Mailing";
		self::$trad["MAIL_description_module"]="Enviar mensajes de correo electrónico con un solo clic !";
		// Index.php
		self::$trad["MAIL_specifier_mail"]="Gracias especificar al menos un destinatario";
		self::$trad["MAIL_title"]="Título del correo electrónico";
		self::$trad["MAIL_fichier_joint"]="Archivo adjunto";
		// Historique Mail
		self::$trad["MAIL_historique_mail"]="Historia de los correos electrónicos enviados";
		self::$trad["MAIL_aucun_mail"]="No correo electrónico";
		self::$trad["MAIL_envoye_par"]="Correo electrónico enviado por";
		self::$trad["MAIL_recipients"]="Destinatarios";
	}

	/*
	 * Jours Fériés de l'année
	 */
	public static function celebrationDays($year)
	{
		// Init
		$dateList=array();

		//Fêtes mobiles (si la fonction de récup' de paques existe)
		if(function_exists("easter_date"))
		{
			$daySecondes=86400;
			$paquesTime=easter_date($year);
			$date=date("Y-m-d", $paquesTime+$daySecondes);
			$dateList[$date]="Lunes de Pascua";
		}

		//Fêtes fixes	$dateList[$year."-01-01"]="Día de Año Nuevo";
		$dateList[$year."-01-06"]="Epifanía";
		$dateList[$year."-05-01"]="Día del Trabajo";
		$dateList[$year."-08-15"]="Asunción";
		$dateList[$year."-10-12"]="Día de la Hispanidad";
		$dateList[$year."-11-01"]="Toussaint";
		$dateList[$year."-12-06"]="Día de la Constitución";
		$dateList[$year."-12-08"]="Inmaculada Concepción";
		$dateList[$year."-12-25"]="Navidad";

		//Retourne le résultat
		return $dateList;
	}
}