<?php

namespace BonPlansBundle\Form;

use BonPlansBundle\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonPlanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categorie',EntityType::class, array('class'=>'BonPlansBundle\Entity\Categorie', 'choice_label'=>'type'))
            ->add('name')
            ->add('adresse')
            ->add('phone')
            ->add('note')
            ->add('description')
            ->add('image',FileType::class, array('label' => 'image (Image)','data_class'=>null))
            ->add('etoile')
            ->add('prix')
            ->add('save',SubmitType::class);;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BonPlansBundle\Entity\BonPlan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bonplansbundle_bonplan';
    }


}
