<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Activity implements InputFilterAwareInterface
{
	public $id_activity;
	public $title;
	public $description;
	public $id_location;
	public $periodicrule;
	public $exceptionrule;
	public $displaying_order;
	
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_activity     = (isset($data['id_activity'])) ? $data['id_activity'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->description  = (isset($data['description'])) ? $data['description'] : null;
		$this->id_location  = (isset($data['id_location'])) ? $data['id_location'] : null;
		$this->periodicrule  = (isset($data['periodicrule'])) ? $data['periodicrule'] : null;
		$this->exceptionrule  = (isset($data['exceptionrule'])) ? $data['exceptionrule'] : null;
		$this->displaying_order  = (isset($data['displaying_order'])) ? $data['displaying_order'] : null;
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
                'name'     => 'id_activity',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'description',
                'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'id_location',
            		'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'periodicrule',
            		'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'exceptionrule',
            		'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'displaying_order',
            		'required' => false,
            )));
            

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}