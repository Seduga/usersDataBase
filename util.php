
<?php 
require_once "pdo.php";
          function flashMessage(){
            if(isset($_SESSION['error'])){
                echo ("<p style='color: red'>" . htmlentities($_SESSION['error']).   "</p> \n");
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])){
                echo ("<p style='color: green'>" . htmlentities($_SESSION['success'])  .  "</p> \n");
                unset($_SESSION['success']);
            }
        }
        
        function validateProfile(){
            if(strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 || strlen($_POST['email']) == 0 || strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0){
                return "All fields are required";
            
            if(strpos($_POST['email'] , "@") === false){
                return "email must be contain @";
            } else{
                return true;
            }
        }
        }
        function validateSignUp(){
            if(strlen($_POST['email']) < 1  || strlen($_POST['pass']) < 1){
                $_SESSION['error'] = "All fields are required";
                return "All fields are required";
             }
             if(strpos($_POST['email'] , "@") === false ){
                $_SESSION['error'] = "Invalid email adress";
                return "Invalid email adress";
             }
             if(strlen($_POST['pass']) < 3){
                $_SESSION['error'] = "Password is to short";
                return "Password is to short";
             };
        }

        function deniedAccess(){
            if (!isset($_SESSION['user_id'])) {
                die("ACCESS DENIED");
            }
        }

        function validatePos(){
            for($i = 0;$i<9;$i++){
                if(!isset($_POST['year' . $i])) continue;
                if(!isset($_POST['decs' .$i])) continue;
                $year = $_POST['year' . $i];
                $desc = $_POST['desc' . $i];
                if(strlen($year) == 0 || strlen($desc) == 0){
                    return "All fields are required";
                }
                if(!is_numeric($year)){
                    return "Year must be numeric";
                }
            }
            return true;
        }
        function validateEdu(){
            for($i = 0;$i<9;$i++){
            if(!isset($_POST['edu_year' . $i])) continue;
            if(!isset($_POST['edu_school' . $i])) continue; 
            $year = $_POST['edu_year' . $i];
            $school = $_POST['edu_school' . $i];
            if(strlen($year) == 0 || strlen($school) == 0){
                return "All fields are required";
            }
            if(!is_numeric($year)){
                return "Year must be numeric";
            }
            }
            return true;
        }

         function loadPos($pdo,$profile_id){
            $stmt = $pdo->prepare('SELECT * from position where profile_id = :prof ORDER By rank');
             $stmt->execute(array(
                 ':prof'=>$profile_id));
                 $positions = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                     $positions[] = $row;
                 }
                 return $positions;
               }

               function loadEducation($pdo,$profile_id){
                 $stmt = $pdo->prepare('SELECT year,name from education JOIN institution on education.institution_id = institution.institution_id
                  where profile_id = :prof order by rank');
                  $stmt->execute(array(
                    ':prof' => $profile_id
                  ));
                  // fetchAll заменяет строки  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ $positions[] = $row;}
                  $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  return $educations;
                }

               function insertPos($pdo,$profile_id){
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

               function insertEdu($pdo,$profile_id){
                $rank = 1;
                for($i=0;$i<=9;$i++){
                    if(!isset($_POST['edu_year' . $i])) continue;
                    if(!isset($_POST['edu_school' . $i])) continue;  
                      $year = $_POST['edu_year' . $i];
                      $school = $_POST['edu_school' . $i];
                      $institution_id = false;
                      $stmt = $pdo->prepare('SELECT institution_id from institution where name = :name');
                      $stmt->execute(array(':name' =>$school));
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      if($row !==false) $institution_id = $row['institution_id'];

                      if($institution_id === false){
                        $stmt = $pdo->prepare('INSERT INTO institution(name) VALUES(:name)  ');
                        $stmt->execute(array(':name' =>$school));
                        $institution_id = $pdo->lastInsertId();

                      }
                    $stmt = $pdo->prepare('INSERT INTO education (profile_id,rank,year,institution_id) VALUES (:pid,:rank,:year,:iid)');
                    $stmt->execute(array(
                        ':pid' => $profile_id,
                        ':rank' => $rank,
                        ':year' => $year,
                        ':iid' => $institution_id));
                }
                $rank++;
               }

               function insertUsers($pdo){
                $salt = 'XyZzy12*_';
                $pass = hash('md5', $salt . $_POST['pass']);
                $stmt = $pdo -> prepare('INSERT INTO users (name,email,password) values (:nam,:email,:pass)');
                $stmt->execute(array(
                    ":nam" => $_POST['name'],
                    ":email" => $_POST['email'],
                    "pass" => $pass
                ));
               }


?>