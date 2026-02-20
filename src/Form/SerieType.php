<?php

namespace App\Form;

use App\DataTransformer\SlashTransformer;
use Symfony\Component\Form\CallbackTransformer;
use App\Entity\Serie;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la serie',
                'required' => false,
            ])
            ->add('overview')
            ->add('genres', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => array_combine(
                    ['War','Thriller', 'Politics', 'Western', 'Drama', 'Sci-Fi', 'Comedy'],
                    ['War','Thriller', 'Politics', 'Western', 'Drama', 'Sci-Fi', 'Comedy']),
                'multiple' => true
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'En cours' => 'returning',
                    'Terminé' => 'ended',
                    'Annulé' => 'Canceled',
                ],
                'placeholder' => '--Choisissez un statut--',
            ])
            ->add('vote', TextType::class, [
                'required' => false,
            ])
            ->add('popularity')
            ->add('firstAirDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(1900, date('Y')),  // De 1900 à l'année actuelle
                'format' => 'dd-MM-yyyy',           // Ordre d'affichage : jour-mois-année
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour']
            ])
            ->add('lastAirDate')
            ->add('backdrop')
            //on remplace le champ poster présent dans Serie par posterFile pour l'upload
            ->add('posterFile', FileType::class, [
                //pour qu'il n'aille pas chercher poster_file dans l'entité série car champs non mappé
                'mapped' => false,
                'label' => 'Upload Poster',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Votre fichier est trop lourd !',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Format accepté : jpg, jpeg, png',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
        $builder->get('genres')->addModelTransformer(new SlashTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
