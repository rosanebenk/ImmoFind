<?php
class Model {

    static $connections = array();
    public $conf = 'default';
    public $table = false ;
    public $db;
    public $primarykey = 'id';
    public $id;

    public function __construct()
    {   
        //initialisation des var 
        if ($this->table === false ){
        $this->table = strtolower(get_class($this));// nom de la table doit etre = au nom de la classe 
        //debug($this->table);
        }
        // cnx à la BDD
        $conf = Conf::$databases[$this->conf];
        //debug($conf);
        if (isset(Model::$connections[$this->conf])) {
            $this->db = Model::$connections[$this->conf];
            //debug($this->db);
            return true;
        }
       try{
        $pdo = new PDO('pgsql:host='.$conf['host'].';port='.$conf['port'].';dbname='.$conf['database'].';',
            $conf['login'],
            $conf['password'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
        );
        //print_r($pdo);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);//pour gerer lurl n'importe quoi qui fou la merde dans la requete sql du genre where blabla -_-

        Model::$connections[$this->conf] = $pdo;
        $this->db = $pdo;
        //debug($this->db);
       }catch(PDOException $e){
           if (Conf::$debug >= 1) {
           die($e->getMessage());
           }else{
            die('Impossible de se connecter à la BDD');
           }
       }
       //echo "BDD chargéé et bien connetée";
      
    }

    //******************SEARCH*******************//
    public function find($req){// $req = null

        $sql = 'SELECT ';//this->table contient le nom de la table dans la BDD
        
        if (isset($req['fields'])){
            if (is_array($req['fields'])){
                $sql .= implode(',', $req['fields']);//concatenation
            }else{
                $sql .= $req['fields'];
            }
        }else {
            $sql .= ' * ';
        }

        $sql .= 'FROM '.$this->table.' as '.get_class($this). ' ';

        // construction de la condition
        //print_r($req['condition']);
        if(isset($req['condition'])) {
            //$sql .= 'WHERE '.$req['condition'];
            $sql .= 'WHERE ';
            if (!is_array($req['condition'])){
                $sql .=$req['condition'];
            }else{
                $cond = array();
                foreach($req['condition'] as $k=>$v){
                    if (!is_numeric($v)){
                        $v= $this->db->quote($v);
                    }
                   $cond[] = "$k=$v";
                }
                $sql .= implode(' AND ',$cond);
            }   
        }
        if (isset($req['limit'])){
              $sql .= 'LIMIT '.$req['limit'];
        }
        if (isset($req['orderby'])){//ordre compte fait attention à comment tu mets les attributs
            $sql .= ' ORDER BY ';
            if (is_array($req['orderby'])){
                $sql .= implode(',', $req['orderby']);//concatenation
            }else{
                $sql .= $req['orderby'].' ';
            }
            $sql .= $req['order'];
        }
        //die($sql);
        $pre = $this->db->prepare($sql);
        $pre->execute();
        return $pre->fetchAll((PDO::FETCH_OBJ));
        die($this->table);
    }
    public function findFirst($req){
        return current($this->find($req));//return le premier enregistrement qui est current 
    }

    //****************Insert******************************/
    public function add($req){
        $sql = 'INSERT INTO '.$this->table. 'VALUES'.get_class($this). ' ';//this->table contient le nom de la table dans la BDD
        
        // construction de la condition
        //print_r($req['condition']);
        if(isset($req['condition'])) { 
            //$sql .= 'WHERE '.$req['condition'];
            $sql .= 'WHERE ';
            if (!is_array($req['condition'])){
                $sql .=$req['condition'];
            }else{
                $cond = array();
                foreach($req['condition'] as $k=>$v){
                    if (!is_numeric($v)){
                        $v= $this->db->quote($v);
                    }
                   $cond[] = "$k=$v";
                }
                $sql .= implode(' AND ',$cond);
            }   
        }
        //die($sql);
        $pre = $this->db->prepare($sql);
        $pre->execute();
        return $pre->fetchAll((PDO::FETCH_OBJ));
        die($this->table);
    }

    //******************************* */
    public function findCount(){
        $res = $this-> findFirst(array(
            'fields' => 'COUNT('.$this->primarykey.') as count ',
        ));
        return $res->count;
    }
    //****************************** */
    public function delete($id){
        $sql = "DELETE FROM $this->table WHERE $this->primarykey = $id";
        //debug($sql);
        $this->db->query($sql);
    }
    //**************************** */
    /*UPDATE public.bien
	SET id=?, titre=?, typeannonce=?, prix=?, descrption=?, ville=?, codepostal=?, modif=?, typebien=?
	WHERE <condition>; */
    public function update($data){
        $key = $this->primarykey;
        $fields = array();
        $d = array();
        foreach($data as $k=>$v){
            $fields[ ]= " $k=:$k";
            $d[":$k"] = $v;
             
        }
        if (isset($data->$key) && !empty($data->$key)){
            $sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields).', modif = NOW()'.' WHERE '.$key.'=:'.$key; 
            debug($sql);
            $this->id = $data->$key;
        }
        $pre = $this->db->prepare($sql);
        $pre->execute($d);
        return true;
        //$pre->execute();
       
    }
}