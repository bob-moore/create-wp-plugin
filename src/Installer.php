<?php

namespace Devkit\PluginBuilder;

class Installer
{

    public static function install( $event ) {
        var_dump( get_class($event) );
        $io = $event->getIO();
        $user = $io->ask( 'Enter your Username: ');
        echo $user;
    }
}