<?php
// src/AppBundle/Entity/ProjectRepository.php
namespace AppBundle\Repository;

use AppBundle\AppBundle;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectTranslation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ProjectRepository extends EntityRepository
{

	/**
	 * @var EntityManager
	 */
	protected $_em;

	/**
	 * @var \Doctrine\ORM\Mapping\ClassMetadata
	 */
	protected $_class;

	/**
	 * @var AuthorizationChecker
	 */
	protected $_authorizationChecker;



	public function __construct($em, ClassMetadata $class)
	{
		parent::__construct($em, $class);
	}

	/**
	 * Sets the authorization checker to get roles of the logged-in user
	 *
	 * @param $authorizationChecker
	 */
	public function setAuthorizationChecker($authorizationChecker)
	{
		if($this->_authorizationChecker === null)
		{
			$this->_authorizationChecker = $authorizationChecker;
		}
	}


	/**
	 * Updates querybuilder passed as param to query objects depending on the user's rights
	 *
	 * @param QueryBuilder $qb
	 * @param $alias
	 * @return QueryBuilder
	 */
	private function addProjectCriteria(QueryBuilder $qb, $alias)
	{
		if($this->_authorizationChecker !== null)
		{
			if(!$this->_authorizationChecker->isGranted('ROLE_ADMIN'))
			{
				$qb->andWhere($alias.'.displayed = true');
			}
		}

		return $qb;
	}



	public function getListProjectsByCategory($category)
	{
		$subQuery = null;
		if($category !== null)
		{
			// Get projects which have a specific category
			$subQuery = $this->createQueryBuilder('p2')
			                 ->select('p2.id')
			                 ->leftJoin('p2.categories', 'c2')
			                 ->where("c2.slug = '".$category."' ");

			// It will return the good projects ids but with only the category selected

			// If you want to do it in 2 queries, here is one solution
			// Doctrine ArrayResult gives us an array of array.
			// To get just an array of ids, we use array_column (PHP5.5 and up) to generate an array of ids and array_keys to keep just the keys created
			// $projectsIds = array_keys(array_column($projectsByCategories, null, 'id'));
		}


		// Now we just query the projects and pass the sub-query to get the ids
		// We call the getDQL function to get the DQL string (Doctrine Query Language)
		// If no category, no need for subquery, we can just get all projects
		$qb = $this->createQueryBuilder('p')
		           ->select('p, c')
		           ->leftJoin('p.categories', 'c');

		if($category !== null) {
			$qb->where( $qb->expr()->in( 'p.id', $subQuery->getDQL() ) );
		}

		$qb = $this->addProjectCriteria($qb, 'p');

		$qb->orderBy('p.updatedAt', 'desc');

		$projects = $qb->getQuery()->getArrayResult();

		return $projects;
	}

	/**
	 * Get the current project with slug as param
	 *
	 * @param slug
	 *
	 * @return Project
	 */
	public function getCurrentProject($slug, $locale)
	{
//
		// Don't set maxresults when doing joins, it limits the total number of rows fetched (in this example, gets just 1 category)
		// For this example it's ok (without maxresults), usually slugs are unique and we send just the first project.
		// If need max results and joins, use paginator : http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/pagination.html

//		SELECT * FROM `project`
//left join `project_translation` as t on t.translatable_id = `project`.id
//where t.locale = 'ja'
//      and `project`.id = 1

		$query = $this->createQueryBuilder('p')
						  ->select('p, pt, cat, i')
						  ->leftJoin('p.translations', 'pt')
						  ->leftJoin('p.categories', 'cat')
						  ->leftJoin('p.images', 'i')
						  ->where("p.slug = '".$slug."'")
						  ->andWhere("pt.locale = '".$locale."'");

		$query = $this->addProjectCriteria($query, 'p');

		$project = $query->getQuery()->getArrayResult();
		// Getting an array instead of objects is way faster for fetching data.
		// Moreover getting all objects we need in one query is way more efficient than
		// just findBy Slug and then query other tables in the Twig template

		return (!empty($project)) ? $project[0] : null;
	}

}