<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    //Surcharge du formulaire de création de compte avec les champs de l'entité User
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label'=>'Nom',
                                        'attr' => ['class'=>'form-control']))
            ->add('firstname', 'text', array('label'=>'Prénom',
                                             'attr' => ['class'=>'form-control']))
            ->add('username', null, array('label' => 'Username'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('plainPassword', 'repeated', array(
                  'type' => 'password',
                  'options' => array('translation_domain' => 'FOSUserBundle'),
                  'first_options' => array('label' => 'Mot de passe'),
                  'second_options' => array('label' => 'Confirmez le mot de passe'),
                  'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('Enregistrez-vous', 'submit', array('attr'=>['class'=>'btn btn-primary']))
        ;
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    public function getName()
    {
        return 'user_registration';
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }
}
