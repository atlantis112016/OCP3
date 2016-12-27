<?php

namespace MyApp\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
=======
>>>>>>> refs/remotes/origin/debug

class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
<<<<<<< HEAD
            ->add('nom', TextType::class, array(
            'attr' => array(
                'class' => 'nom',
                'required',
                'placeholder' => 'Entrez votre Nom'
            )
        ))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'class' => 'prenom',
                    'required',
                    'placeholder' => 'Entrez votre Prénom'
                )
            ))
            ->add('pays', CountryType::class, array(
                'data' => 'FR'
            ))
            ->add('dateNaissance', BirthdayType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'naissance'
                )
            ))
            ->add('typeJournee', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journee',
                    'Demi-journée' => 'demiJournee'
                ),
                'attr' => array(
                    'class' => 'choixType'
                )
            ))
            ->add('tarifReduit', CheckboxType::class, array(
                'label' => 'Tarif Réduit',
                'label_attr' => array(
                    'id' => 'labelReduit'
                ),
                'required' => false,
                'attr' => array(
                    'class' => 'choixReduit'
                )
            ))

         /*   ->add('commande')
            ->add('typeTarif')*/
=======
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance',BirthdayType::class, array(
                'days' => range(1,31),
                'months' => range(1, 12),
                'years' => range(1902, date('Y')),
                'format' => 'dd-MM-yyyy'))
            ->add('isTarifReduit', CheckboxType::class, array(
                'label' => 'Tarif réduit ',
                'required'  =>  false))
            ->add('pays', CountryType::class, array(
                'preferred_choices' => array('FR')
            ))
>>>>>>> refs/remotes/origin/debug
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyApp\BilletterieBundle\Entity\Billet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myapp_billetteriebundle_billet';
    }


}
