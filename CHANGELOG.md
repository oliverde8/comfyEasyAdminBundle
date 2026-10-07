# 1.1.0

- :star2: Support for symfony 8.0 and EasyAdmin 5 has been added.
- :star2: Config tree only lists visible configs the user is allowed to edit.
- :collision: Config page is registered with `#[AdminRoute]`, route name is now prefixed by the dashboard route (`admin_comfy_configs`). The routing import is no longer needed.
- :collision: Support for EasyAdmin < 4.24 has been dropped.
- :star2: Improved design of the config page, now uses EasyAdmin page title and actions.
- :wrench: Removed jQuery dependency from the config page.
- :wrench: Fix scope being read from the `config` parameter.

# 1.0.0

- :star2: Support for symfony 6.0 has been added.
- :collision: Support for Symfony 4.4 has been dropped.
- :collision: Support for php 7.4 has been dropped.

# 1.0.0 Alpha #3
- :wrench: Fixes for the BC introduced in ` 1.0.0 Alpha #4` of comfy bundle.

# 1.0.0 Alpha #2
- :star2: Improved quality of the form builders. **THIS INTRODUCES A BC TO FORM PROVIDERS**
- :wrench: Fixed scope selector when there is over 2 level of scope

# 1.0.0 Alpha #1
- :confetti_ball: :tada: First release :tada: :confetti_ball:
