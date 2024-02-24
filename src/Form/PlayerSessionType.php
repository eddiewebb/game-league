<?php

namespace App\Form;

use App\Entity\GameRole;
use App\Entity\Player;
use App\Entity\PlayerSession;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('session', EntityType::class, [
                'class' => Session::class,
            'choice_label' => function (Session $session): string {
                return $session->getDate()->format('Y-m-d H:i');
            },
            ])
            ->add('gameRole', EntityType::class, [
                'class' => GameRole::class,
'choice_label' => 'name',
            ])
            ->add('player', EntityType::class, [
                'class' => Player::class,
'choice_label' => 'name',
            ])
            ->add('score')
            ->add('isWinner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerSession::class,
        ]);
    }
}
