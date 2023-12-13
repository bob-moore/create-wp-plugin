#!/bin/bash

read -r -p 'Vendor (for namespace) : ' php_vendor
read -r -p 'Project (for namespace) : ' php_project
read -r -p 'Plugin Name : ' plugin_name
read -r -p 'Plugin Slug : ' plugin_slug
read -r -p 'Plugin URI : ' plugin_uri
read -r -p 'Description : ' description
read -r -p 'Author : ' author
read -r -p 'Author URI : ' author_uri
read -r -p 'Author Email : ' author_email

cat <<END >plugin.code-workspace
{
	"folders": [
		{
			"path": "."
		}
	],
	"settings": {
		"intelephense.environment.includePaths" : [],
		"phpcs.enable": true,
		"phpcs.standard": "./tests/phpcs.xml",
		"phpcs.executablePath": "./vendor/bin/phpcs",
		"phpcs.showWarnings": true,
		"phpcs.showSources": true,
		"phpcs.composerJsonPath": "./composer.json",
		"phpcs.errorSeverity": 6,
		"php.suggest.basic": true,
		"git.ignoreLimitWarning": true,
        "editor.rulers": [
            80,
            120
        ]
	}
}
END



mv ./src/* ./

PHP ./installers/installer.php $php_vendor $php_project $plugin_name $plugin_slug $plugin_uri $description $author $author_uri $author_email
