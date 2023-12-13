#!/bin/bash
rm packages.json
rm composer-lock.json
rm .gitignore
rm -rf ./installers

composer install
