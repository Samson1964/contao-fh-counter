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

$GLOBALS['FE_MOD']['fhcounter'] = array
(
	'fhcounter_register' => 'CounterRegister',
	'fhcounter_view' => 'CounterFrontend',
); 

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('CounterTag', 'fhcounter');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('CounterTag', 'fhcounter_view');

