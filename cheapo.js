var user;
var inbox,sent,read;
var error = '';
var r;

function setup(){
    $('#loginbutton').on('click',login);
    $('.textarea').each(function(){
        $(this).on('keypress',function(e){
            if(e.which == 10 || e.which == 13) {
                login();
            }
        });
    });
}

function login(){
    var u = $('#username').val();
    var p = $('#password').val();
    error = '';
    
    if(u===''){
        error += 'Username is required. </br>';
    }
    if(p===''){
        error+= 'Password is required.';
    }
    
    if(p!=='' && u!==''){
        $.post('login.php',{username:u, password:p}, function(result){
            try{            
                user = JSON.parse(result);
                console.log(user);
                if(user['id'].indexOf("admin") !== -1){
                    $.post('pages.php',{request:'admin'},function(result){
                        $('body').html(result);
                        console.log(result);
                        setup2(true);
                    },'html');
                }
                else{
                     $.post('pages.php',{request:'user'},function(result){
                        $('body').html(result);
                        console.log(result);
                        setup2(true);
                    },'html');
                }
            }catch(e){
                error+= 'Username/Password is invalid';
                $('#warning').html(error);
            }
        });
    }
    $('.error').html(error);
}


function setup2(a){
    $('#welcome').html('Welcome '+user['firstname']+' '+user['lastname']+'!');
    $('#logout').on('click',logout);
    
    getMessages();
    setTimeout(function(){
        setMessages(inbox ,10);
    },2000);
    
    $('#newuser').click(function(){
        $.post('pages.php',{request:'adduser'},function(result){
            $('#details').html(result);
            console.log(result);
            setTimeout(function(){
                $('#addnewuser').click(newUser);
                $('.textarea').each(function(){
                   $(this).on('keypress',function(e){
                        if(e.which == 10 || e.which == 13) {
                            newUser();
                        }
                    }); 
                });
            },2000);
        },'html'); 
        select($(this));
    });
    
    $('#inbox').click(function(){
        $('#list').html('');
        setMessages(inbox,'all');
        select($(this));
    });
    
    $('#sent').click(function(){
        $('#list').html('');
        setMessages(sent,'all');
        select($(this));
    });
    
    $('#users').click(function(){
        $('#list').html('');
        users();
        select($(this));
    });
    
    $('#composebutton').click(function(){
        $.post('pages.php',{request:'compose'},function(result){
            $('#details').html(result);
            console.log(result);
            setTimeout(function(){
                $('#sendbutton').click(sendMessage);
            },1000);
        });
    });
    
    setInterval(getMessages,60000);
}



function select(el){
    $('.selected').each(function(){
        $(this).removeClass('selected');
    });
    el.addClass('selected');
}

function users(){
    $.post('users.php',{request:'users'},function(result){
       $('#list').html(result);
       console.log(result);
    });
}

function newUser(){
    var fname = $('#firstname').val();
    var lname = $('#lastname').val();
    var uname = $('#username').val();
    var pword = $('#pword').val();
    var a;
    if($('#admin').is(':checked')){
        a = 'true';
    }else{
        a = 'false';
    }
    
    $.post('users.php',{request:'newuser',firstname:fname,lastname:lname,username:uname,password:pword,admin:a},function(result){
        $('.error').html(result);
        console.log(result);
    });
    
    $('#firstname').val('');
    $('#lastname').val('');
    $('#username').val('');
    $('#pword').val('');
    $('#admin').prop('checked',false);
}

function logout(){
    $.post('pages.php',{request:'home'},function(result){
        $('body').html(result);
        console.log(result);
    },'html').done(setup);
    user = '';
    inbox = ''; 
    sent = '';
    read = '';
}

function getMessages(){
    $.post('messages.php',{id:user['id'], request:'inbox'}, function(result){
        inbox=JSON.parse(result);
    });
    
    $.post('messages.php',{id:user['id'], request:'sent'}, function(result){
        sent=JSON.parse(result);
    });
    
    $.post('messages.php',{id:user['id'], request:'readlist'}, function(result){
        read=JSON.parse(result);
        console.log(read);
    });
    
    setTimeout(function(){
        $('.counter').html(inbox.length-read.length);
    },1000);
}


function setMessages(a,n){
    var element;
    
    if(n==='all'){
        n=a.length;
    }
    
    for(var i=0;i<n;i++){
        var sender=a[i]['lastname']+', '+a[i]['firstname'];
        var subject=a[i]['subject'];
        var datesent = a[i]['date_sent'];
        var body=a[i]['body'];

        if(read.indexOf(a[i]['id'])!==-1){
            element="<div class='message read'>";
            element+="<span class='sender'>"+sender+"</span>";
            element+="<span class='date'>read        "+datesent.substring(0,10)+"</span><br>";
        }else if(a===sent){
            element="<div class='message read'>";
            element+="<span class='sender'>"+sender+"</span>";
            element+="<span class='date'>sent        "+datesent.substring(0,10)+"</span><br>";
        }else{
            element="<div class='message'>";
            element+="<span class='sender'>"+sender+"</span>";
            element+="<span class='date'>"+datesent.substring(0,10)+"</span><br>";
        }
       
        element+="<span class='subject'>"+subject+"</span><br>";
        element+="<span class='bodysegment'>"+body.substring(0,15)+"...</span>";
        element+="<span class='index'>"+i+"</span>";
        element+="</div>";
        $('#list').append(element);
        setupMessages(a);
    }
    
    if(a=='' || a==[]){
        element="<div id='nomessages'>No messages here</div>";
        $('#list').append(element);
    }
}

function setupMessages(a){
    $('.message').each(function(){
        $(this).click(function(){
            var indx = parseInt($('span.index',this).html());
            console.log(indx);
            displayMessage(a,indx);
            if(!$(this).hasClass('read')){
                $(this).addClass('read');
                $.post('messages.php',{id:user['id'],request:'read',m_id:a[indx]['id']},function(result){
                    console.log(result);
                });
            }
            select($(this));
            setTimeout(getMessages, 500);
        });
    });
}


function displayMessage(a,i){
    $.post('pages.php',{request:'readmessage'},function(result){
        $('#details').html(result);
        console.log(result);
    },'html');
    setTimeout(function(){
        var sender=a[i]['lastname']+', '+a[i]['firstname'];
        var subject=a[i]['subject'];
        var datesent = a[i]['date_sent'];
        var username = a[i]['username'];
        var body=a[i]['body'];
        
        $('#subject').html(subject);
        $('#sender').html(sender);
        $('#uname').html(username);
        $('#datesent').html(datesent.substring(0,10));
        $('#body').html(body);
    },1000);
}

function sendMessage(){
    var recipients = $('#recipients').val().split(',');
    var subject = $('#subj').val();
    var body = $('#newbody').val();
    $.post('messages.php',{id:user['id'],request:'send',to:JSON.stringify(recipients),subj:subject,body:body},function(result){
        console.log(result);
        $('#recipients').val('');
        $('#subj').val('');
        $('#newbody').val('');
    }).done(function(){
        getMessages();
        setTimeout(function(){
        displayMessage(sent,0);
        },1000);
    });
}

$(document).ready(setup);