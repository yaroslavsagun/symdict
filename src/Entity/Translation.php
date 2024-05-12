<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
#[ORM\Table(name: 'translations')]
class Translation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Language $original_language = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Language $translation_language = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Word $original_word = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Word $translation_word = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalLanguage(): ?Language
    {
        return $this->original_language;
    }

    public function setOriginalLanguage(?Language $original_language): static
    {
        $this->original_language = $original_language;

        return $this;
    }

    public function getTranslationLanguage(): ?Language
    {
        return $this->translation_language;
    }

    public function setTranslationLanguage(?Language $translation_language): static
    {
        $this->translation_language = $translation_language;

        return $this;
    }

    public function getOriginalWord(): ?Word
    {
        return $this->original_word;
    }

    public function setOriginalWord(?Word $original_word): static
    {
        $this->original_word = $original_word;

        return $this;
    }

    public function getTranslationWord(): ?Word
    {
        return $this->translation_word;
    }

    public function setTranslationWord(?Word $translation_word): static
    {
        $this->translation_word = $translation_word;

        return $this;
    }
}
