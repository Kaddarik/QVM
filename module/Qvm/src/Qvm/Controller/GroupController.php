<?php 
namespace Qvm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Qvm\Model\Group;

class GroupController extends AbstractActionController
{
	protected $groupTable;

    public function indexAction()
    {
    	return array('groups' => $this->getGroupTable()->fetchAll());
    }
    
	public function getGroupTable()
	{
		if (!$this->groupTable) {
			$sm = $this->getServiceLocator();
			$this->groupTable = $sm->get('Qvm\Model\GroupTable');
		}
		return $this->groupTable;
	}
	
}