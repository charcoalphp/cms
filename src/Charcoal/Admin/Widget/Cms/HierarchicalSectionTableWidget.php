<?php

namespace Charcoal\Admin\Widget\Cms;

use Pimple\Container;

// From 'charcoal-support'
use Charcoal\Support\Admin\Widget\HierarchicalTableWidget;

/**
 * The hierarchical table widget displays a collection in a tabular (table) format.
 */
class HierarchicalSectionTableWidget extends HierarchicalTableWidget
{
    use SectionTableTrait;

    /**
     * Inject dependencies from a DI Container.
     *
     * @param  Container $container A dependencies container instance.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setSectionFactory($container['cms/section/factory']);
    }
}
