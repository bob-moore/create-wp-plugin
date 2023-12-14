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
        var_dump(get_defined_vars());
        // $this->setNamespace( $plugin_namespace );
        // $this->setPluginName( $plugin_name );
        // $this->setPluginSlug( $plugin_slug );
        // $this->setPluginUri( $plugin_uri );
        // $this->setDescription( $description );
        // $this->setAuthorName( $author_name );
        // $this->setAuthorUri( $author_uri );
        // $this->setAuthorEmail( $author_email );
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

        // $installer->moveFiles();
    }
    /**
     * Move files from vendor directory to root
     *
     * @return void
     */
    public function moveFiles(): void
    {
        shell_exec( 'mv ./vendor/devkit/plugin-boilerplate/* ./' );   
    }
    /**
     * Inject variables into (new) composer.json file
     *
     * @param array $args
     *
     * @return void
     */
    public function createComposerFile( array $args ): void
    {
        $dir = dirname( __DIR__, 1 );

        $composer = json_decode( file_get_contents( $dir . '/composer.json' ), true );
        
        $composer['name'] = strtolower( str_replace( '\\', '/', $this->namespace ) );

        $composer['description'] = $description;

        $composer['autoload']['psr-4'] = [
            $namespace . '\\' => 'inc/'
        ];
        $composer['extra']['wpify-scoper']['prefix'] = "{$namespace}\\Deps";
        $composer['extra']['wpify-scoper']['autorun'] = true;
        file_put_contents( $dir . '/composer.json', json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
    }
    /**
     * Set namespace
     *
     * @param string $namespace : Namespace to set.
     *
     * @return void
     */
    public function setNamespace( string $namespace = '' ): void
    {
        $this->namespace = ! empty( $namespace ) ? $namespace : 'Devkit\Plugin';
    }
    /**
     * Set plugin name
     *
     * @param string $plugin_name : Plugin name to set.
     *
     * @return void
     */
    public function setPluginName( string $plugin_name = '' ): void
    {
        $this->plugin_name = ! empty( $plugin_name ) ? $plugin_name : 'Devkit Plugin';
    }
    /**
     * Set plugin slug
     *
     * @param string $plugin_slug : Plugin slug to set.
     *
     * @return void
     */
    public function setPluginSlug( string $plugin_slug = '' ): void
    {
        $this->plugin_slug = ! empty( $plugin_slug ) ? $plugin_slug : 'devkit_plugin';
    }
    /**
     * Set plugin uri
     *
     * @param string $plugin_uri : Plugin uri to set.
     *
     * @return void
     */
    public function setPluginUri( string $plugin_uri = '' ): void
    {
        $this->plugin_uri = ! empty( $plugin_uri ) ? $plugin_uri : 'PLUGIN URI';
    }
    /**
     * Set plugin description
     *
     * @param string $description : Plugin description to set.
     *
     * @return void
     */
    public function setDescription( string $description = '' ): void
    {
        $this->description = ! empty( $description ) ? $description : 'Devkit Plugin Boilerplate';
    }
    /**
     * Set author name
     *
     * @param string $author_name : Author name to set.
     *
     * @return void
     */
    public function setAuthorName( string $author_name = '' ): void
    {
        $this->author_name = ! empty( $author_name ) ? $author_name : 'AUTHOR NAME';
    }
    /**
     * Set author uri
     *
     * @param string $author_uri : Author uri to set.
     *
     * @return void
     */
    public function setAuthorUri( string $author_uri = '' ): void
    {
        $this->author_uri = ! empty( $author_uri ) ? $author_uri : 'AUTHOR URI';
    }
    /**
     * Set author email
     *
     * @param string $author_email : Author email to set.
     *
     * @return void
     */
    public function setAuthorEmail( string $author_email = '' ): void
    {
        $this->author_email = ! empty( $author_email ) ? $author_email : 'AUTHOR EMAIL';
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