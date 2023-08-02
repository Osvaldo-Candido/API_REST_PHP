<?php

namespace Classes\Service;
use Classes\Repository\UsuariosRepository;
use Classes\Util\ConstantesGenericasUtil;

class UsuariosService {

    private array $dados;
    private object $repositoryUsuarios;
    private $dadosRequest = [];
    const RECURSO_GET = ['listar'];
    const RECURSO_DELETE = ['delete'];
    const RECURSO_POST = ['cadastrar'];
    const RECURSO_PUT = ['actualizar'];
    const TABELA = 'usuario';
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->repositoryUsuarios = new UsuariosRepository();
    }

    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso,self::RECURSO_GET))
        {
            $retorno = $this->dados['id'] > 0 ? self::getOneById() : $this->$recurso(); 
        }else{
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            
        }
        
        if($retorno === null)
        {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarDelete()
    {
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso,self::RECURSO_DELETE))
            {
                if($this->dados['id'] > 0)
                {
                    $retorno = $this->$recurso();
                }else{
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
                    
                }
            }else{
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                
            }

            return $retorno;
    }
    public function setDadosCorpoRequest($dadosRequest)
    {
          return $this->dadosRequest = $dadosRequest;
    }

    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSO_POST))
        {
            $retorno = $this->$recurso();
        }else{
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            
        }

        if($retorno == null)
        {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            
        }
        return $retorno;
    }
    private function getOneById()
    {
        return $this->repositoryUsuarios->getMySql()->getOneByKey(self::TABELA,$this->dados['id']);
    }

    public function listar()
    {
        return $this->repositoryUsuarios->getMySql()->getAll(self::TABELA);
    }

    public function delete()
    {
        return $this->repositoryUsuarios->getMySql()->delete(self::TABELA,$this->dados['id']);
    }

    public function cadastrar()
    {
           [$login, $senha] = [$this->dadosRequest['login'], $this->dadosRequest['senha']];

           if(isset($login) && isset($senha))
           {
                if($this->repositoryUsuarios->insertUser($login, $senha))
                {
                    $idInserido = $this->repositoryUsuarios->getMySql()->getDb()->lastInsertId();
                    $this->repositoryUsuarios->getMySql()->getDb()->commit();
                    return ['idInserido' => $idInserido];
                }
           }

           throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO
        );
           
    }

    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSO_PUT))
        {
            $retorno = $this->$recurso();
        }else{
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            
        }

        if($retorno == null)
        {
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            
        }
        return $retorno;
    }

    public function actualizar()
    {
        [$id, $login, $senha] = [$this->dadosRequest['id'],$this->dadosRequest['login'],$this->dadosRequest['senha']];

        if($id > 0 && $login && $senha)
        {
            $this->repositoryUsuarios->updateUser($id,$login,$senha);
            $this->repositoryUsuarios->getMySql()->getDb()->commit();
            return ["sucess" => "Actualizado com sucesso"];
        }

        throw new  \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
    }

}