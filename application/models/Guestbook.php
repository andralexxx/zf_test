<?php

class Application_Model_Guestbook
{
    protected $_email;
    protected $_firstname;
    protected $_lastname;
    protected $_phone;
    protected $_birthday;
    protected $_photo;
    protected $_created;
    protected $_id;
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->_email;
    }
 
    public function setFirstname($firstname)
    {
        $this->_firstname = (string) $firstname;
        return $this;
    }
 
    public function getFirstname()
    {
        return $this->_firstname;
    }
 
    public function setLastname($lastname)
    {
        $this->_lastname = (string) $lastname;
        return $this;
    }
 
    public function getLastname()
    {
        return $this->_lastname;
    }
 
    public function setPhone($phone)
    {
        $this->_phone = (string) $phone;
        return $this;
    }
 
    public function getPhone()
    {
        return $this->_phone;
    }
 
    public function setBirthday($birthday)
    {
        $this->_birthday = (string) $birthday;
        return $this;
    }
 
    public function getBirthday()
    {
        return $this->_birthday;
    }
 
    public function setPhoto($photo)
    {
        $this->_photo = (string) $photo;
        return $this;
    }
 
    public function getPhoto()
    {
        return $this->_photo;
    }
 
    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }
 
    public function getCreated()
    {
        return $this->_created;
    }
 
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}

