<?php

    namespace HenriqueCacerez\MasterPDO;

    class MasterDatabaseConnection {

        private $connection;
        private $dns = null;

        public function __construct(string $dns = null)
        {
            $this->dns = $dns;
        }

        public function setDatabase(array $database)
        {
            $db = json_decode(json_encode($database), false);
            $this->dns = "{$db->driver}:dbname={$db->name};host={$db->host};user={$db->user};port={$db->port}";
            
            return $this;
        }

        public function getConn()
        {
            return $this->connection ? $this->connection : $this->initConnection();
        }

        private static function loadEnv()
        {
            $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        private function checkSQLite()
        {
            $sqlite = explode('@', getenv('DB_DRIVER') ?: $this->dns);

            if (isset($sqlite[1])) {
                return $this->dns = 'sqlite:' . dirname(__DIR__, 2) . '/' . $sqlite[1];
            }

            return false;
        }

        private function getDNS()
        {
            self::loadEnv();

            if ($this->checkSQLite() OR $this->dns) {
                return $this->dns;
            }

            return $this->dns = "".getenv('DB_DRIVER').":dbname=".getenv('DB_NAME').";host=".getenv('DB_HOST').";user=".getenv('DB_USER').";port=".getenv('DB_PORT')."";
        }

        private function initConnection()
        {
            try {
                $pdo = new \PDO($this->getDNS());
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $this->connection = $pdo;
            } catch (\PDOException $e) {
                die('ERROR DATABASE:' . $e->getMessage());
            }
        }

    }