<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface
{
	public $id_category;
	public $label;
	public $displaying_order;
	public $icon_class;

    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_category     = (isset($data['id_category'])) ? $data['id_category'] : null;
		$this->label = (isset($data['label'])) ? $data['label'] : null;
		$this->displaying_order  = (isset($data['displaying_order'])) ? $data['displaying_order'] : null;
		$this->icon_class  = (isset($data['icon_class'])) ? $data['icon_class'] : null;
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
                'name'     => 'id_category',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'label',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'displaying_order',
                'required' => false,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'icon_class',
            		'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    
}