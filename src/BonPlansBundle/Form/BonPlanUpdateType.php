<?php

namespace BonPlansBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use BonPlansBundle\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonPlanUpdateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('adresse')
            ->add('phone')
            ->add('note',NumberType::class)
            ->add('description')
            ->add('image', FileType::class, array(
                'label' => 'Image',
                'data_class' => null,
                'required' => false
            ))
            ->add('rating', RatingType::class, [
                'label' => 'Rating'])
            ->add('prix')
            ->add('Modifier',SubmitType::class)
            ->add('Annuler',ResetType::class);;

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
