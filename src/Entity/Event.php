<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "The event name must be at least {{ limit }} characters long",
     *     maxMessage = "The event name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;
 /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min = 10, minMessage = "The description must be at least {{ limit }} characters long")
     */
    private $description;

     /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThan("today", message="The start date must be in the future.")
     */
    private $startDate;

      /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTimeInterface")
     * @Assert\Expression(
     *     "this.getEndDate() > this.getStartDate()",
     *     message="The end date must be after the start date."
     * )
     */
    private $endDate;

     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $location;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="user_id", nullable=false)
     */
    private $organizer;


   /**
 * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="event", cascade={"remove"})
 */
private $ratings;

/**
 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="event", cascade={"remove"})
 */
private $comments;

/**
 * @ORM\OneToMany(targetEntity="App\Entity\EventLike", mappedBy="event", cascade={"remove"})
 */
private $likes;

      /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
     private $updatedAt;
     
/**
 * @ORM\OneToMany(targetEntity="App\Entity\EventImage", mappedBy="event", cascade={"persist", "remove"})
 */
private $images;


  /**
     * @ORM\Column(type="integer")
     */
    private $likeCount;

   /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Positive()
     * @Assert\LessThan(
     *     value = 1000,
     *     message="The max number of attendees must be less than 1000."
     * )
     */
    private $maxAttendees;
 
   /**
 * @ORM\OneToMany(targetEntity="App\Entity\EventParticipant", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
 */
private $participants;

/**
 * @ORM\Column(type="string", length=255)
 */
private $status = 'Pending'; // Default status

     
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
{
    return $this->status;
}

public function setStatus(string $status): self
{
    $this->status = $status;

    return $this;
}


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
    public function getMaxAttendees(): ?int
    {
        return $this->maxAttendees;
    }
    
    public function setMaxAttendees(int $maxAttendees): self
    {
        $this->maxAttendees = $maxAttendees;
    
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

 public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

  

    public function __construct()
    {
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->maxAttendees = 0;
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();



    }




    /**
 * @return Collection|EventImage[]
 */
public function getImages(): Collection
{
    return $this->images;
}

public function addImage(EventImage $image): self
{
    if (!$this->images->contains($image)) {
        $this->images[] = $image;
        $image->setEvent($this);
    }

    return $this;
}

public function removeImage(EventImage $image): self
{
    if ($this->images->contains($image)) {
        $this->images->removeElement($image);
        // Set the owning side to null (unless already changed)
        if ($image->getEvent() === $this) {
            $image->setEvent(null);
        }
    }

    return $this;
}


    public function getRatings(): ?\Doctrine\Common\Collections\Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setEvent($this);
        }

        return $this;
    }


    public function removeRating(Rating $rating): self
{
    if ($this->ratings->contains($rating)) {
        $this->ratings->removeElement($rating);
        $rating->setEvent(null);
    }

    return $this;
}

public function getComments(): ?\Doctrine\Common\Collections\Collection
{
    return $this->comments;
}

public function addComment(Comment $comment): self
{
    if (!$this->comments->contains($comment)) {
        $this->comments[] = $comment;
        $comment->setEvent($this);
    }

    return $this;
}

public function removeComment(Comment $comment): self
{
    if ($this->comments->contains($comment)) {
        $this->comments->removeElement($comment);
        $comment->setEvent(null);
    }

    return $this;
}

public function getLikes(): ?\Doctrine\Common\Collections\Collection
{
    return $this->likes;
}

public function addLike(EventLike $like): self
{
    if (!$this->likes->contains($like)) {
        $this->likes[] = $like;
        $like->setEvent($this);
    }

    return $this;
}

public function removeLike(EventLike $like): self
{
    if ($this->likes->contains($like)) {
        $this->likes->removeElement($like);
        $like->setEvent(null);
    }

    return $this;
}

public function getLikeCount(): int
{
    return $this->likes->count();
}
public function setLikeCount(int $likeCount): self
{
    $this->likeCount = $this->likes->count();

    return $this;
}

public function hasUserLiked(User $user): bool
{
  foreach ($this->likes as $like) {
    if ($like->getUser() === $user) {
      return true;
    }
  }

  return false;
}

public function getLikeByUser(User $user): ?EventLike
{
    return $this->likes->filter(function (EventLike $like) use ($user) {
        return $like->getUser() === $user;
    })->first();
}


 /**
     * @return Collection|EventParticipant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $user): self
    {
        if ($this->getMaxAttendees() <= $this->getParticipantCount()) {
            throw new \Exception('Maximum attendee limit has been reached.');
        }
    
        $participant = new EventParticipant();
        $participant->setUser($user);
        $participant->setEvent($this);
    
        $this->participants[] = $participant;
    
        return $this;
    }
    
    public function addEventParticipant(EventParticipant $eventParticipant): self
{
    if (!$this->participants->contains($eventParticipant)) {
        $this->participants[] = $eventParticipant;
        $eventParticipant->setEvent($this);
    }

    return $this;
}

public function removeParticipant(EventParticipant $participant): self
{
    if ($this->participants->contains($participant)) {
        $this->participants->removeElement($participant);
        // Set the owning side to null (unless already changed)
        if ($participant->getEvent() === $this) {
            $participant->setEvent(null);
        }
    }

    return $this;
}
    public function getParticipantCount(): int
    {
        return count($this->participants);
    }

    public function getRatingAverage(): ?float
    {
        $sum = 0;
        $count = 0;

        foreach ($this->ratings as $rating) {
            $sum += $rating->getValue();
            $count++;
        }

        if ($count > 0) {
            return $sum / $count;
        }

        return null;
    }

    public function getUserRating(User $user): ?int
    {
        $rating = $this->getRatings()->filter(function (Rating $rating) use ($user) {
            return $rating->getUser() === $user;
        })->first();

        return $rating ? $rating->getValue() : null;
    }
}

