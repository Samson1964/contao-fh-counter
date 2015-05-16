<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   fh-counter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */


/**
 * Table tl_fh-counter
 */
$GLOBALS['TL_DCA']['tl_fh_counter'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id'     => 'primary',
				'pid'    => 'index',
				'source' => 'index'
			)
		)
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		// Zeitstempel, der bei jedem Schreibzugriff aktualisiert wird
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		// Erststart dieses Zählers (timestamp)
		'starttime' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		// Zählerquelle: tL_news, tl_page usw.
		'source' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		// ID der Zählerquelle
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		// Gesamtzugriffe dieses Zählers (spart die Dekodierung vom Feld counter)
		'totalhits' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		// Letzte IP-Adresse (spart die Dekodierung vom Feld iparray)
		'lastip' => array
		(
			'sql'                     => "varchar(50) NOT NULL default ''"
		),
		// Letzte Zählung
		'lastcounting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		// Topwert Besucher online (serialisiertes Array)
		'toponline' => array
		(
			'sql'                     => "text NULL"
		),
		// Letzte IP-Adressen (serialisiertes Array)
		'iparray' => array
		(
			'sql'                     => "text NULL"
		),
		// Zähler (serialisiertes Array)
		'counter' => array
		(
			'sql'                     => "mediumtext NULL"
		),
		// Besucher online (serialisiertes Array)
		'online' => array
		(
			'sql'                     => "text NULL"
		),
	)
);
