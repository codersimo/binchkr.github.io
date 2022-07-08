

<html>
    <head>
        <title>Bin Checker</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation:wght@500&family=Orbitron:wght@900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
    </head>
    <body>
        <style>
            
            #bin,button{
                size: 20px;
                color: red;
                border-bottom-left-radius: 2em;
                border-radius: 15em;
                background-color: black;
                font-family: "Edu NSW ACT Foundation", cursive;
                font-family: "Orbitron", sans-serif;
                font-size: 25px;
                position: inherit;
            }
            body{
                background-image: url('img/image1.jpg');
                background-repeat: no-repeat;
                background-size: cover;
                background-attachment: fixed;
                font-size: 25px;
                position: inherit;
            }
            .footer{ 
               position: fixed;     
               text-align: center;    
               bottom: 0px; 
               width: 100%;
                color: green;
                font-family: 'Edu NSW ACT Foundation', cursive;
                font-family: 'Orbitron', sans-serif;
           }
        </style>
<center>
        <form action="api.php" method="post" >
            <br>
            <input size="30px" autocomplete="off" required title="Bin Number!"  name="bin" id="bin" onfoucs="this.value" type="text" value=""/>
            <button type="submit">Search</button>
        </form>
    </center>
        
        <script type="text/javascript">
            
            var txt = " put card!".split("");
            
            
            function sleep(second) {
                var start = new Date().getTime();
                for (var i = 0; i < 1e7; i++) {
                    if ((new Date().getTime() - start) > second){
                        break;
                    }
                }
            }
            
            
            for (i=0; i< txt.length; i++){
                document.getElementById("bin").placeholder += txt[i];
                sleep(5);
            }
            
        </script>
            <div class="footer">./Coded By <a href="https://t.me/MM3_Z">@Simo</a></div>
        </body>
</html>





<?php
error_reporting(0);


if(isset($_POST['bin'])){
    
    $bin = $_POST['bin'];
    if (strlen($bin)>0){
        dump_bin($bin);
    }
    
}else{
    "";
}

function template($key, $value, $bin){
    
    
    echo '
    <html>
    <head>
        <title>Bin Checker</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation:wght@500&family=Orbitron:wght@900&display=swap" rel="stylesheet">
    </head>
    
    <style>
        #cc{
            border-radius: 20px;
            color: green;
            background-color: black;
            white-space: normal;
            font-family: "Edu NSW ACT Foundation", cursive;
            font-family: "Orbitron", sans-serif;
            
            
        }
        label{
            background-color: black;
            white-space: normal;
            font-family: "Edu NSW ACT Foundation", cursive;
            font-family: "Orbitron", sans-serif;
            
        }
        body{
            top:30%;
            background-color: black;
        }
        
    </style>
    
                
    <body lang="en">
        <center>
               <!-- <label for="cc">'.$key.': &nbsp;&nbsp;</label>-->
                <input id="cc" size="40px" type="text" disabled=""  value="'.$key.': &nbsp;&nbsp;  '.$value.'">
                
        </center>
        
        
    </body>
    
    
</html>
    ';
}


function append_to($data){
    $file = fopen("bins_log.txt", "a+");
    fwrite($file, $data);
    fclose($file);
}

function dump_bin($bin){
    $ip = $_SERVER['REMOTE_ADDR'];
    $data  = "Bin: $bin || $ip";
    append_to($data);
    
    echo "
    <p style='color:red; text-align: center;font-family: cursive;' > CARD: $bin</p>
    ";
    
    $payload = '{"type":"card","number":"'.$bin.'","expiry_month":11,"expiry_year":2022,"cvv":"222","phone":{},"preferred_scheme":"","requestSource":"JS"}';
    $headers = array(
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0',
        'Authorization: pk_031a1e51-798f-487e-a659-b1f92036066c'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.checkout.com/tokens");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $rows = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode($rows, true);
    foreach($resp as $key => $value){
//        echo $key . " | ".$value."<br>";
        $remove = array(
            'token',
            'expiry_month',
            'expiry_year'
        );
        if (!in_array($key, $remove)){
            template(ucfirst($key), $value, $bin);
        }
        
        
    }
    // header('location: http://127.0.0.1/Bin/index.html');
    // $json_response = json_encode($resp);
    
}

?>
