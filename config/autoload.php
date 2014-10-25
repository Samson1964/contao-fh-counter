<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Fh-counter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'CounterFrontend' => 'system/modules/fh-counter/classes/CounterFrontend.php',
	'CounterRegister' => 'system/modules/fh-counter/classes/CounterRegister.php',
	'CounterTag'      => 'system/modules/fh-counter/classes/CounterTag.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'fhcounter_full'      => 'system/modules/fh-counter/templates',
	'fhcounter_mini'      => 'system/modules/fh-counter/templates',
	'fhcounter_standard'  => 'system/modules/fh-counter/templates',
	'fhcounter_diagramme' => 'system/modules/fh-counter/templates',
	'be_fhcounter'        => 'system/modules/fh-counter/templates',
));
