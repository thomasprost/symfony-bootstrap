<?php
// src/AppBundle/Entity/Category.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category.
 *
 * @author Zoltan
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
{
    /**
     * The identifier of the category.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * The category name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;


    /**
     * Projects in the category.
     *
     * @var Project[]
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="categories")
     **/
    protected $projects;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set all projects in the category.
     *
     * @param Project[] $projects
     */
    public function setProjects($projects)
    {
        $this->projects->clear();
        $this->projects = new ArrayCollection($projects);
    }

    /**
     * Add project in the category
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Category
     */
    public function addProject($project)
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
        }
    }

    /**
     * Remove project
     *
     * @param \AppBundle\Entity\Project $project
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
