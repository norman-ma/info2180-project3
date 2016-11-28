<?php
    $uname = $pword = '';
    
    $host = getenv('IP');
    $username = getenv('C9_USER');
    $password = '';
    $dbname = 'cheapomail';
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $uname = test_input($_POST["username"]);
        $pword = test_input($_POST["password"]);
        
        $results = "";
            
        $phash = hash('sha256',$pword);
        
        $stmt = $conn -> query("SELECT id,firstname,lastname FROM user WHERE user.username='$uname' AND user.password='$phash';");
            
        if($stmt !== false){
            $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        } 
        
        if($results!=""){
            foreach ($results as $row) {
                $r = array();
                $i = 0;
                foreach($row as $element){
                    $r[$i] = $element;
                    $i ++;
                }
                echo json_encode($row);
            }
        }
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }