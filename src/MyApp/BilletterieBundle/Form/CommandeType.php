<?php
namespace MyApp\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MyApp\BilletterieBundle\Entity\Commande;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datereserv', DateType::class, array ('widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'label' => false,
                'attr' => array(
                    'class' => 'date dateVisite',
                    'style' => 'visibility:hidden')))
            ->add('dateVisite', DateType::class, array ('widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'label' => 'Votre date de visite'))
            ->add('email', EmailType::class,
                array( 'required' => true,
                    'label'  => 'Votre E-mail'))
            ->add('nbBillet', TextType::class,
                array( 'required' => true,
                    'label'  => 'Choisir nombre de billet'))
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
