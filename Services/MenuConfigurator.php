<?php

namespace oliverde8\ComfyEasyAdminBundle\Services;


use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuConfigurator
{
    public function __construct(
        protected ConfigManagerInterface $config,
        protected TranslatorInterface  $translator
    ) {
    }


    public function getMenuItem(string $routeName = 'admin_comfy_configs')
    {
        $name = $this->translator->trans('comfy.config');
        return MenuItem::linkToRoute($name, 'fas fa-sliders-h', $routeName);
    }
}
