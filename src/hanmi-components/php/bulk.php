<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
    $csv = [];
        //이미지링크 임포트 구문
        function uploadRemoteImageAndAttach($image_url, $parent_id) {
    
            $image = $image_url;
            $get = wp_remote_get( $image );
            var_dump($get);
            $type = wp_remote_retrieve_header( $get, 'content-type' );
        
            if (!$type)
                return false;
        
            $mirror = wp_upload_bits( basename( $image ), '', wp_remote_retrieve_body( $get ) );
            $attachment = array(
                'post_title'=> basename( $image ),
                'post_mime_type' => $type
            );
        
            $attach_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );
        
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        
            $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            return $attach_id;
        }
        
        // check there are no errors
        if(($_FILES['csv']['error'] ?? NULL) == 0 && ($_POST["ptype"] ?? false)){
            $name = $_FILES['csv']['name'];
            $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
            $type = $_FILES['csv']['type'];
            $tmpName = $_FILES['csv']['tmp_name'];
        
            // check the file is a csv
            if($ext === 'csv'){
                if(($handle = fopen($tmpName, 'r')) !== FALSE) {
                    // necessary if a large csv file
                    set_time_limit(0);
        
                    $row = 0;
    
                    //1. 둘째줄 필드명들 어레이로 받아오기
                    $fields = [];
                    for($i=0;$i<2;$i++) {
                        $fields = fgetcsv($handle, 1000, ',');
                    }    
                    while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        // number of fields in the csv
                        $col_count = count($data);
                        // 2. 캐치한 필드 어레이 통하여 값넣기
                        foreach($fields as $idx=>$field) {
                            // 3. 특수한 필드들은 따로 지정
                            switch($field) {
                                // case "thumb":
                                //     $csv[$row][$field] = ;
                                // break;
                                default:
                                $csv[$row][$field] = $data[$idx];
                            }
                        }
        
                        // inc the row
                        $row++;
                    }
    
                    fclose($handle);
                    foreach($csv as $idx=>$data) {
                        $ptype = $_POST["ptype"];
                        $pname = "${ptype}_${idx}";
                        $post = false;
                        $arg = [
                            "post_type"=>$ptype,
                            "name"=>$pname,
                        ];
                        $posts = get_posts($arg);

                        if(empty($data["post_title"])) $data["post_title"] = "무제-$idx";

                        if(!empty($data["post_title"])) {
                            if(empty($posts)) {//if new
                                $post = wp_insert_post([
                                    "post_title"=>$data["post_title"],
                                    "post_type"=>$ptype,
                                    "post_name"=>$pname,
                                    "post_status"=>"publish"
                                ]);
                                $post = get_post($post);
                            } else {
                                $post = $posts[0];
                            }
        
                            foreach($data as $field=>$value) {
                                if(in_array($field,["thumb"])) {
                                    if($img = get_field($field,$post->ID)) {
                                        wp_delete_attachment($img['id'], true);
                                    }
                                    $value = media_sideload_image($value, $post->ID, "" , "id");
                                }
                                if(in_array($field,["imgs"])) {
                                    $values = explode(",",$value);
                                    if(empty($values)) $values = [$data["thumb"] ?? null];
                                    $out = [];
                                    if($imgs = get_field($field,$post->ID)) {
                                        foreach($imgs as $img) {
                                            wp_delete_attachment($img['id'], true);
                                        }
                                    }
                                    foreach($values as $val) {
                                        $img = media_sideload_image($val, $post->ID, "" , "id");
                                        $out[] = $img;
                                    }
                                    $value = $out;
                                }
                                if(in_array($field,["meta"])) {
                                    $values = explode("\n",$value);
                                    $out = [];
                                    for($i=0;$i < count($values);$i+=2) {
                                        $out[] = [
                                            "title"=>($values[$i] ?? ""),
                                            "desc"=>($values[$i+1] ?? "")
                                        ];
                                    }
                                    $value = $out;
                                }
                                if(in_array($field,["publish_category","category"])) {
                                    $term = get_term_by("slug",$value,$field);
                                    wp_set_post_terms($post->ID, [$term->term_id], $field, false);
                                } else {
                                    if(!empty($value))
                                        update_field($field,$value,$post);
                                }
                            }
                        }
    
                    ?>
                    "<?= $idx ?>.<?= $post->post_title ?>" 업로드 혹은 업데이트 되었습니다.<br/>
                    <?php
                    }
                }
            }
        }
?>