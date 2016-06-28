<?php

namespace ETECH\SSOTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ETECH\SSOTestBundle\Entity\SSO_User;

/**
 * token
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ETECH\SSOTestBundle\Entity\tokenRepository")
 */
class token
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token_session", type="string", length=255)
     */
    private $tokenSession;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validity", type="datetimetz")
     */
    private $validity;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tokenSession
     *
     * @param string $tokenSession
     *
     * @return token
     */
    public function setTokenSession($tokenSession)
    {
        $this->tokenSession = $tokenSession;

        return $this;
    }

    /**
     * Get tokenSession
     *
     * @return string
     */
    public function getTokenSession()
    {
        return $this->tokenSession;
    }

    /**
     * Set validity
     *
     * @param \DateTime $validity
     *
     * @return token
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return \DateTime
     */
    public function getValidity()
    {
        return $this->validity;
    }
    /**
    * @var SSOUser $ssoUser
    *
    * @ORM\ManyToOne(targetEntity="ETECH\SSOTestBundle\Entity\SSO_User", inversedBy="tokens", cascade={"persist", "merge"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $ssoUser;
    
    public function setSsoUser(SSO_User $user){
        $this->ssoUser = $user;
    }
    public function getSsoUser(){
        return $this->ssoUser;
    }

    /**
    * @ORM\OneToOne(targetEntity="token")
    */
    private $ssid;
    
    /**
     * Set ssid
     *
     * @param \DateTime $validity
     *
     * @return token
     */
    public function setSsid(token $_ssid){
        $this->ssid = $_ssid;
        return $this;
    }
    /**
     * Get ssid
     *
     * @return ETECH\SSOTestBundle\Entity\token
     */
    public function getSsid(){
        return $this->ssid;
    }
}

