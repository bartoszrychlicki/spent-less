<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class TransactionType extends AbstractType
{
    private $options;
    
    public function __construct(array $options = null) 
    {
        $this->options['preferred_choices'] = array();
        if($options != null) {
            $this->options = $options;
        }
        
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isExpense', 'choice', array(
                'choices' => array(
                    '1'   => 'Wydatek',
                    '0'   => 'Przychód',
                ),
                'multiple' => false,
                'expanded' => true,
                'label'     => false,
                'attr'      => array(
                    'class' => 'hidden'
                    )
                ))
            ->add('category', 'entity', array(
                    'group_by' => 'masterCategory.name',
                    'class'     => 'AppBundle\Entity\Category',
                    'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('c')
                                      ->orderBy('c.name', 'ASC')
                                      ->where('c.masterCategory is not NULL');
                            },
                    'preferred_choices' => $this->options['preferred_choices']
                ))
            ->add('amount', 'money', array(
                'currency' => 'PLN',
                'attr' => array('tab-order'=> 0, 'type' => 'number')
                ))
            ->add('tags', 'text', array(
                'required' => false,
                'mapped'   => false,
                ))
            ->add('isFlagged', 'checkbox', array(
                'label'    => 'Oflagować?',
                'required' => false,
                ))
            ->add('payee')
            ->add('createdAt')
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
