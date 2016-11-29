<?php
namespace MyApp\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MyApp\BilletterieBundle\Entity\Billet;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('datereserv', TextType::class,
                array( 'disabled' => true))
            ->add('datevisite', TextType::class)
            ->add('email', EmailType::class,
                array( 'required' => true ))
            ->add('nbBillet', ChoiceType::class,
                array( 'required' => true,
                    'label'  => 'Choisir nombre de billet',
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
            ->add('save', SubmitType::class, array ('attr' => array(
                'class' => 'btn-primary pull-right'),
                'label' => 'ETAPE 2'))
        ;
    }
/*         ->add('montantTotal', TextType::class,
                array( 'required' => true,
                    'label'  => 'Montant Total'))
            ->add('codeReserv', TextType::class,
                array( 'required' => true,
                    'label'  => 'Code de la réservation'))
            ->add('statut', TextType::class,
                array( 'required' => true,
                    'label'  => 'Statut de la commande'))
            ->add('tokenStripe', TextType::class,
                array( 'required' => true,
                    'label'  => 'Code Stripe'))
*/
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
