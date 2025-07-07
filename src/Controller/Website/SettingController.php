<?php

namespace Pixel\AccessibilityBundle\Controller\Website;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\AccessibilityBundle\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    #[Route(path: '/ajax/accessibility-options', name: 'ajax_accessibility_options')]
    public function accessibilityOptions(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $settings = $entityManager->getRepository(Setting::class)->findOneBy([]);
        $json = [];

        if ($settings) {
            $accessibilityCookie = false;
            if ($request->cookies->get("accessibility")) $accessibilityCookie = json_decode($request->cookies->get("accessibility"));

            $json['hasSettings'] = true;
            $json['template'] = $this->renderView("@Accessibility/twig/accessibility_content.html.twig", [
                'settings' => $settings,
                'accessibilityCookie' => $accessibilityCookie
            ]);
        } else {
            $json['hasSettings'] = false;
        }

        return new JsonResponse($json);
    }
}
