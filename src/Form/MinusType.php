<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Minus;
use App\Entity\Transaction;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value')
            ->add('dat')
            ->add('comment')
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
            ])
            ->add('transaction', EntityType::class, [
                'class' => Transaction::class,
                'choice_label' => 'id',
            ])
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Minus::class,
        ]);
    }
}
