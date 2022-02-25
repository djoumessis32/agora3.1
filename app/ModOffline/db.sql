CREATE TABLE ap_dashboardNews (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  description text,
  une tinyint(4) unsigned DEFAULT NULL,
  offline tinyint(4) unsigned DEFAULT NULL,
  dateOnline datetime DEFAULT NULL,
  dateOffline datetime DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_calendar (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  description text,
  evtColorDisplay varchar(255) DEFAULT NULL,
  timeSlot varchar(255) DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_calendarEventCategory (
  _id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  _idSpaces text,
  title varchar(255) DEFAULT NULL,
  description text,
  color varchar(255) DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_calendarEvent (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  description text,
  dateBegin datetime DEFAULT NULL,
  dateEnd datetime DEFAULT NULL,
  _idCat smallint(5) unsigned DEFAULT NULL,
  important tinyint(4) unsigned DEFAULT NULL,
  contentVisible varchar(255) DEFAULT NULL,
  periodType varchar(255) DEFAULT NULL,
  periodValues varchar(1000) DEFAULT NULL,
  periodDateEnd date DEFAULT NULL,
  periodDateExceptions text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idCat,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_calendarEventAffectation (
  _idEvt mediumint(8) unsigned NOT NULL,
  _idCal mediumint(8) unsigned NOT NULL,
  confirmed tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (_idEvt,_idCal),
  KEY `indexes` (_idEvt,_idCal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_agora (
  `name` varchar(255) DEFAULT NULL,
  description text,
  lang varchar(255) DEFAULT NULL,
  timezone varchar(255) DEFAULT NULL,
  wallpaper varchar(255) DEFAULT NULL,
  logo varchar(255) DEFAULT NULL,
  logoUrl varchar(255) DEFAULT NULL,
  dateUpdateDb date DEFAULT NULL,
  version_agora varchar(255) DEFAULT NULL,
  skin varchar(255) DEFAULT NULL,
  footerHtml text,
  messengerDisabled tinyint(4) unsigned DEFAULT NULL,
  personalCalendarsDisabled tinyint(4) unsigned DEFAULT NULL,
  moduleLabelDisplay varchar(255) DEFAULT NULL,
  personsSort varchar(255) DEFAULT NULL,
  logsTimeOut smallint(6) DEFAULT NULL,
  ldap_server varchar(255) DEFAULT NULL,
  ldap_server_port varchar(255) DEFAULT NULL,
  ldap_admin_login varchar(255) DEFAULT NULL,
  ldap_admin_pass varchar(255) DEFAULT NULL,
  ldap_base_dn varchar(255) DEFAULT NULL,
  ldap_crea_auto_users tinyint(4) unsigned DEFAULT NULL,
  ldap_pass_cryptage varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_contact (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  civility varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  firstName varchar(255) DEFAULT NULL,
  picture varchar(255) DEFAULT NULL,
  companyOrganization text,
  `function` text,
  adress text,
  postalCode varchar(255) DEFAULT NULL,
  city varchar(255) DEFAULT NULL,
  country varchar(255) DEFAULT NULL,
  telephone varchar(255) DEFAULT NULL,
  telmobile varchar(255) DEFAULT NULL,
  fax varchar(255) DEFAULT NULL,
  mail text,
  website text,
  skills text,
  hobbies text,
  `comment` text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_contactFolder (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  description text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_space (
  _id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  description text,
  public tinyint(4) unsigned DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  usersInscription tinyint(4) unsigned DEFAULT NULL,
  usersInvitation tinyint(4) unsigned DEFAULT NULL,
  wallpaper varchar(255) DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_file (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  description text,
  octetSize int(11) DEFAULT NULL,
  downloadsNb int(10) unsigned NOT NULL DEFAULT '0',
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_fileFolder (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  description text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_fileVersion (
  _idFile mediumint(8) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  realName text,
  octetSize int(10) unsigned DEFAULT NULL,
  description text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  KEY `indexes` (_idFile,_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_forumMessage (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idMessageParent int(10) unsigned DEFAULT NULL,
  _idContainer mediumint(8) unsigned DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  description text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idMessageParent,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_forumSubject (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  description text,
  _idTheme smallint(6) DEFAULT NULL,
  dateLastMessage datetime DEFAULT NULL,
  usersConsultLastMessage varchar(1000) DEFAULT NULL,
  usersNotifyLastMessage varchar(1000) DEFAULT NULL,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idTheme,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_forumTheme (
  _id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  _idSpaces text,
  title varchar(255) DEFAULT NULL,
  description text,
  color varchar(255) DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_mailHistory (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  recipients text,
  title text,
  description text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_invitation (
  _idInvitation varchar(255) DEFAULT NULL,
  _idSpace smallint(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  firstName varchar(255) DEFAULT NULL,
  mail varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  KEY `indexes` (_idInvitation,_idSpace,_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_joinSpaceModule (
  _idSpace smallint(5) unsigned DEFAULT NULL,
  moduleName varchar(255) DEFAULT NULL,
  rank tinyint(4) unsigned DEFAULT NULL,
  `options` text,
  KEY `indexes` (_idSpace)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_joinSpaceUser (
  _idSpace smallint(5) unsigned DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  allUsers tinyint(3) unsigned DEFAULT NULL,
  accessRight varchar(255) DEFAULT NULL,
  KEY `indexes` (_idSpace,_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_userMessenger (
  _idUserMessenger mediumint(8) unsigned DEFAULT NULL,
  allUsers tinyint(3) unsigned DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  KEY `indexes` (_idUserMessenger,_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_objectTarget (
  objectType varchar(255) DEFAULT NULL,
  _idObject mediumint(8) unsigned DEFAULT NULL,
  _idSpace smallint(5) unsigned DEFAULT NULL,
  target varchar(255) DEFAULT NULL,
  accessRight float unsigned DEFAULT NULL,
  KEY `indexes` (_idObject,_idSpace)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_objectAttachedFile (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  objectType varchar(255) DEFAULT NULL,
  _idObject mediumint(8) unsigned DEFAULT NULL,
  downloadsNb int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idObject)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_link (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  adress text,
  description text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_linkFolder (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  description text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_log (
  `action` varchar(50) DEFAULT NULL,
  moduleName varchar(50) DEFAULT NULL,
  objectType varchar(50) DEFAULT NULL,
  _idObject mediumint(8) unsigned DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  _idSpace smallint(5) unsigned DEFAULT NULL,
  ip varchar(100) DEFAULT NULL,
  `comment` varchar(300) DEFAULT NULL,
  KEY `indexes` (_idObject,_idUser,_idSpace)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_task (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  title text,
  description text,
  priority varchar(255) DEFAULT NULL,
  advancement tinyint(4) unsigned DEFAULT NULL,
  humanDayCharge float DEFAULT NULL,
  budgetAvailable int(10) unsigned DEFAULT NULL,
  budgetEngaged int(10) unsigned DEFAULT NULL,
  responsiblePersons text,
  dateBegin datetime DEFAULT NULL,
  dateEnd datetime DEFAULT NULL,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_taskFolder (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idContainer mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  description text,
  shortcut tinyint(4) unsigned DEFAULT NULL,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  guest varchar(255) DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idContainer,_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_user (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  civility varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  firstName varchar(255) DEFAULT NULL,
  login varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  picture varchar(255) DEFAULT NULL,
  adress text,
  postalCode varchar(255) DEFAULT NULL,
  city varchar(255) DEFAULT NULL,
  country varchar(255) DEFAULT NULL,
  telephone varchar(255) DEFAULT NULL,
  telmobile varchar(255) DEFAULT NULL,
  fax varchar(255) DEFAULT NULL,
  mail text,
  website text,
  skills text,
  hobbies text,
  `function` text,
  companyOrganization text,
  `comment` text,
  lastConnection int(10) unsigned DEFAULT NULL,
  previousConnection int(10) unsigned DEFAULT NULL,
  generalAdmin tinyint(4) unsigned DEFAULT NULL,
  lang varchar(255) DEFAULT NULL,
  connectionSpace varchar(255) DEFAULT NULL,
  calendarDisabled tinyint(4) unsigned DEFAULT NULL,
  _idNewPassword varchar(255) DEFAULT NULL,
  ipControlAdresses text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idNewPassword)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_userGroup (
  _id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255),
  _idSpace mediumint(8) unsigned,
  _idUsers text,
  dateCrea datetime DEFAULT NULL,
  _idUser mediumint(8) unsigned DEFAULT NULL,
  dateModif datetime DEFAULT NULL,
  _idUserModif mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idSpace,_idUsers(1000),_idUser)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ap_userInscription (
  _id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  _idSpace smallint(5) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  firstName varchar(255) DEFAULT NULL,
  mail varchar(255) DEFAULT NULL,
  login varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  message text,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (_id),
  KEY `indexes` (_id,_idSpace)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_userLivecouter (
  _idUser mediumint(8) unsigned DEFAULT NULL,
  ipAdress varchar(255) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  KEY `indexes` (_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_userMessengerMessage (
  _idUser mediumint(8) unsigned DEFAULT NULL,
  _idUsers text,
  message text,
  color varchar(255) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  KEY `indexes` (_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ap_userPreference (
  _idUser mediumint(8) unsigned DEFAULT NULL,
  keyVal varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  KEY `indexes` (_idUser)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
