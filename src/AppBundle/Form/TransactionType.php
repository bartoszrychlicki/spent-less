<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Category;

class TransactionType extends AbstractType
{
    
    private $formOptions;
    
    public function __construct($options = array()){        
        $this->formOptions = $options;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isExpense')
            ->add('category'
                //, 'choice', array(
                //'choices' => $this->formOptions['em']->getRepository('AppBundle\Entity\Category')->getCategoriesAsTree())
                
                )
            ->add('amount', 'money', array(
                'currency' => 'PLN',
                'attr' => array('tab-order'=> 0, 'type' => 'number')
                ))
            ->add('tags', 'text', array(
                'required' => true,
                'mapped'   => false,
                ))
            ->add('isFlagged', 'checkbox', array(
                'label'    => 'Oflagować?',
                'required' => false,
                ))
            ->add('createdAt')
            ->add('payee')
            ->add('memo')
            ->add('account')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Transaction'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_transaction';
    }
}
