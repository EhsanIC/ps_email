<?php
use PrestaShop\PrestaShop\Core\MailTemplate\Layout\LayoutInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCatalogInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCollectionInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeInterface;

class MyEmailThemeModule extends Module 
{
    public function install() 
    {
        return parent::install()
            // This class constant contains 'actionListMailThemes'
            && $this->registerHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK)
        ;
    }

    public function uninstall() 
    {
        return parent::uninstall()
            && $this->unregisterHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK)
        ;
    }

    public function enable() 
    {
        return parent::enable()
            && $this->registerHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK)
        ;
    }

    public function disable() 
    {
        return parent::disable()
            && $this->unregisterHook(ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK)
        ;
    }

    /**
     * @param array $hookParams
     */
    public function hookActionListMailThemes(array $hookParams)
    {
        if (!isset($hookParams['mailThemes'])) {
            return;
        }

        /** @var ThemeCollectionInterface $themes */
        $themes = $hookParams['mailThemes'];

        /** @var ThemeInterface $theme */
        foreach ($themes as $theme) {
            if (!in_array($theme->getName(), ['classic', 'modern'])) {
                continue;
            }

            // Add a layout to each theme (don't forget to specify the module name)
            $theme->getLayouts()->add(new Layout(
                'custom_template',
                __DIR__ . '/mails/layouts/custom_' . $theme->getName() . '_layout.html.twig',
                '',
                $this->name
            ));
        }
    }
}