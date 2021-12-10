<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('text')
            
            ->add('date_creation')
            ->add('active')
            ->add('author')
            // ->add('featured_image')
            ->add('featured_image', FileType::class,[
                'label' => 'Featured image on home page only landscape format!!',
                'multiple' =>false,
                'mapped' => false,
                'required' =>false
            ])
            ->add('updatedAt')
            ->add('slug')
            ->add('images', FileType::class,[
                'label' => '3 other images for each article',
                'multiple' =>true,
                'mapped' => false,
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
