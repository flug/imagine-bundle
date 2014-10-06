<?php


namespace Clooder\ImagineBundle\Twig;


use Clooder\ImagineBundle\Engine\FilterImagine;

class TwigImagineExtension extends \Twig_Extension
{

    private $im;

    public function __construct(FilterImagine $imagine)
    {
        $this->im = $imagine;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('filter_exec', [$this, 'filterExec'])
        ];
    }

    public function filterExec($pathImage, $filterApply)
    {
        return $this->im->getResolver($pathImage, $filterApply)->getPath();
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'clooder_imagine_extension';
    }
}