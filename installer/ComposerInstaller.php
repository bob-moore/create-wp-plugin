<?php

namespace Devkit\PluginInstaller;

use Composer\Script\Event;

class ComposerInstaller
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
     * Whether or not to install Timber support
     *
     * @var bool
     */
    protected bool $timber_support = false;
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
        string $plugin_namespace = '',
        string $plugin_name = '',
        string $plugin_slug = '',
        string $plugin_uri = '',
        string $description = '',
        string $author_name = '',
        string $author_uri = '',
        string $author_email = '',
        bool $timber_support
    )
    {
        $constructor_params = get_defined_vars();

        foreach ( $constructor_params as $key => $value ) {
            if ( property_exists( $this, $key ) && ! empty( $value ) ) {
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

        $installer = new ComposerInstaller( 
            $io->ask( 'Namespace: ' ),
            $io->ask( 'Plugin Name: ' ),
            $io->ask( 'Slug: ' ),
            $io->ask( 'Plugin URI: ' ),
            $io->ask( 'Plugin Description: ' ),
            $io->ask( 'Author Name: ' ),
            $io->ask( 'Author URI: ' ),
            $io->ask( 'Author Email: ' ),
            $io->askConfirmation( 'Install Timber support? [y/n] ' )
        );

        $installer->createComposerFile();

        $installer->createPluginFiles( dirname( __DIR__, 1 ) . '/vendor/devkit/plugin/inc/*' );

        $installer->replaceStrings( dirname( __DIR__, 1 ) . '/vendor/devkit/plugin/plugin.php' );

        $installer->moveFiles();

        $io->write( 'Plugin installed, Enjoy!' );
    }
    /**
     * Inject variables into (new) composer.json file
     *
     * @return void
     */
    public function createComposerFile(): void
    {
        $dir = dirname( __DIR__, 1 );

        $composer = json_decode( file_get_contents( $dir . '/vendor/devkit/plugin/src/composer.json' ), true );

        $name = explode( '\\', $composer['name'] );

        $vender = array_shift( $name );

        $package = implode( '-', $name );
        
        $composer['name'] = rtrim( strtolower( $vender . '/' . $package ), '/' );

        $composer['description'] = $this->description;

        $composer['autoload']['psr-4'] = [
            $this->plugin_namespace . '\\' => 'inc/'
        ];
        $composer['extra']['wpify-scoper']['prefix'] = "{$this->plugin_namespace}\\Deps";

        file_put_contents( $dir . '/vendor/devkit/plugin/src/composer.json', json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );

        if ( ! $this->timber_support ) {
            $composer_deps = json_decode( file_get_contents( $dir . '/vendor/devkit/plugin/src/composer-deps.json' ), true );

            unset( $composer_deps['require']['timber/timber'] );

            file_put_contents( $dir . '/vendor/devkit/plugin/src/composer-deps.json', json_encode( $composer_deps, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );

            $this->removeLine( $dir . '/vendor/devkit/plugin/src/inc/Main.php', "Controllers\\Compiler::class" );
        }
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
        $path = empty( $path ) ? dirname( __DIR__, 1 ) . '/vendor/devkit/plugin/inc/*' : $path;

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
    /**
     * Remove an entire line that contains a string
     *
     * @param string $file : the file to search.
     * @param string $remove : the string to search for.
     *
     * @return void
     */
    function removeLine( string $file, string $remove ): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES );
        
        foreach ($lines as $key => $line ) {
            if ( str_contains( $line, $remove ) ) {
                unset( $lines[$key] );
            }
        }
        
        $data = implode( PHP_EOL, $lines );

        file_put_contents( $file, $data );
    }
    /**
     * Move files from vendor directory to root
     *
     * @return void
     */
    public function moveFiles(): void
    {
        shell_exec( 'rm ./composer.lock && rm composer.json && rm packages.json' );
        shell_exec( 'mv ./vendor/devkit/plugin/* ./ && rm -rf ./vendor' );

        if ( ! $this->timber_support ) {
            shell_exec( 'rm -f ./inc/Controllers/Compiler.php' );
            shell_exec( 'rm -f ./inc/Services/Compiler.php' );
        }

        // shell_exec( 'composer install -d ./src' );
        // shell_exec( 'cat <<END >plugin.code-workspace
        // {
        //     "folders": [
        //         {
        //             "path": "."
        //         }
        //     ],
        //     "settings": {
        //         "intelephense.environment.includePaths" : [],
        //         "phpcs.enable": true,
        //         "phpcs.standard": "./tests/phpcs.xml",
        //         "phpcs.executablePath": "./vendor/bin/phpcs",
        //         "phpcs.showWarnings": true,
        //         "phpcs.showSources": true,
        //         "phpcs.composerJsonPath": "./composer.json",
        //         "phpcs.errorSeverity": 6,
        //         "php.suggest.basic": true,
        //         "git.ignoreLimitWarning": true,
        //         "editor.rulers": [
        //             80,
        //             120
        //         ]
        //     }
        // }' );
        shell_exec( 'rm -rf ./installer' );
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
            'PLUGIN_NAMESPACE'                                       => $this->plugin_namespace,
            'PLUGIN_NAME'                                            => $this->plugin_name,
            'PLUGIN_SLUG'                                            => $this->plugin_slug,
            'PLUGIN_URI'                                             => $this->plugin_uri,
            'PLUGIN_DESCRIPTION'                                     => $this->description,
            'AUTHOR_NAME'                                            => $this->author_name,
            'AUTHOR_URI'                                             => $this->author_uri,
            'AUTHOR_EMAIL'                                           => $this->author_email,
            'Devkit\Plugin'                                          => $this->plugin_namespace,
            'Devkit Plugin Boilerplate'                              => $this->plugin_name,
            'devkit_plugin'                                          => $this->plugin_slug,
            'https://github.com/bob-moore/Devkit-Plugin-Boilerplate' => $this->plugin_uri,
            'Bob Moore'                                              => $this->author_name,
            'https://www.bobmoore.dev'                               => $this->author_uri,
            'bob@bobmoore.dev'                                       => $this->author_email,
        ];

        $content = file_get_contents( $file );
            
        foreach ( $replacements as $key => $value ) {
            $content = str_replace( $key, $value, $content );
        }
        
        file_put_contents( $file, $content );
    }

}
