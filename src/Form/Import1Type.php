<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Import1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('queryExtra', TextType::class, [
                'attr' => [
                    'autofocus' => true,
                    'size' => 120,
                    'maxlength' => 100,
                    'value' => $options['initialValue'],
                ],
                'help' => 'i.last i.use {0, 1} ### i.valuedate desc, i.id ### i.postingdate desc, i.id',
                'label' => 'queryExtra',
                'mapped' => false,
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'initialValue' => null,
        ]);
        $resolver->setAllowedTypes('initialValue', 'string');
    }
}
