<?php


namespace markorm\_system;


class Commands {

    public static function createMarkOrmScript($event){
        $composer = $event->getComposer();

        $composerFile = $composer->getConfig()->get('vendor-dir') . '/../composer.json';

        $json = json_decode(file_get_contents($composerFile), true);
        $json['scripts']['mark'] = 'php vendor/bin/mark';
        $json['scripts']['build-scheme'] = 'php vendor/bin/mark-orm-build-scheme';


        file_put_contents($composerFile, json_encode($json, JSON_PRETTY_PRINT));
    }

}