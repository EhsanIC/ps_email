<?php


use PrestaShop\PrestaShop\Core\MailTemplate\Layout\LayoutInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCatalogInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCollectionInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeInterface;

class ps_email extends Module {
 

    public function __construct()
    {
        $this->name = 'ps_email';
        $this->author = 'Erfan';
        $this->tab = 'other';
        $this->version = '1.0.0';
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK);   
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstall(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK);
    }


    public function enable($force_all = false)
    {
        return parent::enable()
            && $this->registerHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK);
    }

    public function disable($force_all = false)
    {
        return parent::disable(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK);
    }

    public function hookDisplayHome()
    {
        return 'ufck';
    }

    public function hookActionListMailThemes(array $hookParams)
    {

        if (!isset($hookParams['mailThemes'])) {
            return;
        }

        $themes = $hookParams['mailThemes'];


        foreach ($themes as $theme) {
            if (!in_array($theme->getName(), ['classic', 'modern'])) {
                continue;
            }

            $theme->getLayouts()->add(new Layout(
                'custom_template',
                __DIR__ . '/mails/layouts/custom_' . $theme->getName() . '.html.twig',
                '',
                $this->name
            ));
        }
    }

}