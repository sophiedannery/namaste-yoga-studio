<?php

namespace App\Form;

use App\Entity\ClassType;
use App\Entity\Room;
use App\Entity\Session;
use App\Repository\ClassTypeRepository;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('classType', EntityType::class, [
                'class' => ClassType::class,
                'choice_label' => 'title',
                'placeholder' => 'Sélectionnez un type de cours',
                'query_builder' => function (ClassTypeRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.title', 'ASC');
                },
                'label' => 'Type de cours',
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'nameRoom',
                'required' => false,
                'placeholder' => '— Aucune (à définir plus tard)',
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('r')->orderBy('r.nameRoom', 'ASC');
                },
                'label' => 'Salle',
            ])
            ->add('startAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Début',
                'help'   => 'Date et heure de début',
                'attr'   => [
                    'min' => (new \DateTimeImmutable('now'))->format('Y-m-d\TH:i'),
                ],
            ])
            ->add('endAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Fin',
                'help'   => 'La fin doit être après le début',
            ])
            ->add('capacity', IntegerType::class, [
                'label' => 'Places disponibles',
                'attr'  => ['min' => 1, 'step' => 1],
            ])

            ->add('price', NumberType::class, [
                'required' => false,
                'label'    => 'Tarif (€)',
                'scale'    => 2,
                'html5'    => true,
                'attr'     => ['step' => '0.01', 'min' => '0'],
                'help'     => 'Laissez vide si gratuit.',
            ])

            ->add('details', TextareaType::class, [
                'required' => false,
                'label'    => 'Détails / description',
                'attr'     => ['rows' => 4, 'placeholder' => 'Précisez le niveau, le matériel, etc.'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}