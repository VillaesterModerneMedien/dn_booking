# Joomla! 4.x Component Boilerplate

Diginerds Booking
Our Joomla component enables easy and flexible booking management for various purposes. Administrators can effortlessly create, edit, and track bookings, while users can seamlessly reserve appointments or services. With responsive design for accessibility across devices and integrated payment processing, the booking process is streamlined.
com_dnbooking

Copyright (C) 2024 Mario Hewera. All rights reserved. 
Mario Hewera
https://www.diginerds.de

Diese mit dem formativ.net Extension Builder erstellte Joomla! 4.x Komponente stellt nur ein Grundgerüst für Ihre 
Komponente dar.

## Weiterführende Informationen

Developing an MVC Component for Joomla 4.x

https://docs.joomla.org/J4.x:Developing_an_MVC_Component

Joomla! Coding Standards

https://developer.joomla.org/coding-standards/basic-guidelines.html

Astrid Günther: Joomla 4.x-Tutorial - Entwicklung von Erweiterungen - Der Weg zu Joomla 4 Erweiterungen

https://blog.astrid-guenther.de/der-weg-zu-joomla4-erweiterungen/

## Erste Schritte

### Sprachen-Dateien

Die erstellten Sprachen-Dateien haben alle den gleichen Inhalt. Bitte ergänzen und übersetzen Sie diese. 

### Datenbank-Tabellen

Die Tabellen sind Vorlagen die Spalten für gängige Funktionen enthalten. Diese müssen von Ihnen angepasst werden.

### Javascript

Alle Frontend-Views laden via Joomla! Web Asset Manager die Javascript-Datei /media/com_dnbooking/js/script.js

In der script.js können Sie Ihre Javascript-Funktionen hinterlegen.

### Komponenten Hilfe

Im Joomla! Backend Ihrer Komponenten finden Sie in der Toolbar einen Hilfe Button. Über diesen kann der 
Benutzer Ihrer Komponente die von Ihnen bereitgestellte Anleitung aufrufen.

Bitte hinterlegen Sie auf der von Ihnen Angegeben Hilfe-URL die Anleitung zu Ihrer Komponente. Sie können diese URL in der Backend-Sprachdatei ändern. 

https://YOUR-HELP-URL.COM
## Setup-Script

Das Setup-Script wird während der Installation Ihrer Joomla! 4.x Komponente ausgeführt.
Aufgabe des Setup-Script ist es zusätzliche Installationsschritte auszuführen die der Joomla! Installer nicht selbst
anhand der XML-Datei und der SQL-Datei ausführen kann.

## Komponenten-Dashboard

Den Inhalt des Komponenten-Dashboard können über die XML-Datei in folgenden Ordner anpassen /administrator/com_dnbooking/presets

## Komponenten-Kategorie


## Update-Server

https://docs.joomla.org/Deploying_an_Update_Server/de

https://blog.astrid-guenther.de/joomla-update-und-change-logeinrichten/

Kopieren Sie die Datei extension.xml sowie die Zip-Datei auf Ihren Server.

https://YOUR-UPDATE-URL.COM/com_dnbooking/extension.xml

https://YOUR-UPDATE-URL.COM/com_dnbooking/com_dnbooking.zip

