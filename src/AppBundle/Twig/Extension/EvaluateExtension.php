<?php

namespace AppBundle\Twig\Extension;



/**
 * Class EvaluateExtension
 */
class EvaluateExtension extends \Twig_Extension{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('evaluate', array($this, 'evaluate', array(
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => array(
                    'evaluate' => true
                )
            ))),
        );
    }

    /**
     * This function will evaluate $string through the $environment, and return its results.
     * 
     * @param array $context
     * @param string $string 
     */
    public function evaluate( \Twig_Environment $environment, $context, $string ) {
        $loader = $environment->getLoader( );

        $parsed = $this->parseString( $environment, $context, $string );

        $environment->setLoader( $loader );
        return $parsed;
    }

    /**
     * Sets the parser for the environment to Twig_Loader_String, and parsed the string $string.
     * 
     * @param \Twig_Environment $environment
     * @param array $context
     * @param string $string
     * @return string 
     */
    protected function parseString( \Twig_Environment $environment, $context, $string ) {
        $environment->setLoader( new \Twig_Loader_String( ) );
        return $environment->render( $string, $context );
    }
}