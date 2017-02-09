<?php

namespace Charcoal\Cms;

use Exception;

use Charcoal\Object\CategoryInterface;
use Charcoal\Object\CategoryTrait;
use Charcoal\Object\Content;
use Charcoal\Translation\TranslationString;

/**
 * CMS Tag
 */
class Tag extends Content implements
    CategoryInterface
{
    use CategoryTrait;

    /**
     * @var object|string $name The tag's name.
     */
    protected $name;

    /**
     * @var string $color The tag's color.
     */
    protected $color;

    /**
     * Section constructor.
     * @param array|null $data The object's data options.
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        $this->setData($this->defaultData());
    }

    // ==========================================================================
    // Functions
    // ==========================================================================

    /**
     * @throws Exception If function is called.
     * @return void
     */
    public function loadCategoryItems()
    {
        throw new Exception('Cannot use loadCategoryItems');
    }

    // ==========================================================================
    // GETTERS
    // ==========================================================================

    /**
     * @return mixed The tag's name.
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed The tag's color.
     */
    public function color()
    {
        return $this->color;
    }

    // ==========================================================================
    // SETTERS
    // ==========================================================================

    /**
     * @param string|string[] $name The name of the tag.
     * @return self chainable
     */
    public function setName($name)
    {
        if (TranslationString::isTranslatable($name)) {
            $this->name = new TranslationString($name);
        } else {
            $this->name = null;
        }

        return $this;
    }

    /**
     * @param string $color The color in HEX format as a string.
     * @return self chainable
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
}
