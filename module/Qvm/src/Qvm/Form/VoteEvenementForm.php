<?php
namespace Qvm\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class VoteEvenementForm extends Form
{
	public  function __construct($name = null){
		parent::__construct("vote evenement");
		
		$select = new Element\Select('voteEvenementSelect');
        $select->setName('voteEvenement');
        $this->add($select);
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Valider',
                'id' => 'submitbutton',
            ),
        ));
	}

}