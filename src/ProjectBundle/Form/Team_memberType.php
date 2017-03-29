<?php

namespace ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Team_memberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('role', 'choice', array('label'=>'Sélectionnez un role',
                                              'choices'=>['1'=>'Développement',
                                                          '2'=>'Intégration',
                                                          '3'=>'Référent technique',
                                                          '4'=>'Gestion de projet',
                                                          '5'=>'Invité'],
                                              'attr'=>['class'=>'form-control']))
                ->add('project', null,  array('label'=>'Sélectionnez un projet',
                                              'class' => 'ProjectBundle:Project',
                                              'choice_label'=>'name',
                                              'attr'=>['class'=>'form-control']))
                ->add('users', null,    array('label'=>'Sélectionnez un utilisateur',
                                              'class' => 'UserBundle:User',
                                              'choice_label'=>'id',
                                              'multiple'=> true,
                                              'attr'=>['class'=>'form-control']))
                ->add('Validez','submit', array('attr'=>['class'=>'btn btn-success btn-lg']));
    }   
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectBundle\Entity\Team_member'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projectbundle_team_member';
    }


}
