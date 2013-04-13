<?php

namespace QoopLmao\FileUploaderBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends ContainerAware
{
    public function thumbnailAction(Request $request, $id)
    {
        /** @var $uploadManager \QoopLmao\FileUploaderBundle\Model\UploadManager */
        $uploadManager = $this->container->get('qoop_lmao_file_uploader.upload.manager');
        /** @var $upload \QoopLmao\FileUploaderBundle\Model\UploadInterface */
        $upload = $uploadManager->findUploadById($id);
        /** @var $generator \QoopLmao\FileUploaderBundle\Generator\GeneratorAdapter */
        $generator = $this->container->get('qoop_lmao_file_uploader.generator.adapter');

        if (!$upload)
        {
            throw new NotFoundHttpException('Image not found');
        }

        $response = $generator->getThumbnailResponse($upload);

        return $response;
    }
}