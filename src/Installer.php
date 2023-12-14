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
        $plugin_slug  = $io->ask( 'Slug: ', strtolower( str_replace( '\\', '_', $namespace ) ) );
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

        self::moveFiles();
    }

    protected static function moveFiles(): void
    {
        shell_exec( 'pwd' );
        shell_exec( 'mv ./src_real/* ./' );
        
    }
    /**
     * Replace namespace string in composer.json file
     *
     * @param string $namespace : the namespace to install.
     *
     * @return void
     */
    protected static function composerJson( array $args ): void
    {
        $dir = dirname( __DIR__, 1 );
        $composer = json_decode( file_get_contents( $dir . '/composer.json' ), true );
        
        $composer['name'] = $name;

        $composer['description'] = $description;

        $composer['autoload']['psr-4'] = [
            $namespace . '\\' => 'inc/'
        ];
        $composer['extra']['wpify-scoper']['prefix'] = "{$namespace}\\Deps";
        $composer['extra']['wpify-scoper']['autorun'] = true;
        file_put_contents( $dir . '/composer.json', json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
    }
}