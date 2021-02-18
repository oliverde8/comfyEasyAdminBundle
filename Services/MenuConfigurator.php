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
        $menuItem = $this->getRecursiveMenuItem(0, '', $this->config->getAllConfigs()->getArray());
        if (is_null($menuItem)) {
            return MenuItem::linktoRoute("No config found!", null, "comfy_configs");
        }

        return $menuItem;
    }

    protected function getRecursiveMenuItem($level, $parent, $configItems)
    {
        foreach ($configItems as $key => $config) {
            if (is_object($config)) {
                return null;
            }
            $child = $this->getRecursiveMenuItem($level + 1,  $parent . "." . $key, $config);

            if (is_null($child)) {
                $name = $this->translator->trans('comfy.config');
                return MenuItem::linktoRoute($name, 'fas fa-sliders-h', "comfy_configs", ['config' => $parent . "." . $key]);
            } else {
                return $child;
            }
        }
    }
}
