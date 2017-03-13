<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class ProjectTranslation
{
    use ORMBehaviors\Translatable\Translation;


    /**
     * @ORM\Column(nullable=true)
     */
    protected $descriptionTitle;


	/**
	 * @ORM\Column(nullable=true, type="text")
	 */
	protected $description;

    public function getDescriptionTitle()
    {
        return $this->descriptionTitle;
    }

    public function setDescriptionTitle($descriptionTitle)
    {
        $this->descriptionTitle = $descriptionTitle;

        return $this;
    }

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}
}
