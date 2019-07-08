<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
/**
 * User type
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we use email as the username
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle','attr'=>array('class'=>'form-control','placeholder'=>'')))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'translation_domain' => 'FOSUserBundle',
                ],
                'first_options' => [
                    'label' => 'form.password',
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation',
                ],
                'invalid_message' => 'fos_user.password.mismatch',
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
			->add('groups', EntityType::class, array(
                'class'        => 'AppBundle:Group',
                'choice_label' => 'name',
                'multiple'     => true,
                'expanded' => true,
                'label' => 'Groupes'
        ))
            ->add('enabled', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
			->add('firstname', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Nom',
            ])
            
            ;
    }

    /**
     * Default option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'webdentist_user';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
