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
 * Class CounterFrontend
 *
 * @copyright  Frank Hoppe 2014
 * @author     Frank Hoppe
 *
 * Ausgabeklasse vom FH-Counter
 * Bereitet die Zählerwerte in $GLOBALS für das Template auf
 */
class CounterFrontend extends \Module
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
			$objTemplate = new \FrontendTemplate('be_fhcounter');

			$objTemplate->wildcard = '### FH-COUNTER AUSGABEMODUL ###';
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
		//echo "<pre>";
		//print_r($GLOBALS["fhcounter"]);
		//echo "</pre>";

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
		// Vormonat ermitteln und in Array ablegen
		$vormonat = explode(".", date("Y.n", strtotime("last month", $this->zeit)));

		// Template-Objekt anlegen
		$this->Template = new \FrontendTemplate($this->fhc_template);

		// Ausgabeoptionen dem Template zuweisen
		$this->Template->ViewPages = $this->fhc_view_pages;
		$this->Template->ViewArticles = $this->fhc_view_articles;
		$this->Template->ViewNews = $this->fhc_view_news;
		($this->fhc_view_pages || $this->fhc_view_articles || $this->fhc_view_news) ? $this->Template->ViewDefault = true : $this->Template->ViewDefault = false;
		$this->Template->ViewCounterinfo = $this->fhc_infos_counter;
		$this->Template->ViewDebuginfo = $this->fhc_infos_debug;
		$this->Template->ViewDiagrams = $this->fhc_view_diagrams;
		$this->Template->ViewTables = $this->fhc_view_tables;

		// Zählerwerte tl_page in das Template schreiben
		if($GLOBALS['fhcounter']['tl_page'] && $this->fhc_view_pages)
		{
			// Zählerwerte zuweisen
			$this->Template->PageCounterAll = 0 + $GLOBALS['fhcounter']['tl_page']['counter']["all"];
			$this->Template->PageCounterThisYear = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$this->jahr]["all"];
			$this->Template->PageCounterThisMonth = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$this->jahr][$this->monat]["all"];
			$this->Template->PageCounterThisDay = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$this->jahr][$this->monat][$this->tag]["all"];
			$this->Template->PageCounterThisHour = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$this->jahr][$this->monat][$this->tag][$this->stunde];
			$this->Template->PageCounterYesterday = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$gjahr][$gmonat][$gtag]["all"];
			$this->Template->PageCounterLastMonth = 0 + $GLOBALS['fhcounter']['tl_page']['counter'][$vormonat[0]][$vormonat[1]]["all"];
			// Restliche Templatevariablen zuweisen
			$this->Template->PageCounterTstamp = $GLOBALS['fhcounter']['tl_page']['tstamp'];
			$this->Template->PageCounterStarttime = $GLOBALS['fhcounter']['tl_page']['starttime'];
			$this->Template->PageCounterSource = $GLOBALS['fhcounter']['tl_page']['source'];
			$this->Template->PageCounterPid = $GLOBALS['fhcounter']['tl_page']['pid'];
			$this->Template->PageCounterTotalhits = $GLOBALS['fhcounter']['tl_page']['totalhits'];
			$this->Template->PageCounterLastip = $GLOBALS['fhcounter']['tl_page']['lastip'];
			$this->Template->PageCounterLastcounting = $GLOBALS['fhcounter']['tl_page']['lastcounting'];
			$this->Template->PageCounterOnline = $GLOBALS['fhcounter']['tl_page']['online'];
			$this->Template->PageCounterTopOnlineCount = $GLOBALS['fhcounter']['tl_page']['toponline']['count'];
			$this->Template->PageCounterTopOnlineTime = $GLOBALS['fhcounter']['tl_page']['toponline']['time'];
			// Durchschnitt je Tag ermitteln
			$this->Template->PageCounterAverage = sprintf('%01.0f',$GLOBALS['fhcounter']['tl_page']['totalhits'] / (($GLOBALS['fhcounter']['tl_page']['tstamp'] - $GLOBALS['fhcounter']['tl_page']['starttime']) / 86400));
		}

		// Zählerwerte tl_article in das Template schreiben
		if($GLOBALS['fhcounter']['tl_article'] && $this->fhc_view_articles)
		{
			// Zählerwerte zuweisen
			$this->Template->ArticleCounterAll = 0 + $GLOBALS['fhcounter']['tl_article']['counter']["all"];
			$this->Template->ArticleCounterThisYear = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$this->jahr]["all"];
			$this->Template->ArticleCounterThisMonth = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$this->jahr][$this->monat]["all"];
			$this->Template->ArticleCounterThisDay = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$this->jahr][$this->monat][$this->tag]["all"];
			$this->Template->ArticleCounterThisHour = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$this->jahr][$this->monat][$this->tag][$this->stunde];
			$this->Template->ArticleCounterYesterday = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$gjahr][$gmonat][$gtag]["all"];
			$this->Template->ArticleCounterLastMonth = 0 + $GLOBALS['fhcounter']['tl_article']['counter'][$vormonat[0]][$vormonat[1]]["all"];
			// Restliche Templatevariablen zuweisen
			$this->Template->ArticleCounterTstamp = $GLOBALS['fhcounter']['tl_article']['tstamp'];
			$this->Template->ArticleCounterStarttime = $GLOBALS['fhcounter']['tl_article']['starttime'];
			$this->Template->ArticleCounterSource = $GLOBALS['fhcounter']['tl_article']['source'];
			$this->Template->ArticleCounterPid = $GLOBALS['fhcounter']['tl_article']['pid'];
			$this->Template->ArticleCounterTotalhits = $GLOBALS['fhcounter']['tl_article']['totalhits'];
			$this->Template->ArticleCounterLastip = $GLOBALS['fhcounter']['tl_article']['lastip'];
			$this->Template->ArticleCounterLastcounting = $GLOBALS['fhcounter']['tl_article']['lastcounting'];
			$this->Template->ArticleCounterOnline = $GLOBALS['fhcounter']['tl_article']['online'];
			$this->Template->ArticleCounterTopOnlineCount = $GLOBALS['fhcounter']['tl_article']['toponline']['count'];
			$this->Template->ArticleCounterTopOnlineTime = $GLOBALS['fhcounter']['tl_article']['toponline']['time'];
			// Durchschnitt je Tag ermitteln
			$this->Template->ArticleCounterAverage = sprintf('%01.0f',$GLOBALS['fhcounter']['tl_article']['totalhits'] / (($GLOBALS['fhcounter']['tl_article']['tstamp'] - $GLOBALS['fhcounter']['tl_article']['starttime']) / 86400));
		}

		// Zählerwerte tl_news in das Template schreiben
		if($GLOBALS['fhcounter']['tl_news'] && $this->fhc_view_news)
		{
			// Zählerwerte zuweisen
			$this->Template->NewsCounterAll = 0 + $GLOBALS['fhcounter']['tl_news']['counter']["all"];
			$this->Template->NewsCounterThisYear = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$this->jahr]["all"];
			$this->Template->NewsCounterThisMonth = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$this->jahr][$this->monat]["all"];
			$this->Template->NewsCounterThisDay = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$this->jahr][$this->monat][$this->tag]["all"];
			$this->Template->NewsCounterThisHour = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$this->jahr][$this->monat][$this->tag][$this->stunde];
			$this->Template->NewsCounterYesterday = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$gjahr][$gmonat][$gtag]["all"];
			$this->Template->NewsCounterLastMonth = 0 + $GLOBALS['fhcounter']['tl_news']['counter'][$vormonat[0]][$vormonat[1]]["all"];
			// Restliche Templatevariablen zuweisen
			$this->Template->NewsCounterTstamp = $GLOBALS['fhcounter']['tl_news']['tstamp'];
			$this->Template->NewsCounterStarttime = $GLOBALS['fhcounter']['tl_news']['starttime'];
			$this->Template->NewsCounterSource = $GLOBALS['fhcounter']['tl_news']['source'];
			$this->Template->NewsCounterPid = $GLOBALS['fhcounter']['tl_news']['pid'];
			$this->Template->NewsCounterTotalhits = $GLOBALS['fhcounter']['tl_news']['totalhits'];
			$this->Template->NewsCounterLastip = $GLOBALS['fhcounter']['tl_news']['lastip'];
			$this->Template->NewsCounterLastcounting = $GLOBALS['fhcounter']['tl_news']['lastcounting'];
			$this->Template->NewsCounterOnline = $GLOBALS['fhcounter']['tl_news']['online'];
			$this->Template->NewsCounterTopOnlineCount = $GLOBALS['fhcounter']['tl_news']['toponline']['count'];
			$this->Template->NewsCounterTopOnlineTime = $GLOBALS['fhcounter']['tl_news']['toponline']['time'];
			// Durchschnitt je Tag ermitteln
			$this->Template->NewsCounterAverage = sprintf('%01.0f',$GLOBALS['fhcounter']['tl_news']['totalhits'] / (($GLOBALS['fhcounter']['tl_news']['tstamp'] - $GLOBALS['fhcounter']['tl_news']['starttime']) / 86400));
		}

		// Zählerwerte default in das Template schreiben
		if($GLOBALS['fhcounter']['default'] && $this->Template->ViewDefault)
		{
			// Zählerwerte zuweisen
			$this->Template->CounterAll = 0 + $GLOBALS['fhcounter']['default']['counter']["all"];
			$this->Template->CounterThisYear = 0 + $GLOBALS['fhcounter']['default']['counter'][$this->jahr]["all"];
			$this->Template->CounterThisMonth = 0 + $GLOBALS['fhcounter']['default']['counter'][$this->jahr][$this->monat]["all"];
			$this->Template->CounterThisDay = 0 + $GLOBALS['fhcounter']['default']['counter'][$this->jahr][$this->monat][$this->tag]["all"];
			$this->Template->CounterThisHour = 0 + $GLOBALS['fhcounter']['default']['counter'][$this->jahr][$this->monat][$this->tag][$this->stunde];
			$this->Template->CounterYesterday = 0 + $GLOBALS['fhcounter']['default']['counter'][$gjahr][$gmonat][$gtag]["all"];
			$this->Template->CounterLastMonth = 0 + $GLOBALS['fhcounter']['default']['counter'][$vormonat[0]][$vormonat[1]]["all"];
			// Restliche Templatevariablen zuweisen
			$this->Template->CounterTstamp = $GLOBALS['fhcounter']['default']['tstamp'];
			$this->Template->CounterStarttime = $GLOBALS['fhcounter']['default']['starttime'];
			$this->Template->CounterSource = $GLOBALS['fhcounter']['default']['source'];
			$this->Template->CounterPid = $GLOBALS['fhcounter']['default']['pid'];
			$this->Template->CounterTotalhits = $GLOBALS['fhcounter']['default']['totalhits'];
			$this->Template->CounterLastip = $GLOBALS['fhcounter']['default']['lastip'];
			$this->Template->CounterLastcounting = $GLOBALS['fhcounter']['default']['lastcounting'];
			$this->Template->CounterOnline = $GLOBALS['fhcounter']['default']['online'];
			$this->Template->CounterTopOnlineCount = $GLOBALS['fhcounter']['default']['toponline']['count'];
			$this->Template->CounterTopOnlineTime = $GLOBALS['fhcounter']['default']['toponline']['time'];
			// Durchschnitt je Tag ermitteln
			$this->Template->CounterAverage = sprintf('%01.0f',$GLOBALS['fhcounter']['default']['totalhits'] / (($GLOBALS['fhcounter']['default']['tstamp'] - $GLOBALS['fhcounter']['default']['starttime']) / 86400));
		}

		// Template-Variablen ggfs. Tausender-Trennzeichen hinzufügen
		if($this->fhc_1000_separator)
		{
			if($GLOBALS['fhcounter']['default'] && $this->Template->ViewDefault)
			{
				// Allgemeiner Zähler
				$this->Template->CounterAll = number_format($this->Template->CounterAll,0,",",".");
				$this->Template->CounterThisYear = number_format($this->Template->CounterThisYear,0,",",".");
				$this->Template->CounterThisMonth = number_format($this->Template->CounterThisMonth,0,",",".");
				$this->Template->CounterThisDay = number_format($this->Template->CounterThisDay,0,",",".");
				$this->Template->CounterThisHour = number_format($this->Template->CounterThisHour,0,",",".");
				$this->Template->CounterYesterday = number_format($this->Template->CounterYesterday,0,",",".");
				$this->Template->CounterLastMonth = number_format($this->Template->CounterLastMonth,0,",",".");
				$this->Template->CounterTotalhits = number_format($this->Template->CounterTotalhits,0,",",".");
				$this->Template->CounterOnline = number_format($this->Template->CounterOnline,0,",",".");
				$this->Template->CounterTopOnlineCount = number_format($this->Template->CounterTopOnlineCount,0,",",".");
				$this->Template->CounterAverage = number_format($this->Template->CounterAverage,0,",",".");
			}
			if($GLOBALS['fhcounter']['tl_page'] && $this->fhc_view_pages)
			{
				// Seiten-Zähler
				$this->Template->PageCounterAll = number_format($this->Template->PageCounterAll,0,",",".");
				$this->Template->PageCounterThisYear = number_format($this->Template->PageCounterThisYear,0,",",".");
				$this->Template->PageCounterThisMonth = number_format($this->Template->PageCounterThisMonth,0,",",".");
				$this->Template->PageCounterThisDay = number_format($this->Template->PageCounterThisDay,0,",",".");
				$this->Template->PageCounterThisHour = number_format($this->Template->PageCounterThisHour,0,",",".");
				$this->Template->PageCounterYesterday = number_format($this->Template->PageCounterYesterday,0,",",".");
				$this->Template->PageCounterLastMonth = number_format($this->Template->PageCounterLastMonth,0,",",".");
				$this->Template->PageCounterTotalhits = number_format($this->Template->PageCounterTotalhits,0,",",".");
				$this->Template->PageCounterOnline = number_format($this->Template->PageCounterOnline,0,",",".");
				$this->Template->PageCounterTopOnlineCount = number_format($this->Template->PageCounterTopOnlineCount,0,",",".");
				$this->Template->PageCounterAverage = number_format($this->Template->PageCounterAverage,0,",",".");
			}
			if($GLOBALS['fhcounter']['tl_article'] && $this->fhc_view_articles)
			{
				// Artikel-Zähler
				$this->Template->ArticleCounterAll = number_format($this->Template->ArticleCounterAll,0,",",".");
				$this->Template->ArticleCounterThisYear = number_format($this->Template->ArticleCounterThisYear,0,",",".");
				$this->Template->ArticleCounterThisMonth = number_format($this->Template->ArticleCounterThisMonth,0,",",".");
				$this->Template->ArticleCounterThisDay = number_format($this->Template->ArticleCounterThisDay,0,",",".");
				$this->Template->ArticleCounterThisHour = number_format($this->Template->ArticleCounterThisHour,0,",",".");
				$this->Template->ArticleCounterYesterday = number_format($this->Template->ArticleCounterYesterday,0,",",".");
				$this->Template->ArticleCounterLastMonth = number_format($this->Template->ArticleCounterLastMonth,0,",",".");
				$this->Template->ArticleCounterTotalhits = number_format($this->Template->ArticleCounterTotalhits,0,",",".");
				$this->Template->ArticleCounterOnline = number_format($this->Template->ArticleCounterOnline,0,",",".");
				$this->Template->ArticleCounterTopOnlineCount = number_format($this->Template->ArticleCounterTopOnlineCount,0,",",".");
				$this->Template->ArticleCounterAverage = number_format($this->Template->ArticleCounterAverage,0,",",".");
			}
			if($GLOBALS['fhcounter']['tl_news'] && $this->fhc_view_news)
			{
				// Nachrichten-Zähler
				$this->Template->NewsCounterAll = number_format($this->Template->NewsCounterAll,0,",",".");
				$this->Template->NewsCounterThisYear = number_format($this->Template->NewsCounterThisYear,0,",",".");
				$this->Template->NewsCounterThisMonth = number_format($this->Template->NewsCounterThisMonth,0,",",".");
				$this->Template->NewsCounterThisDay = number_format($this->Template->NewsCounterThisDay,0,",",".");
				$this->Template->NewsCounterThisHour = number_format($this->Template->NewsCounterThisHour,0,",",".");
				$this->Template->NewsCounterYesterday = number_format($this->Template->NewsCounterYesterday,0,",",".");
				$this->Template->NewsCounterLastMonth = number_format($this->Template->NewsCounterLastMonth,0,",",".");
				$this->Template->NewsCounterTotalhits = number_format($this->Template->NewsCounterTotalhits,0,",",".");
				$this->Template->NewsCounterOnline = number_format($this->Template->NewsCounterOnline,0,",",".");
				$this->Template->NewsCounterTopOnlineCount = number_format($this->Template->NewsCounterTopOnlineCount,0,",",".");
				$this->Template->NewsCounterAverage = number_format($this->Template->NewsCounterAverage,0,",",".");
			}
		}

		if($this->fhc_view_diagrams)
		{
			// Letzte 24 Stunden ausgeben
			$nr = 0; $values = array(); $ticks = array();
			for($x = 23; $x >= 0; $x--)
			{
				$abzug = $x * 3600;
				$neuzeit = $this->zeit - $abzug;
				$xjahr = date("Y",$neuzeit); // Jahr vierstellig
				$xmonat = date("n",$neuzeit); // Monat einstellig
				$xtag = date("j",$neuzeit); // Tag einstellig
				$xstunde = date("G",$neuzeit); // Stunde einstellig
				if($GLOBALS['fhcounter']['default'])
				{
					$nr++;
					$value = 0 + $GLOBALS['fhcounter']['default']['counter'][$xjahr][$xmonat][$xtag][$xstunde];
					$values[] = '['.$nr.', '.$value.']';
					$ticks[] = '['.$nr.', "'.$xstunde.'"]';
				}
			}
			// Templatevariablen zuweisen
			$this->Template->CounterHoursData = '['.implode(', ',$values).']';
			$this->Template->CounterHoursTicks = '['.implode(', ',$ticks).']';
			$this->Template->CounterHoursValues = $nr;

			// Letzte 30 Tage ausgeben
			$nr = 0; $values = array(); $ticks = array();
			for($x = 29; $x >= 0; $x--)
			{
				$abzug = $x * 86400;
				$neuzeit = $this->zeit - $abzug;
				$xjahr = date("Y",$neuzeit); // Jahr vierstellig
				$xmonat = date("n",$neuzeit); // Monat einstellig
				$xtag = date("j",$neuzeit); // Tag einstellig
				if($GLOBALS['fhcounter']['default'])
				{
					$nr++;
					$value = 0 + $GLOBALS['fhcounter']['default']['counter'][$xjahr][$xmonat][$xtag]["all"];
					$values[] = '['.$nr.', '.$value.']';
					$ticks[] = '['.$nr.', "'.$xtag.'"]';
				}
			}
			// Templatevariablen zuweisen
			$this->Template->CounterDaysData = '['.implode(', ',$values).']';
			$this->Template->CounterDaysTicks = '['.implode(', ',$ticks).']';
			$this->Template->CounterDaysValues = $nr;

			// Letzte 12 Monate ausgeben
			$nr = 0; $values = array(); $ticks = array();
			$monatsanfang = mktime(0,0,0,$this->monat,1,$this->jahr); // 1. Tag des aktuellen Monats als Start
			for($x = 11; $x >= 0; $x--)
			{
				$neuzeit = strtotime("-$x months",$monatsanfang);
				$xjahr = date("Y",$neuzeit); // Jahr vierstellig
				$xmonat = date("n",$neuzeit); // Monat einstellig
				if($GLOBALS['fhcounter']['default'])
				{
					$nr++;
					$value = 0 + $GLOBALS['fhcounter']['default']['counter'][$xjahr][$xmonat]["all"];
					$values[] = '['.$nr.', '.$value.']';
					$ticks[] = '['.$nr.', "'.$xmonat.'"]';
				}
			}
			// Templatevariablen zuweisen
			$this->Template->CounterMonthsData = '['.implode(', ',$values).']';
			$this->Template->CounterMonthsTicks = '['.implode(', ',$ticks).']';
			$this->Template->CounterMonthsValues = $nr;
		}
	}

}
