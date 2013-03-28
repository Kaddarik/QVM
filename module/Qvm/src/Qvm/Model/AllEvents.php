<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class AllEvents implements InputFilterAwareInterface
{
	public $id_event;
	public $id_activity;
	public $title;
	public $location_name;
	public $location_url;
	public $id_person;
	public $firstname;
	public $surname;
	public $date;
	public $vote;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_event     = (isset($data['id_event'])) ? $data['id_event'] : null;
		$this->id_activity = (isset($data['id_activity'])) ? $data['id_activity'] : null;
		$this->title  = (isset($data['title'])) ? $data['title'] : null;
		$this->location_name  = (isset($data['location_name'])) ? $data['location_name'] : null;
		$this->location_url  = (isset($data['location_url'])) ? $data['location_url'] : null;
		$this->id_person  = (isset($data['id_person'])) ? $data['id_person'] : null;
		$this->firstname  = (isset($data['firstname'])) ? $data['firstname'] : null;
		$this->surname  = (isset($data['surname'])) ? $data['surname'] : null;
		$this->date  = (isset($data['date'])) ? $data['date'] : null;
		$this->vote  = (isset($data['vote'])) ? $data['vote'] : null;
	} public function setInputFilter(InputFilterInterface $inputFilter)
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
                'required' => true,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'id_activity',
            		'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,

            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'location_name',
                'required' => true,            
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'location_url',
                'required' => true,                
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_person',
                'required' => true,                
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'firstname',
                'required' => true,                
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'surname',
                'required' => true,               
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'date',
                'required' => true,               
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'vote',
                'required' => true,               
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}