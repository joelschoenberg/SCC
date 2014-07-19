<?php
class Application_Model_SiteMapper {
	protected $_dbTable;
	public function setDbTable($dbTable) {
		if (is_string ( $dbTable )) {
			$dbTable = new $dbTable ();
		}
		if (! $dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception ( 'Invalid table data gateway provided' );
		}
		$this->_dbTable = $dbTable;
		return $this;
	}
	public function getDbTable() {
		if (null === $this->_dbTable) {
			$this->setDbTable ( 'Application_Model_DbTable_Site' );
		}
		return $this->_dbTable;
	}
	public function create(Application_Model_Site $site) {
		$data = array (
				'user' => $site->getUser (),
				'state' => $site->getState () 
		);
		
		$this->getDbTable ()->insert ( $data );
	}
	public function save(Application_Model_Site $site) {
		$data = array (
				'user' => $site->getUser (),
				'state' => $site->getState () 
		);
		
		$this->getDbTable ()->update ( $data, array (
				'user = ?' => $site->getUser () 
		) );
	}
	public function fetchAll($user) {
		$query = $this->getDbTable ()->select ()->where ( 'user = ?', $user );
		$resultSet = $this->getDbTable ()->fetchRow ( $query );
		return $resultSet;
	}
}

