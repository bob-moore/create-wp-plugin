<?php

namespace Devkit\PluginBuilder;

use Composer\Script\Event;

class Installer
{

    public static function install( Event $event ): void
    {
        var_dump( get_class($event) );
        $io = $event->getIO();

        $namespace    = $io->ask( 'Namespace: ' );
        $plugin_name  = $io->ask( 'Plugin Name: ' );
        $plugin_slug  = $io->ask( 'Namespace: ', strtolower( str_replace( '\\', '_', $namespace ) ) );
        $plugin_uri   = $io->ask( 'Plugin URI: ' );
        $description  = $io->ask( 'Plugin Description: ' );
        $author_name  = $io->ask( 'Author Name: ' );
        $author_uri   = $io->ask( 'Author URI: ' );
        $author_email = $io->ask( 'Author Email: ' );

        $args = [
            'namespace'    => $namespace,
            'plugin_name'  => $plugin_name,
            'plugin_slug'  => $plugin_slug,
            'plugin_uri'   => $plugin_uri,
            'description'  => $description,
            'author_name'  => $author_name,
            'author_uri'   => $author_uri,
            'author_email' => $author_email,
        ];

        print_r( $args );

        // $args = [
        //     'vendor'       => $argv[1] ?? 'Devkit',
        //     'project'      => $argv[2] ?? 'Plugin',
        //     'plugin_name'  => $argv[3] ?? 'Custom Plugin Name',
        //     'slug'         => $argv[4] ?? 'custom_plugin',
        //     'plugin_uri'   => $argv[5] ?? 'https://github.com/bob-moore/wp-framework-core',
        //     'description'  => $argv[6] ?? '',
        //     'author_name'  => $argv[7] ?? 'Bob Moore',
        //     'author_uri'   => $argv[8] ?? 'https://www.bobmoore.dev',
        //     'author_email' => $argv[8] ?? 'bob@bobmoore.dev',
            
        // ];
    }
}