<?php

    require_once('DAO.php');
    require_once('CategoryDAO.php');
    require_once('UserDAO.php');
    require_once('./../models/ProductoEntity.php');

    class ProductoDAO extends DAO{

        protected $UserDao;
        protected $CategoryDao;

        function __construct($con){
            parent::__construct($con);
            $this->table = 'productos';
            $this->CategoryDao = new CategoryDAO($con);
        }

        // OBTENER UN PRODUCTO
        public function getOne($id){
            $sql = "SELECT id,titulo,descripcion,precio,imagen,activo,destacado,categoria FROM $this->table WHERE id = $id";
            $resultado = $this->con->query($sql,PDO::FETCH_CLASS,'ProductoEntity')->fetch();
            $resultado->setCategoria($this->CategoryDao->getOne($resultado->getCategoria()));

            return $resultado;
        }

        // OBTENER TODOS LOS PRODUCTOS
        public function getAll($where = array()){

            $sqlWhereStr = ' WHERE 1=1 ';

            if(!empty($where['cat'])){
                $sqlWhereStr .= ' AND categoria = '.$where['cat'];
            }

            $sql = "SELECT  id,
                            titulo,
                            descripcion,
                            precio,
                            imagen, 
                            activo,
                            destacado,
                            categoria 
                    FROM $this->table $sqlWhereStr";

            $resultado = $this->con->query($sql,PDO::FETCH_CLASS,'ProductoEntity')->fetchAll();

            foreach($resultado as $index=>$res){
                $resultado[$index]->setCategoria($this->CategoryDao->getOne($res->getCategoria()));
            }

            return $resultado;
        }

        // GUARDAR PRODUCTO
        public function save($data = array()){
            $save = parent::save($data);
            return $data;
        }

        // MODIFICAR PRODUCTO
        public function modify($id, $data = array()){
            $save = parent::modify($id, $data ); 
            return $id;
        }

        // ELIMINAR PRODUCTO
        public function delete($id){
            return parent::delete($id);
        }
        
    }

?>
