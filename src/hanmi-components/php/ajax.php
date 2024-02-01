<?php
	function wp_ajax($function,$priv=false) {
		$out = "wp_ajax_${function}";
		add_action( $out, $function);
		if(!$priv) {
            $out = "wp_ajax_nopriv_${function}";
		    add_action( $out, $function);
        }
	}
	function wpse27856_set_content_type(){
        return "text/html";
    }
    //functions 
	function createEmailCert() {
		session_start();
        $_SESSION["email"] = $_POST["email"];
		$_SESSION["cert_numb"] = rand(100000,999999);
        if(get_user_by_email($_SESSION["email"])) {
            wp_send_json([
                "data"=>"<span class='alert'>이미 등록된 이메일 입니다.</span>"
            ]);
            exit;
        }
        ob_start();
        ?>
        <div>
            <h2>뮤지엄한미 이메일 인증</h2>
            <p>요청하신 이메일 인증번호 입니다.</p>
            <br/>
            <p>
            <b>
                인증번호
            </b>
            </p>
            <h1><?= $_SESSION["cert_numb"] ?></h1>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );
        $mailto = wp_mail($_SESSION["email"], "[뮤지엄한미] 이메일 인증번호", $html, "From : 뮤지엄한미 <noreply@museumhanmi.or.kr>");
        if($mailto) {
            $args = [
                "data"=>"인증번호를 발송했습니다.",
                "mail"=>$_SESSION["email"]
            ];
            if(0 != strpos($_SERVER["HTTP_HOST"],".local")) $args["test"] = $_SESSION["cert_numb"];
            wp_send_json($args);
        } else {
            wp_send_json([
                "data"=>"<span class='alert'>올바른 이메일 주소를 입력해주세요.</span>"
            ]);
        }
	}
	wp_ajax("createEmailCert");
	
	function getNumber() {
		session_start();
		wp_send_json($_SESSION["cert_numb"] ?? "0");
	}
	wp_ajax("getNumber");
    function searchID() {
        $id = $_POST["id"] ?? "";
        $msg = "";
        if(username_exists($id)) {
            $msg = "<span class='alert'>이미 존재하는 아이디 입니다.</span>";
        } else {
            if(validate_username($id) && strlen($id) > 2)
                $msg = "계정을 생성할 수 있습니다.";
            else
                $msg = "<span class='alert'>아이디를 정확히 입력해주세요.(3글자 이상)</span>";
        }
        wp_send_json(["data"=>$msg]);
    }
    wp_ajax("searchID");
?>