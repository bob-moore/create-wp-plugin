<?php

namespace Devkit\PluginBuilder;

require_once dirname( __DIR__, 1 ) . '/vendor/autoload.php';

use Composer\Script\Event;

class Installer
{

    public static function install( Event $event )
    {
        var_dump( get_class($event) );
        $io = $event->getIO();
        $user = $io->ask( 'Enter your Username: ');
        echo $user;
    }
}