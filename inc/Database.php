<?php

class DB {

    private array $config;
    private mysqli | false $con;
    private string | null $table;

    /**
	 * Instantiates a connection with MySql
	 *
     */
    function __construct() {
        if (!class_exists('mysqli')) {
            Raise::error(500, "MySqli extension not found");
            return;
        }

        if (!$db_secret = Secrets::get('database')) Raise::error(123, "Secret Collection Failed");
        $this->config = [...$db_secret];
        $this->table = null;
    }

    /**
	 * Connects to the desired database
	 *
	 *
	 * @param  string $database - name of the desired DB.
     *
     */
    public function connect(string $database): bool {
        
        try {
            $con = new mysqli(...$this->config, ...[$database]);
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
    public function set_table(string $table_name): void {
        if (!isset($this->con)) return;
        
        if (!$this->con->query("select 1 from `{$table_name}` LIMIT 1")) {
            $this->create_table($table_name);
        }
        $this->table = $table_name;
    }

    /**
	 * Create new table with id column
	 *
	 *
	 * @param  string $table_name - name of the new table.
     *
	 * @return mixed res from query
     */
    public function create_table(string $table_name): mixed {
        return $this->con->query("CREATE TABLE {$table_name} (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)");
    }

    /**
	 * Destruct and close connection
	 *
     */
    function __destruct() {
        if (isset($this->con)){
            $this->con->close();
        }
    }
}
