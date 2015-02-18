<?php

if(!defined('_PS_VERSION_'))
    exit;

class PiModule extends Module{


    public function __construct(){


        $this->name = 'pimodule';
        $this->tab = 'Administration';
        $this->version= '1.0.0';
        $this->author='PimoTeamolo';
        $this->need_instance='1';
        $this->ps_version_compilancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Pimodule');
        $this->description = $this->l('C\'est le module du Swaggymolo.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');


        if (!Configuration::get('PIMODULE_NAME')){
            $this->warning = $this->l('No name provided');
        }

    }

    public function install(){

        if(!parent::install() || !Configuration::updateValue('MYMODULE_NAME','Pimodule')){
               return false;
        }

        return true;
    }

}