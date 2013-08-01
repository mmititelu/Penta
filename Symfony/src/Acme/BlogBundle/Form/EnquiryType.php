<?php 
namespace Acme\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('content','textarea');
    }
    
    public function getName()
    {
        return 'new';
    }
}
?>
