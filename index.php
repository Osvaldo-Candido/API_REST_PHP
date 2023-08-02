<?php

use Classes\Util\ConstantesGenericasUtil;
use Classes\Util\JsonUtil;
use Classes\Validator\RequestValidator;
use Classes\Util\RotasUtil;

require_once 'vendor/autoload.php';


try {
    $rotas = new RequestValidator(RotasUtil::getRotas());
    $retorno =  $rotas->processarRequest();

    $jsonUtil = new JsonUtil();
    $jsonUtil->processarArrayParaRetornar($retorno);

} catch (\Exception $th) {
  echo  json_encode([
        ConstantesGenericasUtil::TIPO => ConstantesGenericasUtil::TIPO_ERRO,
        ConstantesGenericasUtil::RESPOSTA => utf8_encode($th->getMessage())
    ]);
}