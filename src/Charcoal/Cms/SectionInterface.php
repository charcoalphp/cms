<?php

namespace Charcoal\Cms;

/**
 *
 */
interface SectionInterface
{
    /**
     * @param string $type The section type.
     * @return SectionInterface Chainable
     */
    public function setSectionType($type);

    /**
     * @return string
     */
    public function sectionType();

    /**
     * @param mixed $title Section title (localized).
     * @return SectionInterface Chainable
     */
    public function setTitle($title);

    /**
     * @return \Charcoal\Translation\TranslationString
     */
    public function title();

    /**
     * @param mixed $subtitle Section subtitle (localized).
     * @return SectionInterface Chainable
     */
    public function setSubtitle($subtitle);

    /**
     * @return \Charcoal\Translation\TranslationString
     */
    public function subtitle();
}