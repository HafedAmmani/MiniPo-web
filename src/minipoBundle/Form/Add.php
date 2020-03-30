<?php



namespace minipoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class Add extends AbstractType
{
/**
* {@inheritdoc}
*/
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('titre')
->add('description', TextareaType::class)
->add('imageFile', VichImageType::class)
->add('Submit', SubmitType::class);
}
/**
* {@inheritdoc}
*/
public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults(array(
'data_class' => 'minipoBundle\Entity\Articles'
));
}




}