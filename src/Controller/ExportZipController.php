<?php

namespace WebEtDesign\RgpdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ExportZipController extends AbstractController
{
    /**
     * @Route("/rgpd/zip/download/{filename}", name="rgpd_zip_download")
     * @param Request $request
     * @param string $filename
     * @return BinaryFileResponse
     */
    public function __invoke(Request $request, string $filename)
    {
        $archiveDir = $this->getParameter('kernel.project_dir') . '/' .
            $this->getParameter('wd_rgpd.export.zip_private_path');

        if (!file_exists($archiveDir . '/' . $filename)) {
            throw new NotFoundHttpException();
        }

        return new BinaryFileResponse($archiveDir . '/' . $filename);
    }

}
