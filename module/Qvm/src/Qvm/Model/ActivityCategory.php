<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ActivityCategory implements InputFilterAwareInterface
{
	public $id_activity;
	public $id_category;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_activity     = (isset($data['id_activity'])) ? $data['id_activity'] : null;
		$this->id_category = (isset($data['id_category'])) ? $data['id_category'] : null;
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
                'name'     => 'id_category',
                'required' => false,
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}