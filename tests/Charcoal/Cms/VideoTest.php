<?php

namespace Charcoal\Cms\Tests;

use PHPUnit_Framework_TestCase;

use Psr\Log\NullLogger;
use Cache\Adapter\Void\VoidCachePool;

use Charcoal\Model\Service\MetadataLoader;

use Charcoal\Cms\Video;
use Charcoal\Cms\VideoCategory;

/**
 *
 */
class VideoTest extends PHPUnit_Framework_TestCase
{

    public $obj;

    public function setUp()
    {
        $metadataLoader = new MetadataLoader([
            'logger' => new NullLogger(),
            'base_path' => __DIR__,
            'paths' => ['metadata'],
            'cache'  => new VoidCachePool()
        ]);

        $this->obj = new Video([
            'logger'=> new NullLogger(),
            'metadata_loader' => $metadataLoader
        ]);
    }

    public function testSetData()
    {
        $ret = $this->obj->setData([
            'name'=>'foo',
            'file'=>'foobar',
            'base_path'=>'baz',
            'base_url'=>'http://example.com/c'
        ]);
        $this->assertSame($ret, $this->obj);

        $this->assertEquals('foo', (string)$this->obj->name());
        $this->assertEquals('foobar', $this->obj->file());
        $this->assertEquals('baz/', $this->obj->basePath());
        $this->assertEquals('http://example.com/c/', $this->obj->baseUrl());
    }

    public function testCategoryType()
    {
        $this->assertEquals(VideoCategory::class, $this->obj->categoryType());
    }
}
