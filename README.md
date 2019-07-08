Dashboard Sellsy
======
C'est une application web, connectant à la base de donnée dans Sellsy à l'aide de l'API Sellsy

## Getting Started
---------------

1. Créer votre répertoire web destiné pour l'application  et récuperer les codes sources utilisant git `git clone https://xxxxx.com//wdsellsy.git`
2. Cette répertoire sera  appelé dans votre VirtualHost .
3. Par exemple si le chemin absolue vers votre répertoire web est /var/www/html/wd-sellsy =>  mettez dans votre VirtualHost /var/www/html/wd-sellsy/web

### Prerequisites

- PHP version is `7.1.3`.

- Mysql version is `5+`.

- [Download git](https://git-scm.com/downloads).

- [Download xampp](https://www.apachefriends.org/download.html) or [Download wampp](http://www.wampserver.com/en/).

## Installation
---------------

1.Clone "git clone https://xxxxx.com//wdsellsy.git"

2.Check out to a branch `git branch develop`  and `git checkout develop` and make pull to "develop" branch

3.Mettez vous à l'interieur de votre répertoier web .

4.Créer une base de donnée et importer le Dump de la base de donnée inclus dans le code sources (répertoire sql) ( manuele ou avec PHPMyadmin)

5.Récuperer le fichier composer.phar avec la commande : php -r "eval('?>'.file_get_contents('http://getcomposer.org/installer'));"

6.Lancez la commande pour installer Symfony: php composer.phar  install

7.Configurer le paramètre d'accès à la base de donnée dans app/config/parameters.yml

8.Lancez les commandes suivants pour compilé les Javascript et CSS : 
	php bin/console assets:install
	php bin/console assetic:dump
	php bin/console ca:cle 
	php bin/console ca:cle --env=prod
	
9.Extraire le fichier teknoo.zip et copier le repertoire obtenu "teknoo" dans le repertoire "vendor" , en ecrasant les anciens fichiers.

10.Le système utilise les tâches cron => ajouter les lignes suivants, (Exemple  pour Linux si votre repertoire web est /var/www/html/wd-sellsy:

> $: crontab -e

*/5  * * * * php /var/www/html/wd-sellsy/bin/console import:ref:data
*/10 * * * * php /var/www/html/wd-sellsy/bin/console import:cliagenda:data
*/20 * * * * php /var/www/html/wd-sellsy/bin/console import:cliphone:data
0 23 * * * php /var/www/html/wd-sellsy/bin/console ca:cle --env=prod

Quelquefois le serveur necessite les commandes suivants pour que l'application puisse ecrire sur son répertoire de cache et de log

$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs

11.Configurer la nouvelle URL dans le panneau de configuration Sellsy:
	Rentrez dans "Menu" => "Réglages" => "Développeur"
	Dans la partie Webhook POST : Activer puis configurer 
	Changer le "Endpoint personnalisé" par votre l'Url de votre nouvelle serveur ( Exemple https://www.wd-dashboard.com/sellsy)
	Toujours dans cette partie : configurer le "Configurer les notifications envoyées au webhook" comme ce qu'on a avec le compte Sellsy de Mme Mélanie (mdouadi@wylog.com/704a8d622977)
	

  

## Built With
---------------

-  [Symfony 3.4 ](https://symfony.com/) - The web framework used

-  [Git](https://git-scm.com/) - Versioning tool

-  [Jquery](https://jquery.com/) - Javascript library

-  [Datatables](https://datatables.net/) - For data display

-  [Bootstrap](https://getbootstrap.com/docs/3.3/) - For user interface resposive

-  [Google Chart](https://developers.google.com/chart/) - For chart diagram

-  [Momentjs](https://momentjs.com/) - A javascript library that Parse, validate, manipulate, and display dates and times in JavaScript.

  

## Authors
---------------

-  **Mélanie Douadi** - _Project Manager_

-  **Jacky Hantsanitolotra** - _Lead Developer_

-  **Lovatiana Rasamizafy** - _QA / Tester_

-  **Tombo** - _Designer__

## License
---
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Acknowledgements
---------------

- [jQuery](https://jquery.com/)
- [Symfony 3.4 ](https://symfony.com/) 
- [Git](https://git-scm.com/)
- [Datatables](https://datatables.net/)
- [Bootstrap](https://getbootstrap.com/docs/3.3/)
- [Google Chart](https://developers.google.com/chart/) 
