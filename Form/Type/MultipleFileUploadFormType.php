<?php

namespace QoopLmao\FileUploaderBundle\Form\Type;

use QoopLmao\FileUploaderBundle\Form\DataTransformer\MultipleFileToUploadArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MultipleFileUploadFormType extends AbstractType
{
    protected $multipleFileTransformer;

    public function __construct(MultipleFileToUploadArrayTransformer $multipleFileTransformer)
    {
        $this->multipleFileTransformer = $multipleFileTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->multipleFileTransformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'invalid_message' => 'Not all files are valid',
            'required' => false,
            'attr'=> array(
                'multiple' => 'multiple',
            ),
        ));
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'qoop_lmao_file_uploader_file_upload';
    }
}