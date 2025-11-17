<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlikDoTabeli1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', ChoiceType::class, [
                'choice_loader' => new CallbackChoiceLoader(static function (): array {
                    $files = scandir('import');
                    if ($files) {
                        $files = array_diff($files, ['.', '..']);
                        $files = array_filter($files, fn($file) => str_ends_with($file, '.csv'));
                        return array_combine($files, $files);
                    }
                    return [];
                }),
                'mapped' => false,
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
