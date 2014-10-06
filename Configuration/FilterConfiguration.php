<?php


namespace Clooder\ImagineBundle\Configuration;


class FilterConfiguration
{


    private $filters;


    private $quality,
        $width,
        $height,
        $mode,
        $type;

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        if ($mode == 'inset') {
            $this->mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }


    private $cacheDirectory;
    private $kernelRootDir;

    public function __construct(
        array $filters = [],
        $cacheDirectory,
        $fileNotFount)
    {
        $this->filters        = $filters;
        $this->cacheDirectory = $cacheDirectory;
        $this->fileNotFound   = $fileNotFount;
    }

    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }
    public function getFileNotFound()
    {
        return $this->fileNotFound;
    }

    public function get($filter)
    {
        if (empty($this->filters[$filter])) {
            throw new \RuntimeException('Filter not defined: ' . $filter);
        }
        $this->filterCall = $filter;
        $filterCall       = $this->filters[$filter];
        $this->setQuality($filterCall['quality']);
        $this->setFilterCalling($filterCall['filters']);

        return $this;
    }

    private $filterCall;

    public function getFilterCall()
    {
        return $this->filterCall;
    }

    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    public function setFilterCalling($filterCalling)
    {
        $currentFilter = current($filterCalling);
        $this->setType(key($filterCalling))
            ->setHeight($currentFilter['size'][1])
            ->setWidth($currentFilter['size'][0])
            ->setMode($currentFilter['mode']);
    }


    public function set($filter, array $config = [])
    {
        return $this->filters[$filter] = $config;
    }
}
