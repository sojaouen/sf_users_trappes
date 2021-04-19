<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // definition de la liste des années
        // $years = range( date('Y')-100 , date('Y')-18 );
        // rsort($years);

        $years = range( date('Y')-18 , date('Y')-100, -1 );


        $builder

            // Email
            ->add('email', EmailType::class, [
                'label' => "Votre adresse email",
                'required' => true,
                'attr' => [
                    'placeholder' => "Saisir votre adresse email"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "L'adresse email est obligatoire."
                    ]),
                    new Email([
                        'message' => "L'adresse email n'est pas valide."
                    ]),
                    new Unique([
                        'message' => "L'adresse email est deja utilisée."
                    ])
                ]
            ])

            // Firstname
            ->add('firstname', TextType::class, [
                'label' => "Votre prénom",
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est obligatoire"
                    ])
                ]
            ])

            // Lastname
            ->add('lastname', TextType::class, [
                'label' => "Votre NOM",
                'label_attr' => [
                    'class' => "col-lg-3"
                ],
                'required' => true,
                'attr' => [
                    'placeholder' => "Ceci est le placeholder du champ lastname"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est obligatoire"
                    ])
                ]
            ])
            
            // Birthday
            ->add('birthday', BirthdayType::class, [
                'label' => "Votre date de naissance",
                'required' => true,

                // Placeholder des champs <select>
                'placeholder' => [
                    'year' => "Année",
                    'month' => "Mois",
                    'day' => "Jour",
                ],

                // Liste des options du champ <select> "Year"
                'years' => $years,

                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ est obligatoire"
                    ]),
                    // new GreaterThan([
                    //     'value' => new \DateTime('now'),
                    //     'message' => "T'es trop jeune mon chaton !!!"
                    // ])
                ]
            ])

            // Agree Terms
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            // Password
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,

                // Pas de label sur le champ "repeated"
                'label' => false,

                'required' => true,
                'mapped' => false,

                // Option du premier champ
                'first_options' => [
                    'label' => "Mot de passe",
                    'attr' => [
                        'placeholder' => "Saisir votre nouveau mot de passe",
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => "Nouveau mot de passe requis."
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => "Minimum 6 caractères",
                            'max' => 32,
                            'maxMessage' => "Maxi 32 caractères",
                        ]),
                        new Regex([
                            'pattern' => "/^[a-z0-9]+/i",
                            'message' => "doit contenir des caractères alphabétique et numerique uniquement"
                        ])
                    ]
                ],

                // Option du second champ
                'second_options' => [
                    'label' => "Confirmation du mot de passe",
                    'attr' => [
                        'placeholder' => "Répéter votre nouveau mot de passe",
                    ]
                ],

                // Message d'erreur si les champs ne correspondent pas
                'invalid_message' => "Les champs ne sont pas identique."

            ])




            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}