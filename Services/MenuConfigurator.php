<?php

namespace oliverde8\ComfyEasyAdminBundle\Services;


use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuConfigurator
{
    protected ConfigManagerInterface $config;

    protected TranslatorInterface $translator;

    /**
     * MenuConfigurator constructor.
     * @param ConfigManagerInterface $config
     */
    public function __construct(ConfigManagerInterface $config, TranslatorInterface  $translator)
    {
        $this->config = $config;
        $this->translator = $translator;
    }


    public function getMenuItem()
    {
        $name = $this->translator->trans('comfy.config');
        return MenuItem::linktoRoute($name, 'fas fa-sliders-h', "comfy_configs");
    }
}
