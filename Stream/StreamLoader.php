<?php

namespace Clooder\ImagineBundle\Stream;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Yaml;

class StreamLoader implements LoaderInterface
{
    private $client;
    private $fs;
    private $kr;
    private $cacheDirectoryVerifcation;
    private $filename;
    private $cacheDirectory;

    public function __construct(Client $client, Filesystem $fs, $kernelRoot)
    {
        $this->client = $client;
        $this->fs     = $fs;
        $this->kr     = $kernelRoot;

        $cacheResources = $this->kr . "/cache/clooder/imagines";
        $this->filename = "etags.yml";
        if (!$this->fs->exists($cacheResources)) {
            $this->fs->mkdir($cacheResources);
        }

        $this->cacheDirectoryVerifcation = $cacheResources;

    }


    function find($path)
    {
        return $this->findFileCache($path);
    }


    private function findFileCache($path)
    {
        $response     = $this->client->head($path);
        $lastModified = current($response->getHeaderAsArray('last-modified'));
        $formatDate   = (new \DateTime($lastModified))->getTimestamp();

        return $this->inspectMediaCache($path, $formatDate);


    }

    private function inspectMediaCache($path, $formatDate)
    {
        $signature     = md5($path);
        $pathFileCache = $this->cacheDirectoryVerifcation . DIRECTORY_SEPARATOR . $signature . "_" . $this->filename;

        if (!$this->fs->exists($pathFileCache)) {
            $this->fs->touch($pathFileCache);
        }

        $informationMediaCache = Yaml::parse(file_get_contents($pathFileCache));


        if ($informationMediaCache == null) {
            $fileInformation = ['last-modified' => $formatDate];
            $yaml            = (new Dumper())->dump($fileInformation);
            $this->fs->dumpFile($pathFileCache, $yaml);

        } else {

            if ($informationMediaCache['last-modified'] != $formatDate) {
                $this->fs->remove($pathFileCache);
                $this->inspectMediaCache($path, $formatDate);
            } else {
                $ext      = current(array_reverse(explode(".", $path)));
                $filePath = $this->cacheDirectory . DIRECTORY_SEPARATOR . $signature . "." . $ext;
                if ($this->fs->exists($filePath)) {
                    return file_get_contents($filePath);
                } else {
                    $content = $this->getContentMedia($path);
                    $this->fs->dumpFile($filePath, $content);

                    return $content;
                }

            }


        }

    }

    private function getContentMedia($path)
    {
        try {
            $response = $this->client->get($path);

            return $response->getBody()->getContents();
        } catch (RequestException $ex) {
            echo $ex->getRequest();
        }

    }


    public function setCacheDirectory($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
        if (!$this->fs->exists($this->cacheDirectory)) {
            $this->fs->mkdir($this->cacheDirectory);
        }
    }

}