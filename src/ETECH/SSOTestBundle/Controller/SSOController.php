<?php

namespace ETECH\SSOTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ETECH\SSOTestBundle\Entity\token;

class SSOController extends Controller
{
    /**
    * PUT Route annotation.
    * @Put("/generate/token/temp", name = "generateTokenTemp" , options={ "method_prefix" = false })
    */
    public function generatetTokenTempAction(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $result = new \stdClass();
            $data = json_decode($request->getContent(), false);
            $token_attr_exist = (is_object($data) && isset($data->token_session));
            $login_attr_exist = (is_object($data) && isset($data->login));
            if($token_attr_exist && $login_attr_exist){
                $token_session = $data->token_session;
                $login = $data->login;
                $em = $this->getDoctrine()->getManager();
                $user_repository = $em->getRepository('ETECHSSOTestBundle:SSO_User');
                $token_repository = $em->getRepository('ETECHSSOTestBundle:token');
                $user = $user_repository->findOneByLogin($login);
                if(is_object($user)){
                    $original_token = $user->isTokenSessionValid($token_session);
                    if(is_object($original_token)){
                        $token_temp = $token_repository->findOneBySsid($original_token->getId());
                        $temp_token_exist = is_object($token_temp);
                        if(!$temp_token_exist){
                            $token_temp = new token();
                        }
                        $token_temp->setValidity(new \DateTime(date("Y-m-d H:i:s", time()+180)));
                        $token_temp->setSsoUser($user);
                        $token_temp->setTokenSession(md5("token_temp".time()));
                        $token_temp->setSSID($original_token);
                        if(!$temp_token_exist){
                            $user->addToken($token_temp);
                        }
                        $em->persist($user);
                        $em->flush();
                        $result->token_temp = $token_temp->getTokenSession();
                        $result->expiration = $token_temp->getValidity()->format('Y-m-d H:i:s');
                        $response = new Response(json_encode($result));
                        $response->headers->set('Content-Type', 'application/json');
                        return $response;
                    }  
                }
            }
            $result->token_temp = null;
            $result->expiration = null;
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        throw new \Exception('format invalide');
    }
    /**
    * POST Route annotation.
    * @Post("/login/token" , name = "loginToken" , options={ "method_prefix" = false })
    */
    public function loginTokenAction(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $result = new \stdClass();
            $data = json_decode($request->getContent(), false);
            $login_attr_exist = (is_object($data) && isset($data->login));
            $expiration_attr_exist = (is_object($data) && isset($data->expiration));
            $token_temp_attr_exist = (is_object($data) && isset($data->token_temp));
            if($login_attr_exist && $expiration_attr_exist && $token_temp_attr_exist){
                $em = $this->getDoctrine()->getManager();
                $user_repository = $em->getRepository('ETECHSSOTestBundle:SSO_User');
                $token_repository = $em->getRepository('ETECHSSOTestBundle:token');
                $user = $user_repository->findOneByLogin($data->login);
                if(is_object($user)){
                    $token_temp = $token_repository->findOneByTokenSession($data->token_temp);
                    if(!empty($token_temp) && $token_temp instanceof token){
                        $token_temp = $user->isTokenSessionValid($token_temp->getTokenSession());
                        $is_token_valid = $user->isTempTokenValid($token_temp,\DateTime::createFromFormat('Y-m-d H:i:s', $data->expiration));
                        if($token_temp instanceof token && $is_token_valid){
                            $token_session = $token_temp->getSsid();
                            //effacer le token temporaire
                            $em->remove($token_temp);
                            $em->flush();
                            $result->token_session = $token_session->getTokenSession();
                            $validity = $token_session->getValidity();
                            $result->validity = ($validity instanceof \DateTime) ? $validity->format('Y-m-d H:i:s') :null;
                            $result->data = null;
                            $response = new Response(json_encode($result));
                            $response->headers->set('Content-Type', 'application/json');
                            return $response;
                        }
                    }
                }
            }
            $result->token_session = null;
            $result->validity = null;
            $result->data = null;
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        throw new \Exception('format invalide');
    }
}
