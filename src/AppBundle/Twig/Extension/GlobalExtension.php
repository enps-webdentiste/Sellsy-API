<?php

namespace AppBundle\Twig\Extension;

class GlobalExtension extends \Twig_Extension
{
    protected $globals = [];

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('set_globals', [$this, 'setGlobals']),
            new \Twig_SimpleFunction('get_globals', [$this, 'getGlobals']),
            new \Twig_SimpleFunction('get_global', [$this, 'getGlobal']),
        ];
    }

    public function setGlobals(array $aGlobals)
    {
        foreach ($aGlobals as $key => $value) {
            $this->globals[$key] = $value;
        }
    }

    public function getGlobals()
    {
        return $this->globals;
    }

    public function getGlobal($key)
    {
        return isset($this->globals[$key]) ? $this->globals[$key] : null;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'global_extension';
    }
}
