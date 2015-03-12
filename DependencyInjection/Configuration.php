<?php


namespace Clooder\ImagineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('clooder_imagine');

        $rootNode->children()
            ->scalarNode('cache_directory')->defaultValue('%kernel.root_dir%/media/cache')->end()
            ->end();

        return $treeBuilder;
    }
}
