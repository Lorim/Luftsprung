<?php
class Application_Model_Gallery
{
    protected $_id;
    protected $_created;
    protected $_title;
    protected $_active;
    protected $_tag;
    protected $_post;
    protected $_preview;
 
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
            throw new Exception('Ungültige Gallery Eigenschaft');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Ungültige Gallery Eigenschaft');
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
 
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        if($this->_id == 0) {
            return null;
        }
        return $this->_id;
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
    
    public function setTitle($text)
    {
        $this->_title = (string) $text;
        return $this;
    }
 
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setActive($active)
    {
        $this->_active = (bool) $active;
        return $this;
    }
    
    public function getActive()
    {
        return $this->_active;
    }
    
    public function setTag($tag)
    {
        $this->_tag = $tag;
        return $this;
    }
    
    public function getTag()
    {
        return $this->_tag;
    }
    
    public function getPost()
    {
    	return $this->_post;
    }
    
    public function setPost($post)
    {
        $this->_post = $post;
        return $this;
    }
    
    public function getPreview()
    {
    	return $this->_preview;
    }
    
    public function setPreview($preview)
    {
        $this->_preview = $preview;
        return $this;
    }
}
