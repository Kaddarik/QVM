<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Comment implements InputFilterAwareInterface
{
	public $id_comment;
	public $body;
	public $datetime;
	public $severity;
	public $id_event;
	public $user_id;
	

    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_comment     = (isset($data['id_comment'])) ? $data['id_comment'] : null;
		$this->body = (isset($data['body'])) ? $data['body'] : null;
		$this->datetime  = (isset($data['datetime'])) ? $data['datetime'] : null;
		$this->severity  = (isset($data['severity'])) ? $data['severity'] : null;
		$this->id_event  = (isset($data['id_event'])) ? $data['id_event'] : null;
		$this->user_id  = (isset($data['user_id'])) ? $data['user_id'] : null;
	} 
	
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_comment',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'body',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'datetime',
                'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'severity',
            		'required' => false,
            )));
            

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'id_event',
            		'required' => false,
            )));
            

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'user_id',
            		'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}