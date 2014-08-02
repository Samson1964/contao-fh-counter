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
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['counter_legend'] = 'Zähler-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['info_legend'] = 'Ausgabe-Einstellungen';

/**
 * Felder für Zählermodul
 */
$GLOBALS['TL_LANG']['tl_module']['fhc_register_pages'] = array('Seitenzugriffe zählen', 'Zugriffe auf Seiten (tl_page) zählen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_register_articles'] = array('Artikelzugriffe zählen', 'Zugriffe auf Artikel (tl_article) zählen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_register_news'] = array('Nachrichtenzugriffe zählen', 'Zugriffe auf Nachrichten (tl_news) zählen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_onlinetime'] = array('Onlinezeit in Sekunden', 'Der Besucher gilt nach dieser Zeit als nicht mehr online.');
$GLOBALS['TL_LANG']['tl_module']['fhc_registernewtime'] = array('Zählsperre in Sekunden', 'Der Besucher wird für diese Zeit gesperrt und nicht neu gezählt.');
$GLOBALS['TL_LANG']['tl_module']['fhc_register_sessions'] = array('Session-Cookie benutzen', 'Für die Identifizierung der Besucher Session-Cookies statt IP-Adressen benutzen.');

/**
 * Felder für Ausgabemodul
 */
$GLOBALS['TL_LANG']['tl_module']['fhc_infos_counter'] = array('Allgemeine Informationen', 'Informationen zu diesem Zähler anzeigen, wie z.B. Erstaufruf und Gesamtzugriffe.');
$GLOBALS['TL_LANG']['tl_module']['fhc_infos_debug'] = array('Debug-Informationen', 'Debug-Informationen zu diesem Zähler anzeigen, wie z.B. die Besucher-Adressen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_view_pages'] = array('Seitenzugriffe', 'Zugriffe auf Seiten anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_view_articles'] = array('Artikelzugriffe', 'Zugriffe auf Artikel anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_view_news'] = array('Nachrichtenzugriffe', 'Zugriffe auf Nachrichten anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_view_diagrams'] = array('Diagramme', 'Zugriffe auf den Standardzähler als Diagramme anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_view_tables'] = array('Tabellen', 'Zugriffe auf alle Zähler als Tabellen anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fhc_template'] = array('Template auswählen', 'Wählen Sie hier das zu benutzende Template.');
$GLOBALS['TL_LANG']['tl_module']['fhc_1000_separator'] = array('1000er Trennzeichen', 'Trennt die Tausender im Zähler mit einem Punkt.');
