#!/bin/bash
composer update
composer dump-autoload
rm -rf ./install
rm packages.json
rm .gitignore
