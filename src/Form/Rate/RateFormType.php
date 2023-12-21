<?php

namespace App\Form\Rate;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', ChoiceType::class, [
                'label' => 'Mena',
                'choices' => ['CZK' => 'CZK', 'USD' => 'USD', 'PLN' => 'PLN'],
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'label' => 'Kurz ku dnu',
                'input_format' => 'dd.MM.yyyy',
                'required' => true,
                'data' => new DateTime(),
            ])
            ->add('get_rate', SubmitType::class);
    }
}
