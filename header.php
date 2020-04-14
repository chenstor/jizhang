<?php
header("Content-Type: text/html;charset=utf-8");
include("data/config.php");
include("inc/function.php");
// 登录状态判断
loginchk($userid);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo SiteNameTitle;?></title>
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo SiteURL;?>/img/apple-touch-icon.png"><link rel="icon" type="image/png" sizes="32x32" href="<?php echo SiteURL;?>/img/favicon-32x32.png"><link rel="icon" type="image/png" sizes="16x16" href="<?php echo SiteURL;?>/img/favicon-16x16.png">
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/itlu.style.css?<?php echo $version;?>" />
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/all.js?<?php echo $version;?>"></script>
</head>

<body><div id="itlu-wrap">
        <div class="table-responsive">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#example-navbar-collapse">
                            <span class="sr-only">导航</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="add.php">记一笔</a>
                    </div>
                    <div class="collapse navbar-collapse" id="example-navbar-collapse">
                        <ul class="nav navbar-nav">
							<?php echo sys_menu($userinfo['isadmin'],$userinfo['role_id'],$userid,get_now_filename());?>						
                            <li><a href="login.php?action=loginout"><?php echo $userinfo['username'];?>|退出</a></li>
                        </ul>
                    </div>
                </div>
            </nav>