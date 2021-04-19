<?php

namespace oliverde8\ComfyEasyAdminBundle\Controller;


use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use oliverde8\ComfyBundle\Form\Type\ConfigsForm;
use oliverde8\ComfyBundle\Manager\ConfigDisplayManager;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use oliverde8\ComfyEasyAdminBundle\Security\Voter\ConfigEditVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    protected ConfigManagerInterface $configManger;
    protected ScopeResolverInterface $scopeResolver;
    protected ConfigDisplayManager $configDisplayManager;

    /**
     * ConfigController constructor.
     *
     * @param ConfigManagerInterface $configManger
     * @param ScopeResolverInterface $scopeResolver
     * @param ConfigDisplayManager $configDisplayManager
     */
    public function __construct(ConfigManagerInterface $configManger, ScopeResolverInterface $scopeResolver, ConfigDisplayManager $configDisplayManager)
    {
        $this->configManger = $configManger;
        $this->scopeResolver = $scopeResolver;
        $this->configDisplayManager = $configDisplayManager;
    }


    /**
     * @Route("/comfy/configs", name="comfy_configs")
     */
    public function index(Request $request): Response
    {
        $scope = $this->getConfigScopeFromRequest($request);
        $configPath = $this->getConfigPathFromRequest($request);
        $configs = $this->getConfigsForPath($configPath);

        $form = $this->createForm(ConfigsForm::class, ['scope' => $scope, 'configs' => $configs]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // We need to recreate the form because config won't take their inheritance properly into account untill all
                // of them are saved.
                $form = $this->createForm(ConfigsForm::class, ['scope' => $scope, 'configs' => $configs]);
            }
        }

        return $this->render(
            "@oliverde8ComfyEasyAdmin/config.html.twig",
            [
                'form' => $form->createView(),
                'config_path' => $configPath,
                'config_keys' => $this->getConfigKeys($configs),
                'config_tree' => $this->configManger->getAllConfigs()->getArray(),
                'scope' => $scope,
                'scopes' => $this->configDisplayManager->getScopeTreeForHtml(),
            ]
        );
    }


    /**
     * Get the config path to use.
     *
     * @param Request $request
     * @return string
     */
    protected function getConfigPathFromRequest(Request $request): string
    {
        $configPath = $request->get('config',  null);
        $configPath = str_replace(".", "/", $configPath);
        $configPath = ltrim($configPath, '/');

        if (empty($configPath)) {
            $configPath = $this->configDisplayManager->getRecursiveFirstConfigPath($this->configManger->getAllConfigs()->getArray());
            $configPath = ltrim($configPath, '/');
        }

        return $configPath;
    }

    /**
     * Get the scope we are editing the configs for.
     *
     * @param Request $request
     * @return string
     */
    protected function getConfigScopeFromRequest(Request $request): string
    {
        $scope = $this->scopeResolver->getScope($request->get("scope", null));

        if (!$this->scopeResolver->validateScope($scope)) {
            throw new NotFoundHttpException("Unknown scope.");
        }

        return $scope;
    }

    /**
     * Get modifiable configs for this path.
     *
     * @param Request $request
     * @return ConfigInterface[]
     *
     * @throws NotFoundHttpException
     */
    protected function getConfigsForPath(string $configPath): array
    {
        /** @var ConfigInterface[] $configs */
        $configs = $this->configManger->getAllConfigs()->get($configPath, []);
        $configs = $this->filterAllowedConfigs($configs);

        if (empty($configs)) {
            throw new NotFoundHttpException("Unknown config path.");
        }

        return $configs;
    }

    /**
     * @param ConfigInterface[] $configs
     * @return array
     */
    protected function filterAllowedConfigs(array $configs)
    {
        $allowedConfigs = [];
        foreach ($configs as $config) {
            if ($config->isHidden()) {
                continue;
            }

            if ($this->isGranted(ConfigEditVoter::ACTION_NEW, $config)) {
                $allowedConfigs[] = $config;
            }
        }

        return $allowedConfigs;
    }

    /**
     * @param Request $request
     * @param ConfigInterface[] $configs
     */
    protected function getSubmitedData(Request $request, $configs)
    {
        $data = [];
        foreach ($configs as $config) {
            $name = $this->configDisplayManager->getConfigHtmlName($config);
            $data[$config->getPath()] = $request->get($name);
        }
    }

    protected function getConfigKeys(array $configs)
    {
        $configKeys = [];
        foreach ($configs as $config) {
            $configKeys[] = $this->configDisplayManager->getConfigHtmlName($config);
        }

        return $configKeys;
    }
}
