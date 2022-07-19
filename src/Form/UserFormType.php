<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
                'label' => false,
                'attr' => [
                    'class'=>'form-control', 'placeholder' =>'Votre nom'
                ]
            ])
            ->add('firstname',null,[
                'label' => false,
                'attr' => [
                    'class'=>'form-control', 'placeholder' =>'Votre prénom'
                ]
            ])
            ->add('email',null,[
                'label' => false,
                'attr' => [
                    'class'=>'form-control', 'placeholder' =>'Votre adresse e-mail'
                ]
            ])
            ->add('imageFile',VichFileType::class,[
                'label' => false,
                'attr' => [
                    'class'=>'form-control', 'placeholder' =>'Choisir une image'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
