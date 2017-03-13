<?php
/**
 * Created by PhpStorm.
 * User: Nacho
 * Date: 17/11/2016
 * Time: 14:52
 */

namespace AppBundle\Naming;

use AppBundle\Entity\Project;
use Behat\Transliterator\Transliterator;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class FileNamer implements NamerInterface {

	/**
	 * {@inheritdoc}
	 */
	public function name($object, PropertyMapping $mapping)
	{
		switch (true) {
			case $object instanceof Project:
				// Based on a specific class
				$name = Transliterator::transliterate($object->getId().'-'.$object->getName());
				break;

			default:
				// Or based on an id
				$name = $object->getId();
		}

		$name .= '-'.uniqid().'-'.$mapping->getFile($object)->getClientOriginalName();

		return $name;
	}

}