<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\ProjectRepository")
 * @UniqueEntity("name")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Your slug must be at least {{ limit }} characters long",
     *      maxMessage = "Your slug cannot be longer than {{ limit }} characters"
     * )
     */
    protected $slug;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_date", type="datetime")
     */
    protected $beginDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=null)
     */
    protected $endDate;

    /**
    *
    * @ORM\OneToMany(targetEntity="ProjectBundle\Entity\Team_member", mappedBy="project", cascade={"remove"})
    */
    protected $team_members;

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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set beginDate
     *
     * @param \DateTime $beginDate
     * @return Project
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    /**
     * Get beginDate
     *
     * @return \DateTime 
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endDate
     *
     * @param string $endDate
     * @return Project
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return string 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->team_member = new \Doctrine\Common\Collections\ArrayCollection();
    }

   

    /**
     * Add team_members
     *
     * @param \ProjectBundle\Entity\Team_member $teamMembers
     * @return Project
     */
    public function addTeamMember(\ProjectBundle\Entity\Team_member $teamMembers)
    {
        $this->team_members[] = $teamMembers;

        return $this;
    }

    /**
     * Remove team_members
     *
     * @param \ProjectBundle\Entity\Team_member $teamMembers
     */
    public function removeTeamMember(\ProjectBundle\Entity\Team_member $teamMembers)
    {
        $this->team_members->removeElement($teamMembers);
    }

    /**
     * Get team_members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeamMembers()
    {
        return $this->team_members;
    }
}
