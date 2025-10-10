<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Plus;
use App\Entity\Source;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value')
            ->add('dat')
            ->add('comment')
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('source', EntityType::class, [
                'class' => Source::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plus::class,
        ]);
    }
}
