<?php


namespace Clooder\ImagineBundle\Manager;


use Clooder\ImagineBundle\Configuration\FilterConfiguration;
use Symfony\Component\Filesystem\Filesystem;

class ImagineManager
{

    private $filterConfiguration;
    private $factory;
    private $fs;

    private $filePath;
    private $filter;

    public function __construct(FilterConfiguration $filterConfiguration, $factory, Filesystem $filesystem)
    {
        $this->filterConfiguration = $filterConfiguration;
        $this->factory             = $factory;
        $this->fs                  = $filesystem;
    }


    public function getPath()
    {
        $imagine = $this->factory;
       return $imagine->build(
            $this->filePath,
            $this->filter
        );



    }

    public function setFile($filePath)
    {
        $this->filePath = $filePath;
    }


    public function setFilter($filter)
    {
        $this->filter = $this->filterConfiguration->get($filter);
    }
} 