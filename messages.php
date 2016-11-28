<?php
    $host = getenv('IP');
    $username = getenv('C9_USER');
    $password = '';
    $dbname = 'cheapomail';
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $id = $_POST["id"];
    $req = $_POST["request"];
    
    $messageid = $_POST["m_id"];
    
    $body = test_input($_POST["body"],false);
    $to = json_decode($_POST['to']);
    $subj = test_input($_POST["subj"],true);
    
    $results = array();
    $r='';
    
    switch($req){
        case 'inbox':
            $stmt = $conn -> query("SELECT message.id,user.firstname,user.lastname,user.username,message.subject,message.body,message.date_sent FROM message JOIN user ON message.user_id=user.id  WHERE recipient_ids='$id' ORDER BY date_sent DESC");
            if($stmt !== false){
                $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'sent':
            $stmt = $conn -> query("SELECT message.id,user.firstname,user.lastname,user.username,message.subject,message.body,message.date_sent FROM message JOIN user ON message.recipient_ids=user.id  WHERE user_id='$id' ORDER BY date_sent DESC");
            if($stmt !== false){
                $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'readlist':
            $stmt = $conn -> query("SELECT message_id FROM message_read WHERE reader_id='$id'");
            if($stmt !== false){
                $r = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            }
            foreach($r as $row){
                array_push($results,$row['message_id']);
            }
            break;
        case 'read':
            $now = date("Y-m-d H:i:s");
            $mrID = newID($conn,'mr',0,199999999,'message_read');
            $stmt = $conn -> query("INSERT INTO message_read VALUES('$mrID','$messageid','$id','$now');");
            if($stmt !== false){
                $results = 'Message Read';
            }
            break;
        case 'send':
            $mID = newID($conn,'m',100000,199999,'message');
            $now = date("Y-m-d H:i:s");
            $stmt = $conn -> prepare("INSERT INTO message VALUES(:mID,:recipient,:id,:subj,:body,:now);");
            foreach($to as $receiver){
                $recipient = getID($conn, $receiver);
                echo $recipient;
                
                $stmt -> bindParam(':mID',$mID,PDO::PARAM_STR,15);
                $stmt -> bindParam(':recipient',$recipient,PDO::PARAM_STR,15);
                $stmt -> bindParam(':id',$id,PDO::PARAM_STR,15);
                $stmt -> bindParam(':subj',$subj,PDO::PARAM_STR);
                $stmt -> bindParam(':body',$body,PDO::PARAM_STR);
                $stmt -> bindParam(':now',$now,PDO::PARAM_STR);
                
                if($stmt->execute()){
                    echo true;
                }else{
                    echo $stmt -> errorCode();
                }
            }
            break;
    }
    
    function newID($con,$identifier, $start, $stop, $table){
        $ids = array();
        $i = '';
        
        $stmt = $con -> query("SELECT id FROM $table;");
        
        if($stmt !== false){
            $i = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        } 
        
        foreach($i as $row){
            array_push($ids,$row['id']);
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
    
    function getID($con, $uname){
        $uname=test_input($uname,true);
        $stmt = $con -> query("SELECT id FROM user WHERE username = '$uname'");
        if($stmt !== false){
            $i = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $i[0]['id'];
        }else{
            return 0;
        }
    }
    
    
    function test_input($data,$all){
        if($all){
            $data = trim($data);
        }else{
            $data = trim($data,"\0\x0B\r ");
        }
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    echo json_encode($results);