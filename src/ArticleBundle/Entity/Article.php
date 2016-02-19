<?php

namespace ArticleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//pour validation des données

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="ArticleBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=150)
     * @Assert\NotNull()
     * @Assert\Length(min=15, max=150)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotNull()
     * @Assert\Regex("/^[a-zA-Z0-9]+/")
     * @Assert\Length(min=15, max=150)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=255)
     */
    private $createdBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="Image", inversedBy="article", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @var Image
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     */
    private $creator;

    /**
     * Article constructor.
     */
    public function __construct(){
        $this->setCreatedAt(new \Datetime());
        $this->setUpdatedAt(new \Datetime());
        $this->comments = new ArrayCollection();

    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->setUpdatedAt(new \Datetime());
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->setUpdatedAt(new \Datetime());
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Article
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Article
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return Article
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(){
        return strtolower(preg_replace("/[^a-zA-Z0-9-]/", "-", $this->title));
    }

    /**
     * Add comments
     *
     * @param \ArticleBundle\Entity\Comment $comments
     * @return Article
     */
    public function addComment(\ArticleBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \ArticleBundle\Entity\Comment $comments
     */
    public function removeComment(\ArticleBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set image
     *
     * @param \ArticleBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\ArticleBundle\Entity\Image $image = null)
    {
        $this->image = $image;
        if(null !== $image){
            $image->setArticle($this);
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return \ArticleBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersistEntity(){
        $this->updatedAt = new \DateTime();
    }
}
