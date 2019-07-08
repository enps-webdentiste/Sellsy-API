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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Regex;



/**
 * User parameter type
 */
class UserParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sincemonth', NumberType::class, [
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Depuis X mois',
            ])
			->add('owners', EntityType::class, [
				// looks for choices from this entity
				'class' => 'AppBundle:Owner',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('o')
					->where("o.name <> 'Supprimé'")
					->orderBy('o.id', 'ASC');
				},
				// uses the User.username property as the visible option string
				'choice_label' => function ($owner) {
					return $owner->getForename() . ' ' . $owner->getName();
				},

				// used to render a select box, check boxes or radios
				 'multiple' => true,
				 'expanded' => true,
				 'label' => 'Conseillers',
			])
            ->add('showdelete', CheckboxType::class, [
                'label' => 'Conseillers supprimés',
                'required' => false,
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
        return 'webdentist_user_parameter';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
