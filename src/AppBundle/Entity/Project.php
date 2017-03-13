<?php
// src/AppBundle/Entity/Project.php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * Class Project.
 *
 * @author Zoltan
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @Vich\Uploadable
 */
class Project
{
	use ORMBehaviors\Translatable\Translatable,
		ORMBehaviors\Timestampable\Timestampable,
		ORMBehaviors\Sluggable\Sluggable;

    /**
     * The identifier of the project.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The name of the project.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;


    /**
     * It only stores the name of the main image associated with the project.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $mainImage;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the project.
     *
     * @Vich\UploadableField(mapping="project_images", fileNameProperty="mainImage")
     *
     * @var File
     */
    private $mainImageFile;



	/**
	 * @var Image[]|ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="Image", cascade={"persist"})
	 * @ORM\JoinTable(name="gallery_images",
	 *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $images;


    /**
     * Indicate if the project is displayed.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $displayed = false;


	/**
	 * Text to generate slug.
	 * As I often work in Japanese, generating url from titles in Jp is ugly
     * I prefer to have a field for typing what i want to be generated in the slug
	 * @ORM\Column(type="string", length=255, nullable=true)
	 *
	 * @var string
	 */
	private $slugText;


    /**
     * List of categories where the project is
     * (Owning side).
     *
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="projects")
     * @ORM\JoinTable(name="project_category")
     */
    private $categories;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
	    $this->images = new ArrayCollection();
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
     * @param File $mainimage
     */

    public function setMainImageFile(File $mainImage = null)
    {
        $this->mainImageFile = $mainImage;

		// VERY IMPORTANT:
	    // It is required that at least one field changes if you are using Doctrine,
	    // otherwise the event listeners won't be called and the file is lost
	    if ($mainImage) {
		    // if 'updatedAt' is not defined in your entity, use another property
		    $this->updatedAt = new \DateTime('now');
	    }
    }

    /**
     * @return File
     */
    public function getMainImageFile()
    {
        return $this->mainImageFile;
    }

    /**
     * Set mainImage
     *
     * @param string $mainImage
     *
     * @return Project
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage
     *
     * @return string
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

	/**
	 * Get images
	 *
	 * @return Image[]|ArrayCollection
	 */
	public function getImages()
	{
		return $this->images;
	}

    /**
     * Set displayed
     *
     * @param boolean $displayed
     *
     * @return Project
     */
    public function setDisplayed($displayed)
    {
        $this->displayed = $displayed;

        return $this;
    }

    /**
     * Get displayed
     *
     * @return boolean
     */
    public function getDisplayed()
    {
        return $this->displayed;
    }


	/**
	 * Set slugText
	 *
	 * @param string $slugText
	 *
	 * @return Project
	 */
	public function setSlugText($slugText)
	{
		$this->slugText = $slugText;

		return $this;
	}

	/**
	 * Get slugText
	 *
	 * @return string
	 */
	public function getSlugText()
	{
		return $this->slugText;
	}


    /**
     * Get all associated categories.
     *
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set all categories of the project.
     *
     * @param Category[] $categories
     */
    public function setCategories($categories)
    {
        // This is the owning side, we have to call remove and add to have change in the category side too.
        foreach ($this->getCategories() as $category) {
            $this->removeCategory($category);
        }
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
    }

    /**
     * Function used by Sluggable behaviour to generate Slug
     * @return array
     */
	public function getSluggableFields()
	{
		return ['id','name', 'slugText'];
	}



    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }
}
