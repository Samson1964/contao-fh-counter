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

class CounterTag extends \Frontend
{
	// Zeitwerte anlegen
	var $zeit;
	var $jahr;
	var $monat;
	var $tag;
	var $stunde;

	var $fhc_register_pages = true;
	var $fhc_register_articles = true;
	var $fhc_register_news = true;             
	
	var $fhc_onlinetime = 180;  
	var $fhc_registernewtime = 600;
	
	// Backend-Status
	var $be_user = false;       

	var $fhc_template = 'fhcounter_mini';
	var $fhc_view_pages = true;
	var $fhc_view_articles = true;
	var $fhc_view_news = true;
	var $fhc_infos_counter = true;
	var $fhc_infos_debug = false;
	var $fhc_view_diagrams = true;
	var $fhc_view_tables = false;

	public function fhcounter_view($strTag)
	{
		$arrSplit = explode('::', $strTag);

		if($arrSplit[0] == 'fhcounterview' || $arrSplit[0] == 'cache_fhcounterview')
		{
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
			$debuginfo = array();
	
			// Zählerwerte tl_page in das Template schreiben
			if($GLOBALS['fhcounter']['tl_page'] && $this->Template->ViewPages)
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
				$divident = ($GLOBALS['fhcounter']['tl_page']['tstamp'] - $GLOBALS['fhcounter']['tl_page']['starttime']) / 86400;
				$this->Template->PageCounterAverage = ($divident) ? sprintf('%01.0f',$GLOBALS['fhcounter']['tl_page']['totalhits'] / $divident) : '0';
				// Besucher gezählt?
				$this->Template->PageCounterCheck = $GLOBALS['fhcounter']['tl_page']['counting'];
			}
			else
			{
				// Kein Zähler vorhanden, dann Ausgabe unterdrücken
				$this->Template->ViewPages = false;
			}
	
			// Zählerwerte tl_article in das Template schreiben
			if($GLOBALS['fhcounter']['tl_article'] && $this->Template->ViewArticles)
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
				$divident = ($GLOBALS['fhcounter']['tl_article']['tstamp'] - $GLOBALS['fhcounter']['tl_article']['starttime']) / 86400;
				$this->Template->ArticleCounterAverage = ($divident) ? sprintf('%01.0f',$GLOBALS['fhcounter']['tl_article']['totalhits'] / $divident) : '0';
				// Besucher gezählt?
				$this->Template->ArticleCounterCheck = $GLOBALS['fhcounter']['tl_article']['counting'];
			}
			else
			{
				// Kein Zähler vorhanden, dann Ausgabe unterdrücken
				$this->Template->ViewArticles = false;
			}
	
