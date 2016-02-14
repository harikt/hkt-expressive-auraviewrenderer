<?php
/**
 * Hari KT (http://harikt.com/)
 *
 * @see       https://github.com/harikt/zend-expressive-auraviewrenderer for the canonical source repository
 * @copyright Copyright (c) 2016 Hari KT (http://harikt.com/)
 * @license   https://github.com/harikt/zend-expressive-auraviewrenderer/blob/master/LICENSE.md MIT License
 */

namespace Hkt\Expressive\AuraView;

use Interop\Container\ContainerInterface;
use Hkt\Expressive\AuraView\AuraViewRenderer;
use Hkt\Expressive\AuraView\AuraViewRendererFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Expressive\Helper\UrlHelper;

class AuraViewRendererTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory   = new AuraViewRendererFactory();
    }

    public function testNothing()
    {
        
    }
}
