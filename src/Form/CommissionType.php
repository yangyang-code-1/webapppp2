<?php

namespace App\Form;

use App\Entity\Commission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('category', ChoiceType::class, [  // Dropdown implementation
                'label' => 'Category',
                'choices' => [
                    'Digital Art' => 'Digital Art',
                    'Traditional Art' => 'Traditional Art',
                    'Portrait' => 'Portrait',
                    'Landscape' => 'Landscape',
                    'Logo Design' => 'Logo Design',
                    'Illustration' => 'Illustration',
                    'Other' => 'Other',  // Optional: For custom needs
                ],
                'placeholder' => 'Select a category',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'PHP',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    '' => '',
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                ],
            ]);
        // 🔹 Notice: no createdAt or updatedAt fields here
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commission::class,
        ]);
    }
}
