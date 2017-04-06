<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * @ORM\Entity()
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Many Groups have Many Items.
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="groups")
     */
    protected $items;

    /**
     * Many Groups have Many Items.
     *
     * @ORM\ManyToMany(targetEntity="Folder", mappedBy="groups")
     */
    protected $folders;


    /**
     * Many Groups have Many Items.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    protected $users;

    public function __toString()
    {
        if ($this->getName()) {
            return $this->getName();
        } else {
            return "";
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Group
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     * @return Group
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     * @return Group
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param mixed $folders
     * @return Group
     */
    public function setFolders($folders)
    {
        $this->folders = $folders;
        return $this;
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

}