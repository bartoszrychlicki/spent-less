<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransactionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isExpense')
            ->add('category')
            ->add('amount', 'money', array(
                'currency' => 'PLN',
                'attr' => array('tab-order'=> 0)
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
            ->add('memo')
            ->add('payee')
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
