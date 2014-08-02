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
 * Class CounterRegister
 *
 * @copyright  Frank Hoppe 2014
 * @author     Frank Hoppe
 *
 * Basisklasse vom FH-Counter
 * Erledigt die Zählung der jeweiligen Contenttypen und schreibt die Zählerwerte in $GLOBALS
 */
class CounterRegister extends \Module
{

	// Zeitwerte anlegen
	var $zeit;
	var $jahr;
	var $monat;
	var $tag;
	var $stunde;

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_fhcounter');

			$objTemplate->wildcard = '### FH-COUNTER ZÄHLERMODUL ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		return parent::generate(); // Weitermachen mit dem Modul
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->ip = $_SERVER['REMOTE_ADDR']; // IP-Adresse des aktuellen Benutzers

		// Aktuelle Zeitwerte zuweisen
		$this->zeit = time();
		$this->jahr = date("Y",$this->zeit); // Jahr vierstellig
		$this->monat = date("n",$this->zeit); // Monat einstellig
		$this->tag = date("j",$this->zeit); // Tag einstellig
		$this->stunde = date("G",$this->zeit); // Stunde einstellig
		// Gestern-Zeitwerte zuweisen
		$gestern = $this->zeit - 86400;
		$gjahr = date("Y",$gestern); // Jahr vierstellig
		$gmonat = date("n",$gestern); // Monat einstellig
		$gtag = date("j",$gestern);	// Tag einstellig

		/*****************************************
		****** Zählung der Seite (tl_page) *******
		******************************************/
		$this->RegisterCounter($objPage->id, 'tl_page', $this->fhc_register_pages);

		/*****************************************
		*** Zählung des Artikels (tl_article) ****
		******************************************/
		$id_article = Input::get('articles');
		$objArticleModel = \ArticleModel::findByIdOrAlias($id_article);
		$id_article = $objArticleModel->id;
		if($id_article) $this->RegisterCounter($id_article, 'tl_article', $this->fhc_register_articles);

		/*****************************************
		*** Zählung der Nachricht (tl_news) ******
		******************************************/

