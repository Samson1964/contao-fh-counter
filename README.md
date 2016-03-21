# FH-Counter

Ein Modul f�r Contao ab mindestens Version 3.1, welches die Zugriffe auf Seiten, Artikel und Nachrichten z�hlen kann.  
Die hier vorliegende Version ist auf schachbund.de zwar schon im produktiven Einsatz, befindet sich aber noch in der Entwicklungsphase.

## Installation

Das Modul mu� im Verzeichnis system/modules/fh-counter installiert werden. Danach m�ssen Sie die Datenbankaktualisierung in Contao aufrufen, damit die Tabelle tl_fh_counter f�r den Z�hler und die Felder in tl_module angelegt werden.

## Einbindung

FH-Counter besteht aus einem Z�hler- und einem Ausgabemodul. Das Z�hlermodul z�hlt die Zugriffe und mu� **vor** dem Ausgabemodul im Seitenlayout oder in einer Seite eingebunden werden.

Diese beiden Frontendmodule finden Sie im Bereich **FH-Counter** unter Themes -> Module. Das Z�hlermodul m�ssen Sie nur einmal einbinden, das Ausgabemodul k�nnen Sie an beliebig vielen Stellen mit verschiedenen Templates einbinden.  
Das Z�hlermodul verwaltet die Daten in der Tabelle tl_fh_counter und schreibt zus�tzlich die Daten der aktuellen Inhalte (Seite, Artikel, Nachricht) in $GLOBALS['fhcounter']. Dort werden sie vom Ausgabemodul weiter verwendet.

## Template-Variablen

**ViewCounterinfo** (boolean): Kopfdaten des Z�hlers vorhanden ja/nein

**ViewDiagrams** (boolean): Diagramme des Z�hlers vorhanden ja/nein. Ben�tigt JQuery!

**CounterSource**: Name des Z�hlers (tl_news, tl_article, tl_page)

**CounterPid**: ID von CounterSource

**CounterStarttime**: Timestamp der ersten Z�hlung

**CounterLastcounting**: Timestamp der letzten Z�hlung

**CounterLastip**: IP-Adresse des letzten Besuchers, gez�hlt oder nicht gez�hlt. In der Regel sollte das die eigene IP sein.

**CounterOnline**: Anzahl der aktuellen Besucher dieser URL

**CounterTopOnlineCount**: Spitzenwert der Anzahl der aktuellen Besucher

**CounterTopOnlineTime**: Timestamp des Spitzenwerts der Anzahl der aktuellen Besucher

**CounterTotalhits** / **CounterAll**: Anzahl der Gesamtzugriffe

**CounterYesterday**: Anzahl der Zugriffe gestern

**CounterThisDay**: Anzahl der Zugriffe heute

**CounterAverage**: Durchschnittliche Besucherzahl je Tag

Das sind bei Weitem nicht alle Template-Variablen, aber die wichtigsten. Dar�berhinaus sind die Templates noch nicht ausgereift und enthalten Fehler. Variablennamen k�nnen sich noch �ndern oder werden nicht mehr benutzt.

Den o.g. Variablennamen kann au�erdem jeweils noch ein Pr�fix mitgegeben werden. So zeigt **PageCounterAverage** z.B. die durchschnittliche Besucherzahl je Tag f�r die aktive Seite, egal ob gerade ein Artikel oder eine Nachricht angezeigt wird. Die anderen Pr�fixe sind **Article** und **News**.  
Der allgemeine Z�hler ohne Pr�fix gewichtet die anderen Z�hler in der Reihenfolge Seite, Artikel, Nachricht. Der allgemeine Z�hler wird also zuerst mit den Daten der Seite gef�llt und anschlie�end mit den Daten des Artikels �berschrieben - falls �berhaupt gerade ein Artikel angezeigt wird.

## Einstellungen von Contao

FH-Counter arbeitet mit folgenden Frontend-Einstellungen offensichtlich einwandfrei:

**URLs umschreiben** = true
**Auto_item aktivieren** = true
**Die Sprache zur URL hinzuf�gen** = false
**Leere URLs nicht umleiten** = false
**Ordner-URLs verwenden** = false
**Keine Seitenaliase verwenden** = false

Andere Einstellungen wurden noch nicht gen�gend getestet. Solange aber nur Seiten gez�hlt werden, sollte FH-Counter keine Probleme haben. Die ID der aktuellen Seite wird einem Modul von Contao zur Verf�gung gestellt.

Bei der Artikelz�hlung holt sich der Z�hler den Inhalt der GET-Variablen articles und ermittelt damit den gerade aktiven Artikel.

Die Nachrichtenz�hlung ist etwas komplizierter, da das Seitenalias frei w�hlbar ist und Contao selbst keine Informationen zu einer angezeigten Nachricht liefert. Der Z�hler ermittelt deshalb f�r alle Nachrichten-Archive zuerst die Weiterleitungsseiten, also die Nachrichtenleser. Entspricht die aktive Seite so einem Nachrichtenleser, wird aus $_SERVER['REQUEST_URI'] der Nachrichten-Alias extrahiert und die Nachricht kann gez�hlt werden.

## Fehler und Support

Da FH-Counter noch in der Entwicklungsphase ist und auch noch nicht im ER von Contao eingebunden wurde, halte ich mich mit Unterst�tzung zur Erweiterung etwas zur�ck. Mich kann man aber gern im Contao-Forum (Samson1964) oder hier auf GitHub kontaktieren.

## Name der Erweiterung

FH sind die Initialen von Frank Hoppe, was mein Name ist. Urspr�nglich hie� meine Erweiterung nur **counter**. Diesen etwas zu allgemeinen und beherrschenden Namen wollte ich aber nicht weiterverwenden, um mit m�glichen Core-Modulen nicht zu kollidieren.

**Frank Hoppe**
