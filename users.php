<?php

    $uname = $pword = $fname = $lname = '';
    
    $host = getenv('IP');
    $username = getenv('C9_USER');
    $password = '';
    $dbname = 'cheapomail';
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $uname = test_input($_POST['username']);
    $pword = test_input($_POST['password']);
    $lname = test_input($_POST['lastname']);
    $fname = test_input($_POST['firstname']);
    $admin = $_POST['admin'];
    $id = newUserID($conn,$admin);
    
    $req = $_POST['request'];
    
    switch($req){
        case 'newuser':
            if($uname === '' || $fname === '' || $lname === '' || $pword === ''){
                echo "All fields are required";
            }elseif(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$pword)===1){
                $phash = hash('sha256',$pword);
                $stmt = $conn -> query("INSERT INTO user VALUES('$id','$fname','$lname','$uname','$phash');");
                
                if($stmt !== false){
                    echo "User Added";
                } else {
                    echo "Error: Please ensure username is unique";
                }
            } else{
                echo "Error: Password must contain atleast 1 capital letter, 1 commmon letter and 1 digit";
            }
            break;
        case 'users':
            $stmt = $conn -> query("SELECT firstname,lastname,username FROM user");
            $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            if($stmt !== false){
                echo "<div id='userlist'>";
                foreach ($results as $row) {
                    echo "<div class='user'>".$row['lastname'].", ".$row['firstname']."<span class='username'>".$row['username']."</span></div>";
                }
                echo "</div>";
            }
    }
    
    
    
    
    function newUserID($con,$admin){
  
        $ids = array();
        $i = '';
        
        if($admin==='true'){
            $identifier = 'admin';
            $start = 2;
            $stop = 1000;
        }else{
            $identifier = 'u';
            $start = 10000;
            $stop = 19999;
        }
        
        $stmt = $con -> query("SELECT id FROM user;");
        
        if($stmt !== false){
            $i = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        } 
        
        foreach($i as $row){
            array_push($ids,$row['message_id']);
        }
        
        $id=$identifier;
        $num=rand($start,$stop);
        $newid = $id.$num;
        while(in_array($newid,$ids)){
            $id=$identifier;
            $num=rand($start,$stop);
            $newid = $id.$num;
        }
        
        return $newid;
    }
    
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }