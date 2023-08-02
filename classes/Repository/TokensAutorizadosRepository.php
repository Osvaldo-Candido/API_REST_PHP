<?php
namespace Classes\Repository;
use Classes\DB\MySQL;
use Classes\Util\ConstantesGenericasUtil;

class TokensAutorizadosRepository{

    private $MySql;
    public const TABELA = 'tokens_autorizados';
    public function __construct()
    {
            $this->MySql = new MySQL;
    }

    public function validarToken($token)
    {
        $token = str_replace([' ','Bearer'],'',$token);
        //var_dump($token);
        if($token)
        {
            $consulta = 'SELECT * FROM '. self::TABELA . ' WHERE token = :token AND status = :status';
            $stmt = $this->getMySql()->getDb()->prepare($consulta);
            $stmt->bindValue(':token',$token);
            $stmt->bindValue(':status',ConstantesGenericasUtil::SIM);
            $stmt->execute();

            if($stmt->rowCount() !== 1)
            {
                    header("HTTP/1.1 401 Unauthorized");
                    throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }
            
        }else{
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_VAZIO);
            
        }
    }


    public function getMySql()
    {
        return $this->MySql;
    }
}