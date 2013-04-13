<?php

namespace QoopLmao\FileUploaderBundle\Generator;

use Liip\ImagineBundle\Controller\ImagineController;
use QoopLmao\FileUploaderBundle\Model\UploadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GeneratorLiipImagine implements GeneratorInterface
{
    private $request;

    private $router;

    private $liip_imagine;

    private $thumb_filter;

    public function __construct(Request $request, UrlGeneratorInterface $router, ImagineController $liip_image, $thumb_filter)
    {
        $this->request = $request;
        $this->router = $router;
        $this->liip_imagine = $liip_image;
        $this->thumb_filter = $thumb_filter;
    }

    public function isImage(UploadInterface $upload)
    {
        $isImage = true;

        try {
            $this->liip_imagine->filterAction(
                $this->request,
                $upload->getWebPath(),      // original image you want to apply a filter to
                $this->thumb_filter              // filter defined in config.yml
            );
        } catch (\Exception $e) {
            $isImage = false;
        }

        return $isImage;
    }

    public function getImageRoute(UploadInterface $upload)
    {
        if (!$this->isImage($upload))
        {
            return null;
        }

        return $this->router->generate('qoop_lmao_file_uploader_thumbnail', array(
            'id' => $upload->getId())
        );
    }

    public function getThumbnailResponse(UploadInterface $upload)
    {
        return $this->liip_imagine->filterAction(
            $this->request,
            $upload->getWebPath(),
            $this->thumb_filter
        );
    }
}