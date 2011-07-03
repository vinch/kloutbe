Belgians on Klout
=================

Ranking of Belgian influencers on [Klout](http://www.klout.com). Inspired by [Data News](http://datanews.rnews.be/fr/ict/actualite/blog/qui-devez-vous-suivre-dans-la-twittosphere-belge/article-1195010830793.htm) and based on [this Twitter list](https://twitter.com/Marievh/belgessurtwitter) (see list.txt).

Files
-----

These are the most important files of this experiment:

* db.inc.php -- Databases informations
* dump.sql -- Dump of database in SQL format
* import.php -- useful for first import of list.txt file (after that, it becomes useless)
* index.php -- The frontend, responsive ;-)
* refresh.php -- Script that refreshes the ranking. You have to apply a CRON on that file (every hour should be OK).