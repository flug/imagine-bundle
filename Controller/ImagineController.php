<?php


namespace Clooder\ImagineBundle\Controller;

use Clooder\ImagineBundle\Manager\ImagineManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ImagineController {


    private $imagineManager;
    public function __construct(ImagineManager $imagineManager)
    {
        $this->imagineManager = $imagineManager;

    }

    public function filterAction(Request $request, $path, $filter)
    {

        return $this->imagineManager->get($request,$filter, $path);

    }
} 