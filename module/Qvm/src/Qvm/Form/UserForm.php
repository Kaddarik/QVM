<?php
namespace Qvm\Form;

use Zend\Form\Form;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;

class UserForm extends Form
{
    public function __construct()
    {
        parent::__construct("user");
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'surname',
            'attributes' => array(
                'type'  => 'text',
            	'required' => 'required'
            ),
            'options' => array(
                'label' => 'Nom : ',
            ),
        ));
        $this->add(array(
            'name' => 'firstname',
            'attributes' => array(
                'type'  => 'text',
            	'required' => 'required'
            ),
            'options' => array(
                'label' => 'Prenom : ',
            ),
        ));
        $this->add(array(
        		'name' => 'password',
        		'attributes' => array(
        			'type'  => 'Password',
        			'required' => 'required'
        		),
        		'options' => array(
        				'label' => 'Mot de passe : ',
        		),
        ));
        $this->add(array(
        		'name' => 'email',
        		'attributes' => array(
        			'type'  => 'Email',
        			'required' => 'required'
        		),
        		'options' => array(
        				'label' => 'Email : ',
        		),
        ));
        $this->add(array(
        		'name' => 'phonenumber',
        		'attributes' => array(
        				'type' => 'tel',
        				'pattern' => '^0[1-9][0-9]{8}$'
        				
        		),
        		'options' => array(
        				'label' => 'Numero de telephone : ',
        		),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Valider',
                'id' => 'submitbutton',
            	'class' => 'btn btn-success'
            ),
        ));
    }
}
