<?php

namespace minipoBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idc', EntityType::class,
            array(
                'class'=>'minipoBundle:Commande',
                'choice_label'=>'refc',
                'multiple'=>false
            ))
            ->add('destination')
            ->add('dateliv')
            ->add('id', EntityType::class,
                array(
                    'class'=>'minipoBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where("u.roles LIKE '%ROLE_LIVREUR%'");
                    },
                    'choice_label'=>'username',
                    'multiple'=>false
                ))
            ->add('valider',SubmitType::class,
                ['attr'=>['formnovalidate'=>'formnovalidate']]);
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
