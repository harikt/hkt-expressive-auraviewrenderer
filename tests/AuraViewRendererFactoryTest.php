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
use Zend\Expressive\Helper\ServerUrlHelper;

class AuraViewRendererFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->factory   = new AuraViewRendererFactory();
    }

    public function testFactoryCanCreateInstanceWithoutConfiguration()
    {
        $this->container->has('config')->willReturn(false);
        $this->container->has('Aura\View\View')->willReturn(false);
        $this->container->has(UrlHelper::class)->willReturn(false);
        $this->container->has(ServerUrlHelper::class)->willReturn(false);
        $result = $this->factory->__invoke($this->container->reveal());
        $this->assertInstanceOf(AuraViewRenderer::class, $result);
    }
}
