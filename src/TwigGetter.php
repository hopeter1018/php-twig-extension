<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Hopeter1018\TwigExtension;

use Hopeter1018\FileOperation\Path;

/**
 * Description of TwigGetter
 *
 * @version $id$
 * @author peter.ho
 */
final class TwigGetter
{

    /**
     *
     * @var \Twig_Loader_Filesystem 
     */
    private static $loader = null;

    /**
     *
     * @return \Twig_Loader_Filesystem 
     */
    public static function getLoader()
    {
        if (static::$loader === null) {
            static::$loader = new \Twig_Loader_Filesystem(APP_WCMS_ROOT);
            !is_dir(APP_TWIG_CACHE) and mkdir(APP_TWIG_CACHE, 0777, true);

            static::$loader->addPath(APP_WORKBENCH_ROOT);
            static::$loader->addPath(\Hopeter1018\Framework\SystemPath::twigCommonHintPath());
//            static::$loader->addPath(\Hopeter1018\Framework\SystemPath::twigCommonHintPath('extends'));
//            static::$loader->addPath(\Hopeter1018\Framework\SystemPath::twigCommonHintPath('embed'));
        }
        return static::$loader;
    }

    /**
     * 
     * @param \Twig_Environment $env
     */
    public static function setEnv($env)
    {
        if (APP_IS_DEV) {
            static::$environment = $env;
        }
    }

    /**
     * 
     * @return \Twig_Environment
     */
    private static $environment = null;

    /**
     * @todo add custom extensions to environment
     * 
     * @return \Twig_Environment
     */
    public static function getEnvironment()
    {
        if (static::$environment === null) {
            static::$environment = new \Twig_Environment(
                static::getLoader(), array (
                'debug' => APP_IS_DEV,
                'cache' => APP_TWIG_CACHE,
                )
            );
            static::plugExtensions();
        }
        return static::$environment;
    }

    /**
     * Register all developed twig extensions
     * 
     */
    private static function plugExtensions()
    {
        static::$environment->addExtension(new Extension\Formatting());
        static::$environment->addExtension(new Extension\Developer());
    }

    /**
     * 
     * @todo check after plug from ehg
     * 
     * @param string $filePath
     * @return \Twig_TemplateInterface
     */
    public static function getTemplate($filePath)
    {
        $twig = self::getEnvironment();
        return $twig->loadTemplate(Path::relativeTo($filePath, APP_WORKBENCH_ROOT));
    }

}
