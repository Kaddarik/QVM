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
	} public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

//  A MODIFIER
	
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_event',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'infos_event',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
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