<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class VoteKind implements InputFilterAwareInterface
{
	public $id_votekind;
	public $label;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_votekind     = (isset($data['id_votekind'])) ? $data['id_votekind'] : null;
		$this->label = (isset($data['label'])) ? $data['label'] : null;
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
                'name'     => 'id_votekind',
                'required' => true,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'label',
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