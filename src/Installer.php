<?php

namespace Devkit\PluginBuilder;

use Composer\Script\Event;

class Installer
{
    /**
     * Namespace to inject into plugin files
     *
     * @var string
     */
    protected string $plugin_namespace = 'Devkit\Plugin';
    /**
     * Name to inject into plugin files
     *
     * @var string
     */
    protected string $plugin_name = 'Devkit Plugin';
    /**
     * Slug to inject into plugin files
     *
     * @var string
     */
    protected string $plugin_slug = 'devkit_plugin';
    /**
     * Plugin URI to inject into plugin files
     *
     * @var string
     */
    protected string $plugin_uri = 'PLUGIN URI';
    /**
     * Plugin description to inject into plugin files
     *
     * @var string
     */
    protected string $description = 'PLUGIN DESCRIPTION';
    /**
     * Author name to inject into plugin files
     *
     * @var string
     */
    protected string $author_name = 'AUTHOR NAME';
    /**
     * Author URI to inject into plugin files
     *
     * @var string
     */
    protected string $author_uri = 'AUTHOR URI';
    /**
     * Author email to inject into plugin files
     *
     * @var string
     */
    protected string $author_email = 'AUTHOR EMAIL';
    /**
     * Protected constructor
     *
     * @param string $namespace : Namespace to set.
     * @param string $plugin_name : Plugin name to set.
     * @param string $plugin_slug : Plugin slug to set.
     * @param string $plugin_uri : URI to the plugin repository or website.
     * @param string $description : Plugin description to set.
     * @param string $author_name : Author name to set.
     * @param string $author_uri : Author uri to set.
     * @param string $author_email : Author email to set.
     */
    protected function __construct(
        string $plugin_namespace,
        string $plugin_name,
        string $plugin_slug,
        string $plugin_uri,
        string $description,
        string $author_name,
        string $author_uri,
        string $author_email,
    )
    {
        $constructor_params = get_defined_vars();

        foreach ( $constructor_params as $key => $value ) {
            if ( property_exists( $this, $key ) ) {
                $this->{$key} = $value;
            }
        }
    }
    /**
     * Initialize the plugin
     *
     * @param Event $event : Composer event.
     *
     * @return void
     */
    public static function init( Event $event ): void
    {
        $io = $event->getIO();

        $installer = new Installer( 
            $io->ask( 'Namespace: ' ),
            $io->ask( 'Plugin Name: ' ),
            $io->ask( 'Slug: ' ),
            $io->ask( 'Plugin URI: ' ),
            $io->ask( 'Plugin Description: ' ),
            $io->ask( 'Author Name: ' ),
            $io->ask( 'Author URI: ' ),
            $io->ask( 'Author Email: ' )
        );

        $installer->moveFiles();

        $installer->createComposerFile();

        $installer->createPluginFiles( dirname( __DIR__, 1 ) . '/inc/*' );
    }
    /**
     * Move files from vendor directory to root
     *
     * @return void
     */
    public function moveFiles(): void
    {
        shell_exec( 'rm -rf ./src && mv ./vendor/devkit/plugin-boilerplate/* ./' );   
    }
    /**
     * Inject variables into (new) composer.json file
     *
     * @return void
     */
    public function createComposerFile(): void
    {
        $dir = dirname( __DIR__, 1 );

        $composer = json_decode( file_get_contents( $dir . '/composer.json' ), true );
        
        $composer['name'] = strtolower( str_replace( '\\', '/', $this->plugin_namespace ) );

        $composer['description'] = $this->description;

        $composer['autoload']['psr-4'] = [
            $this->plugin_namespace . '\\' => 'inc/'
        ];
        $composer['extra']['wpify-scoper']['prefix'] = "{$this->plugin_namespace}\\Deps";

        file_put_contents( $dir . '/composer.json', json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
    }
    /**
     * Replace strings in a given file with the values from the installer
     *
     * @param string $file : full path to the file to perform replacements on.
     *
     * @return void
     */
    public function replaceStrings( string $file ): void
    {
        if ( ! is_file( $file ) ) {
            return;
        }

        $replacements = [
            'PLUGIN_NAMESPACE'   => $this->plugin_namespace,
            'PLUGIN_NAME'        => $this->plugin_name,
            'PLUGIN_SLUG'        => $this->plugin_slug,
            'PLUGIN_URI'         => $this->plugin_uri,
            'PLUGIN_DESCRIPTION' => $this->description,
            'AUTHOR_NAME'        => $this->author_name,
            'AUTHOR_URI'         => $this->author_uri,
            'AUTHOR_EMAIL'        => $this->author_email,
        ];

        $content = file_get_contents( $file );
            
        foreach ( $replacements as $key => $value ) {
            $content = str_replace( $key, $value, $content );
        }
        
        file_put_contents( $file, $content );
    }
    /**
     * Get plugin files to perform replacements on.
     *
     * @param string $path
     *
     * @return void
     */
    public function createPluginFiles( string $path = '' ): void
    {
        $path = empty( $path ) ? dirname( __DIR__, 1 ) . '/inc/*' : $path;

        foreach ( glob( $path ) as $file )
        {
            if ( is_dir( $file ) ) {
                $this->createPluginFiles( $file . '/*' );
            }
            
            if ( ! is_file( $file ) ) {
                continue;
            }

            if ( ! str_contains( $file, '.php' ) ) {
                continue;
            }
            
            $this->replaceStrings( $file );
        }
    }
}