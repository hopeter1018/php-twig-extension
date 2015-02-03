<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zms5Library\TwigExtension\Extension;

use Twig_SimpleFilter;

/**
 * Group of extension for twig developers<br />
 * All methods MUST only display when APP_IS_DEV / APP_IS_UAT
 * 
 * @version $id$
 * @author peter.ho
 */
class Developer extends \Twig_Extension
{

    /**
     * Return array of Twig_SimpleFilter
     * 
     * @link http://twig.sensiolabs.org/doc/advanced.html#id3 description
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        $options = array (
            'needs_environment' => true,
            'needs_context' => true,
            'is_safe' => null,
            'is_safe_callback' => null,
            'pre_escape' => null,
            'preserves_safety' => null,
            'node_class' => 'Twig_Node_Expression_Filter',
        );
        return array (
            new Twig_SimpleFilter('z_htmlcomment', array ($this, 'zHtmlCommentFilter'), $options),
        );
    }

    /**
     * @param string $string 
     * @return string
     */
    public function zHtmlCommentFilter(\Twig_Environment $env, $context, $string)
    {
        return (APP_IS_UAT) ? $string : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Developer';
    }

}
