<?php

namespace minipoBundle\Form;

use Doctrine\ORM\EntityRepository;
use minipoBundle\Entity\CategorieReclamation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationemployeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idcatrecemp', EntityType::class,
                array(
                    'class'=>'minipoBundle:CategorieReclamationEmp',
                    'choice_label'=>'nom',
                    'label'=>'categorie',
                    'multiple'=>false
                ))

            ->add('objet')
            ->add('description',TextareaType::class)
            ->add('image',FileType::class)
            ->add('Envoyer', SubmitType::class,['attr'=>['formnovalidate'=>'formnovalidate']]);
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'minipoBundle\Entity\Reclamationemploye'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'minipobundle_reclamationemploye';
    }


}

