<?php

namespace Plugin\Stamp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchStampType extends AbstractType
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
	if (isset($options['data'])) {
	    $choice = $options['data'];
	} else {
            $choice = null;
        }

        $builder
            ->add('multi', 'text', array(
                'label' => 'ID・スタンプ名',
                'required' => false,
            ))
            ->add('typeform', 'choice', array(
                'label' => 'カテゴリ',
 		'choices' => $choice,
                'required' => false,
		'expanded' => true,
		'multiple' => false,
            ))
            ->add('create_date_start', 'date', array(
                'label' => '登録日',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'empty_value' => array('year' => '----', 'month' => '--', 'day' => '--'),
            ))
            ->add('create_date_end', 'date', array(
                'label' => '登録日',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'empty_value' => array('year' => '----', 'month' => '--', 'day' => '--'),
            ))
            ->add('update_date_start', 'date', array(
                'label' => '更新日',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'empty_value' => array('year' => '----', 'month' => '--', 'day' => '--'),
            ))
            ->add('update_date_end', 'date', array(
                'label' => '更新日',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'empty_value' => array('year' => '----', 'month' => '--', 'day' => '--'),
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function ($event) use ($app) {
            })
            ->addEventSubscriber(new \Eccube\Event\FormEventSubscriber());
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'admin_search_stamp';
    }
}