			// Zählerwerte tl_news in das Template schreiben
			if($GLOBALS['fhcounter']['tl_news'] && $this->Template->ViewNews)
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
				$divident = ($GLOBALS['fhcounter']['tl_news']['tstamp'] - $GLOBALS['fhcounter']['tl_news']['starttime']) / 86400;
				$this->Template->NewsCounterAverage = ($divident) ? sprintf('%01.0f',$GLOBALS['fhcounter']['tl_news']['totalhits'] / $divident) : '0';
				// Besucher gezählt?
				$this->Template->NewsCounterCheck = $GLOBALS['fhcounter']['tl_news']['counting'];
			}
			else
			{
				// Kein Zähler vorhanden, dann Ausgabe unterdrücken
				$this->Template->ViewNews = false;
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
				$this->Template->CounterAverage = @sprintf('%01.0f',$GLOBALS['fhcounter']['default']['totalhits'] / (($GLOBALS['fhcounter']['default']['tstamp'] - $GLOBALS['fhcounter']['default']['starttime']) / 86400));
				// Besucher gezählt?
				$this->Template->CounterCheck = $GLOBALS['fhcounter']['tl_default']['counting'];
			}
			else
			{
				// Kein Zähler vorhanden, dann Ausgabe unterdrücken
				$this->Template->ViewDefault = false;
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
				if($GLOBALS['fhcounter']['tl_page'] && $this->Template->ViewPages)
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
				if($GLOBALS['fhcounter']['tl_article'] && $this->Template->ViewArticles)
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
				if($GLOBALS['fhcounter']['tl_news'] && $this->Template->ViewNews)
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
	
			// Debuginfos ergänzen
			if($this->Template->ViewDebuginfo)
			{
				// head
				$debuginfo['head']['SERVER REQUEST_URI'] = $_SERVER['REQUEST_URI'];
				$debuginfo['head']['GET articles'] = Input::get('articles');
				// tl_page
				($GLOBALS['fhcounter']['tl_page']['counting']) ? $debuginfo['tl_page']['Besucher gezählt'] = "Ja" : $debuginfo['tl_page']['Besucher gezählt'] = "Nein";
				$debuginfo['tl_page']['Letzte Aktualisierung'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_page']['tstamp']);
				$debuginfo['tl_page']['Erststart Zähler'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_page']['starttime']);
				$debuginfo['tl_page']['Name der Quelltabelle'] = $GLOBALS['fhcounter']['tl_page']['source'];
				$debuginfo['tl_page']['ID in Quelltabelle'] = $GLOBALS['fhcounter']['tl_page']['pid'];
				$debuginfo['tl_page']['Gesamtzugriffe'] = $GLOBALS['fhcounter']['tl_page']['totalhits'];
				$debuginfo['tl_page']['Letzte Zählung'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_page']['lastcounting']);
				$debuginfo['tl_page']['Letzter Besucher'] = $GLOBALS['fhcounter']['tl_page']['lastip'];
				$debuginfo['tl_page']['Topbesucher gleichzeitig'] = $GLOBALS['fhcounter']['tl_page']['toponline']['count'] . " am " . date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_page']['toponline']['time']);
				// tl_article
				($GLOBALS['fhcounter']['tl_article']['counting']) ? $debuginfo['tl_article']['Besucher gezählt'] = "Ja" : $debuginfo['tl_article']['Besucher gezählt'] = "Nein";
				$debuginfo['tl_article']['Letzte Aktualisierung'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_article']['tstamp']);
				$debuginfo['tl_article']['Erststart Zähler'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_article']['starttime']);
				$debuginfo['tl_article']['Name der Quelltabelle'] = $GLOBALS['fhcounter']['tl_article']['source'];
				$debuginfo['tl_article']['ID in Quelltabelle'] = $GLOBALS['fhcounter']['tl_article']['pid'];
				$debuginfo['tl_article']['Gesamtzugriffe'] = $GLOBALS['fhcounter']['tl_article']['totalhits'];
				$debuginfo['tl_article']['Letzte Zählung'] = date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_article']['lastcounting']);
				$debuginfo['tl_article']['Letzter Besucher'] = $GLOBALS['fhcounter']['tl_article']['lastip'];
				$debuginfo['tl_article']['Topbesucher gleichzeitig'] = $GLOBALS['fhcounter']['tl_article']['toponline']['count'] . " am " . date("d.m.Y H:i:s", $GLOBALS['fhcounter']['tl_article']['toponline']['time']);
				$this->Template->Debuginfo = $debuginfo;
			}
	
			if($this->Template->ViewDiagrams)
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
				return $this->Template->parse();
			}
		}
	}

	public function fhcounter($strTag)
	{
		$arrSplit = explode('::', $strTag);

		if($arrSplit[0] == 'fhcounter' || $arrSplit[0] == 'cache_fhcounter')
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
	
			// BE-Benutzer soll nicht mitgezählt werden, deshalb Session-ID prüfen
			$objSession = $this->Database->prepare('SELECT * FROM tl_session WHERE name=? AND sessionID=?')->execute('BE_USER_AUTH', session_id()); 
			if($objSession->name == 'BE_USER_AUTH')
			{
				// BE-Benutzer ist eingeloggt, deshalb Zählung deaktivieren
				$this->fhc_register_pages = false;
				$this->fhc_register_articles = false;
				$this->fhc_register_news = false;
				$this->be_user = true;
				//echo "BE eingeloggt";
			}
	
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
			//print_r($GLOBALS['fhcounter']);
			return "";
		}
		// nicht unser Insert-Tag
		return false;
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
				// Prüfen auf doppelte Datensätze
				if($objZaehler->numRows > 1)
				{
					// Restliche Datensätze löschen
					while($objZaehler->next()) {
						$ergebnis = $this->Database->prepare('DELETE FROM tl_fh_counter WHERE id=?')->execute($objZaehler->id);
					}
				}
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
					if($objZaehler->id && !$this->be_user)
					{
						// Zähler vorhanden und BE-Benutzer ignorieren
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
		else
		{
			// source_id ist leer, Fehler loggen
			$this->log('FH-Counter source_id='.$source_id.', source_name='.$source_name.', URI='.$_SERVER['REQUEST_URI'], __METHOD__, TL_ERROR);
		}
	}

}
