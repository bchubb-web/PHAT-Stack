<?php

class DB {

    private array $config;
    private mysqli | false $con;

    function __construct() {
        if (!$db_secret = Secrets::get('database')) Raise::error(123, "Secret Collection Failed");
        $this->config = [...$db_secret];
        $this->connect();
    }

    /**
	 * Connects to the desired database
	 *
	 *
	 * @param  string $database - name of the desired DB.
     *
     */
    private function connect(): bool {
        dump($this->config);
        try {
            $con = new mysqli(...$this->config);
        } catch (Exception $e) {
            Raise::error(500, "DB Connection failed: ".$e);
            return false;
        }
        $this->con = $con;
        return true;
    }
    
    /**
	 * Sets the current table to work on
	 *
	 *
	 * @param  string $table - name of the desired table.
     *
     */
    /*public function set_table(string $table_name): void {
        
        $table_check = $this->con->query("select 1 from `{$table_name}` LIMIT 1");
        if ($table_check === false) {
            $this->create_table($table_name);
        }
        $this->table = $table_name;
    }*/

    /**
	 * Create new table with id column
	 *
	 *
	 * @param  string $table_name - name of the new table.
     *
	 * @return mixed res from query
     */
    /*public function create_table(string $table_name): mixed {
        return self->con->query("CREATE TABLE {$table_name} (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)");
    }*/

    
    /**
	 * Destruct and close connection
	 *
     */
    function __destruct() {
        $this->con->close();
    }
}
