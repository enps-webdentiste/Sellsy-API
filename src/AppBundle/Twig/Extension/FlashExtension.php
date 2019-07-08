<?php

namespace AppBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class FlashExtension
 */
class FlashExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param string  $name    Name
     * @param Session $session Session
     */
    public function __construct( $name = "", SessionInterface $session)
    {
        $this->name = $name;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('draw_flash_messages', [$this, 'drawFlashMessages'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return string
     */
    public function drawFlashMessages()
    {
        return implode(PHP_EOL, array_map(function ($level) {
            if ($this->session->getFlashBag()->has($level)) {
                $alert = <<<'EOF'
<div class="alert alert-%s">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    %s
</div>
EOF;
                return sprintf($alert, $level, implode(PHP_EOL, array_map(function ($message) {
                    return sprintf('<p>%s</p>', $message);
                }, $this->session->getFlashBag()->get($level))));
            }

            return '';
        }, ['success', 'info', 'warning', 'danger']));
    }
}
