<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Artikel;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArtikelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', CheckboxType::class, [
                "label" => "ist Aktiv",
                "required" => false,
            ])
            ->add('productName', TextType::class, [
                "required" => true,
                "label" => "Produkt Name"
            ])
            ->add('text', TextareaType::class, [
                "required" => true,
                "label" => "Copy Name"
            ])
            ->add('image', FileType::class, [
                "required" => false,
                "label" => "Bild",
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/vnd.sealedmedia.softseal.jpg',
                            'image/vnd.sealed.png',
                            'image/jpeg',
                            'image/png',
                        ]
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                "label" => "Speichern"
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artikel::class,
        ]);
    }
}