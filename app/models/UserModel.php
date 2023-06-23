<?

//user model example

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUser($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}


?>