<?php
namespace Qvm\Form;
use Zend\Form\Form;
use Zend\Form\Element;
use Qvm\Model\CategoryTable;
use Qvm\Model\Category;
use Zend\Mvc\Controller\AbstractActionController;

class ActivityForm extends Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setName('activity');
        $this->setAttribute('method', 'post');
        $this->add(array(
        		'name' => 'id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

        $select = new Element\Select('categorieselect');
        $select->setName('categorie');
        $select->setLabel('Categorie :');
        $this->add($select);

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
            	),
                	'options' => array(
        				'label' => 'Nom :',
        		),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'textarea',
             	),
                	'options' => array(
        				'label' => 'Description :',
        		),
        ));
        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Ajouter',
        				'id' => 'submitbutton',
        		),
        ));
    }
    
}
