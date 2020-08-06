<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=blogs::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blog;

    /**
    * @ORM\Column(type="datetime")
    */
    private $created;

    public function getCreated(){
        return $this->created;
    }

    public function setCreated($created){
        $this->created = $created;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getBlog(): ?blogs
    {
        return $this->blog;
    }

    public function setBlog(?blogs $blog): self
    {
        $this->blog = $blog;

        return $this;
    }
}
