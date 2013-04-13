<?php

namespace QoopLmao\FileUploaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class QoopLmaoFileUploaderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->loadClasses($config, $container, $loader);
        $this->loadEntityBlocks($config, $container, $loader);
        $this->loadFormTypes($config, $container, $loader);
        $this->loadGeneratorBlock($config, $container, $loader);

        $loader->load('services/listeners.yml');
    }

    private function loadClasses(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setParameter('qoop_lmao_file_uploader.upload.entity_class', $config['upload_class']);
        $container->setParameter('qoop_lmao_file_uploader.upload.manager_class', $config['upload_manager_class']);
    }

    private function loadEntityBlocks(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $entities = array(
            'upload',
        );

        foreach ($entities as $entity)
        {
            $loader->load(sprintf('services/%s.yml', $entity));

            $this->remapParametersNamespaces($entity, $config[$entity], $container, array(
                'form' => 'qoop_lmao_file_uploader.%s.form.%s',
            ));

            if (!empty($config[$entity]['manager']))
            {
                $container->setAlias(sprintf('qoop_lmao_file_uploader.%s.manager', $entity), $config[$entity]['manager']);
            }
        }
    }

    private function loadFormTypes(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $formTypes = array(
            'multiple_file_upload',
        );

        foreach ($formTypes as $type)
        {
            $loader->load(sprintf('services/form_type_%s.yml', $type));
        }
    }

    private function loadGeneratorBlock(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('services/generator.yml');

        $generator = $config['generator'];

        if ($generator == 'liip_imagine')
        {
            $generator = 'qoop_lmao_file_uploader.generator.liip_imagine';
        }
        elseif ($generator == 'null')
        {
            $generator = 'qoop_lmao_file_uploader.generator.null';
        }

        $container->setAlias('qoop_lmao_file_uploader.generator', $generator);
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces($entity, array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $entity, $name), $value);
                }
            }
        }
    }

}
