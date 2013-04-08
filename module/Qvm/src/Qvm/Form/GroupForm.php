<?php
namespace Qvm\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Mvc\Controller\AbstractActionController;

class GroupForm extends Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setName('group');
        $this->setAttribute('method', 'post');
        $this->add(array(
        		'name' => 'id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

        $this->add(array(
            'name' => 'label',
            'attributes' => array(
                'type' => 'text',
            	'required' => 'required'
            	),
                	'options' => array(
        				'label' => 'Nom : ',
        		),
        ));
        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Ajouter',
        				'id' => 'submitbutton',
        				'class' => 'btn btn-success'
        		),
        ));
    }
    
}
