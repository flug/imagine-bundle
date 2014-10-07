<?php


namespace Clooder\ImagineBundle\Factory;


use Clooder\ImagineBundle\Configuration\FilterConfiguration;
use Imagine\Filter\Transformation;
use Symfony\Component\Filesystem\Filesystem;

class ImagineFactory
{

    private $driver;
    private $pathCache;
    private $fs;
    private $kernelRootDir;

    public function __construct(
        $driver,
        $pathCache,
        Filesystem $filesystem,
        $kernelRootDir
    )
    {
        $this->driver        = $driver;
        $this->pathCache     = $pathCache;
        $this->fs            = $filesystem;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function get()
    {

        switch ($this->driver) {
            case 'gd':
            default:
                return new \Imagine\Gd\Imagine();
                break;
            case 'imagick':
                return new \Imagine\Imagick\Imagine();
                break;
            case 'gmagick':
                return new \Imagine\Gmagick\Imagine();
                break;

        }
    }

    private function size($size, $origineSize)
    {

        list ($origineWidth, $origineHeight) = $origineSize;

        $height = (int)(($size->getWidth() / $origineWidth) * $origineHeight);
        $width  = (int)(($size->getHeight() / $origineHeight) * $origineWidth);


        return new \Imagine\Image\Box($width, $height);
    }

    private function buildCachePath($cacheDirectory, $confiqurationCall, $type)
    {

        $returnToWebRoot = '/../web/';
        $baseCache       = $this->kernelRootDir . $returnToWebRoot . $cacheDirectory;
        $ds              = DIRECTORY_SEPARATOR;
        if (!$this->fs->exists($baseCache)) {
            $this->fs->mkdir($baseCache);
        }

        $directoryConfiguration = $baseCache . $ds . $confiqurationCall . $ds . $type;

        if (!$this->fs->exists($directoryConfiguration)) {
            $this->fs->mkdir($directoryConfiguration);
        }

        return $directoryConfiguration;
    }

    public function build($filePath, $filter)
    {
        $returnToWebRoot = '/../web/';
        if (!strpos($filePath, 'tmp')) {
            $filePath = $this->kernelRootDir . '/../web' . $filePath;
        }
        if (!$this->fs->exists($filePath)) {
            $filePath = $filter->getFileNotFound();
        }

        $filename = current(array_reverse(explode('/', $filePath)));

        $webPath  = $filter->getCacheDirectory() . DIRECTORY_SEPARATOR .
            $filter->getFilterCall() . DIRECTORY_SEPARATOR .
            $filter->getType() . DIRECTORY_SEPARATOR .
            $filename;
        $fullPath = $this->buildCachePath($filter->getCacheDirectory(), $filter->getFilterCall(), $filter->getType()) . '/' . $filename;

        if (!$this->fs->exists($fullPath)) {
            $imagine  = $this->get();

            $transformation = new Transformation();

            if(!strpos($filePath, "/../web"))
            {
                $filePath = $this->kernelRootDir. $returnToWebRoot  .$filePath;
            }
            $transformation->thumbnail($this->size($filter, getimagesize($filePath), $filter->getMode()))->save($fullPath);

            $transformation->apply($imagine->open($filePath));


        }

        return $webPath;
    }

}
