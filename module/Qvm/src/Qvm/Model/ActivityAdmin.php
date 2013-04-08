<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ActivityAdmin implements InputFilterAwareInterface
{
	public $id_activity;
	public $id_person;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_activity     = (isset($data['id_activity'])) ? $data['id_activity'] : null;
		$this->id_person = (isset($data['user_id'])) ? $data['user_id'] : null;
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
                'name'     => 'user_id',
                'required' => false,
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}