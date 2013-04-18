<?php

namespace QoopLmao\FileUploaderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('qoop_lmao_file_uploader');

        $this->addUploadSection($rootNode);

        return $treeBuilder;
    }

    private function addUploadSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('generator')->defaultValue('null')->cannotBeEmpty()->end()
                ->scalarNode('upload_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('add_user')->defaultValue(false)->cannotBeEmpty()->end()
                ->scalarNode('upload_manager_class')->defaultValue('QoopLmao\FileUploaderBundle\Model\UploadManager')->cannotBeEmpty()->end()
                ->arrayNode('upload')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('manager')->defaultValue('qoop_lmao_file_uploader.upload.manager.default')->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('qoop_lmao_file_uploader_upload')->cannotBeEmpty()->end()
                                ->scalarNode('name')->defaultValue('qoop_lmao_file_uploader_upload_form')->cannotBeEmpty()->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Policy', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

}
