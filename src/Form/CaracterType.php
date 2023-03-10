<?php

namespace App\Form;

use App\Entity\Caracter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CaracterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('caste', TextType::class)
            ->add('knowledge', TextType::class)
            ->add('intelligence', IntegerType::class)
            ->add('life', TextType::class)
            ->add('image', TextType::class)
            ->add('kind', TextType::class)
            ->add('created', DateTimeType::class)
            ->add('identifier', TextType::class)
            ->add('modified', DateTimeType::class)
            ->add('player')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Caracter::class,
        ]);
    }
}
