<?php

namespace Devkit\PluginBuilder;

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