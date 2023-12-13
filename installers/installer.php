<?php
/**
 * Ensure being called from bash
 */
if ( ! isset( $argv ) ) {
    return;
}

$args = [
    'vendor'       => $argv[1] ?? 'Devkit',
    'project'      => $argv[2] ?? 'Plugin',
    'plugin_name'  => $argv[3] ?? 'Custom Plugin Name',
    'slug'         => $argv[4] ?? 'custom_plugin',
    'plugin_uri'   => $argv[5] ?? 'https://github.com/bob-moore/wp-framework-core',
    'description'  => $argv[6] ?? '',
    'author_name'  => $argv[7] ?? 'Bob Moore',
    'author_uri'   => $argv[8] ?? 'https://www.bobmoore.dev',
    'author_email' => $argv[8] ?? 'bob@bobmoore.dev',
    
];
$args['namespace'] = $args['vendor']. '\\' . $args['project'];
$args['package'] = strtolower( $args['vendor'] . '/' . $args['project'] );

install_composer_namespace( 
    $args['namespace'],
    $args['package'],
    $args['description']
);

create_plugin_file( $args );

replace_inc_namespace( 
    $args, 
    dirname( __DIR__, 1 ) . '/inc/*'
);

/**
 * Replace namespace string in composer.json file
 *
 * @param string $namespace : the namespace to install.
 *
 * @return void
 */
function install_composer_namespace( string $namespace, string $name, string $description ): void
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


function create_plugin_file( array $args ): void
{
    $dir = dirname( __DIR__, 1 );
    
    $content = file_get_contents( $dir . '/plugin.php' );

    foreach ( $args as $key => $value ) {
        $content = str_replace( '%' . $key . '%', $value, $content );
    }

    file_put_contents( $dir . '/plugin.php', $content );
}
// replace_functions_namespace( $namespace_arg, $slug_arg );
/**
 * Update namespace in inc directory
 *
 * @param string $namespace : namespace to replace default with.
 * @param string $path : path to search for files.
 *
 * @return void
 */
function replace_inc_namespace( array $args, string $path = 'inc/*' ): void
{
    foreach ( glob( $path ) as $filename )
    {
        if ( is_dir( $filename ) ) {
            replace_inc_namespace( $args, $filename . '/*' );
        }
        if ( ! is_file( $filename ) ) {
            continue;
        }
        if ( ! str_contains( $filename, '.php' ) ) {
            continue;
        }
        
        $content = file_get_contents( $filename );
        
        foreach ( $args as $key => $value ) {
            $content = str_replace( '%' . $key . '%', $value, $content );
        }
        
        file_put_contents( $filename, $content );
    }
}
// replace_inc_namespace( $namespace_arg, dirname( __DIR__, 1 ) . '/inc/*' );
