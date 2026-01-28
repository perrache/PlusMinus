<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Minus;
use App\Entity\Transaction;
use App\Entity\Type;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', IntegerType::class, [
                'label' => 'Value',
                'attr' => ['autofocus' => true, 'placeholder' => 'Value'],
                'row_attr' => ['class' => 'form-floating'],
            ])
            ->add('dat', null, [
                'label' => 'Data',
                'attr' => ['placeholder' => 'Data'],
                'row_attr' => ['class' => 'form-floating'],
                'widget' => 'single_text',
            ])
            ->add('comment', null, [
                'label' => 'Comment',
                'attr' => ['placeholder' => 'Comment'],
                'row_attr' => ['class' => 'form-floating'],
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => function (Type $type): string {
                    return $type->getKind()->getName() . ' - ' . $type->getName();
                },
                'group_by' => function (Type $type): string {
                    return $type->getKind()->getName();
                },
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('t')
                        ->join('t.kind', 'k')
                        ->orderBy('k.name', 'ASC')
                        ->addOrderBy('t.name', 'ASC');
                },
                'placeholder' => '=== Select Type ===',
            ])
            ->add('transaction', EntityType::class, [
                'class' => Transaction::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'placeholder' => '=== Select Transaction ===',
            ])
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => function (Account $account): string {
                    return $account->getOrganization()->getName() . ' - ' . $account->getName();
                },
                'group_by' => function (Account $account): string {
                    return $account->getOrganization()->getName();
                },
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->join('a.organization', 'o')
                        ->orderBy('o.name', 'ASC')
                        ->addOrderBy('a.name', 'ASC');
                },
                'placeholder' => '=== Select Account ===',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Minus::class,
        ]);
    }
}
