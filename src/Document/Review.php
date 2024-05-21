<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(collection: 'reviews')]
class Review
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    private $rating;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    private $content;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    private $pseudo;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[MongoDB\Field(type: 'bool')]
    private $validate = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }
}
