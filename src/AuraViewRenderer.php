<?php
/**
 * Hari KT (http://harikt.com/)
 *
 * @see       https://github.com/harikt/zend-expressive-auraviewrenderer for the canonical source repository
 * @copyright Copyright (c) 2016 Hari KT (http://harikt.com/)
 * @license   https://github.com/harikt/zend-expressive-auraviewrenderer/blob/master/LICENSE.md MIT License
 */

namespace Hkt\Expressive\AuraView;

use Aura\View\View;
use Zend\Expressive\Template\ArrayParametersTrait;
use Zend\Expressive\Template\DefaultParamsTrait;
use Zend\Expressive\Template\Exception;
use Zend\Expressive\Template\TemplatePath;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuraViewRenderer implements TemplateRendererInterface
{
    use ArrayParametersTrait;
    use DefaultParamsTrait;

    /**
     * @var string
     */
    private $layout;

    /**
     * @var View
     */
    private $renderer;

    /**
     * @var array
     */
    private $paths;

    /**
     * Constructor
     *
     * Inject Aura\View\View and optionally layout.
     *
     * The layout is a string layout name
     *
     * @param View $renderer
     * @param string $layout
     */
    public function __construct(View $renderer = null, $layout = null)
    {
        $this->layout = $layout;
        $this->renderer = $renderer;
    }

    /**
     * Render a template with the given parameters.
     *
     * If a layout was specified during construction, it will be used;
     * alternately, you can specify a layout to use via the "layout"
     * parameter/variable, using a string layout template name
     *
     * Layouts specified with $params take precedence over layouts passed to
     * the constructor.
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function render($name, $params = [])
    {
        list($namespace, $viewName) = explode('::', $name);

        $params = $this->mergeParams($name, $this->normalizeParams($params));

        $this->prepareLayout($params);

        $this->renderer->setView($viewName);

        $this->renderer->setData($params);

        return $this->renderer->__invoke();
    }

    /**
     * Add a path for templates.
     *
     * @param string $path
     * @param string $namespace
     */
    public function addPath($path, $namespace = null)
    {
        $this->paths[$namespace] = $path;

        $viewRegistry = $this->renderer->getViewRegistry();
        $viewRegistry->appendPath($path);

        $layoutRegistry = $this->renderer->getLayoutRegistry();
        $layoutRegistry->appendPath($path);
    }

    /**
     * Get the template directories
     *
     * @return TemplatePath[]
     */
    public function getPaths()
    {
        $paths = [];

        foreach ($this->paths as $namespace => $path) {
            if (! is_string($namespace)) {
                $namespace = null;
            }
            $paths[] = new TemplatePath($path, $namespace);
        }

        return $paths;
    }

    /**
     * Prepare the layout, if any.
     *
     * If the params contains a non-empty 'layout' variable, that value
     * will be used to seed a layout
     *
     * If a layout is discovered in this way, it will override the one set in
     * the constructor, if any.
     *
     * @param array $params
     * @return null
     */
    private function prepareLayout($params)
    {
        if (isset($params['layout'])) {
            $this->layout = $params['layout'];
        }

        $this->renderer->setLayout($this->layout);
    }
}
