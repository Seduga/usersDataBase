<?php
require_once "pdo.php";
class User{
    public $user;
    public function __construct(array $user)
    {
        $this->user = $user;
    }
    static function getUser($pdo, $email): User|null{
        $stmt = $pdo->query("SELECT * from users Where email = '$email'");
        $email = $_POST['email'];
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return new User($user);
        }
        return null;
    }
    public function getName () {
        return $this->user['name'];
    }
    public function getEmail(){
        return $this->user['email'];
        
    }
    public function getUserId(){
        return $this->user['user_id'];
    }
    public function getUserPass(){
        return $this->user['password'];
    }
}

function flashMessage()
{
    if (isset($_SESSION['error'])) {
        echo ("<p style='color: red'>" . htmlentities($_SESSION['error']) . "</p> \n");
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo ("<p style='color: green'>" . htmlentities($_SESSION['success']) . "</p> \n");
        unset($_SESSION['success']);
    }
}

function validateProfile()
{
    if (strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 || strlen($_POST['email']) == 0 || strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0) {
        return "All fields are required";

        if (strpos($_POST['email'], "@") === false) {
            return "email must be contain @";
        } else {
            return true;
        }
    }
}
function validateSignUp()
{
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "All fields are required";
        return "All fields are required";
    }
    if (strpos($_POST['email'], "@") === false) {
        $_SESSION['error'] = "Invalid email adress";
        return "Invalid email adress";
    }
    if (strlen($_POST['pass']) < 3) {
        $_SESSION['error'] = "Password is to short";
        return "Password is to short";
    }
    ;
}

function deniedAccess()
{
    if (!isset($_SESSION['user_id'])) {
        die("ACCESS DENIED");
    }
}

function validatePos()
{
    for ($i = 0; $i < 9; $i++) {
        if (!isset($_POST['year' . $i]))
            continue;
        if (!isset($_POST['decs' . $i]))
            continue;
        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }
        if (!is_numeric($year)) {
            return "Year must be numeric";
        }
    }
    return true;
}
function validateEdu()
{
    for ($i = 0; $i < 9; $i++) {
        if (!isset($_POST['edu_year' . $i]))
            continue;
        if (!isset($_POST['edu_school' . $i]))
            continue;
        $year = $_POST['edu_year' . $i];
        $school = $_POST['edu_school' . $i];
        if (strlen($year) == 0 || strlen($school) == 0) {
            return "All fields are required";
        }
        if (!is_numeric($year)) {
            return "Year must be numeric";
        }
    }
    return true;
}


function insertPos($pdo, $profile_id)
{
    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i]))
            continue;
        if (!isset($_POST['desc' . $i]))
            continue;
        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        $sql = "INSERT INTO position (profile_id,rank,year,description) VALUES (:pid,:rank,:yr,:desc)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':yr' => $year,
                ':desc' => $desc,
            )
        );
        $rank++;
    }
}
function insertEdu($pdo, $profile_id)
{
    $rank = 1;
    for ($i = 0; $i <= 9; $i++) {
        if (!isset($_POST['edu_year' . $i]))
            continue;
        if (!isset($_POST['edu_school' . $i]))
            continue;
        $year = $_POST['edu_year' . $i];
        $school = $_POST['edu_school' . $i];
        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id from institution where name = :name');
        $stmt->execute(array(':name' => $school));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false)
            $institution_id = $row['institution_id'];

        if ($institution_id === false) {
            $stmt = $pdo->prepare('INSERT INTO institution(name) VALUES(:name)  ');
            $stmt->execute(array(':name' => $school));
            $institution_id = $pdo->lastInsertId();

        }
        $stmt = $pdo->prepare('INSERT INTO education (profile_id,rank,year,institution_id) VALUES (:pid,:rank,:year,:iid)');
        $stmt->execute(
            array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':iid' => $institution_id
            )
        );
        $rank++;
    }
   
}

function insertProfile($pdo){
    $sql = 'INSERT INTO profile(user_id,first_name,last_name,email,headline,summary ) VALUES (:uid,:fn,:ln,:em,:he,:sum)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(
                    ":uid" => ($_SESSION['user_id']),
                    ':fn' => ($_POST['first_name']),
                    ':ln' => ($_POST['last_name']),
                    ':em' => ($_POST['email']),
                    ':he' => ($_POST['headline']),
                    ':sum' => ($_POST['summary']),
                )
            );
}

function insertUsers($pdo)
{
    $salt = 'XyZzy12*_';
    $pass = hash('md5', $salt . $_POST['pass']);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password) values (:nam,:email,:pass)');
    $stmt->execute(
        array(
            ":nam" => $_POST['name'],
            ":email" => $_POST['email'],
            "pass" => $pass
        )
    );
}

function updateProfile($pdo,$profile_id){
    $sql = "UPDATE profile SET  first_name = :fn, last_name = :ln , email = :em , headline = :he, summary = :sum  WHERE profile_id = :pfid ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':pfid' => $profile_id,
        )
    );
}
?>