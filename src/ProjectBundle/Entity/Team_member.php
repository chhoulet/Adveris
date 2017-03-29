<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Team_member
 *
 * @ORM\Table(name="team_member")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\Team_memberRepository")
 */
class Team_member
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
     * @var integer
     *
     * @ORM\Column(name="role", type="integer", nullable=true)    
     */
    protected $role;

    /**
    *
    * @ORM\ManyToOne(targetEntity="ProjectBundle\Entity\Project", inversedBy="team_members")
    * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
    */
    protected $project;

     /**
    *
    * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", mappedBy="team_members")
    * @ORM\JoinTable(name="teamMembers_users")
    *
    */
    protected $users;


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
     * Set role
     *
     * @param string $role
     * @return Team_member
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
   

    /**
     * Set project
     *
     * @param \ProjectBundle\Entity\Project $project
     * @return Team_member
     */
    public function setProject(\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return Team_member
     */
    public function addUser(\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser(\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
