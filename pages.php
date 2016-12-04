<?php
    $req = $_POST["request"];
    
    $admin = "<body>
                <div name='sidebar' id='sidebar'>
                    <div name='account' id='account'>
                        <span id='welcome'></span>
                        <input type='submit' class='button' id='logout' value='logout'>
                    </div>
                    <div name='controls' id='controls'>
                        <div id='inbox'>Inbox<div class='counter'></div></div>
                        <div id='sent'>Sent</div>
                        <div id='users'>Users</div>
                        <div id='newuser'>Add New User</div>
                    </div>
                </div>
                <div name='main' id='main'>
                    <div name='compose' id='compose'>
                        <input type='submit' name='compasebutton' class='button' id='composebutton' value='+'>
                        <label for='compasebutton'>Compose New Message</label>
                    </div>
                    <div name='list' id='list'></div>
                    <div name='details' id='details'></div>
                </div>
            </body>";
        
    $user = "<body>
                <div name='sidebar' id='sidebar'>
                    <div name='account' id='account'>
                        <span id='welcome'></span>
                        <input type='submit' class='button' id='logout' value='logout'>
                    </div>
                    <div name='controls' id='controls'>
                        <div id='inbox'>Inbox<div class='counter'></div></div>
                        <div id='sent'>Sent</div>
                        <div id='users'>Users</div>
                    </div>
                </div>
                <div name='main' id='main'>
                    <div name='compose' id='compose'>
                        <input type='submit' name='compasebutton' class='button' id='composebutton' value='+'>
                        <label for='compasebutton'>Compose New Message</label>
                    </div>
                    <div name='list' id='list'></div>
                    <div name='details' id='details'></div>
                </div>
            </body>";
            
    $home = "<body>
                <div name='sidebar' id='sidebar'>
                    <div name='login' id='login'>
                        Username <input type='text' class='textarea' name='username' id='username'> 
                        <br><br>
                        Password <input type='password' class='textarea' name='password' id='password'>
                        <br>
                        <input type='submit' class='button' id='loginbutton' value='Login'>
                        <div class='error'></div>
                    </div>
                </div>
                <div name='pic' id='pic'>
                    <img src='/logo.svg' id='logo'>
                </div>
            </body>";
            
    $adduser = "<div name='adduser' id='adduser'>
                    <p>
                        Please complete the form below in order to add a new user to CheapoMail. <br/>Note: all fields are required. 
                    </p>
                    <div class='error'></div><br/>
                    <label for='firstname'>Firstname</label><input type='text' name='firstname' id='firstname' class='textarea'/><br/><br/>
                    <label for='lastname'>Lastname</label><input type='text' name='lastname' id='lastname' class='textarea'/><br/><br/>
                    <label for='username'>Username</label><input type='text' name='username' id='username' class='textarea'/><br/><br/>
                    <label for='pword'>Password</label><input type='password' name='pword' id='pword' class='textarea'/><br/><br/><br/>
                    <label for='admin'>Administrator</label><input type='checkbox' name='admin' id='admin'><br/><br/>
                    <input type='submit' name='addnewuser' id='addnewuser' class='button'>
                </div>";
                
    $readmessage = "<div name='readmessage' id='readmessage'>
                        <span id='subject'></span><br>
                        <div id='container'>
                            <span id='sender'></span><span id='uname'></span><span id='datesent'></span><br>
                            <p id='body'></p>
                        </div>
    
                    </div>";
                    
    $compose = "<div name='newmessage' id='newmessage'>
                        <label for='recipients'>Recipients: </label>
                        <input type='text' name='recipients' id='recipients' class='textarea'><br/>
                        <span id='tip'>Please separate multiple recipients using a comma(,)</span><br/><br/>
                        <label for='subject'>Subject: </label>
                        <input type='text' name='subject' id='subj' class='textarea'><br/>
                        <textarea id='newbody' cols='80' rows='500'></textarea><br/>
                        <input type='submit' value='Send' id='sendbutton' class='button'>
                    </div>";
    
    switch($req){
        case 'admin': 
            echo $admin;
            break;
        case 'user':
            echo $user;
            break;
        case 'home':
            echo $home;
            break;
        case 'adduser':
            echo $adduser;
            break;
        case 'readmessage':
            echo $readmessage;
            break;
        case 'compose':
            echo $compose;
            break;
    }