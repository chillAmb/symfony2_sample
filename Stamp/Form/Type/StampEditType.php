<?php

namespace Plugin\Stamp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class StampEditType extends AbstractType
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
	    $choice = $options['data']->choice;
	} else {
            $choice = null;
        }
        $builder
            ->add('id', 'integer', array(
                'label' => 'ID',
                )
            )
            ->add('name', 'text', array(
                'label' => 'スタンプ名',
                'required' => true,
            ))
            ->add('type', 'text', array(
                'label' => 'type',
                )
            )
            ->add('typeform', 'choice', array(
                'label' => 'カテゴリ',
 		'choices' => $choice,
                'required' => true,
		'multiple' => false,
		'expanded' => true
            ))
            ->add('publish', 'choice', array(
                'label' => '公開・非公開',
 		'choices' => array('公開', '非公開'),
                'required' => true,
		'multiple' => false,
		'expanded' => true
            ))
            ->add('img', 'text', array(
                'label' => '画像',
                'required' => true,
            ))
            ->add('rank', 'text', array(
                'label' => 'rank',
            ))
            ->add('add_images', 'collection', array(
                'type' => 'hidden',
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
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
        return 'admin_stamp_edit';
    }
}
