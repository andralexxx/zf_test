<?php

class Application_Form_Guestbook extends Zend_Form
{

    
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
    	$this->setAttrib('enctype', 'multipart/form-data');

        // Add an first name element
        $this->addElement('text', 'firstname', array(
            'label'      => 'First name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
                )
        ));

        // Add an last name element
        $this->addElement('text', 'lastname', array(
            'label'      => 'Last name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
                )
        ));

        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add an phone element
        $this->addElement('text', 'phone', array(
            'label'      => 'Phone:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
                )
        ));

        $birthdate = new ZendX_JQuery_Form_Element_DatePicker('birthday');
        
        $birthdate->setLabel('Birthdate:')
            ->setJQueryParam('dateFormat', 'dd.mm.yy')
            ->setJQueryParam('changeYear', 'true')
            ->setJqueryParam('changeMonth', 'true')
            ->setJqueryParam('regional', 'ua')
            ->setJqueryParam('yearRange', "1980:2000")
            ->setDescription('dd.mm.yyyy')
            ->addValidator(new Zend_Validate_Date(
                array(
                    'format' => 'dd.mm.yyyy',
                )))
            ->setRequired(true);
        
        $this->addElement($birthdate);

        // Add a photo element
        $this->addElement('file', 'photo', array(
            'label'      => 'Photo:',
            'destination' => PUBLIC_PATH . '\files',
            'validators' => array(
                array('validator' => 'Extension', 'options' => array('jpg','gif','png')),
                array('validator' => 'Count', 'options' => array(1)),
                array('validator' => 'Size', 'options' => array(102400)),
            ),
            'required'   => false,
        ));

        $this->addElement('checkbox', 'deletephoto', array(
            'label'      => 'Delete photo:',
            'required'   => false,
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => false,
            'label'    => 'Add',
        ));

        // Add the cancel button
        $this->addElement('submit', 'cancel', array(
            'ignore'   => false,
            'label'    => 'Cancel',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
    }

}