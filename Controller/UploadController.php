<?php

namespace QoopLmao\FileUploaderBundle\Controller;

use QoopLmao\FileUploaderBundle\Event\Event\FilterFilesUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Event\Event\FilterUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Event\Event\FormEvent;
use QoopLmao\FileUploaderBundle\Event\Event\GetFilesUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Event\Event\GetUploadResponseEvent;
use QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderEvents;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends ContainerAware
{
    public function formAction(Request $request)
    {
        /** @var $formFactory \QoopLmao\FileUploaderBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('qoop_lmao_file_uploader.upload.form.factory');

        $form = $formFactory->createForm();

        $route_reg = $request->get('route_reg') ?: $this->container->get('router')->generate('qoop_lmao_file_uploader_upload');
        $route_json = $request->get('route_json') ?: $this->container->get('router')->generate('qoop_lmao_file_uploader_upload_json');

        return $this->container->get('templating')->renderResponse('QoopLmaoFileUploaderBundle:Upload:form.html.twig', array(
            'form' => $form->createView(),
            'route_reg' => $route_reg,
            'route_json' => $route_json,
        ));
    }

    public function listAction(Request $request)
    {
        /** @var $uploadManager \QoopLmao\FileUploaderBundle\Model\UploadManager */
        $uploadManager = $this->container->get('qoop_lmao_file_uploader.upload.manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetUploadResponseEvent($uploadManager->createUpload(), $request);
        $dispatcher->dispatch(QoopLmaoFileUploaderEvents::LIST_INITIALIZE, $event);

        if (null === $response = $event->getResponse())
        {
            $uploads = $uploadManager->findAllUploads();

            $route_reg = $request->get('route_reg') ?: $this->container->get('router')->generate('qoop_lmao_file_uploader_upload');
            $route_json = $request->get('route_json') ?: $this->container->get('router')->generate('qoop_lmao_file_uploader_upload_json');

            $response = $this->container->get('templating')->renderResponse('QoopLmaoFileUploaderBundle:Upload:list.html.twig', array(
                'files' => $uploads,
                'route_reg' => $route_reg,
                'route_json' => $route_json,
            ));
        }

        return $response;
    }

    public function uploadAction(Request $request)
    {
        /** @var $uploadManager \QoopLmao\FileUploaderBundle\Model\UploadManager */
        $uploadManager = $this->container->get('qoop_lmao_file_uploader.upload.manager');
        /** @var $formFactory \QoopLmao\FileUploaderBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('qoop_lmao_file_uploader.upload.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $dispatcher = $this->container->get('event_dispatcher');

        $form = $formFactory->createform();

        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(QoopLmaoFileUploaderEvents::UPLOAD_INITIALIZE, $event);

        if (null !== $response = $event->getResponse())
        {
            return $response;
        }

        if ('POST' === $request->getMethod())
        {
            if (!$form->isBound())
            {
                $form->bind($request);
            }

            if ($form->isValid())
            {
                $uploads = $form->get('files')->getData();

                $event = new GetFilesUploadResponseEvent($uploads, $request);
                $dispatcher->dispatch(QoopLmaoFileUploaderEvents::UPLOAD_SUCCESS, $event);

                $uploadManager->updateMultipleUploads($uploads);

                if (null === $response = $event->getResponse())
                {
                    $this->setFlash('success', 'Files uploaded successfully');

                    $url = $this->container->get('router')->generate('qoop_lmao_file_uploader_list');
                    $response = new RedirectResponse($url);
                }

                $event = new FilterFilesUploadResponseEvent($uploads, $request, $response);
                $dispatcher->dispatch(QoopLmaoFileUploaderEvents::UPLOAD_COMPLETED, $event);

                return $event->getResponse();
            }
        }

        $url = $this->container->get('router')->generate('qoop_lmao_file_uploader_list');
        $response = new RedirectResponse($url);

        return $response;
    }

    public function downloadAction()
    {

    }

    public function deleteAction(Request $request, $id)
    {
        /** @var $uploadManager \QoopLmao\FileUploaderBundle\Model\UploadManager */
        $uploadManager = $this->container->get('qoop_lmao_file_uploader.upload.manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $dispatcher = $this->container->get('event_dispatcher');

        /** @var $upload \QoopLmao\FileUploaderBundle\Model\UploadInterface */
        $upload = $uploadManager->getUpload($id);

        $event = new GetUploadResponseEvent($upload, $request);
        $dispatcher->dispatch(QoopLmaoFileUploaderEvents::DELETE_INITIALIZE, $event);

        if (null === $response = $event->getResponse())
        {
            $this->setFlash('notice', 'File deleted successfully');

            $url = $this->container->get('router')->generate('qoop_lmao_file_uploader_list');
            $response = new RedirectResponse($url);
        }

        $uploadManager->removeUpload($upload);

        $event = new FilterUploadResponseEvent($upload, $request, $response);
        $dispatcher->dispatch(QoopLmaoFileUploaderEvents::DELETE_COMPLETED, $event);

        return $event->getResponse();
    }

    private function setFlash($name, $value)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $this->container->get('session');

        $session->getFlashBag()->add($name, $value);
    }
}
