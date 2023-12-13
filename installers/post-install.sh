#!/bin/bash
rm packages.json
rm composer.lock
rm .gitignore
rm -rf ./installers

composer install
