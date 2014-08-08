<?php
/**
 * Avatar for Contao Open Source CMS
 *
 * Copyright (C) 2013 Kirsten Roschanski
 * Copyright (C) 2013 Tristan Lins <http://bit3.de>
 *
 * @package    Avatar
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Add palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['fhcounter_register'] = '{title_legend},name,type;{counter_legend:hide},fhc_register_pages,fhc_register_articles,fhc_register_news,fhc_onlinetime,fhc_registernewtime,fhc_register_sessions,fhc_register_be_user;{expert_legend:hide},cssID,align,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['fhcounter_view'] = '{title_legend},name,headline,type;{info_legend:hide},fhc_view_pages,fhc_view_articles,fhc_view_news,fhc_infos_counter,fhc_infos_debug,fhc_view_diagrams,fhc_view_tables,fhc_1000_separator;{template_legend:hide},fhc_template;{protected_legend:hide},protected;{expert_legend:hide},cssID,align,space';

// Zählung tl_page ja/nein

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_register_pages'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_register_pages'],
	'inputType'     => 'checkbox',
	'default'       => true,
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Zählung tl_articles ja/nein

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_register_articles'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_register_articles'],
	'inputType'     => 'checkbox',
	'default'       => true,
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Zählung tl_news ja/nein

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_register_news'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_register_news'],
	'inputType'     => 'checkbox',
	'default'       => true,
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Anzahl Sekunden die ein Besucher als online gilt

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_onlinetime'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_onlinetime'],
	'inputType'     => 'text',
	'default'       => '120',
	'eval'          => array('rgxp'=>'digit', 'tl_class'=>'w50 clr'),
	'sql'           => "smallint(5) unsigned NOT NULL default '120'"
);

// Anzahl Sekunden nach der ein Besucher neu gezählt wird

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_registernewtime'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_registernewtime'],
	'inputType'     => 'text',
	'default'       => '900',
	'eval'          => array('rgxp'=>'digit', 'tl_class'=>'w50'),
	'sql'           => "smallint(5) unsigned NOT NULL default '900'"
);

// Trennzeichen bei Zahlen beim Tausender setzen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_1000_separator'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_1000_separator'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Informationen zum Zähler anzeigen, wie Zählerstart, Gesamtzugriffe, Besucher online

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_infos_counter'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_infos_counter'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50 clr','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Erweiterte Informationen zum Zähler anzeigen, wie das IP-Array

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_infos_debug'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_infos_debug'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Zähler tl_page bei der Ausgabe berücksichtigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_view_pages'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_view_pages'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Zähler tl_article bei der Ausgabe berücksichtigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_view_articles'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_view_articles'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Zähler tl_news bei der Ausgabe berücksichtigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_view_news'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_view_news'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Diagramme für den Default-Zähler anzeigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_view_diagrams'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_view_diagrams'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50 clr','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Tabellen für alle Zähler anzeigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_view_tables'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_view_tables'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Session-Cookies statt IP-Adressen benutzen?

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_register_sessions'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_register_sessions'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Eingeloggte BE-Benutzer berücksichtigen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_register_be_user'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['fhc_register_be_user'],
	'inputType'     => 'checkbox',
	'eval'          => array('tl_class' => 'w50','isBoolean' => true),
	'sql'           => "char(1) NOT NULL default ''",
);

// Template zuweisen

$GLOBALS['TL_DCA']['tl_module']['fields']['fhc_template'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['fhc_template'],
	'exclude'          => true,
	'inputType'        => 'select',
	'options_callback' => array('tl_module_fhcounter', 'getCounterTemplates'),
	'eval'             => array('tl_class'=>'w50'),
	'sql'              => "varchar(32) NOT NULL default ''"
);

/**
 * Class tl_module_fhcounter
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
 */
class tl_module_fhcounter extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return all event templates as array
	 * @return array
	 */
	public function getCounterTemplates()
	{
		return $this->getTemplateGroup('fhcounter_');
	}


}
