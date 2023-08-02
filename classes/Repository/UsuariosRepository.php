<?php 
namespace Classes\Repository;
use Classes\DB\MySql;

class UsuariosRepository{


        private object $MySql;
        private const TABLE = 'usuario';
        public function __construct()
        {
            $this->MySql = new MySql();
        }

        public function insertUser($login, $senha)
        {
                $consultarInsert = "INSERT INTO ".self::TABLE." (login, senha) VALUES (:login, :senha) ";
                $this->MySql->getDb()->beginTransaction();

                $stmt = $this->MySql->getDb()->prepare($consultarInsert);
                $stmt->bindParam(':login',$login);
                $stmt->bindParam(':senha',$senha);
                $stmt->execute();
                return $stmt->rowCount();
        }        

        public function updateUser($id, $login, $senha)
        {
            $actualizarUsuario = "UPDATE ".self::TABLE." SET login = :login, senha = :senha WHERE id = :id";
            $this->MySql->getDb()->beginTransaction();

            $stmt = $this->MySql->getDb()->prepare($actualizarUsuario);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':login',$login);
            $stmt->bindParam(':senha',$senha);
            $stmt->execute();
            return $stmt->rowCount();
        }
        public function getMySql()
        {
            return $this->MySql;
        }
}