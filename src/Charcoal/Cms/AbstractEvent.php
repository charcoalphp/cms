<?php

namespace Charcoal\Cms;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

// From PSR-7
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// From 'charcoal-object'
use Charcoal\Object\Content;
use Charcoal\Object\CategorizableInterface;
use Charcoal\Object\CategorizableTrait;
use Charcoal\Object\PublishableInterface;
use Charcoal\Object\PublishableTrait;
use Charcoal\Object\RoutableInterface;
use Charcoal\Object\RoutableTrait;

// From 'charcoal-translator'
use Charcoal\Translator\Translation;

// From 'charcoal-cms'
use Charcoal\Cms\MetatagInterface;
use Charcoal\Cms\SearchableInterface;
use Charcoal\Cms\TemplateableInterface;

use Charcoal\Cms\MetatagTrait;
use Charcoal\Cms\SearchableTrait;
use Charcoal\Cms\TemplateableTrait;

/**
 *
 */
abstract class AbstractEvent extends Content implements
    CategorizableInterface,
    EventInterface,
    MetatagInterface,
    PublishableInterface,
    RoutableInterface,
    SearchableInterface,
    TemplateableInterface
{
    use CategorizableTrait;
    use PublishableTrait;
    use MetatagTrait;
    use RoutableTrait;
    use SearchableTrait;
    use TemplateableTrait;

    /**
     * @var Translation|string|null
     */
    private $title;

    /**
     * @var Translation|string|null
     */
    private $subtitle;

    /**
     * @var Translation|string|null
     */
    private $summary;

    /**
     * @var Translation|string|null
     */
    private $content;

    /**
     * @var Translation|string|null
     */
    private $image;

    /**
     * @var DateTimeInterface|null
     */
    private $startDate;

    /**
     * @var DateTimeInterface|null
     */
    private $endDate;

    /**
     * @param  mixed $title The event title (localized).
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $this->translator()->translation($title);

        return $this;
    }

    /**
     * @return Translation|string|null
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param  mixed $subtitle The event subtitle (localized).
     * @return self
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $this->translator()->translation($subtitle);

        return $this;
    }

    /**
     * @return Translation|string|null
     */
    public function subtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param  mixed $summary The news summary (localized).
     * @return self
     */
    public function setSummary($summary)
    {
        $this->summary = $this->translator()->translation($summary);

        return $this;
    }

    /**
     * @return Translation|string|null
     */
    public function summary()
    {
        return $this->summary;
    }

    /**
     * @param  mixed $content The event content (localized).
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $this->translator()->translation($content);

        return $this;
    }

    /**
     * @return Translation|string|null
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @param  mixed $image The section main image (localized).
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $this->translator()->translation($image);

        return $this;
    }

    /**
     * @return Translation|string|null
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * @param  string|DateTimeInterface $startDate Event starting date.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return self
     */
    public function setStartDate($startDate)
    {
        if ($startDate === null || $startDate === '') {
            $this->startDate = null;

            return $this;
        }
        if (is_string($startDate)) {
            $startDate = new DateTime($startDate);
        }
        if (!($startDate instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Start Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function startDate()
    {
        return $this->startDate;
    }

    /**
     * @param  string|DateTimeInterface $endDate Event end date.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return self
     */
    public function setEndDate($endDate)
    {
        if ($endDate === null || $endDate === '') {
            $this->endDate = null;

            return $this;
        }
        if (is_string($endDate)) {
            $endDate = new DateTime($endDate);
        }
        if (!($endDate instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "End Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function endDate()
    {
        return $this->endDate;
    }

    /**
     * MetatagTrait > canonical_url
     *
     * @todo
     * @return string
     */
    public function canonicalUrl()
    {
        return '';
    }

    /**
     * @return Translation|string|null
     */
    public function defaultMetaTitle()
    {
        return $this->title();
    }

    /**
     * @return Translation|string|null
     */
    public function defaultMetaDescription()
    {
        $content = $this->translator()->translation($this->content());
        if ($content instanceof Translation) {
            $desc = [];
            foreach ($content->data() as $lang => $text) {
                $desc[$lang] = strip_tags($text);
            }

            return $this->translator()->translation($desc);
        }

        return null;
    }

    /**
     * @return Translation|string|null
     */
    public function defaultMetaImage()
    {
        return $this->image();
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function preSave()
    {
        $this->setSlug($this->generateSlug());
        $this->generateDefaultMetaTags();

        return parent::preSave();
    }

    /**
     * {@inheritdoc}
     *
     * @param  array $properties Optional properties to update.
     * @return boolean
     */
    public function preUpdate(array $properties = null)
    {
        if (!$this->slug) {
            $this->setSlug($this->generateSlug());
        }
        $this->generateDefaultMetaTags();

        return parent::preUpdate($properties);
    }

    /**
     * GenericRoute checks if the route is active.
     * Default in RoutableTrait.
     *
     * @return boolean
     */
    public function isActiveRoute()
    {
        return (
            $this->active() &&
            $this->isPublished()
        );
    }
}
