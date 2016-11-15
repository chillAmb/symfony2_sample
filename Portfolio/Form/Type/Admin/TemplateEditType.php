<?php

namespace Plugin\Template\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TemplateEditType extends AbstractType
{
    public $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $app = $this->app;

        $builder
            ->add('name', 'text', array(
                'label' => 'Name',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('id', 'hidden')
            ->addEventListener(FormEvents::POST_SUBMIT, function ($event) use ($app) {
            })
            ->addEventSubscriber(new \Eccube\Event\FormEventSubscriber());
    }

    /**
    * {@inheritdoc}
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => 'Plugin\MailMagazine\Entity\MailMagazineTemplate',
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'plugin_template_edit';
    }
}
