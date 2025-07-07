<?php

declare(strict_types=1);

namespace Pixel\AccessibilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

#[ORM\Entity]
#[ORM\Table(name: 'accessibility_settings')]
#[Serializer\ExclusionPolicy('all')]
class Setting implements AuditableInterface
{
    use AuditableTrait;

    public const RESOURCE_KEY = 'accessibility_settings';
    public const FORM_KEY = 'accessibility_settings';
    public const SECURITY_CONTEXT = 'accessibility_settings.settings';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Serializer\Expose]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Serializer\Expose]
    private ?bool $fontSize = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Serializer\Expose]
    private ?bool $contrast = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Serializer\Expose]
    private ?bool $dyslexiaFont = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Serializer\Expose]
    private ?bool $lineHeight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFontSize(): ?bool
    {
        return $this->fontSize;
    }

    public function setFontSize(?bool $fontSize): self
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    public function getContrast(): ?bool
    {
        return $this->contrast;
    }

    public function setContrast(?bool $contrast): self
    {
        $this->contrast = $contrast;

        return $this;
    }

    public function getDyslexiaFont(): ?bool
    {
        return $this->dyslexiaFont;
    }

    public function setDyslexiaFont(?bool $dyslexiaFont): self
    {
        $this->dyslexiaFont = $dyslexiaFont;

        return $this;
    }

    public function getLineHeight(): ?bool
    {
        return $this->lineHeight;
    }

    public function setLineHeight(?bool $lineHeight): self
    {
        $this->lineHeight = $lineHeight;

        return $this;
    }

    /**
     * Checks if any accessibility feature is enabled
     */
    public function hasAnyFeatureEnabled(): bool
    {
        return $this->fontSize === true
            || $this->contrast === true
            || $this->dyslexiaFont === true
            || $this->lineHeight === true;
    }

    /**
     * Returns an array of enabled features
     */
    public function getEnabledFeatures(): array
    {
        $features = [];

        if ($this->fontSize === true) {
            $features[] = 'fontSize';
        }

        if ($this->contrast === true) {
            $features[] = 'contrast';
        }

        if ($this->dyslexiaFont === true) {
            $features[] = 'dyslexiaFont';
        }

        if ($this->lineHeight === true) {
            $features[] = 'lineHeight';
        }

        return $features;
    }

    /**
     * Bulk enable/disable features
     */
    public function setFeaturesState(array $features): self
    {
        $this->fontSize = $features['fontSize'] ?? null;
        $this->contrast = $features['contrast'] ?? null;
        $this->dyslexiaFont = $features['dyslexiaFont'] ?? null;
        $this->lineHeight = $features['lineHeight'] ?? null;

        return $this;
    }

    /**
     * Reset all features to disabled state
     */
    public function resetAllFeatures(): self
    {
        $this->fontSize = false;
        $this->contrast = false;
        $this->dyslexiaFont = false;
        $this->lineHeight = false;

        return $this;
    }
}