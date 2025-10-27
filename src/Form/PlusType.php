<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Plus;
use App\Entity\Source;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', IntegerType::class, [
                'attr' => ['autofocus' => true],
            ])
            ->add('dat', null, [
                'widget' => 'single_text',
            ])
            ->add('comment')
            ->add('source', EntityType::class, [
                'class' => Source::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'placeholder' => '=== Select Source ===',
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
            'data_class' => Plus::class,
        ]);
    }
}
