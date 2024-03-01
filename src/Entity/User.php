<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Badge;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 */

class User implements UserInterface
{
    /**
     * @var int
     *
     * @Groups("post:read")
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups("post:read")
     */
    private $userId;

    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Email cannot be blank.")
     * @Assert\Email(message="The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *     min=8,
     *     max=4096,
     *     minMessage="Password must be at least {{ limit }} characters long.",
     *     maxMessage="Password cannot be longer than {{ limit }} characters."
     * )
     * 
     */
    private $password;


      /**
     * The plainPassword is not persisted in the database, so we don't need ORM annotations
     * @Assert\NotBlank(message="Password cannot be blank.")
     * @Assert\Length(
     *     min=8,
     *     max=4096,
     *     minMessage="Password must be at least {{ limit }} characters long.",
     *     maxMessage="Password cannot be longer than {{ limit }} characters."
     * )
     */
    private $plainPassword;


    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups("post:read")
     */
    private $password2;


    /**
     * @return mixed
     */
    public function getPassword2()
    {
        return $this->password2;
    }

    /**
     * @param mixed $password2
     */
    public function setPassword2($password2): void
    {
        $this->password2 = $password2;
    }


    protected $captchaCode;


    /**
     * @return mixed
     */
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    /**
     * @param mixed $captchaCode
     */
    public function setCaptchaCode($captchaCode): void
    {
        $this->captchaCode = $captchaCode;
    }



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="First name cannot be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="First name must contain only letters."
     * )
     */
    private $firstName;

     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Last name cannot be blank.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Last name must contain only letters."
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     * 
     * @Assert\NotBlank(message="Date of birth cannot be blank.")
     */
     
    private $dateOfBirth;

    /**
     * @ORM\Column(name="balance", type="float", precision=10, scale=0, nullable=true)
     */
    private $balance;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_limited", type="boolean")
     */
    private $isLimited;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="limited_at", type="datetime", nullable=true)
     */
    private $limitedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="is_deleted", type="integer")
     */
    private $isDeleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="user")
     * @ORM\JoinTable(name="user_role",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     *   }
     * )
     */
    private $role;

   



        /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="participants")
     */
    private $events;

 /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage="Please upload a valid image (jpeg or png)."
     * )
     */
private $profilePicture;



// Define badge constants
const BADGE_NEWCOMER = 'Newcomer';
const BADGE_EXPERT = 'Expert';
const BADGE_MASTER = 'Master';


    // Constants for badge thresholds
    const SCORE_NEWCOMER = 100;
    const SCORE_EXPERT = 500;
    const SCORE_MASTER = 1000;


/**
 * @ORM\Column(type="string", length=255)
 */
private $badge;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    // If you want to add token expiration
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resetTokenExpiresAt;

    // Getter and Setter for resetToken
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();

        $this->events = new \Doctrine\Common\Collections\ArrayCollection();

        $this->badge = self::BADGE_NEWCOMER;


    }

    /**
 * Get the user's badge.
 */
public function getBadge(): ?string
{
    return $this->badge;
}

/**
 * Set the user's badge.
 *
 * @param string $badge
 * @return self
 */
public function setBadge(string $badge): self
{
    $this->badge = $badge;

    return $this;
}
  
    public function getBadgeIconClass(): string
    {
        switch ($this->badge) {
            case self::BADGE_NEWCOMER:
                return 'fas fa-star';
            case self::BADGE_EXPERT:
                return 'fas fa-trophy';
            case self::BADGE_MASTER:
                return 'fas fa-crown';
            default:
                return ''; 
        }
    }

  
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    // If you've added expiration
    public function getResetTokenExpiresAt(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeInterface $resetTokenExpiresAt): self
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;

        return $this;
    }
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    } 


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTime $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * Set the user's score and update the badge based on the new score.
     *
     * @param int $score
     * @return self
     */
    public function setScore(int $score): self
    {
        $oldBadge = $this->badge;
        $this->score = $score;
    
        // Update badge based on the new score
        if ($score >= self::SCORE_MASTER) {
            $this->badge = self::BADGE_MASTER;
        } elseif ($score >= self::SCORE_EXPERT) {
            $this->badge = self::BADGE_EXPERT;
        } elseif ($score >= self::SCORE_NEWCOMER) {
            $this->badge = self::BADGE_NEWCOMER;
        }
    
        // Check if badge has changed
        if ($oldBadge !== $this->badge) {
            // Use Symfony's session to store a badge upgrade message
            $session = new Session();
            $session->set('badgeUpgrade', [
                'oldBadge' => $oldBadge,
                'newBadge' => $this->badge,
                'message' => "Congratulations! You've earned a new badge: {$this->badge}"
            ]);
        }
    
        return $this;
    }
    
    public function incrementScore(int $points): self
    {
        // Increment the score
        $newScore = $this->score + $points;
    
        // Use setScore to update the score and the badge accordingly
        $this->setScore($newScore);
    
        return $this;
    }
    
    

    public function setIsLimited(bool $isLimited): void
    {
        $this->isLimited = $isLimited;
    }


    public function setIsDeleted(int $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getIsLimited(): ?bool
    {
        return $this->isLimited;
    }

    public function getLimitedAt(): ?\DateTimeInterface
    {
        return $this->limitedAt;
    }

    public function setLimitedAt(?\DateTimeInterface $limitedAt): self
    {
        $this->limitedAt = $limitedAt;

        return $this;
    }


    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

   

  

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }


  
    

       //------generate interface methods-------------------------
    
     /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $array=array();
        foreach($this->getRole() as $i => $role) {
            array_push($array, $role->getRoleName() );
        }
        return $array;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    //---------------------------------------------------------

    public function getProfilePicture(): ?string
{
    return $this->profilePicture;
}

public function setProfilePicture(?string $profilePicture): self
{
    $this->profilePicture = $profilePicture;
    return $this;
}


}
