<?php
    unset($_SESSION["cert_numb"]);
    
    function passwordCheck($_str)
    {
        $pw = $_str;
        $num = preg_match('/[0-9]/u', $pw);
        $eng = preg_match('/[a-z]/u', $pw);
        $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);
     
        $checks = [
            (strlen($pw) < 10 || strlen($pw) > 12), //자릿수
            (preg_match("/\s/u", $pw) != 0), //공백
            ( $num == 0 || $eng == 0) //숫자영문 포함
        ];
        
        $res = true;

        foreach($checks as $check) {
            if($check) $res = false;
        }
     
        return [$res];
    }
    $certi = true;
    //유효성 검사
    $checks = [
        "id"=>(isset($_POST["id"]) && (validate_username($_POST["id"]) || strlen($_POST["id"]) > 2)),
        "pw"=>(isset($_POST["pw"]) && passwordCheck($_POST["pw"])[0]),
        "pw_"=>(isset($_POST["pw"]) && ($_POST["pw_"] == $_POST["pw"])),
        "tel"=>(isset($_POST["tel-1"]) && (strlen($_POST["tel-2"]) > 2) && (strlen($_POST["tel-3"]) > 2) && (strlen($_POST["tel-2"]) < 5) && (strlen($_POST["tel-3"]) < 5)),
        "age"=>(isset($_SESSION["age"])),
        "address"=>(isset($_POST["address"])) && strlen(trim($_POST["address"])) > 5
    ];
    foreach($checks as $check) {
        if($check == false) $certi = false;
    }
    if($certi) {
        $user = wp_create_user(
            $_POST["id"],
            $_POST["pw"],
            $_SESSION["email"]
        );
        // var_dump($user);
        if(!isset($user->errors)) {
            $user_id = "user_".$user;
            update_field("birth",$_SESSION["birth"],$user_id);
            update_field("address",$_POST["address"],$user_id);
            update_field("age",$_SESSION["age"],$user_id);
            update_field("tel",$_POST["tel-1"]."-".$_POST["tel-2"]."-".$_POST["tel-3"],$user_id);
            update_user_meta( $user, 'nickname', $_SESSION["user_name"] );
            wp_update_user([
                "ID"=>$user,
                "display_name"=>$_SESSION["user_name"]
            ]);
            $_SESSION["id"] = $_POST["id"];
            wp_redirect(getPage("account-create-result")->permalink);
            exit;
        } else {
    
            // var_dump($user->errors);
            
        }
    }
    
    // var_dump($checks);
?>