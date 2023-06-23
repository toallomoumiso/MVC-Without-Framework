<?
class DatabaseConnection
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        
        $this->connect();
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error):
            die('Veritabanına bağlanılamadı: ' . $this->conn->connect_error);
        else:
            echo 'bağlandı';
        endif;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function close()
    {
        $this->conn->close();
    }
}


?>