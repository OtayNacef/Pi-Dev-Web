<?php

namespace HotesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotesUpdateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class)
            ->add('description', TextType::class)
            ->add('pays', TextType::class)
            ->add('capacites', NumberType::class)
            ->add('site_web', TextType::class)
            ->add('tel', TelType::class)
            ->add('mail', EmailType::class)
            ->add('image', FileType::class, array('label' => 'Image(png)', 'data_class' => null))
            ->add('prix', NumberType::class)
            ->add('adresse', TextType::class)
            ->add('gouvernorat', TextType::class)
            ->add("Submit", SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HotesBundle\Entity\MaisonsHotes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hotesbundle_maisonshotes';
    }


}
