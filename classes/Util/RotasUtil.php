<?php

namespace Classes\Util;

class RotasUtil {

    public static function getRotas()
    {
            $url = self::getUrl();
            $request = [];
            $request['rota'] = strtoupper($url[0]);
            $request['recurso'] = $url[1] ?? null;
            $request['id'] = $url[2] ?? null;
            $request['metodo'] = $_SERVER['REQUEST_METHOD'];
            return $request;
    }

    private static function getUrl()
    {
        $url = str_replace('/api','',$_SERVER['REQUEST_URI']);
        $url = explode('/',trim($url,'/'));

        return $url;
    }

}