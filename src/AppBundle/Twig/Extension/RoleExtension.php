<?php

namespace AppBundle\Twig\Extension;

use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class RoleExtension
 */
class RoleExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string  $name    Name
     * @param Session $session Session
     */
    public function __construct($name = "")
    {
        $this->name = $name;
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
            new \Twig_SimpleFunction('display_role', [$this, 'displayRole']),
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public function displayRole(User $user = null)
    {
        if (!$user) {
            return '';
        }

        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            return 'Super Admin';
        } elseif ($user->hasRole('ROLE_PRATICIEN')) {
            return 'Praticien';
        } elseif ($user->hasRole('ROLE_PARTICULIER')) {
            return 'Particulier';
        } 

        return '';
    }
}
