<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Participation implements InputFilterAwareInterface
{
	public $id_event;
	public $user_id;
	public $vote;
	public $start_datetime;
	public $guest;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_event     = (isset($data['id_event'])) ? $data['id_event'] : null;
		$this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->vote  = (isset($data['vote'])) ? $data['vote'] : null;
		$this->start_datetime  = (isset($data['start_datetime'])) ? $data['start_datetime'] : null;
		$this->guest  = (isset($data['guest'])) ? $data['guest'] : null;
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
                'name'     => 'id_event',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => false,
            )));
	
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'vote',
            		'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'start_datetime',
                'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'guest',
            		'required' => false,
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}