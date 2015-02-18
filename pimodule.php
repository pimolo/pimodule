<?php
if(!defined('_PS_VERSION_'))
    exit;

class PiModule extends Module
{

    public function __construct()
    {
        $this->name = 'pimodule';
        $this->tab = 'Administration';
        $this->version = '1.0.0';
        $this->author = 'PimoTeamolo';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Pimodule');
        $this->description = $this->l('C\'est le module du Swaggymolo.');

        $this->confirmUninstall = $this->l('Voulez vous vraiment le désinstaller ?');

        if (!Configuration::get('PIMODULE_NAME'))
            $this->warning = $this->l('No name provided');
    }

    public function install()
    {
        if (!parent::install() ||
            !Configuration::updateValue('PIMODULE_NAME', 'my friend')
        )
            return false;
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall())
            return false;
        return true;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $pimodule = strval(Tools::getValue('PIMODULE_NAME'));
            if (!$pimodule
                || empty($pimodule)
                || !Validate::isGenericName($pimodule)
            )
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            else {
                Configuration::updateValue('PIMODULE_NAME', $pimodule);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Vos options'),
                    'desc' => $this->l('Choisir une option'),
                    'name' => 'PIMODULE_NAME',
                    'required' => true,
                    'values' => array(),

                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true; // false -> remove toolbar
        $helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['PIMODULE_NAME'] = Configuration::get('PIMODULE_NAME');

        return $helper->generateForm($fields_form);
    }

}

