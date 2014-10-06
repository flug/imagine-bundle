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

            ->scalarNode('driver')->defaultValue('gd')
            ->validate()
            ->ifTrue(function ($v) {
                return !in_array($v, array('gd', 'imagick', 'gmagick'));
            })
            ->thenInvalid('Invalid imagine driver specified: %s')
            ->end()
            ->end()
            ->scalarNode('cache_directory')->defaultValue('/media/cache')->end()
            ->scalarNode('file_not_found')->defaultValue('bundles/clooderimagine/images/notfound.png')->end()
            ->arrayNode('filters_configuration')
                ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->fixXmlConfig('filter', 'filters')
                            ->children()
                                ->scalarNode('path')->end()
                                ->scalarNode('quality')->defaultValue(100)->end()
                                ->scalarNode('format')->defaultNull()->end()
                                ->arrayNode('filters')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                ->useAttributeAsKey('name')
                                ->prototype('variable')->end()
                                ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
