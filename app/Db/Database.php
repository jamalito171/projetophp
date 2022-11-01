<?php
/**Esta classe ela faz uma ponte entre o sistema e o banco
 * usando PDO, abstraindo informações e funcionando como QueryBuilder
 * QUeryBuilder é um conceito onde você não precisa escrever sql
 * mas consegue executar comandos sql, pois uma classe abstrai esta escrita
 * onde o usuário passa os dados e esta classe escrete sql no banco para ele.
 */
namespace App\Db;

use \PDO;
use \PDOException;

class Database{

    /**
     * Host de conexão com o banco de dados
     * @var string
     */
    const HOST = 'localhost';
    /**
     * Nome do Banco
     * @var string
     */
    const NAME = 'wdev_vagas';
    /**
     * usuário e senha do banco
     * @var string
     *
     */
    const USER = 'renan';
    const PASS = 'jamal171';
    /**
     * Nome da tabela a ser manipulada
     */
    private $table;
    /**
     * Instancia da conexão com o banco, em PDO, que vai facilitar a portabilidade
     * bancos.
     * @var PDO
     */
    private $connection;

    /**
     * Construtor que define a tabela e instancia a conexão
     * @param string $table
     */
    public function __construct($table = null){
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por criar uma conexão com o banco de dados
     */
    private function setConnection(){
        try{
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        }

    }
    /**
     * Método responsável por executar queries dentro do banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query,$params = []){
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        }catch(PDOException $e){
            die('ERROR: '.$e->getMessage());
        }


    }
    /**
     * Método responsável por inserir dados no banco
     * @param array $values [ field => ]
     * @return integer ID inserido
     */
    public function insert($values){
        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds = array_pad([],count($fields),'?');

        //MONTA A QUERY        
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

               
        //EXECUTA O INSERT
        $this->execute($query,array_values($values));
       
        //RETORNA O ID INSERIDO
        return $this->connection->lastInsertId();
               
    }

     /**
     * Método responsável por fazer uma consulta banco de dados
    * @param string $where
    * @param string $limit
    * @param string $order
    * @param string $fields
    * @return PDOStatement
    */
    public function select($where = null, $order = null, $limit = null, $fields = '*'){
        //DADOS DA QUERY
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        //MONTA A QUERY
        $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

        //EXECUTA A QUERY
        return $this->execute($query);
    }
    /**
     * Método responsável por executar atualizações no banco de dados
     * @param string $where
     * @param array $values [ field => value ]
     * @return boolean
     */

     public function update($where,$values){
        //DADOS DA QUERY
        $fields = array_keys($values);
        
        //MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;
       
        //EXECUTA A QUERY
        $this->execute($query,array_values($values));

        //RETORNA SUCESSO
        return true;
     }
     /**
      * Método responsável por excluir dados do banco
      * @param string $where
      * @return boolean
      */
     public function delete($where){
        //MONTA A QUERY
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;
        //EXECUTA A QUERY
        $this->execute($query);
        //RETORNA SUCESSO
        return true;
     }
}