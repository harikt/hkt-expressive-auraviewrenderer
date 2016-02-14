<?php
/**
 * Hari KT (http://harikt.com/)
 *
 * @see       https://github.com/harikt/hkt-expressive-auraviewrenderer for the canonical source repository
 * @copyright Copyright (c) 2016 Hari KT (http://harikt.com/)
 * @license   https://github.com/harikt/hkt-expressive-auraviewrenderer/blob/master/LICENSE.md MIT License
 */

namespace Hkt\Expressive\AuraView;

use Aura\View\View;
use Aura\View\ViewFactory;
use Aura\Html\HelperLocatorFactory;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\RouterInterface;

/**
 * Create and return a AuraView template instance.
 *
 * Requires the Zend\Expressive\Router\RouterInterface service (for creating
 * the UrlHelper instance).
 *
 * Optionally uses the service 'config', which should return an array. This
 * factory consumes the following structure:
 *
 * <code>
 * 'templates' => [
 *     'layout' => 'name of layout view to use, if any',
 *     'paths'  => [
 *         // namespace / path pairs
 *         //
 *         // Numeric namespaces imply the default/main namespace. Paths may be
 *         // strings or arrays of string paths to associate with the namespace.
 *     ],
 * ]
 * </code>
 */
class AuraViewRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @returns AuraViewRenderer
     */
    public function __invoke(ContainerInterface $container)
    {
        $config   = $container->has('config') ? $container->get('config') : [];
        $config   = isset($config['templates']) ? $config['templates'] : [];

        if ($container->has('Aura\View\View')) {
            $renderer = $container->get('Aura\View\View');
        } else {
            $factory = new HelperLocatorFactory();
            $helpers = $factory->newInstance();

            $view_factory = new ViewFactory();
            $renderer = $view_factory->newInstance($helpers);
        }

        // Inject helpers
        $this->injectHelpers($renderer, $container);

        // Inject renderer
        $view = new AuraViewRenderer($renderer, isset($config['layout']) ? $config['layout'] : null);

        // Add template paths
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $view->addPath($path, $namespace);
            }
        }

        return $view;
    }

    /**
     * Inject helpers into the View
     *
     * In each case, injects with the custom url/serverurl implementations.
     *
     * @param View $renderer
     * @param ContainerInterface $container
     */
    private function injectHelpers(View $renderer, ContainerInterface $container)
    {
        $helpers = $renderer->getHelpers();

        $helpers->set('url', function () use ($container) {
            if (! $container->has(UrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    UrlHelper::class
                ));
            }
            return $container->get(UrlHelper::class);
        });
        $helpers->set('serverurl', function () use ($container) {
            if (! $container->has(ServerUrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    ServerUrlHelper::class
                ));
            }
            return $container->get(ServerUrlHelper::class);
        });
    }
}
