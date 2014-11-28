<?php

namespace Dzuelke\SymfonyCon2014\Bundle\RaffleBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class FridayEntrant
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\String
     */
    protected $email;

    /**
     * @MongoDB\Boolean
     */
    protected $won;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set won
     *
     * @param boolean $won
     * @return self
     */
    public function setWon($won)
    {
        $this->won = $won;
        return $this;
    }

    /**
     * Get won
     *
     * @return boolean $won
     */
    public function getWon()
    {
        return $this->won;
    }
    
    public function hydrate($record)
    {
        foreach($record['answers'] as $answer) {
            
            if($answer['component_id'] == 844274) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1798038) {
                        $this->setEmail($field['text']);
                    }
                }
            }
            if($answer['component_id'] == 844275) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1798039) {
                        $this->setName($field['text']);
                    }
                }
            }
        }
        
        $this->setWon(false);
        
        if(!$this->getName() && !$this->getEmail()) {
            throw new \Exception('No name or email given!');
        }
    }
}