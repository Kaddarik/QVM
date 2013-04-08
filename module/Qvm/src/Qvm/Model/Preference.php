<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Preference implements InputFilterAwareInterface
{
	public $id_preference;
	public $user_id;
	public $id_activity;
	public $vote;
	public $is_create_notif;
	public $is_delete_notif;
	public $is_comment_notif;
    protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id_preference     = (isset($data['id_preference'])) ? $data['id_preference'] : null;
		$this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->id_activity  = (isset($data['id_activity'])) ? $data['id_activity'] : null;
		$this->vote  = (isset($data['vote'])) ? $data['vote'] : null;
		$this->is_create_notif  = (isset($data['is_create_notif'])) ? $data['is_create_notif'] : null;
		$this->is_delete_notif  = (isset($data['is_delete_notif'])) ? $data['is_delete_notif'] : null;
		$this->is_comment_notif  = (isset($data['is_comment_notif'])) ? $data['is_comment_notif'] : null;
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
                'name'     => 'id_preference',
                'required' => true,
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'user_id',
            		'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id_activity',
                'required' => true,

            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'vote',
            		'required' => true,
            
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'is_create_notif',
                'required' => true,            
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'is_delete_notif',
                'required' => true,                
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'is_comment_notif',
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