<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Move;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value')
            ->add('dat')
            ->add('comment')
            ->add('accplus', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('accminus', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Move::class,
        ]);
    }
}
