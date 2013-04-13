<?php

namespace QoopLmao\FileUploaderBundle\Form\DataTransformer;

use QoopLmao\FileUploaderBundle\Model\UploadManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFileToUploadArrayTransformer implements DataTransformerInterface
{
    protected $uploadManager;

    public function __construct(UploadManager $uploadManager)
    {
        $this->uploadManager = $uploadManager;
    }

    /**
     * Transforms a Department emails array to string.
     *
     * @param mixed $value Emails array
     *
     * @return mixed|null|string emails as string
     *
     * @throws UnexpectedTypeException if the gives value is not an array
     */
    public function transform($value)
    {
        return null;
    }

    /**
     * Transforms a departmentname string into a DepartmentInterface instance.
     *
     * @param mixed $value Emails string
     *
     * @return array|mixed|null emails as array
     *
     * @throws UnexpectedTypeException if the given value is not a string

     * @throws TransformationFailedException if an email is not valid
     */
    public function reverseTransform($value)
    {
        $files = array();

        if (null === $value || '' === $value)
        {
            return $files;
        }

        if (!is_array($value))
        {
            throw new UnexpectedTypeException($value, 'array');
        }

        if ($value instanceof UploadedFile)
        {
            $upload = $this->uploadManager->createUpload()->setFile($value);
            $files[] = $upload;
        }
        else
        {
            foreach ($value as $file)
            {
                if ($file instanceof UploadedFile && !empty($file))
                {
                    //echo $file->getError();
                    $upload = $this->uploadManager->createUpload()->setFile($file);
                    $files[] = $upload;
                }
            }
        }

        return $files;
    }
}