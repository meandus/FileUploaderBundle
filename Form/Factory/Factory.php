<?php

namespace QoopLmao\FileUploaderBundle\Form\Factory;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class Factory extends FormFactory implements FactoryInterface, FormFactoryInterface
{
    /**
     * The Symfony form factory
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * The message form type
     *
     * @var string
     */
    protected $formType;

    /**
     * The name of the form
     *
     * @var string
     */
    protected $formName;

    /**
     * The validation groups for the form
     *
     * @var array
     */
    protected $validationGroups;

    public function __construct(FormFactoryInterface $formFactory, $formType, $formName, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->formName = $formName;
        $this->validationGroups = $validationGroups;
    }

    /**
     * Creates a new instance of the form
     *
     * @return FormInterface
     */
    public function createForm()
    {
        return $this->formFactory->createNamed($this->formName, $this->formType, null, array('validation_groups' => $this->validationGroups));
    }
}