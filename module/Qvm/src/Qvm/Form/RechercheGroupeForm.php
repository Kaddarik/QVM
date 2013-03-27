<?php
namespace Qvm\Form;

use Zend\Form\Fieldset;

use Zend\Form\Form;
use Zend\Form\Element;

class RechercheGroupeForm extends Fieldset
{
	public  function __construct($name = null){
		parent::__construct("rechercher un groupe");

		$this->setLabel('Recherche');

        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Nom :  '
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
        
       $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Valider',
                'class' => 'pull-right',
            ),
        ));
	}

}