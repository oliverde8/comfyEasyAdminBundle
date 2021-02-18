<?php

namespace oliverde8\ComfyEasyAdminBundle\Controller;


use oliverde8\ComfyBundle\Form\Type\ConfigsForm;
use oliverde8\ComfyBundle\Manager\ConfigDisplayManager;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        // TODO validate scope.
        $scope = $this->scopeResolver->getScope($request->get("scope", null));
        // TODO validate config path.
        $configPath = $request->get('config',  null);
        $configPath = str_replace(".", "/", $configPath);
        $configPath = ltrim($configPath, '/');

        /** @var ConfigInterface[] $configs */
        $configs = $this->configManger->getAllConfigs()->get($configPath);

        $form = $this->createForm(ConfigsForm::class, ['scope' => $scope, 'configs' => $configs]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            foreach ($configs as $config) {
                $configName = $this->configDisplayManager->getConfigHtmlName($config);
                $valueName = 'value:' . $configName;
                $useParentName = 'use_parent:' . $configName;

                if ($data[$useParentName]) {
                    $config->set(null, $scope);
                } else {
                    $config->set($data[$valueName], $scope);
                }
            }

            return $this->redirectToRoute($request->get('_route'), $request->query->all());
        }

        return $this->render(
            "@oliverde8ComfyEasyAdmin/config.html.twig",
            [
                'form' => $form->createView(),
                'config_keys' => $this->getConfigKeys($configs),
                'config_tree' => $this->configManger->getAllConfigs()->getArray(),
                'scope' => $scope,
                'scopes' => $this->configDisplayManager->getScopeTreeForHtml(),
            ]
        );
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
