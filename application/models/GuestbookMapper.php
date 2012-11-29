<?php
 
class Application_Model_GuestbookMapper extends Application_Model_DbTable_Guestbook
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
		if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Guestbook');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Guestbook $guestbook)
    {
		$data = array(
            'email'   => $guestbook->getEmail(),
            'firstname' => $guestbook->getFirstName(),
            'lastname' => $guestbook->getLastName(),
            'phone' => $guestbook->getPhone(),
            'birthday' => $guestbook->getBirthday(),
            'photo' => $guestbook->getPhoto(),
            'created' => date('Y-m-d H:i:s'),
        );
        if (null === ($id = $guestbook->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Guestbook $guestbook)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
			return;
        }
        $row = $result->current();
        $guestbook->setId($row->id)
                  ->setEmail($row->email)
                  ->setFirstName($row->firstname)
                  ->setLastName($row->lastname)
                  ->setPhone($row->phone)
                  ->setBirthday($row->birthday)
                  ->setPhoto($row->photo)
                  ->setCreated($row->created);
		return $guestbook;
    }
 
    public function deleteGuest($id, Application_Model_Guestbook $guestbook)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $this->delete('id =' . (int)$id);
    }
 
    public function fetchAll()
    {
		$resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Guestbook();
			$entry->setId($row->id)
				  ->setEmail($row->email)
				  ->setFirstName($row->firstname)
				  ->setLastName($row->lastname)
				  ->setPhone($row->phone)
				  ->setBirthday($row->birthday)
				  ->setPhoto($row->photo)
				  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }
}