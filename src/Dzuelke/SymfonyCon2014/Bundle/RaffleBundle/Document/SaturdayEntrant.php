<?php

namespace Dzuelke\SymfonyCon2014\Bundle\RaffleBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class SaturdayEntrant
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
     * @MongoDB\String
     */
    protected $herokuAppUrl;

    /**
     * @MongoDB\String
     */
    protected $githubPullRequestUrl;

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
     * Set herokuAppUrl
     *
     * @param string $herokuAppUrl
     * @return self
     */
    public function setHerokuAppUrl($herokuAppUrl)
    {
        $this->herokuAppUrl = $herokuAppUrl;
        return $this;
    }

    /**
     * Get herokuAppUrl
     *
     * @return string $herokuAppUrl
     */
    public function getHerokuAppUrl()
    {
        return $this->herokuAppUrl;
    }

    /**
     * Set githubPullRequestUrl
     *
     * @param string $githubPullRequestUrl
     * @return self
     */
    public function setGithubPullRequestUrl($githubPullRequestUrl)
    {
        $this->githubPullRequestUrl = $githubPullRequestUrl;
        return $this;
    }

    /**
     * Get githubPullRequestUrl
     *
     * @return string $githubPullRequestUrl
     */
    public function getGithubPullRequestUrl()
    {
        return $this->githubPullRequestUrl;
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
            
            if($answer['component_id'] == 934898) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1995180) {
                        $this->setName($field['text']);
                    }
                }
            }
            if($answer['component_id'] == 933640) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1992206) {
                        $this->setEmail($field['text']);
                    }
                }
            }
            if($answer['component_id'] == 933633) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1992190) {
                        $this->setHerokuAppUrl($field['text']);
                    }
                }
            }
            if($answer['component_id'] == 933638) {
                foreach($answer['form_fields'] as $field) {
                    if($field['choice']['id'] == 1992205) {
                        $this->setGithubPullRequestUrl($field['text']);
                    }
                }
            }
            
        }
        
        $this->setWon(false);
        
        if(!$this->getEmail()) {
            throw new \Exception('No email given!');
        }
        
        if(!$this->getHerokuAppUrl() || !$this->getGithubPullRequestUrl()) {
            throw new \Exception('Needs both Heroku app URL and Github pull request URL!');
        }
    }
}