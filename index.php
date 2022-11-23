<?php
    $file = fopen("password.txt", "r") or die("Hiányzik az adatállomány");
    $teljesfile=fread($file, filesize("password.txt")); 
    fclose($file);

    $keynumbers=[5,-14,31,-9,3];
    $decoded=[];
    $number = 0;
    for ($i = 0; $i<strlen($teljesfile); $i++){
        if(ord($teljesfile[$i])==10){
            $number=0;
            array_push($decoded," ");
        }
        else{
            array_push($decoded,chr(ord($teljesfile[$i])-$keynumbers[$number]));
            if ($number<4){
                $number++;
            }
            else{
                $number = 0;
            }
        }
    }
    $logins = explode(" ", join($decoded));

    $logs = [];
    foreach ($logins as $a){
        array_push($logs,explode("*",$a));
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  </head>
  <body style="height: 100%">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <form method="POST" action="#" class="w-100">
            <div class="w-25 p-4 mx-auto text-center rounded" style="background-color: gray;">
                <input type="text" class="form-control mb-3" name="username" placeholder="Username">
                <input type="text" class="form-control" name="password" placeholder="Password">
                <input type="submit" name="submit" class="btn btn-dark mt-4" >
            </div>
            <?php
                if(isset($_POST["submit"])) {
                    $user=$_POST['username'];
                    $pass=$_POST['password'];
                
                    $isuservalid = false;
                    foreach($logs as $x){
                        if ($user == $x[0] && $pass == $x[1]){

                            $conn = new mysqli("localhost", "root", "", "database");
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql = "select titkos from tabla where username='".$user."';";
                            $result = $conn->query($sql);
                            $eredmely = mysqli_fetch_row($result);
                            $color = "";
                            switch ($eredmely[0]){
                                case "piros":
                                    $color = "red";
                                    break;
                                case "kek":
                                    $color = "glue";
                                    break;                        
                                case "zold":
                                    $color = "green";
                                    break;
                                case "sarga":
                                    $color = "yellow";
                                    break;
                                case "fekete":
                                    $color = "black";
                                    break;
                                case "feher":
                                    $color = "white";
                                    break;
                            }
                            echo '<div class="text-center w-25 mx-auto mt-3 rounded" style="background-color:gray;"><span style="font-size: x-large; color: '.$color.';">'.$eredmely[0].'</span></div>';
                            exit();
                        }
                        elseif($user == $x[0] && $pass != $x[1]){
                            $isuservalid = true;
                        }
                    }
                    if($isuservalid){
                        sleep(3);
                        ob_start();
                        $url = 'https://www.police.hu';
                        while (ob_get_status()) 
                        {
                            ob_end_clean();
                        }
                        header( "Location: $url" );
                    }
                    else{
                        echo '<div class="text-center w-25 mx-auto mt-3 rounded" style="background-color:gray;"><span style="font-size: x-large;">Nincs ilyen felhasználó!</span></div>';
                    }
                }
            ?>
        </form>
    </div>
  </body>
</html>
