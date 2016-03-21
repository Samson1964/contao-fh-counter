# FH-Counter

Ein Modul für Contao ab mindestens Version 3.1, welches die Zugriffe auf Seiten, Artikel und Nachrichten zählen kann.  
Die hier vorliegende Version ist auf schachbund.de zwar schon im produktiven Einsatz, befindet sich aber noch in der Entwicklungsphase.

## Installation

Das Modul muß im Verzeichnis system/modules/fh-counter installiert werden. Danach müssen Sie die Datenbankaktualisierung in Contao aufrufen, damit die Tabelle tl_fh_counter für den Zähler und die Felder in tl_module angelegt werden.

## Einbindung

FH-Counter besteht aus einem Zähler- und einem Ausgabemodul. Das Zählermodul zählt die Zugriffe und muß **vor** dem Ausgabemodul im Seitenlayout oder in einer Seite eingebunden werden.

Diese beiden Frontendmodule finden Sie im Bereich **FH-Counter** unter Themes -> Module. Das Zählermodul müssen Sie nur einmal einbinden, das Ausgabemodul können Sie an beliebig vielen Stellen mit verschiedenen Templates einbinden.  
Das Zählermodul verwaltet die Daten in der Tabelle tl_fh_counter und schreibt zusätzlich die Daten der aktuellen Inhalte (Seite, Artikel, Nachricht) in $GLOBALS['fhcounter']. Dort werden sie vom Ausgabemodul weiter verwendet.

## Template-Variablen

**ViewCounterinfo** (boolean): Kopfdaten des Zählers vorhanden ja/nein

**ViewDiagrams** (boolean): Diagramme des Zählers vorhanden ja/nein. Benötigt JQuery!

**CounterSource**: Name des Zählers (tl_news, tl_article, tl_page)

**CounterPid**: ID von CounterSource

**CounterStarttime**: Timestamp der ersten Zählung

**CounterLastcounting**: Timestamp der letzten Zählung

**CounterLastip**: IP-Adresse des letzten Besuchers, gezählt oder nicht gezählt. In der Regel sollte das die eigene IP sein.

**CounterOnline**: Anzahl der aktuellen Besucher dieser URL

**CounterTopOnlineCount**: Spitzenwert der Anzahl der aktuellen Besucher

**CounterTopOnlineTime**: Timestamp des Spitzenwerts der Anzahl der aktuellen Besucher

**CounterTotalhits** / **CounterAll**: Anzahl der Gesamtzugriffe

**CounterYesterday**: Anzahl der Zugriffe gestern

**CounterThisDay**: Anzahl der Zugriffe heute

**CounterAverage**: Durchschnittliche Besucherzahl je Tag

Das sind bei Weitem nicht alle Template-Variablen, aber die wichtigsten. Darüberhinaus sind die Templates noch nicht ausgereift und enthalten Fehler. Variablennamen können sich noch ändern oder werden nicht mehr benutzt.

Den o.g. Variablennamen kann außerdem jeweils noch ein Präfix mitgegeben werden. So zeigt **PageCounterAverage** z.B. die durchschnittliche Besucherzahl je Tag für die aktive Seite, egal ob gerade ein Artikel oder eine Nachricht angezeigt wird. Die anderen Präfixe sind **Article** und **News**.  
Der allgemeine Zähler ohne Präfix gewichtet die anderen Zähler in der Reihenfolge Seite, Artikel, Nachricht. Der allgemeine Zähler wird also zuerst mit den Daten der Seite gefüllt und anschließend mit den Daten des Artikels überschrieben - falls überhaupt gerade ein Artikel angezeigt wird.

## Einstellungen von Contao

FH-Counter arbeitet mit folgenden Frontend-Einstellungen offensichtlich einwandfrei:

**URLs umschreiben** = true
**Auto_item aktivieren** = true
**Die Sprache zur URL hinzufügen** = false
**Leere URLs nicht umleiten** = false
**Ordner-URLs verwenden** = false
**Keine Seitenaliase verwenden** = false

Andere Einstellungen wurden noch nicht genügend getestet. Solange aber nur Seiten gezählt werden, sollte FH-Counter keine Probleme haben. Die ID der aktuellen Seite wird einem Modul von Contao zur Verfügung gestellt.

Bei der Artikelzählung holt sich der Zähler den Inhalt der GET-Variablen articles und ermittelt damit den gerade aktiven Artikel.

Die Nachrichtenzählung ist etwas komplizierter, da das Seitenalias frei wählbar ist und Contao selbst keine Informationen zu einer angezeigten Nachricht liefert. Der Zähler ermittelt deshalb für alle Nachrichten-Archive zuerst die Weiterleitungsseiten, also die Nachrichtenleser. Entspricht die aktive Seite so einem Nachrichtenleser, wird aus $_SERVER['REQUEST_URI'] der Nachrichten-Alias extrahiert und die Nachricht kann gezählt werden.

## Fehler und Support

Da FH-Counter noch in der Entwicklungsphase ist und auch noch nicht im ER von Contao eingebunden wurde, halte ich mich mit Unterstützung zur Erweiterung etwas zurück. Mich kann man aber gern im Contao-Forum (Samson1964) oder hier auf GitHub kontaktieren.

## Name der Erweiterung

FH sind die Initialen von Frank Hoppe, was mein Name ist. Ursprünglich hieß meine Erweiterung nur **counter**. Diesen etwas zu allgemeinen und beherrschenden Namen wollte ich aber nicht weiterverwenden, um mit möglichen Core-Modulen nicht zu kollidieren.

**Frank Hoppe**
