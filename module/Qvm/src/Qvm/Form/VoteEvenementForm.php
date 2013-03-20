<?php
namespace Qvm\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class VoteEvenementForm extends Form
{
	public  function __construct($name = null){
		parent::__construct("vote evenement");
		
		$this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'vote',
            'options' => array(
                'value_options' => array(
                    1 => 'Oui',
                    2 => 'Non',
                    3 => 'Peut-etre',
                	4 => 'En attente'
                ),
            ),
            'attributes' => array(
                'value' => 1 //set selected to '1'
            )
        ));
	}
}