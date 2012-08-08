<?php

namespace Entity;

use Core\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="abilities")
 *
 */
class Ability extends BaseEntity
{
	
	/**
	 * @param Proofreader $proofreader
	 * @param Field $field
	 */
	public function __construct(Proofreader $proofreader, Field $field)
	{
		parent::__construct();
		
		$this->proofreader = $proofreader;
		$this->field = $field;
	}
	
	/**
	 * @var Entity\Proofreader
	 * @ORM\ManyToOne(targetEntity="Proofreader")
	 * @ORM\JoinColumn(name="proofreader_id", referencedColumnName="id", nullable=false)
	 */
	private $proofreader;
	
	/**
	 * @var Entity\Field
	 * @ORM\ManyToOne(targetEntity="Field")
	 * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
	 */
	private $field;
	
	
	/**
	 * @return Proofreader
	 */
	public function getProofreader()
	{
		return $this->proofreader;
	}
	
	
	/**
	 * @return Field  
	 */
	public function getField()
	{
		return $this->field;
	}
}