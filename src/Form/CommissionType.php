<?php

namespace App\Form;

use App\Entity\Commission;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter commission title'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Describe the commission details'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'placeholder' => 'Select a category',
                'required' => true,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'PHP',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => '0.00'],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                ],
                'data' => 'Pending',  // Default status
                'attr' => ['class' => 'form-select'],
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
