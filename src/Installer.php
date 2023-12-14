<?php

namespace Devkit\PluginBuilder;

class Installer
{

    public static function install( $c ) {
        var_dump( get_class_methods($c) );
    }
}