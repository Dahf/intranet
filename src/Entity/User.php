<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userTo", orphanRemoval=true)
     */
    private $messagesTo;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userFrom", orphanRemoval=true)
     */
    private $messagesFrom;

    /**
     * @ORM\ManyToMany(targetEntity=Chat::class, mappedBy="users")
     */
    private $chats;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="projectMember")
     */
    private $projects;

    public function __construct()
    {
        $this->messagesTo = new ArrayCollection();
        $this->messagesFrom = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Custom Status
     */
    public function getStatus(): string
    {
        return (string) $this->status;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesTo(): Collection
    {
        return $this->messagesTo;
    }

    public function addMessagesTo(Message $messagesTo): self
    {
        if (!$this->messagesTo->contains($messagesTo)) {
            $this->messagesTo[] = $messagesTo;
            $messagesTo->setUserTo($this);
        }

        return $this;
    }

    public function removeMessagesTo(Message $messagesTo): self
    {
        if ($this->messagesTo->removeElement($messagesTo)) {
            // set the owning side to null (unless already changed)
            if ($messagesTo->getUserTo() === $this) {
                $messagesTo->setUserTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesFrom(): Collection
    {
        return $this->messagesFrom;
    }

    public function addMessagesFrom(Message $messagesFrom): self
    {
        if (!$this->messagesFrom->contains($messagesFrom)) {
            $this->messagesFrom[] = $messagesFrom;
            $messagesFrom->setUserFrom($this);
        }

        return $this;
    }

    public function removeMessagesFrom(Message $messagesFrom): self
    {
        if ($this->messagesFrom->removeElement($messagesFrom)) {
            // set the owning side to null (unless already changed)
            if ($messagesFrom->getUserFrom() === $this) {
                $messagesFrom->setUserFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->addUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            $chat->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addProjectMember($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeProjectMember($this);
        }

        return $this;
    }
}
