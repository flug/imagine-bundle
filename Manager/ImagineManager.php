<?php


namespace Clooder\ImagineBundle\Manager;


use Clooder\ImagineBundle\Configuration\FilterConfiguration;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class ImagineManager
{

    private $filterConfiguration;
    private $factory;
    private $fs;

    private $filePath;
    private $filter;
    private $kernelRootDir;
    const  WEB_ROOT_DIR = '/../web';

    public function __construct(FilterConfiguration $filterConfiguration,
                                $factory,
                                Filesystem $filesystem,
                                $kernelRootDir)
    {
        $this->filterConfiguration = $filterConfiguration;
        $this->factory             = $factory;
        $this->fs                  = $filesystem;
        $this->kernelRootDir       = $kernelRootDir . self::WEB_ROOT_DIR;
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


    public function get(Request $request, $filter, $localPath)
    {
        $ds              = DIRECTORY_SEPARATOR;
        $cacheDirectory  = $this->filterConfiguration->getCacheDirectory();
        $pathConstructor = $this->kernelRootDir;
        $pathConstructor .= $cacheDirectory;
        if (substr($cacheDirectory, -1) != $ds) {
            $pathConstructor .= $ds;
        }
        $pathConstructor .= $filter . $ds . $localPath;

        $format = pathinfo($pathConstructor, PATHINFO_EXTENSION);
        $format = $format ? : 'png';

        $quality = empty($config['quality']) ? 100 : $config['quality'];

        $image = $this->factory->get();

        $contentType = $request->getMimeType($format);
        $imageShow = $image->open($pathConstructor)->show($format, ['jpeg_quality'=> $quality]);


        if (empty($contentType)) {
            $contentType = 'image/' . $format;
        }

        return new Response($imageShow, 200, array('Content-Type' => $contentType));
    }
} 