<?php

namespace ETECH\SSOTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ETECH\SSOTestBundle\Entity\token;
/**
 * SSO_User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ETECH\SSOTestBundle\Entity\SSO_UserRepository")
 */
class SSO_User
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
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * @var ArrayCollection $tokens
     *
     * @ORM\OneToMany(targetEntity="ETECH\SSOTestBundle\Entity\token", mappedBy="ssoUser", cascade={"persist", "remove", "merge"}, fetch="EAGER")
     */
    private $tokens;

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
     * Set login
     *
     * @param string $login
     *
     * @return SSO_User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return SSO_User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
    * @return ArrayCollection $tokens
    */
    public function getTokens() {
        return $this->tokens;
    }
    
    public function addToken(token $token){
        $this->getTokens()->add($token);
    }
    public function removeToken(token $token){
        return $this->getTokens()->removeElement($token);
    }
    public function isTokenSessionValid($tokenSession){
        $tokens = $this->getTokens();
        if(count($tokens) > 0){
            foreach($tokens as $token){
                if($token->getTokenSession() == $tokenSession){
                    return $token;
                }
            }
        }
        return false;
    }
    public function isTempTokenValid(token $tempToken, $validity){
        if($tempToken instanceof token){
            $currentDate = new \DateTime();
            $tokenValidity = $tempToken->getValidity();
            if($validity == $tokenValidity && $currentDate < $tokenValidity){
                return true;
            }
        }
        return false;
    }
}

