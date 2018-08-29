<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Medicament;

class MedicamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('code', TextType::class, [
          'label' => 'Código nacional'
        ])
        ->add(
            'situation',
            ChoiceType::class,
            [
                'label' => 'Situación',
                'choices' =>
                [
                    'Alta' => 1,
                    'Anulado' => 2,
                    'Suspendido' => 0,
                ],
            ]
        )
        ->add('laboratory')
        ->add('activePrinciple')
        ->add('billSNS', CheckboxType::class, [
          'label' => '¿Facturable por SNS?',
          'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Medicament'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_meds';
    }
}
