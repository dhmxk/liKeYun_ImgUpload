<?php

    // 编码
    header("Content-type:application/json");
     
    // 获取文件
    $file = $_FILES["file"]["name"];
     
    // 获取文件后缀名
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    
    // 上传目录
    $uploadDirectory = 'upload';
    
    // 重命名
    $newfile = uniqid() . '.' . $extension;
     
    // 允许上传的文件
    $fileType = $_FILES["file"]["type"];
    $allowedTypes = ["image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png"];
    
    if (in_array($fileType, $allowedTypes)) {
        
        // 允许上传的文件大小（10MB）
        if($_FILES["file"]["size"] <= 10485760) {
            
            // 可以上传
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDirectory. "/" . $newfile);
            
            // 当前文件在服务器的路径
            // $filepath = realpath(dirname(__FILE__)) . "/" . $uploadDirectory . "/" . $newfile;
            
            // 获取http协议类型
            $HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            
            $result = array(
                "code" => 200,
                "msg" => "上传成功！",
                "path" => $HTTP_TYPE . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['REQUEST_URI'])) . '/api/' . $uploadDirectory . "/" . $newfile
            );
            
            // // 验证是否为合法的URL
            // if(filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                
            //     // 上传结果
            //     $result = array(
            //         "code" => 200,
            //         "msg" => "上传成功！",
            //         "path" => $imgUrl
            //     );
            // }else {
                
            //     // 上传结果
            //     $result = array(
            //         "code" => 201,
            //         "msg" => "图片上传失败！可能是登录失效，可前往Url：https://dev.360.cn/mod3/developer/index/submittype/1 登录后再试！"
            //     );
            // }
            
            // 删除临时文件
            // unlink($filepath);
            
        }else {
            
            // 文件大小超出限制
            $result = array(
                "code" => 201,
                "msg" => "文件大小超出限制！最大只能上传10MB的文件！"
            );
        }
    } else {
        
        // 文件类型无效
        $result = array(
            "code" => 201,
            "msg" => "只允许上传gif、jpeg、jpg、png格式的图片文件！"
        );
    }
    
    // 输出JSON
    echo json_encode($result, true);
    
?>