		$objNews = $this->Database->prepare('SELECT * FROM tl_news_archive')->execute();
		$nachrichtenleser = array();
		while($objNews->next())
		{
			$nachrichtenleser[] = $objNews->jumpTo;
		}
		// Ist die aktuelle Seiten-ID ein Nachrichtenleser?
		if(in_array($objPage->id,$nachrichtenleser))
		{
			// Ja! Jetzt URL abfragen nach Alias
			// Einfaches Regex: Was steht zwischen Alias und Suffix?
			$urlsuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];
			$uri = $_SERVER['REQUEST_URI']; // URI laden
			$newsalias = '';
			if(substr($uri,-strlen($urlsuffix)) == $urlsuffix)
			{
				// Suffix steht am Ende der URI, dann News-Alias extrahieren
				$newsalias = substr($uri,strlen($objPage->alias)+2,-strlen($urlsuffix));
			}
			// ID für diese Nachricht holen
			if($newsalias)
			{
				$objNews = $this->Database->prepare('SELECT * FROM tl_news WHERE alias=?')->execute($newsalias);
				$this->RegisterCounter($objNews->id, 'tl_news', $this->fhc_register_news);
			}
		}

	}

	/**
	 * Funktion RegisterCounter
	 *
	 * @param $source_id: ID in Tabelle $source_name
	 * @param $source_name: tl_page, tl_articles oder tl_news
	 * @param $source_register: Zählen ja/nein
	 *
	 * @return: -
	 */
	protected function RegisterCounter($source_id, $source_name, $source_register)
	{
		// Zählwerk nur arbeiten lassen, wenn die ID nicht false oder 0 ist
		if($source_id)
		{
			// Zähler laden, wenn vorhanden
			$objZaehler = $this->Database->prepare('SELECT * FROM tl_fh_counter WHERE pid=? AND source=?')->execute($source_id, $source_name);
			if($objZaehler->id)
			{
				// Daten zuweisen
				$array_toponline = unserialize($objZaehler->toponline);
				$array_iparray = unserialize($objZaehler->iparray);
				$array_counter = unserialize($objZaehler->counter);
				$array_online = unserialize($objZaehler->online);
				$lastvisit = $objZaehler->tstamp;
				$lastcounting = $objZaehler->lastcounting;
				$starttime = $objZaehler->starttime;
				$lastip = $objZaehler->lastip;
			}
			else
			{
				// Keine Daten
				$array_toponline = array();
				$array_iparray = array();
				$array_counter = array();
				$array_online = array();
				$lastvisit = 0;
				$lastcounting = 0;
				$starttime = 0;
				$lastip = '';
			}

			// Zähler aktualisieren
			if($source_register)
			{
				/**********************************
				 Onlinestatus
				 **********************************/
				// Älteste Onlinezeit festlegen
				$onlinezeitende = $this->zeit - $this->fhc_onlinetime;
				// Besucher entfernen, deren Onlinezeit abgelaufen ist
				if($array_online)
				{
					foreach($array_online as $key => $value)
					{
						if($value < $onlinezeitende) unset($array_online[$key]);
					}
				}
				// Aktuellen Besucher aktualisieren/eintragen
				$array_online[$this->ip] = $this->zeit;

				/**********************************
				 Topliste der Onlinebesucher
				 **********************************/
				if(!$array_toponline) // Anlegen, wenn nicht vorhanden
				{
					$array_toponline['count'] = 0; // Topbesucherzahl
					$array_toponline['time'] = 0; // Datum/Zeit
					$array_toponline['onlinetime'] = $this->fhc_onlinetime; // Eingestellte Onlinezeit
				}
				if(count($array_online) > $array_toponline['count'])
				{
					// Neue Bestmarke eintragen
					$array_toponline['count'] = count($array_online);
					$array_toponline['time'] = $this->zeit;
					$array_toponline['onlinetime'] = $this->fhc_onlinetime;
				}

				 /**********************************
				 Zählstatus
				 **********************************/
				// Älteste Sperrzeit festlegen
				$sperrzeitende = $this->zeit - $this->fhc_registernewtime;
				// Besucher entfernen, deren Sperrzeit abgelaufen ist
				if($array_iparray)
				{
					foreach($array_iparray as $key => $value)
					{
						if($value < $sperrzeitende) unset($array_iparray[$key]);
					}
				}
				// Besucher bereits gezählt?
				($array_iparray[$this->ip]) ? $zaehlen = false : $zaehlen = true;
				// Aktuellen Besucher aktualisieren/eintragen
				$array_iparray[$this->ip] = $this->zeit;

				/**********************************
				 Zählwerk starten
				 **********************************/
				if($zaehlen)
				{
					/**********************************
					 Zähler aktualisieren
					 **********************************/
					$array_counter["all"]++;
					$array_counter[$this->jahr]["all"]++;
					$array_counter[$this->jahr][$this->monat]["all"]++;
					$array_counter[$this->jahr][$this->monat][$this->tag]["all"]++;
					$array_counter[$this->jahr][$this->monat][$this->tag][$this->stunde]++;

					/**********************************
					 Datenbanktabelle aktualisieren
					 **********************************/
					if($objZaehler->id)
					{
						// Zähler vorhanden
						$set = array('tstamp'       => $this->zeit,
									 'totalhits'    => $array_counter['all'],
									 'lastip'       => $this->ip,
									 'lastcounting' => $this->zeit,
									 'toponline'    => serialize($array_toponline),
									 'iparray'      => serialize($array_iparray),
									 'counter'      => serialize($array_counter),
									 'online'       => serialize($array_online)
									);
						$this->Database->prepare("UPDATE tl_fh_counter %s WHERE pid=? AND source=?")->set($set)->execute($source_id, $source_name);
						$lastvisit = $set['tstamp'];
						$lastcounting = $set['lastcounting'];
						$lastip = $set['lastip'];
					}
					else
					{
						// Neuen Zähler schreiben
						$set = array('tstamp'       => $this->zeit,
									 'starttime'    => $this->zeit,
									 'source'       => $source_name,
									 'pid'          => $source_id,
									 'totalhits'    => $array_counter['all'],
									 'lastip'       => $this->ip,
									 'lastcounting' => $this->zeit,
									 'toponline'    => serialize($array_toponline),
									 'iparray'      => serialize($array_iparray),
									 'counter'      => serialize($array_counter),
									 'online'       => serialize($array_online)
									);
						$this->Database->prepare("INSERT INTO tl_fh_counter %s")->set($set)->execute();
						$lastvisit = $set['tstamp'];
						$lastcounting = $set['lastcounting'];
						$starttime = $set['starttime'];
						$lastip = $set['lastip'];
					}
				}
				else
				{
					/**********************************
					 Datenbanktabelle aktualisieren
					 **********************************/
					if($objZaehler->id)
					{
						// Zähler vorhanden
						$set = array('tstamp'       => $this->zeit,
									 'lastip'       => $this->ip,
									 'toponline'    => serialize($array_toponline),
									 'online'       => serialize($array_online)
									);
						$this->Database->prepare("UPDATE tl_fh_counter %s WHERE pid=? AND source=?")->set($set)->execute($source_id, $source_name);
						$lastvisit = $set['tstamp'];
						$lastip = $set['lastip'];
					}
				}
			}

			// GLOBALS füllen
			$GLOBALS['fhcounter'][$source_name]['counting'] = $zaehlen;
			$GLOBALS['fhcounter'][$source_name]['tstamp'] = $lastvisit;
			$GLOBALS['fhcounter'][$source_name]['starttime'] = $starttime;
			$GLOBALS['fhcounter'][$source_name]['source'] = $source_name;
			$GLOBALS['fhcounter'][$source_name]['pid'] = $source_id;
			$GLOBALS['fhcounter'][$source_name]['totalhits'] = $array_counter['all'];
			$GLOBALS['fhcounter'][$source_name]['lastcounting'] = $lastcounting;
			$GLOBALS['fhcounter'][$source_name]['lastip'] = $lastip;
			$GLOBALS['fhcounter'][$source_name]['toponline'] = $array_toponline;
			$GLOBALS['fhcounter'][$source_name]['counter'] = $array_counter;
			$GLOBALS['fhcounter'][$source_name]['online'] = count($array_online);

			// Standardzähler in GLOBALS aktualisieren
			$GLOBALS['fhcounter']['default']['counting'] = $GLOBALS['fhcounter'][$source_name]['counting'];
			$GLOBALS['fhcounter']['default']['tstamp'] = $GLOBALS['fhcounter'][$source_name]['tstamp'];
			$GLOBALS['fhcounter']['default']['starttime'] = $GLOBALS['fhcounter'][$source_name]['starttime'];
			$GLOBALS['fhcounter']['default']['source'] = $GLOBALS['fhcounter'][$source_name]['source'];
			$GLOBALS['fhcounter']['default']['pid'] = $GLOBALS['fhcounter'][$source_name]['pid'];
			$GLOBALS['fhcounter']['default']['totalhits'] = $GLOBALS['fhcounter'][$source_name]['totalhits'];
			$GLOBALS['fhcounter']['default']['lastcounting'] = $GLOBALS['fhcounter'][$source_name]['lastcounting'];
			$GLOBALS['fhcounter']['default']['lastip'] = $GLOBALS['fhcounter'][$source_name]['lastip'];
			$GLOBALS['fhcounter']['default']['toponline'] = $GLOBALS['fhcounter'][$source_name]['toponline'];
			$GLOBALS['fhcounter']['default']['counter'] = $GLOBALS['fhcounter'][$source_name]['counter'];
			$GLOBALS['fhcounter']['default']['online'] = $GLOBALS['fhcounter'][$source_name]['online'];
		}
	}

}
