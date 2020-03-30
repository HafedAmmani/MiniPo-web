<?php

namespace minipoBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idc', EntityType::class,
                    array(
                        'class'=>'minipoBundle:Commande',
                        'choice_label'=>'idcmd',
                        'multiple'=>false
                    ))
                ->add('destination')
                ->add('dateliv')
                ->add('id', EntityType::class,
                    array(
                        'class'=>'minipoBundle:User',
                        'choice_label'=>'id',
                        'multiple'=>false
                    ))
            ->add('modifier',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'minipoBundle\Entity\Livraison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'minipobundle_livraison';
    }


}
