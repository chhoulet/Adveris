<?php

namespace ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name','text',            array('label'=>'Nom',
                                                      'attr'=>['class'=>'form-control']))
                ->add('slug','text',            array('label'=>'Slug',
                                                      'attr'=>['class'=>'form-control']))
                ->add('beginDate','datetime',   array('label'=>'Date de début',
                                                      'attr'=>['class'=>'js-datepicker'],
                                                      'html5'=>false,
                                                      'widget'=>'single_text'))
                ->add('endDate','datetime',     array('label'=>'Date de fin',
                                                      'attr'=>['class'=>'js-datepicker'],
                                                      'html5'=>false,
                                                      'widget'=>'single_text'))
                ->add('Valider','submit',       array('attr'=>['class'=>'btn btn-primary']));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectBundle\Entity\Project'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projectbundle_project';
    }


}
