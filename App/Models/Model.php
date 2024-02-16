<?php

    namespace App\Models;

    use App\Database\MasterPDO;

    abstract class Model extends MasterPDO {

        protected $handler;
        protected $primaryKey;
        protected $timestamps;
        protected $defaultFind;

        function __construct(
            string $tableName,
            string $primaryKey,
            bool   $timestamps  = false,
            string $defaultFind = "",
            \PDO   $databaseConnection = null
        ){
            parent::__construct($databaseConnection);

            $this->table       = $tableName;   
            $this->primaryKey  = $primaryKey;
            $this->timestamps  = $timestamps;
            $this->data        = new \stdClass();

            $this->setDefaultFind($defaultFind);
        }

        public function __get($name) 
        {
            return (isset($this->data->$name) ? $this->data->$name : false);
        }

        private function setDefaultFind($defaultFind)
        {
            if ($defaultFind !== "") {
                $this->find($defaultFind);   
            }
        }

        public function setData($data)
        {
            $data = json_decode(json_encode($data));

            foreach ($data as $column => $value) {
                $this->data->$column = $value;
            }
            
            return $this;
        }

        /**
         * Responsável por retornar os dados um único registro na tabela com base na PRIMARY_KEY.
         * @param integer $id
         */
        public function findById(int $id)
        {
            parent::from($this->table)->where([$this->primaryKey => ['=', $id]])->select();
            return $this;
        }

        /**
         * Retorna os dados do último registro inserido na tabela.
         */
        public function getLastInsert()
        {
            parent::from($this->table)->orderBy([$this->primaryKey => "DESC"])->select();
            return $this;
        }

        /**
         * Simplifica a query WHERE do MasterPDO.
         * exemplo: column = value, column2 != value2
         */
        public function find(string $columns = null)
        {
            if ($columns === null) {
                parent::from($this->table);
                return $this;
            }

            $pattern = '/(\w+)\s*(!=|>=|<=|=|>|<|<>)([^,]+)/';
            preg_match_all($pattern, $columns, $matches, PREG_SET_ORDER);
            
            $where = [];
            
            foreach ($matches as $match) {
                $column   = trim($match[1]);
                $operator = trim($match[2]);
                $value    = trim($match[3]);
                
                $where[$column] = [$operator, $value];
            }

            parent::from($this->table)->where($where);
            return $this;
        }

        private function insertData(array $data)
        {
            if ($this->timestamps) {
                $data["created_at"] = date('Y-m-d H:i:s');
            } else {
                unset($data['created_at']);
            }

            $registerId = parent::from($this->table)->insert($data);

            // if ($registerId) {
            //     return $this->findById($registerId);
            // }

            return $registerId;
        }
        
        private function updateData(array $data)
        {
            if ($this->timestamps) {
                $data["updated_at"] = date('Y-m-d H:i:s');
            } else {
                unset($data['updated_at']);
            }
            
            $primaryKey = $this->primaryKey;

            $updated = parent::from($this->table)->where([$primaryKey => ['=', $this->data->$primaryKey]])->update($data);
            
            return $updated ? $this->data->id : false;
        }

        public function save()
        {
            $attributes = get_object_vars($this->data);
            $primaryKey = $this->primaryKey;

            if (!isset($this->data->$primaryKey)) {
                return $this->insertData($attributes);
            }

            return $this->updateData($attributes);
        }

        public function cleanData()
        {
            $this->data = new \stdClass;
        }

    }