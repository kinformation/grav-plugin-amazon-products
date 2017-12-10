<?php
/**
 * Short description for amazon-products.php
 *
 * @package amazon-products
 * @author Kazuya Kanatani
 * @version 0.1
 * @copyright (C) 2017 kinformation<kanatani.social@gmail.com>
 * @license MIT
 */

namespace Grav\Plugin;

use Grav\Common\Plugin;
// use Grav\Common\Data\Data;
// use Grav\Common\Page\Page;
// use Grav\Common\Twig\Twig;
// use Grav\Plugin\Youtube\Twig\YoutubeTwigExtension;
// use RocketTheme\Toolbox\Event\Event;

class AmazonProductsPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ];
    }

    /**
     * Add style and script to page.
     */
    public function onTwigSiteVariables()
    {
        if (!$this->isAdmin()) {
            $this->grav['assets']->addCss('plugin://amazon-products/assets/css/amazon-products.css');
        }
        if ($this->isAdmin() && $this->config->get('plugins.amazon-products.editor_button')) {
            $this->grav['assets']->add('plugin://amazon-products/admin/editor-button/js/button.js');
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Initialize configuration
     */
    public function onShortcodeHandlers()
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }
}
