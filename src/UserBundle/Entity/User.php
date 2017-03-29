<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
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
    *
    * @ORM\ManyToMany(targetEntity="ProjectBundle\Entity\Team_member", inversedBy="users")
    *
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

   

    public function __construct()
   {
       parent::__construct();       
   }

    /*public function getFullName()   
    {
        return $this->getFirstName().' '. $this->getName();
    }*/

    /**
     * Add team_members
     *
     * @param \ProjectBundle\Entity\Team_member $teamMembers
     * @return User
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
