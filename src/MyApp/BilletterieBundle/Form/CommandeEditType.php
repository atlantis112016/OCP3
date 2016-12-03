<?php

namespace MyApp\BilletterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommandeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('billets');
     }

   public function getParent()
    {
        return CommandeType::class;
    }
}
