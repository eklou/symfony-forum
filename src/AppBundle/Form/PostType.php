<?php

namespace AppBundle\Form;



use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ["label"=>"Titre"])
            ->add('text', CKEditorType::class, [
                "label" =>"Texte",
                "attr" => ["rows" =>12]
            ])

           // ->add('author', EmailType::class, ["label" => "Auteur"])
            //->add('createdAt', DateTimeType::class, ["label" => "Date de publication", "widget" =>"single_text"])
            ->add('slug')
            ->add('theme', EntityType::class,[
                "class" => "AppBundle\Entity\Theme",
                "placeholder" => "Choisissez un thème",
                "choice_label" => "name"

            ])
            -> add('submit', SubmitType::class, ["label" => " valider"]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
