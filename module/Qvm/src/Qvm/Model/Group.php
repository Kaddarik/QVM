<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Group implements InputFilterAwareInterface
{
	public $id_group;
	public $label;
	public $is_private;

	public function exchangeArray($data)
	{
		$this->id_group     = (isset($data['id_group'])) ? $data['id_group'] : null;
		$this->label = (isset($data['label'])) ? $data['label'] : null;
		$this->is_private  = (isset($data['is_private'])) ? $data['is_private'] : null;
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
                'name'     => 'id_group',
                'required' => false,
                )
            ));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'label',
                'required' => false,
                )
            ));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'is_private',
                'required' => false,
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
}