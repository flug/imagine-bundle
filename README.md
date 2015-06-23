ClooderImagineBundle
=================

This bundle require ["liip/imagine-bundle"](https://github.com/liip/LiipImagineBundle) , so you need to follow the documentation @liip installation if it is not already.

## Installation

    composer require clooder/imagine-bundle : 2.*@dev

### Configuration

Add in you AppKernel :

```php

    # AppKernel.php

    $bundles  = array(
        new  Clooder\ImagineBundle\ClooderImagineBundle()
    );

```
After installing the bundle:

``` yaml

# app/config/config.yml

clooder_imagine:
    cache_directory: %kernel.root_dir%/../web/uploads

#and add in liip configuration : 

data_loader: clooder_loader

```
