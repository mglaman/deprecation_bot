#!/usr/bin/env bash
curl -O https://ftp.drupal.org/files/projects/drupal-8.7.x-dev.tar.gz
tar -xzf drupal-8.7.x-dev.tar.gz
cd drupal-8.7.x-dev

if [ -n "$ProjectName" ]; then
	composer config minimum-stability dev
	composer config prefer-stable false
    composer require drupal/$ProjectName
fi

drupal-check -d $CheckPath --format junit > ../report.xml
