<?php
namespace Qvm\Form;
use Zend\Form\Form;

class CommentForm extends Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setName('comment');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'body',
            'attributes' => array(
                'type' => 'textarea',
             	),
        ));
        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Ajouter un commentaire',
        				'id' => 'submitbutton',
        				'class' => 'btn btn-success '
        		),
        ));
    }
    
}
