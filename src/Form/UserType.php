<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if ($user instanceof User && null === $user->getId()) {
                    // Le formulaire est utilisé pour la création d'un utilisateur
                    $form->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                        'required' => true,
                        'first_options'  => [
                            'label' => 'Mot de passe',
                        ],
                        'second_options' => [
                            'label' => 'Tapez le mot de passe à nouveau',
                        ],
                    ]);
                } else {
                    // Le formulaire est utilisé pour la modification d'un utilisateur
                    $form->remove('password');
                }
            })
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'required' => true,
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
