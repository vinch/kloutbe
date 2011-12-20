Belgians on Klout
=================

Ranking of Belgian influencers on [Klout](http://www.klout.com). Inspired by [Data News](http://datanews.rnews.be/fr/ict/actualite/blog/qui-devez-vous-suivre-dans-la-twittosphere-belge/article-1195010830793.htm) and based on the following twitter lists:

https://twitter.com/8x3h/belgasphere
https://twitter.com/aubertm/bxl
https://twitter.com/barthox/belges
https://twitter.com/Belgique_info/belgium-twitter
https://twitter.com/BeNews/politic-be
https://twitter.com/Benoit_Dupont/dot-be
https://twitter.com/blueclock/belgoids
https://twitter.com/bnox/belgians
https://twitter.com/bnox/brusselsgirlgeekdinners
https://twitter.com/bnox/mechelen
https://twitter.com/bnox/sanomamediabelgium
https://twitter.com/BVLG/leuven
https://twitter.com/BVLG/leuven-2
https://twitter.com/BVLG/leuven-3
https://twitter.com/BVLG/politiek-cdenv
https://twitter.com/BVLG/politiek-groen
https://twitter.com/BVLG/politiek-ldd
https://twitter.com/BVLG/politiek-nva
https://twitter.com/BVLG/politiek-openvld
https://twitter.com/BVLG/politiek-sp-a
https://twitter.com/BVLG/politiek-vlaamsbelang
https://twitter.com/cleverwood/newwaysof
https://twitter.com/condontm/brussels-english
https://twitter.com/davanac/agence-belga
https://twitter.com/davanac/belgium-politics
https://twitter.com/davanac/corelio
https://twitter.com/davanac/ihecs
https://twitter.com/davanac/ipm
https://twitter.com/davanac/mediafin
https://twitter.com/davanac/persgroep
https://twitter.com/davanac/rossel
https://twitter.com/davanac/roularta
https://twitter.com/davanac/rtbf-12
https://twitter.com/davanac/rtl-group
https://twitter.com/davanac/vrt
https://twitter.com/Eurocentrique/brussels
https://twitter.com/l_amande/brussels
https://twitter.com/momobxl/bxlcommunity
https://twitter.com/objectif_web/web-in-be
https://twitter.com/ogillin/belgian-minds
https://twitter.com/papercutny/brussels-bruxelles
https://twitter.com/ransbottyn/klout-top100-august-11
https://twitter.com/ransbottyn/klout-top250-october-11
https://twitter.com/ransbottyn/klout-top500-december-11
https://twitter.com/ransbottyn/klout-top500-september-11
https://twitter.com/tanguypay/belgianplayers
https://twitter.com/tbnv/belgium
https://twitter.com/TVanHoornyck/belgian-politics
https://twitter.com/webmission/entrepreneurbe
https://twitter.com/Wings1980/politics-belgium
https://twitter.com/xavierthiriaux/belgians
https://twitter.com/zedyork/beweb

Some twitter accounts have also been added directly from the site: http://v1n.ch/klout.be/

Files
-----

These are the most important files of this experiment:

* config.inc.php -- Informations relative to Klout API
* db.inc.php -- Databases informations
* dump.sql -- Dump of database in SQL format
* index.php -- The frontend, responsive ;-)
* refresh.php -- Script that refreshes the ranking. You have to apply a CRON on that file (every hour should be OK)
* submit.php -- Script to add new people to the ranking
* import.php -- useful for first import of list.txt file (can also be used to add new usernames to the database)
* list.txt --stores list of usernames to add to the database. to be used for the initial load and/or for updates