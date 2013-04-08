<?php
namespace Qvm\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class VoteEvenementForm extends Form
{
	public  function __construct($name = null){
		parent::__construct("vote evenement");
		
		$this->add(array(
				'name' => 'id_event',
				'attributes' => array(
						'type' => 'hidden',
				),
		));
		
		$select = new Element\Select('voteEvenementSelect');
        $select->setName('voteEvenement');
        $this->add($select);
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Valider',
                'id' => 'submitbutton',
            	'class' => 'btn btn-success btn-small btn-vert-cent'
            ),
        ));
	}

}