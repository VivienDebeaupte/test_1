<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class'       => 'form-control text-left',
                    'id'          => 'name',
                    'placeholder' => 'John Dumont'
                ]
            ])
            ->add('email', EmailType::class,[
                'required' => true,
                'attr' => [
                    'class'       => 'form-control',
                    'id'          => 'email',
                    'placeholder' => 'name@example.com'
                ]
            ])
            ->add('subject', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class'       => 'form-control text-left',
                    'id'          => 'subject',
                    'placeholder' => 'Subject'
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class'       => 'form-control',
                    'id'          => 'message',
                    'placeholder' => 'Your message here ..',
                    'rows'   => 10,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
