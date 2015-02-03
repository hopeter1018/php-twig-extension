<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Hopeter1018\TwigExtension\Extension;

use Twig_SimpleFilter;
use Twig_Extension;

/**
 * Group of text-formatting extension for twig
 * 
 * @version $id$
 * @author peter.ho
 */
class Formatting extends Twig_Extension
{

    /**
     * Return array of Twig_SimpleFilter
     * 
     * @link http://twig.sensiolabs.org/doc/advanced.html#id3 description
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return array (
            new Twig_SimpleFilter('attr', array($this, 'attrFilter')),
            new Twig_SimpleFilter('camelize', array($this, 'camelizeFilter')),
            new Twig_SimpleFilter('humanize', array($this, 'humanizeFilter')),
            new Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new Twig_SimpleFilter('json', array($this, 'jsonFilter'), array ('is_safe' => array ("html"),)),
        );
    }

    /**
     * Turn php array to html attributes
     * 
     * @param array $array associative array
     * @return string
     */
    public function attrFilter($array)
    {
        return implode(' ', array_map(function($key) use ($array) {
                $result = null;
                if (is_bool($array[$key])) {
                    $result = $array[$key] ? $key : '';
                } else {
                    $result = $key . '="' . $array[$key] . '"';
                }
                return $result;
            }, array_keys($array)));
    }

    /**
     * Turn to camelcase
     * 
     * @param string $str
     * @return string
     */
    public function camelizeFilter($str)
    {
        return \Doctrine\Common\Inflector\Inflector::camelize($str);
    }

    /**
     * Turn to humanized string (seperated by space)
     * 
     * @param string $str
     * @return string
     */
    public function humanizeFilter($str)
    {
        return \Hopeter1018\Helper\NamingConvention::toHuman(\Hopeter1018\Helper\NamingConvention::fromSpinalOrTrain($str));
    }

    /**
     * Return price format: $xxx,xxx,xxx.xx
     * 
     * @param double|int $priceString
     * @return string
     */
    public function priceFilter($priceString)
    {
        return "$" . number_format($priceString, 2, '.', ',');
    }

    /**
     * Return json format
     * 
     * @link Formatting::jsonFilter test nb
     * @param string|array|\stdClass $array
     * @return string
     */
    public function jsonFilter($array)
    {
        return "<pre>" . static::prettyJsonEncode($array) . "</pre>";
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Formatting';
    }

    
    /**
     * Return json_encode<br />
     * If uat, the encoded json will be beautified
     * 
     * @param array $in
     * @return string
     */
    private static function prettyJsonEncode($in)
    {
        if (APP_IS_UAT) {
            $result = '';
            $json = json_encode($in);
            $istr = '    ';
            for ($p = $q = $i = 0; isset($json[$p]); $p++) {
                ($json[$p] == '"') && ($p > 0 ? $json[$p - 1] : '') != '\\' && $q = !$q;
                if (strchr('}]', $json[$p]) && !$q && $i--) {
                    strchr('{[', $json[$p - 1]) || $result .= "\n" . str_repeat($istr, $i);
                }
                $result .= $json[$p];
                if (strchr(',{[', $json[$p]) && !$q) {
                    $i += strchr('{[', $json[$p]) === FALSE ? 0 : 1;
                    strchr('}]', $json[$p + 1]) || $result .= "\n" . str_repeat($istr, $i);
                }
            }
        } else {
            $result = json_encode($in);
        }
        return $result;
    }

}
