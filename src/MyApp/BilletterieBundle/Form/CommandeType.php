<?php

namespace MyApp\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MyApp\BilletterieBundle\Entity\Billet;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('email',EmailType::class,
                array( 'required' => true ))
            ->add('dateVisite', DateTimeType::class, array('widget'=> 'single_text'))
            ->add('typeJournee',ChoiceType::class, array('choices'  => array(
        'Journée' => 'Journee',
        'Demi-journée' => 'Demi-journee'),
        'choices_as_values' => true))
             ->add('nbBillet', ChoiceType::class,
                array( 'required' => true,
                    'label'  => 'Choisir le nombre de billet',
                    'choices' => array(
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6,
                        '7' => 7,
                        '8' => 8,
                        '9' => 9,
                        '10' => 10,
                    )))
                    ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\BilletterieBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myapp_billetteriebundle_commande';
    }


}
