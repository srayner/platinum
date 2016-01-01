<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * An calendar event.
 *
 * @ORM\Entity
 * @ORM\Table(name="events")
 */
class Event extends Entity
{
    protected $inputFilter;
    
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="event_id", type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var boolean
     * @ORM\Column(name="allday", type="boolean");
     */
    protected $allday;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime")
     */
    protected $start;
    
    /**
     * @var datetime
     * @ORM\Column(type="datetime")
     */
    protected $end;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;
    
    /**
     * @var string
     * @ORM\Column(name="class_name", type="string", length=255, nullable=true)
     */
    protected $className;
    
    /**
     * @var boolean
     * @ORM\Column(name="editable", type="boolean");
     */
    protected $editable;
    
    /**
     * @var integer
     * @ORM\Column(name="color", type="integer");
     */
    protected $color;
    
    /**
     * @var integer
     * * @ORM\Column(name="background_color", type="integer");
     */
    protected $backgroundColor;
    
    /**
     * @var integer
     * * @ORM\Column(name="border_color", type="integer");
     */
    protected $borderColor;
    
    /**
     * @var integer
     * * @ORM\Column(name="text_color", type="integer");
     */
    protected $textColor;
    
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->allday = ($data['allday'] == 'true');
        $this->setStart($data['start']);
        $this->setEnd($data['end']);
        $this->editable = true;
        $this->color = 0x0000ff;
        $this->backgroundColor = 0x0000ff;
        $this->borderColor = 0x0000ff;
        $this->textColor = 0x000000;
    }
    
    /**
     * 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param unknown_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param unknown_type $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     *
     */
    public function getAllday()
    {
        return $this->allday;
    }
    
    /**
     * @param unknown_type $allday
     */
    public function setAllday($allday)
    {
        $this->allday = $allday;
    }
    
    /**
     * 
     */
    public function getStart()
    {
        return $this->start;
    }
    
    /**
     * @param unknown_type $start
     */
    public function setStart($start)
    {
        //'D M d Y H:i:s '
        //$this->start = new \DateTime($start);
        
        $this->start = \DateTime::createFromFormat('d/m/Y', $start);
             
    }
    
    /**
     * 
     */
    public function getEnd()
    {
        return $this->end;
    }
    
    /**
     * @param unknown_type $end
     */
    public function setEnd($end)
    {
        $this->end = \DateTime::createFromFormat('d/m/Y', $end);
        //$this->end = new \DateTime($end);
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
    
            // Id input filter.
            $inputFilter->add($factory->createInput(array(
                    'name' => 'id',
                    'required' => true,
                    'filters' => array(
                            array('name' => 'Int'),
                    ),
            )));
    
            $inputFilter->add($factory->createInput(array(
                    'name' => 'title',
                    'required' => true,
                    'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                            array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                            'encoding' => 'UTF-8',
                                            'min' => 1,
                                            'max' => 32,
                                    ),
                            ),
                    ),
            )));
    
            $inputFilter->add($factory->createInput(array(
                    'name' => 'start',
                    'required' => true,
                    'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                            array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                            'encoding' => 'UTF-8',
                                            'min' => 1,
                                            'max' => 32,
                                    ),
                            ),
                    ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                    'name' => 'end',
                    'required' => true,
                    'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                            array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                            'encoding' => 'UTF-8',
                                            'min' => 1,
                                            'max' => 32,
                                    ),
                            ),
                    ),
            )));
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}