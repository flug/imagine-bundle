<?php


namespace Clooder\ImagineBundle\Routing;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Clooder\ImagineBundle\Configuration\FilterConfiguration;

class ClooderLoader extends Loader
{
    private $controllerAction;
    private $cacheDirectory;
    private $filters;

    public function __construct($controllerAction, FilterConfiguration $filterConfiguration)
    {
        $this->controllerAction = $controllerAction;
        $this->filters          = $filterConfiguration->getFilters();
        $this->cacheDirectory   = $filterConfiguration->getCacheDirectory();
    }

    public function load($resource, $type = null)
    {
        $requirements = array('_method' => 'GET', 'filter' => '[A-z0-9_\-]*', 'path' => '.+');
        $routes       = new RouteCollection();

        if (count($this->filters) > 0) {
            foreach ($this->filters as $key => $filter) {
                $pattern = $this->cacheDirectory ;
                $defaults = array(
                    '_controller' => $this->controllerAction ,
                    'filter'      => $key,
                );
                $pattern .= '/'.$key;


                $routeRequirements = $requirements;
                $routeDefaults     = $defaults;
                $routeOptions      = array();

                $routes->add('_imagine_' . $key, new Route(
                    $pattern . '/{path}',
                    $routeDefaults,
                    $routeRequirements,
                    $routeOptions
                ));
            }
        }

        return $routes;

    }

    public
    function supports($resource, $type = null)
    {
        return $type === 'clooder_imagine';
    }
}