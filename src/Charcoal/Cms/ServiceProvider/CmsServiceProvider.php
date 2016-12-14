<?php

namespace Charcoal\Cms\ServiceProvider;

// Pimple dependencies
use Pimple\Container;
use Pimple\ServiceProviderInterface;

// Cms Loaders
use Charcoal\Cms\Service\Loader\SectionLoader;

// dependencies from `charcoal-core`
use Charcoal\Model\AbstractModel;

// dependencies from `charcoal-cms`
use Charcoal\Cms\SectionInterface;

// dependencies from `charcoal-factory`
use Charcoal\Factory\GenericFactory;

/**
 * Cms Service Provider
 *
 * Provide the following service to container:
 *
 * - `cms/section/factory`
 */
class CmsServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple\Container $container A container instance
     */
    public function register(Container $container)
    {
        $this->registerSectionServices($container);

        /**
         * @param Container $container
         * @return AbstractModel Website configurations (from database).
         */
        $container['cms/config'] = function (Container $container) {

            $cmsConfig   = $container['config']['cms'];
            $adminConfig = $cmsConfig['config_obj'];
            $factory     = $container['model/factory'];
            $config      = $factory->create($adminConfig);
            $config->setData($cmsConfig);
            $config->load(1);

            return $config;
        };
    }

    /**
     * @param Container $container Pimple DI Container.
     * @return void
     */
    private function registerSectionServices(Container $container)
    {
        /**
         * @param Container $container Pimple DI Container.
         * @return GenericFactory
         */
        $container['cms/section/factory'] = function (Container $container) {
            return new GenericFactory([
                'base_class'       => SectionInterface::class,
                'arguments'        => $container['model/factory']->arguments(),
                'resolver_options' => [
                    'suffix' => 'Section'
                ]
            ]);
        };

        /**
         * @param Container $container Pimple DI Container.
         * @return SectionLoader
         */
        $container['cms/section/loader'] = function (Container $container) {
            $sectionLoader = new SectionLoader([
                'loader'  => $container['model/collection/loader'],
                'factory' => $container['model/factory'],
                'cache'   => $container['cache']
            ]);

            // Cms.json
            $sectionConfig = $container['cms/config']->sectionConfig();
            $sectionLoader->setObjType($sectionConfig->get('objType'));
            $sectionLoader->setBaseSection($sectionConfig->get('baseSection'));

            return $sectionLoader;
        };
    }
}
