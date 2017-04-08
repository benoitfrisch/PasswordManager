<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="folders")
 */
class Folder
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="folder")
     */
    private $items;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hidden;

    /**
     * One Folder has Many Subfolders.
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent")
     */
    private $children;

    /**
     * Many Subfolders have One MainFolder.
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * Many Folders have Many Groups.
     * @Assert\Count(min=1)
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="folders")
     * @ORM\JoinTable(name="folders_groups")
     */
    private $groups;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Folder
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function isAccessible(AdvancedUserInterface $user)
    {
        return !empty(array_intersect($user->getGroups()->getValues(), $this->getGroups()->getValues()));
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     * @return Folder
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Folder
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Folder
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemsCount()
    {
        return count($this->items);
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param mixed $hidden
     * @return Folder
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     * @return Folder
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return Folder
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
}