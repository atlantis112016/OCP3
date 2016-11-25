namespace MyApp\BilletterieBundle\Form;

use MyApp\BilletterieBundle\Entity\Billet ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Component\Form\AbstractType ;
use Symfony\Component\Form\FormBuilderInterface ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;

class DateReserv extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder
            -> add ( 'task' )
            -> add ( 'dueDate' , null , array ( 'widget' => 'single_text' ))
            -> add ( 'save' , SubmitType :: class )
        ;
    }
}