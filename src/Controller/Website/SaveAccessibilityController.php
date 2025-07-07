<?php

declare(strict_types=1);

namespace Pixel\AccessibilityBundle\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SaveAccessibilityController extends AbstractController
{
    private const COOKIE_NAME = 'accessibility';
    private const COOKIE_EXPIRY_PERIOD = '+1 month';

    private const ALLOWED_OPTIONS = ['fontSize', 'contrast', 'dyslexiaFont', 'lineHeight'];

    #[Route(path: '/ajax/save-accessibility', name: 'ajax_save_accessibility')]
    public function saveAccessibility(Request $request): JsonResponse
    {
        $selectedOptions = $this->extractAccessibilityOptions($request);
        $selectedOptionsJson = json_encode($selectedOptions, JSON_THROW_ON_ERROR);

        $response = new JsonResponse(['selectedOptions' => $selectedOptionsJson]);

        // Clear existing cookie if present
        if ($request->cookies->has(self::COOKIE_NAME)) {
            $response->headers->clearCookie(self::COOKIE_NAME);
        }

        $response->headers->setCookie($this->createAccessibilityCookie($selectedOptionsJson));

        return $response;
    }

    private function extractAccessibilityOptions(Request $request): array
    {
        $options = [];

        foreach (self::ALLOWED_OPTIONS as $option) {
            $value = $request->get($option);
            if ($value !== null) {
                $options[$option] = $value;
            }
        }

        return $options;
    }

    private function createAccessibilityCookie(string $value): Cookie
    {
        return Cookie::create(self::COOKIE_NAME)
            ->withValue($value)
            ->withExpires(new \DateTimeImmutable(self::COOKIE_EXPIRY_PERIOD))
            ->withHttpOnly(false)
            ->withSecure($this->isSecureRequest())
            ->withSameSite(Cookie::SAMESITE_LAX);
    }

    private function isSecureRequest(): bool
    {
        return $this->container->get('request_stack')->getCurrentRequest()?->isSecure() ?? false;
    }
}