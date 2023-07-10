<?php
$con = false;
if (class_exists('mysqli')){
    include_once(__DIR__.'/connections.php');
    $con = new mysqli($hostname, $username, $password, $database);
}

/**class SQL {
    private array $config;
    private $con;
    private string $table;

    function __construct($hostname, $username, $password) {
        self->config = [$hostname, $username, $password];
    }

    /**
	 * Connects to the desired database
	 *
	 *
	 * @param  string $database - name of the desired DB.
     *
	 *
    public function connect(string $database): void {
        self->con = new mysqli(...self->config, $database);
    }
    
    /**
	 * Sets the current table to work on
	 *
	 *
	 * @param  string $table - name of the desired table.
     *
	 *
    public function set_table(string $table_name): void {
        
        $table_check = self->con->query("select 1 from `{$table_name}` LIMIT 1");
        if ($table_check === false) {
            self->create_table($table_name);
        }
        self->table = $table_name;
    }

    /**
	 * Create new table with id column
	 *
	 *
	 * @param  string $table_name - name of the new table.
     *
	 * @return mixed res from query
	 *
    public function create_table(string $table_name): mixed {
        return self->con->query("CREATE TABLE {$table_name} (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)");
    }

    
    /**
	 * Destruct and close connection
	 *
	 *
    function __destruct() {
        self->con->close();
    }
}*/
