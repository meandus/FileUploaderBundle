<?php

namespace QoopLmao\FileUploaderBundle\Form\Factory;

use Symfony\Component\Form\FormInterface;

interface FactoryInterface
{
    /**
     * Creates a new instance of the form
     *
     * @return FormInterface
     */
    public function createForm();
}