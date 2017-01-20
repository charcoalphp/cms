<?php

namespace Charcoal\Cms;

// Module `charcoal-base` dependencies
use Charcoal\Object\Content;
use Charcoal\Object\CategoryInterface;
use Charcoal\Object\CategoryTrait;

use Charcoal\Cms\Faq;

/**
 * FAQ Category
 */
final class FaqCategory extends Content implements CategoryInterface
{
    use CategoryTrait;

    /**
     * CategoryTrait > item_type()
     *
     * @return string
     */
    public function itemType()
    {
        return Faq::class;
    }

    /**
     * @return Collection
     */
    public function loadCategoryItems()
    {
        return [];
    }
}
