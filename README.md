
# Comfy Easy Admin Bundle

This bundle adds the edition interface to easy admin so that admins can configure their site using comfy bundle. 

Check Comfy bundles documentation [here](https://github.com/oliverde8/comfyBundle)
![alt text](docs/example.png)

## Install

```shell
composer require oliverde/comfy-easy-admin-bundle
```

To add a link to the menu edit your `DashnoardController` to inject the MenuConfigurator service: 

```php
protected MenuConfigurator $menuConfigurator;

/**
 * DashboardController constructor.
 * @param ConfigInterface $testConfig
 */
public function __construct(MenuConfigurator $menuConfigurator)
{
    $this->menuConfigurator = $menuConfigurator;
}
```

and now add the Menu link

```php
    public function configureMenuItems(): iterable
    {
        /** Other menu elements .... */
        yield $this->menuConfigurator->getMenuItem();
    }
```

Finally add the add router. 

```yaml
comfy_bundle:
    resource: '@oliverde8ComfyEasyAdminBundle/Controller'
    type: annotation
    prefix: /admin
```

You are ready to go, to create configuration elements check comfy bundles [documentation](https://github.com/oliverde8/comfyBundle)

## TODO

- [ ] When scope select is changed redirect to correct page. :warning:
- [ ] Add default always true voter & document howto change.
- [ ] Fix after submit not refreshing for data.
- [ ] Add validation to the config controller for both scope and config path
- [ ] Allow config controller to load without a config path.
