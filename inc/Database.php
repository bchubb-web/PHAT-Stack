<?php

class SQL {

    private array $config;
    private mysqli | false $con;

    function __construct($hostname, $username, $password) {
        if (!$db_secret = Secrets::get('database')) Raise::error(123);
        $this->config = [...$db_secret];
    }

    /**
	 * Connects to the desired database
	 *
	 *
	 * @param  string $database - name of the desired DB.
     *
     */
    public function connect(): void {
        $this->con = new mysqli(...self->config);
    }
    
    /**
	 * Sets the current table to work on
	 *
	 *
	 * @param  string $table - name of the desired table.
     *
     */
    public function set_table(string $table_name): void {
        
        $table_check = self->con->query("select 1 from `{$table_name}` LIMIT 1");
        if ($table_check === false) {
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
        return self->con->query("CREATE TABLE {$table_name} (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)");
    }

    
    /**
	 * Destruct and close connection
	 *
     */
    function __destruct() {
        self->con->close();
    }
}
