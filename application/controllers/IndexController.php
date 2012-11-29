<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$this->_helper->AjaxContext()->addActionContext('ajaxedit', 'json')->initContext('json');
    }

    public function indexAction()
    {
		$guestbook = new Application_Model_GuestbookMapper();
		$this->view->entries = $guestbook->fetchAll();
    }

    public function addAction()
    {
        $this->view->title = "Add new guest";
        $this->view->headTitle($this->view->title);

        $request = $this->getRequest();
        $form    = new Application_Form_Guestbook();
		$form->removeElement('deletephoto');
 
        if ($this->getRequest()->isPost()) {
            $form->populate($request->getPost());
            
            if ($form->cancel->isChecked()) {
                return $this->_helper->redirector('index');
            }
            if ($form->submit->isChecked()) {
                if ($form->isValid($request->getPost())) {
                    $guestbook = new Application_Model_Guestbook($request->getPost());
                    
					$uploaded_file = new Zend_File_Transfer_Adapter_Http();
					$uploaded_file->setDestination(PUBLIC_PATH . '\files');
						try {
							// upload the file
							$uploaded_file->receive();
						} catch (Zend_File_Transfer_Exception $e) {
							$e->getMessage();
						}
					$file_name = urlencode ($uploaded_file->getFileName('photo', false) );
					$file_path = $uploaded_file->getFileName('photo');

                    $guestbook->photo = 'uploads/files/' . $file_name;
                    
                    $mapper = new Application_Model_GuestbookMapper();
                    $mapper->save($guestbook);
 
                    return $this->_helper->redirector('index');
                }
            }
        }
 
        $this->view->form = $form;
    }

    public function editAction()
    {
		if ($this->getRequest()->isXmlHttpRequest()) {
			$data = array(
				'id' => $this->_getParam('id'),
				'firstname' => $this->_getParam('firstname'),
				'lastname' => $this->_getParam('lastname'),
			);

			if ($data['id'] && ($data['firstname'] || $data['lastname']))
			{
				$guestbook = new Application_Model_Guestbook($data);
				$mapper = new Application_Model_GuestbookMapper();
				$mapper->save($guestbook);
				$errors = array();
				echo Zend_Json::encode(array('result' => $mapper, 'errors' => $errors));
			}
		} else {
			$this->view->title = "Edit the guest";
			$this->view->headTitle($this->view->title);
	
			$request = $this->getRequest();
			$form    = new Application_Form_Guestbook();
			$form->getElement('submit')->setLabel('Edit');

			if ($this->getRequest()->isPost()) {
				$form->populate($request->getPost());

				if ($form->cancel->isChecked()) {
					return $this->_helper->redirector('index');
				}
				if ($form->submit->isChecked()) {
					if ($form->isValid($request->getPost())) {

						$guestbook = new Application_Model_Guestbook($request->getPost());

						if ($request->getPost('deletephoto')) {
							$guestbook->photo = '';
						} else {
							$uploaded_file = new Zend_File_Transfer_Adapter_Http();
							$uploaded_file->setDestination(PUBLIC_PATH . '\files');
								try {
									// upload the file
									$uploaded_file->receive();
								} catch (Zend_File_Transfer_Exception $e) {
									$e->getMessage();
								}
							$file_name = urlencode ($uploaded_file->getFileName('photo', false) );
							$file_path = $uploaded_file->getFileName('photo');
							$guestbook->photo = 'uploads/files/' . $file_name;
						}
						$guestbook->id = $request->getParam('id');
						
						$mapper = new Application_Model_GuestbookMapper();
						$mapper->save($guestbook);
						
						return $this->_helper->redirector('index');
					}
				}
			}
			//else {
				$id = $this->_getParam('id', 0);
				if ($id > 0) {
					$mapper = new Application_Model_GuestbookMapper();
					$guestbook = $mapper->find($id, new Application_Model_Guestbook());
					$data = array(
						'firstname' => $guestbook->getFirstname(),
						'lastname' => $guestbook->getLastname(),
						'email' => $guestbook->getEmail(),
						'phone' => $guestbook->getPhone(),
						'birthday' => $guestbook->getBirthday(),
						'photo' => $guestbook->getPhoto(),
					);
					if(empty($data['photo']) && 'uploads/files/' != $data['photo']) {
						$form->removeElement('deletephoto');
					}
					$form->populate($data);
				}
			//}
			$this->view->form = $form;
			//pa(6,1);
	    }
    }

    public function deleteAction()
    {
        $this->view->title = "Delete guest";
        $this->view->headTitle($this->view->title);
    
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $guestbook = new Application_Model_Guestbook();
                $id = $this->getRequest()->getPost('id');
                $mapper = new Application_Model_GuestbookMapper();
                $mapper->deleteGuest($id, $guestbook);
            }
            $this->_helper->redirector('index');
        } else {
            $guestbook = new Application_Model_Guestbook();
            $id = $this->_getParam('id', 0);
            $mapper = new Application_Model_GuestbookMapper();
            $this->view->guestbook = $mapper->find($id, $guestbook);
        
		}
    }

    public function ajaxeditAction()
    {
		$data = array(
			'id' => $this->_getParam('id'),
			'firstname' => $this->_getParam('firstname'),
			'lastname' => $this->_getParam('lastname'),
		);
		if (($data['firstname'] || $data['lastname']))
		{
			$errors = array();
			$mapper = new Application_Model_GuestbookMapper();
			$guestbook = $mapper->find($data['id'], new Application_Model_Guestbook);

			$guestbook->setFirstname($data['firstname']);
			$guestbook->setLastname($data['lastname']);
			
			$mapper->save($guestbook);
			$guestbook = $mapper->find($data['id'], $guestbook);
			
			if ($data['firstname'] != $guestbook->firstname)
			{
				$errors[] = 'unable to write firstname';
			}
			if ($data['lastname'] != $guestbook->lastname)
			{
				$errors[] = 'unable to write lastname';
			}

			$this->view->errors = $errors;
			$this->view->result = empty($errors) ? 'success' : '';
		}
	}

}