#!/bin/bash

if [ "$PHPDOC" = 1 ]; then
	sudo apt-get -qq update;
	sudo apt-get -qq install graphviz;
fi;

git clone https://github.com/FriendsOfCake/travis.git --depth 1 ../travis;
../travis/before_script.sh;
if [ "$PHPCS" != 1 ]; then
	echo "
		CakePlugin::load('HttpSource', array('bootstrap' => true, 'routes' => true));
		CakePlugin::load('ElasticsearchSource', array('bootstrap' => false, 'routes' => true));
	" >> ../cakephp/app/Config/bootstrap.php;
	mv ../cakephp/app/Config/database.php ../cakephp/app/Config/database-default.php;
	elasticsearchTest=;
	cat ../cakephp/app/Config/database-default.php | sed "s/class DATABASE_CONFIG {/class DATABASE_CONFIG extends DB_CONFIG {/" > ../cakephp/app/Config/database.php;
	echo "class DB_CONFIG {
			public \$elasticsearchTest = array(
				'datasource' => 'ElasticsearchSource.Http/ElasticsearchSource',
				'host' => 'localhost',
				'prefix' => '',
				'port' => 9200,
				'timeout' => 5
			);
		}" >> ../cakephp/app/Config/database.php;
	echo "<?php
		require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
		require_once dirname(dirname(dirname(__FILE__))) . '/lib/Cake/Console/ShellDispatcher.php';
		return ShellDispatcher::run(\$argv);
	" > ../cakephp/app/Console/cake.php;
	echo "Configure::write('debug', 0);" >> ../cakephp/app/Config/core.php;
fi;
if [ "$PHPCS" = 1 ]; then
	rm -rf ~/.phpenv/versions/$(phpenv version-name)/pear/PHP/CodeSniffer/Standards/CakePHP;
	git clone https://github.com/imsamurai/cakephp-codesniffer.git --depth 1 ~/.phpenv/versions/$(phpenv version-name)/pear/PHP/CodeSniffer/Standards/CakePHP;
fi;

curl -XGET '127.0.0.1:9200' -vvvv;
curl -XPUT '127.0.0.1:9200/test_index';