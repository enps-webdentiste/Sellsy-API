<?php


namespace AppBundle\Twig\Extension;

class FormatExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('to_int', [$this, 'toInt']),
        ];
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('integer', [$this, 'isInteger']),
        ];
    }

    public function toInt($value)
    {
        if (is_integer($value)) {
            return $value;
        }
        if (is_string($value) and $value != '0') {
            if (intval($value) != '0') {
                return intval($value);
            }
        } elseif (is_string($value) and $value == '0') {
            return 0;
        }

        return null;
    }

    public function isInteger($value)
    {
        return is_integer($this->toInt($value));
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'format_extension';
    }
}
