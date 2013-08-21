<?php
namespace Acme\StoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('price');
    }
    
    public function getName()
    {
        return 'new';
    }
}

?>
