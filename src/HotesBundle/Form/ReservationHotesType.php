<?php

namespace HotesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationHotesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime('now');
        $builder->add('date_debut', DateType::class, array(
            'widget' => 'single_text',
            'data' => new \DateTime(),
            'attr' => ['min' => $today->format('Y-m-d')]))
            ->add('date_fin', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('nb_personne');
        //->add("Modifier", SubmitType::class)
//            ->add("Annuler", ResetType::class);;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HotesBundle\Entity\ReservationHotes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hotesbundle_reservationhotes';
    }


}
