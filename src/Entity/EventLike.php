<?php 

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventLikeRepository")
 * @ORM\Table(name="event_like")
 */
class EventLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;
    
   /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="likes")
 * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
 */
private $event;


 /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        $this->username = $user->getFirstName();

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }
    
    public function setEvent(?Event $event): self
    {
        $this->event = $event;
    
        return $this;
    }

    // ... (other methods)
}
