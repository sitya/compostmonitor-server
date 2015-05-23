<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SensorValueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('sensorData')
            ->add('timestamp', 'datetime', array( 'widget' => 'single_text', 'date_format' => 'yyyy-MM-dd HH:mm:ss' ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SensorValue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_sensorvalue';
    }
}
