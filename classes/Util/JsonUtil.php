<?php
namespace Classes\Util;

class JsonUtil {

    public function processarArrayParaRetornar($retorno)
    {
        $dados = [];
        $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO;

        if(is_array($retorno) && count($retorno) > 0 || strlen($retorno) > 10)
        {
            $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
            $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
        }

        return $this->retornarJson($dados);
    }

    public function retornarJson($dados)
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        echo json_encode($dados);
        exit;
    }
    public static function tratarCorpoRequisicaoJson()
    {
        try {
            $postJson = json_decode(file_get_contents('php://input'),true);
        } catch (\JsonException $th) {
            //throw $th;
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_JSON_VAZIO);
        }

        if(is_array($postJson) && count($postJson) > 0)
        {
            return $postJson;
        }
    }
}