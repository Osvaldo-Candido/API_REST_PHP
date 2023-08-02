<?php

namespace Classes\Validator;

use Classes\Repository\TokensAutorizadosRepository;
use Classes\Service\UsuariosService;
use Classes\Util\ConstantesGenericasUtil;
use Classes\Util\JsonUtil;

class RequestValidator{

    private $request;
    private $dadosRequest = [];
    const GET = 'GET';
    const DELETE = 'DELETE';
    const USUARIOS = 'USUARIOS';
    private object $tokensAutorizadosRepository;
    public function __construct($request)
    {
        $this->request = $request;
        $this->tokensAutorizadosRepository = new TokensAutorizadosRepository();
    }

    public function processarRequest()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

       
        if(in_array($this->request['metodo'],ConstantesGenericasUtil::TIPO_REQUEST,true))
        {
           
            $retorno = $this->redirecionarRiquest(); 
        }

        return $retorno;
    }

    public function redirecionarRiquest()
    {
        if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE)
        { 
                $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }
        $this->tokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);

        $metodo = $this->request['metodo'];
        return $this->$metodo();
    }

    public function get()
    {
        $retorno = json_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
 
        if(in_array($this->request['rota'],ConstantesGenericasUtil::TIPO_GET))
        {
            switch($this->request['rota'])
            {
                case self::USUARIOS:
                    $usuarioService = new UsuariosService($this->request);
                    $retorno = $usuarioService->validarGet();
                    break;
                default:
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                

            }

        }    

        return $retorno;
        
    }

    public function delete()
    {
        $retorno = json_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if(in_array($this->request['rota'],ConstantesGenericasUtil::TIPO_DELETE))
        {
            switch($this->request['rota'])
            {
                case self::USUARIOS:
                    $usuarioService = new UsuariosService($this->request);
                    $retorno = $usuarioService->validarDelete();
                    break;
                default:
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                
            }
        }

        return $retorno;
    }

    public function post()
    {
        $retorno = json_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if(in_array($this->request['rota'],ConstantesGenericasUtil::TIPO_POST))
        {
                switch($this->request['rota'])
                {
                    case self::USUARIOS:
                        $usuarioService = new UsuariosService($this->request);
                        $usuarioService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $usuarioService->validarPost();
                        break;
                    default:
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                    
                }
        }
        return $retorno;
    }

    public function put()
    {
        $retorno = json_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if(in_array($this->request['rota'],ConstantesGenericasUtil::TIPO_PUT))
        {
            switch($this->request['rota'])
            {
                case self::USUARIOS:
                    $usuario = new UsuariosService($this->request);
                    $usuario->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuario->validarPut();
                    break;
                default:
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }
}