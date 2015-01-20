ClooderImagineBundle
=================

This is a bundle inspired by LiipImagineBundle @liip. It makes it easy to manage images either from a folder to find this on the server or remotely. It will download and put them in the cache for reading the images on the site.

Here is an example of use:

	<img src="{{ '/relative/path/to/image.jpg' | filter_exec('thumbnail') }}" />


## Installation

	composer require clooder/imagine-bundle


### Configuration

After installing the bundle, make sure you add this route to your routing:

``` yaml
# app/config/routing.yml

_clooder_imagine:
    resource: .
    type:     clooder_imagine
```


## Basic Usage

This bundle works by configuring a set of filters and then applying those
filters to images inside a template So, start by creating some sort of filter
that you need to apply somewhere in your application. For example, suppose
you want to thumbnail an image to a size of 295x393 pixels:

``` yaml
# app/config/config.yml

clooder_imagine:
    driver: imagick
    cache_directory: /media/cache
    filters_configuration:
        295x393:
            quality: 100
            filters:
                thumbnail: { size: [295, 393], mode: outbound }
```

You've now defined a filter set called `my_thumb` that performs a thumbnail transformation.
We'll learn more about available transformations later, but for now, this
new filter can be used immediately in a template:

``` jinja 
<img src="{{ '/relative/path/to/image.jpg' | imagine_filter('295x393') }}" />
```

Or if you're using PHP templates:

``` php
<img src="<?php $this['imagine']->filter('/relative/path/to/image.jpg', '295x393') ?>" />
```
Or a remote image

``` jinja 
<img src="{{ 'http://example.com/image.jpg' | imagine_filter('295x393') }}" />
```

If you wish you can remove all the generated cache via this command:

		app/console clooder:imagine:clear:cache
