<?php

// src/Form/PlayerTransferType.php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlayerTransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'choice_label' => function (Player $player) {
                    return $player->getName() . ' ' . $player->getSurname();
                },
            ])
            ->add('sellingTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('buyingTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('transferFee');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // This form does not map directly to a Doctrine entity, so we don't set a 'data_class' option.
        ]);
    }
}
