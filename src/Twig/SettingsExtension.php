<?php

namespace Pixel\AccessibilityBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingsExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Environment $twig
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_accessibility_options', $this->getAccessibilityOptions(...), ['is_safe' => ['html']]),
            new TwigFunction('get_accessibility_classes', $this->getAccessibilityClasses(...), ['is_safe' => ['html']]),
        ];
    }

    public function getAccessibilityOptions(string $buttonId, string $elementId): string
    {
        return $this->twig->render('@Accessibility/twig/accessibility_options.html.twig', [
            'buttonId' => $buttonId,
            'elementId' => $elementId,
        ]);
    }

    public function getAccessibilityClasses(): string
    {
        return $this->twig->render('@Accessibility/twig/accessibility_classes.html.twig');
    }
}