<?php
/**
 * Bootstrap file for unit tests.
 *
 * @package Placeholder\Plugin
 */
require_once dirname( __DIR__, 2 ).'/vendor/autoload.php';
require_once dirname( __DIR__, 2 ) . '/vendor/scoped/autoload.php';
require_once dirname( __DIR__, 2 ) . '/vendor/scoped/scoper-autoload.php';
require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

require_once dirname(  __FILE__ ) . '/wp-function-mocks.php';

define('TEST_UNIT_PACKAGE_NAME', 'placeholder_plugin' );

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();