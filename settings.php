<?php

error_reporting (E_ALL ^ E_NOTICE);

try {
    
    $config = parse_ini_file('settings.ini.php', true);

} catch(Exception $e) {
    
    die('<b>Unable to read settings.ini.php. Did you rename it from example.ini.php?</b><br><br>Error message: ' .$e->getMessage());

}

// $html = "https://github.com/causefx/iDashboard-PHP/releases/latest";

$html = "";
    
$doc = new DOMDocument();

libxml_use_internal_errors(true);

$doc->loadHTMLFile($html);

libxml_use_internal_errors(false);

$xpath = new DomXpath($doc);

$githubChanges = $xpath->query('//div[@class="markdown-body"]//p')->item(0)->nodeValue;

$githubVersion = $xpath->query('//span[@class="css-truncate-target"]')->item(0)->nodeValue;

if(!isset($githubVersion)){

    $githubVersion = "null";

}

if(!isset($githubChanges)){

    $githubChanges = "null";

}

$currentVersion = "1.082";

foreach ($config as $keyname => $section) {
    
    if(($keyname == "general")) {
        
        $cookiepass = $section["password"];
        
        $backgroundColor = $section["tabcolor"];
    
    }
    
}

// if($currentVersion == $githubVersion){
    
//     $alertColor = "info";
//     $alertTitle = "Up-To-Date!";
//     $alertText = "Nice, no worries here, you're on the current version :)";
    
// }elseif($githubVersion == "null"){
    
//     $alertColor = "warning";
 //    $alertTitle = "Can't Get GitHub Version!";
 //    $alertText = "The Dashboard Failed to check for update! We need to have php-xml enabled.";
    
// }elseif($currentVersion < $githubVersion){
    
//     $alertColor = "success";
//     $alertTitle = "New Version Is Out!";
//     $alertText = "Looks like it's time to update the dashboard. <a class=\"btn btn-success btn-sm\" href=\"https://github.com/causefx/iDashboard-PHP/archive/master.zip\" role=\"button\">Download Now</a> <button type=\"button\" class// =\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\"#changes\">View Changes</button>";
    
// }

if($_COOKIE["logged"] !== $cookiepass){
    
    echo "<!DOCTYPE html>";
    echo "<head>";
    echo "<title>Form submitted</title>";
    echo "<script type='text/javascript'>window.location.replace('setup.php');</script>";
    echo "</head>";
    echo "<body></body></html>";
    die;

}

if(isset($_GET["action"])){$action = $_GET["action"];}

if($action == "logout"){
    
    if (isset($_COOKIE['logged'])) {
        
        unset($_COOKIE['logged']);
        setcookie('logged', '', time() - 3600, '/');
        
    }
    
    sleep(.5);
    echo "<!DOCTYPE html>";
    echo "<head>";
    echo "<title>Logout</title>";
    echo "<script type='text/javascript'>window.parent.location.reload();</script>";
    echo "</head>";
    echo "<body></body></html>";
    
}

if($action == "dontbugme"){

    setcookie("dontbugme", "true", time() + (86400 * 365), "/");
    sleep(.5);
    echo "<!DOCTYPE html>";
    echo "<head>";
    echo "<title>Dont bug me scrub</title>";
    echo "<script type='text/javascript'>window.location.replace('settings.php');</script>";
    echo "</head>";
    echo "<body></body></html>";
    
}

function write_ini_file($content, $path) { 

    if (!$handle = fopen($path, 'w')) {
        
        
        return false; 
    }

    $success = fwrite($handle, $content);
    fclose($handle); 
    return $success; 

}

$configfile = 'settings.ini.php';

if(array_key_exists('category-0', $_POST) == true){
    
    foreach ($config as $keyname => $section) { if(($keyname == "general")) { $nopass = $section["password"]; } }
    if(empty($_POST["password-0"])){ $_POST["password-0"] = $nopass;}
    if(strlen($_POST["password-0"]) < 50){ $_POST["password-0"] = password_hash($_POST["password-0"], PASSWORD_DEFAULT); }     
    
    setcookie("logged", $_POST["password-0"], time() + (86400 * 7), "/");
    
    $sampleData .= '; <?php die("Access denied"); ?>' . "\r\n";
    
    $getGroup = 0;

    foreach ($_POST as $parameter => $value) {
        
        $splitParameter = explode('-', $parameter);
        
        if ($value == "on")
            $value = "true";

        if($splitParameter[0] == "group"){
            
            if($value > $getGroup){
                
                $getGroup++;
                
            }
            
        }
        
        if($splitParameter[0] == "category"){
            
            $sampleData .= "[" . $value . "]\r\n";
        
        }else{
            
            $sampleData .= $splitParameter[0] . " = \"" . $value . "\"\r\n";
        
        }

    }
    
    //$sampleData .= "[groups]\r\n";
    
    $sampleData .= "groups = \"" . $getGroup . "\"\r\n";

    if($action == "write"){
        
        write_ini_file($sampleData, $configfile);
        sleep(.5);
        echo "<!DOCTYPE html>";
        echo "<head>";
        echo "<title>Form submitted</title>";
        echo "<script type='text/javascript'>window.parent.location.reload();</script>";
        echo "</head>";
        echo "<body></body></html>";
        
    }

}
                                                
?>

<!doctype html>
<html>

    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="mobile-web-app-capable" content="yes" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="msapplication-tap-highlight" content="no" />
        <link rel="shortcut icon" href="favicon.ico" type="image/ico"/>
        <link rel='stylesheet prefetch' href='off/bootstrap332.min.css'>
        <link rel='stylesheet prefetch' href='off/font-awesome.min.css'>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css"/>
        <!-- Bootstrap-Iconpicker -->
        <link rel="stylesheet" href="css/bootstrap-iconpicker.min.css"/>
        <!--test-->
        <link href="off/bootstrap336.min.css" rel="stylesheet">
        <link href="css/bootstrap-colorpicker.min.css" rel="stylesheet">
        <!--end test-->
        
        <style>
            
            body {
                margin: 10px;
                background-color: <?=$backgroundColor;?>;
            }
            
            .well {
                background-color: white;
            }

            .fa {
                min-width: 14px;
            }

            .form-inline .form-control,
            .form-inline .btn {
                margin-bottom: 8px;
            }

            .form-group {
                width: 100%;
            }

            input[type=checkbox].css-checkbox {
                position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;
            }

            input[type=checkbox].css-checkbox + label.css-label {
                padding-left:55px;
                height:30px; 
                display:inline-block;
                line-height:30px;
                background-repeat:no-repeat;
                background-position: 0 0;
                font-size:14px;
                vertical-align:middle;
                cursor:pointer;
            }

            input[type=checkbox].css-checkbox:checked + label.css-label {
                background-position: 0 -30px;
            }
            label.css-label {
                background-image:url(img/check.png);
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            hr {
                display: block;
                height: 1px;
                border: 0;
                border-top: 1px solid #ccc;
                margin: 1em 0 auto;
                padding: 0;
            }
            
        </style>
        
    </head>
        
    <body>
        
        <div class="alert alert-<?=$alertColor;?> alert-dismissible fade in" role="alert">
            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        
                <span aria-hidden="true">&times;</span>
        
            </button>
        
            <strong><?=$alertTitle;?></strong> <?=$alertText;?>
        
        </div>

        <form action="?action=write" method="post" name="adminForm" class="form-inline">

            <?php if($_COOKIE["dontbugme"] !== "true"){
    
                echo "<div class=\"alert alert-warning\">";
                echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                echo "<strong>Tip!</strong> Choose an option from below to change some settings. <strong><a href=\"?action=dontbugme\">Dont Remind Me Again!</a></strong></div>";
            
            }?>
            
            <div class="btn-group btn-group-justified">
                
                <div class="btn-group"><button data-toggle="collapse" data-target="#general" type="button" class="btn btn-primary">General</button></div>
                <div class="btn-group"><button data-toggle="collapse" data-target="#color" type="button" class="btn btn-primary">Colors</button></div>
                <div class="btn-group"><button data-toggle="collapse" data-target="#tabs" type="button" class="btn btn-primary">Tabs</button></div>
                <div class="btn-group"><a href="?action=logout" class="btn btn-warning" role="button">Logout</a></div>
                <div class="btn-group"><button type="submit" class="btn btn-success">Save</button></div>
                
            </div>
            
            <?php foreach ($config as $keyname => $section) {
            
                if(($keyname == "general")) {
                    
                    echo "<div id=\"general\" class=\"collapse\">";
                    echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px;p adding-top: 10px; margin-bottom: 5px; background-color: $versionColor\"><span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-server\"></span></span> Current Version:[<strong>$currentVersion</strong>] - GitHub Version: [<strong>" . $githubVersion . "</strong>]</div>  <br>";
                    echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px; padding-top: 10px; margin-bottom: 5px;\">";
                    echo "<input type=\"hidden\" name=\"category-0\" class=\"form-control\" value=\"general\">";
                    echo "<input type=\"hidden\" name=\"version-0\" class=\"form-control\" value=\"$currentVersion\">";
                    echo "<span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-cog\"></span></span> ";
                    echo "<div style=\"margin-bottom: 8px\" class=\"input-group\"><div class=\"input-group-addon\">Title</div>";
                    echo "<input style=\"margin-bottom: 0px\" type=\"text\" name=\"title-0\" class=\"form-control\" value=\"" . $section["title"] ."\"></div> ";
                    echo "<div style=\"margin-bottom: 8px\" class=\"input-group\"><div class=\"input-group-addon\">Password</div>";
                    echo "<input style=\"margin-bottom: 0px\" type=\"password\" name=\"password-0\" class=\"form-control\" placeholder=\"Leave Blank if no change\" value=\"\"></div> ";
                    if($section['useicons'] == "true"){echo "<input type=\"checkbox\" name=\"useicons-0\" id=\"useicons-0\" class=\"css-checkbox\" checked> ";}else {echo "<input type=\"checkbox\" name=\"useicons-0\" id=\"useicons-0\" class=\"css-checkbox\"> ";}
                    echo "<label for=\"useicons-0\" class=\"css-label\">Icons</label>  ";
                    if($section['usemargins'] == "true"){echo "<input type=\"checkbox\" name=\"usemargins-0\" id=\"usemargins-0\" class=\"css-checkbox\" checked> ";}else {echo "<input type=\"checkbox\" name=\"usemargins-0\" id=\"usemargins-0\" class=\"css-checkbox\"> ";}
                    echo "<label for=\"usemargins-0\" class=\"css-label\">Margins</label>  </div></div>";
                    echo"<div id=\"color\" class=\"collapse\">";
                    echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px;p adding-top: 10px; margin-bottom: 5px;\">";
                    echo "<span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-eyedropper\"></span></span> ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"bg\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Bg&nbsp;</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"bgText\" name=\"bg-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["bg"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabborder\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab Border</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabborderText\" name=\"tabborder-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabborder"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabhighlight\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab Highlight</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabhighlightText\" name=\"tabhighlight-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabhighlight"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<br><span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-paint-brush\"></span></span> ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabcoloractive\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabcoloractiveText\" name=\"tabcoloractive-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabcoloractive"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"fontcoloractive\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Font Color</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"fontcoloractiveText\" name=\"fontcoloractive-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["fontcoloractive"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabshadowactive\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab &nbsp;Shadow</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabshadowactiveText\" name=\"tabshadowactive-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabshadowactive"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<br><span class=\"btn btn-inactive disabled\" type=\"button\"><span class=\"fa fa-paint-brush\"></span></span> ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabcolor\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabcolorText\" name=\"tabcolor-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabcolor"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div>  ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"fontcolor\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Font Color</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"fontcolorText\" name=\"fontcolor-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["fontcolor"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div> ";
                    echo "<div style=\"margin-bottom: 8px\" id=\"tabshadow\" class=\"input-group colorpicker-component\"><div class=\"input-group-addon\">Tab &nbsp;Shadow</div>";
                    echo "<input style=\"margin-bottom: 0px\" id=\"tabshadowText\" name=\"tabshadow-0\" type=\"text\" data-format=\"hex\" value=\"" . $section["tabshadow"] ."\" class=\"form-control\"/>";
                    echo "<span class=\"input-group-addon\"><i></i></span>";
                    echo "</div></div> ";
                    echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px;p adding-top: 10px; margin-bottom: 5px;\">";
                    echo "<span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-eye\"></span></span> ";
                    echo "<button onclick=\"defaultTheme()\" class=\"btn btn-default\">Default</button> ";
                    echo "<button onclick=\"plexTheme()\" class=\"btn btn-info\" style=\"background-color: #cc7c2a; border-color: #975c20;\">Plex</button> ";
                    echo "<button onclick=\"Theme1()\" class=\"btn btn-info\">Theme 1</button> ";
                    echo "<button onclick=\"Theme2()\" class=\"btn btn-success\">Theme 2</button> ";
                    echo "<button onclick=\"Theme3()\" class=\"btn btn-warning\">Theme 3</button> ";
                    echo "<button onclick=\"Theme4()\" class=\"btn btn-primary\">Theme 4</button> ";
                    echo "<button onclick=\"Theme5()\" class=\"btn btn-danger\">Theme 5</button> ";
                    echo "</div> </div>";
                    
                }
            
            }?>
            
            <div id="tabs" class="collapse">
                
                <div class="btn-group"><button data-toggle="collapse" data-target="#groupnames" type="button" class="btn btn-primary">Edit Group Names</button></div>
                
                <div id="tagsForm" class="sortable">

                    <?php $i = 0;
                    
                    foreach ($config as $keyname => $section) {

                        if(($keyname !== "general") && ($keyname !== "groups")) {
                            
                            if(!isset($section['group'])){
                                
                                $section['group'] = "1";
                            
                            }?>  

                            <div class="form-group clearfix well well-sm" style="padding-bottom: 0px; padding-top: 10px; margin-bottom: 5px;">
                                
                                <span class="btn btn-default move" type="button"><span class="fa fa-arrows"></span></span>
                                
                                <div style="margin-bottom: 8px" class="input-group">
                                    
                                    <div class="input-group-addon">Name</div>
                                    
                                    <input style="margin-bottom: 0px" type="text" name="category-<?=$i;?>" class="form-control" value="<?=$keyname;?>">
                                
                                </div>
                                
                                <div style="margin-bottom: 8px" class="input-group">
                                    
                                    <div class="input-group-addon">URL</div>
                                    
                                    <input style="margin-bottom: 0px" type="text" name="url-<?=$i;?>" class="form-control" placeholder="url" value="<?=$section['url']?>">
                                
                                </div>
                                
                                <div style="margin-bottom: 8px" class="input-group">
                                    
                                    <div class="input-group-addon">Group</div>
                                    
                                    <input style="margin-bottom: 0px; width: 35px" type="text" name="group-<?=$i;?>" class="form-control" placeholder="1" value="<?=$section['group']?>">
                                
                                </div>
                                
                                <button data-placement="left" data-cols="5" data-rows="5" class="btn btn-default" name="icon-<?=$i;?>" role="iconpicker" data-iconset="fontawesome" data-icon="<?=$section['icon']?>"></button>
                                
                                <?php if($section['default'] == "true"){echo '<input type="radio" name="default" checked>';}else {echo '<input type="radio" name="default">';}?> <label> Default</label>
                                
                                <?php if($section['enabled'] == "true"){echo '<input type="checkbox" name="enabled-' . $i .'" id="enabled-' . $keyname . '" class="css-checkbox" checked>';}else {echo '<input type="checkbox" name="enabled-' . $i .'" id="enabled-' . $keyname . '" class="css-checkbox">';}?>      
                                <label for="enabled-<?=$keyname;?>" class="css-label">Enabled</label> 
                                
                                <?php if($section['guest'] == "true"){echo '<input type="checkbox" name="guest-' . $i .'" id="guest-' . $keyname . '" class="css-checkbox" checked>';}else {echo '<input type="checkbox" name="guest-' . $i .'" id="guest-' . $keyname . '" class="css-checkbox">';}?>
                                <label for="guest-<?=$keyname;?>" class="css-label">Guest</label> &nbsp;&nbsp;
                                
                                <button style="float: right;" class="btn btn-danger deleteGroup" id="remScnt" type="button"><span class="fa fa-trash"></span></button>
                            
                            </div>

                        <?php }
                        
                        $i++;
                        
                    }?>

                </div>
                
                <div id="groupnames" class="collapse">
                    
                    <input type="hidden" name="category-x" class="form-control" value="groups">
                
                    <?php 
                    
                    $alphabet = range('A', 'Z');

                    //echo $alphabet[3]; // returns D
                    //echo array_search('D', $alphabet); // returns 3
                    
                    foreach ($config as $keyname => $section) {

                        if(($keyname == "groups")) {
                    
                            if($section['groups'] > count($section)){
                                
                                echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px; padding-top: 10px; margin-bottom: 5px;\">";                            
                                
                                foreach(range(1,$section['groups']) as $index) {
                                    
                                    echo "<span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-folder-open\"></span></span> ";
                                    
                                    echo "<div style=\"margin-bottom: 8px\" class=\"input-group\"><div class=\"input-group-addon\">Group-". $alphabet[$index - 1] . "</div>";
                                    
                                    echo "<input style=\"margin-bottom: 0px\" type=\"text\" name=\"group" . $alphabet[$index - 1] . "-xx\" class=\"form-control\" value=\"" . $section["title"] . "\"></div> <br>";

                                }
                                
                                echo "</div>";
                                
                            }else{
                                
                                echo "<div class=\"form-group clearfix well well-sm\" style=\"padding-bottom: 0px; padding-top: 10px; margin-bottom: 5px;\">";                            
                                
                                foreach(range(1,$section['groups']) as $index) {
                                    
                                    $groupLetter = $alphabet[$index - 1];
                                    
                                    echo "<span class=\"btn btn-inactive \" type=\"button\"><span class=\"fa fa-folder-open\"></span></span> ";
                                    
                                    echo "<div style=\"margin-bottom: 8px\" class=\"input-group\"><div class=\"input-group-addon\">Group-" . $index . "</div>";
                                    
                                    echo "<input style=\"margin-bottom: 0px\" type=\"text\" name=\"group" . $groupLetter . "-xx\" class=\"form-control\" value=\"" . $section["group$groupLetter"] . "\"></div> <br>";

                                }
                                
                                echo "</div>";
                                
                            }
                            
                        }
    
                    }?>
                
                </div>

                <div class="form-group clearfix">

                    <button class="btn btn-primary" id="addScnt" type="button"><span class="fa fa-plus"></span></button> 

                    <button class="btn btn-success" type="submit"> Save Settings </button>

                </div>
            
            </div>

        </form>
        
        <div class="modal fade" id="changes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            
            <div class="modal-dialog" role="document">
            
                <div class="modal-content">
                
                    <div class="modal-header">
                    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        
                            <span aria-hidden="true">×</span>
                        
                        </button>
                        
                        <h4 class="modal-title" id="myModalLabel">New Changes/Fixes</h4>
                        
                    </div>
                        
                    <div class="modal-body">
                    
                        <?=$githubChanges;?>
                    
                    </div>
                        
                    <div class="modal-footer">
                    
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>

        <script type="text/javascript" src="off/jquery-1.4.3.min.js"></script>

        <script>
            
            $(function () {
                
                $('#tabcoloractive').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#fontcoloractive').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#tabcolor').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#fontcolor').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#tabshadowactive').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#tabshadow').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#tabborder').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#bg').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script>
            
            $(function () {
                
                $('#tabhighlight').colorpicker({
                    
                    format: "hex",
                    align: "left",
                    colorSelectors: {
                        
                        '#777777': '#777777',
                        '#337ab7': '#337ab7',
                        '#5cb85c': '#5cb85c',
                        '#5bc0de': '#5bc0de',
                        '#f0ad4e': '#f0ad4e',
                        '#d9534f': '#d9534f'
                        
                    }
                    
                });
                
            });
            
        </script>
        
        <script type='text/javascript'>

            $(function() {
                
                var scntDiv = $('#tagsForm');
                var i = <?=$i?>;

                $('#addScnt').on('click', function() {
                    
                    $('<div class="form-group clearfix ui-sortable-handle well well-sm" style="padding-bottom: 0px; padding-top: 10px; margin-bottom: 5px;"> <span class="btn btn-default move" type="button"><span class="fa fa-arrows"></span></span> <div style="margin-bottom: 8px" class="input-group"><div class="input-group-addon">Name</div><input style="margin-bottom: 0px" name="category-' + i +'" class="form-control" placeholder="Tag" value="New Tab"></div> <div style="margin-bottom: 8px" class="input-group"><div class="input-group-addon">URL</div><input style="margin-bottom: 0px" type="text" name="url-' + i +'" class="form-control" placeholder="url" value="Add URL"></div> <div style="margin-bottom: 8px" class="input-group"><div class="input-group-addon">Group</div><input style="margin-bottom: 0px; width: 35px" type="text" name="group-' + i +'" class="form-control" placeholder="1" value="1"></div> <button data-placement="left" data-cols="5" data-rows="5" class="btn btn-default iconpicker" name="icon-' + i +'" role="iconpicker" data-iconset="fontawesome" data-icon="fa-question"><i class="fa fa-play-circle-o"></i><input type="hidden" name="icon-' + i +'" value="fa-play-circle-o"><span class="caret"></span></button> <input type="radio" name="default"> <label> Default</label><input type="checkbox" name="enabled-' + i +'" id="enabled-' + i +'" class="css-checkbox" checked> <label for="enabled-' + i +'" class="css-label">Enabled</label> <input type="checkbox" name="guest-' + i +'" id="guest-' + i +'" class="css-checkbox"> <label for="guest-' + i +'" class="css-label">Guest</label> <button style="float: right" class="btn btn-danger deleteGroup" id="remScnt" type="button"><span class="fa fa-trash"></span></button></div>').appendTo(scntDiv);
                    i++;    
                    return false;

                });

                $(document).on('click','#remScnt', function() {

                    $(this).closest('div').remove();
                    i--;

                    return false;
                    
                });

            });

        </script>
        
        <script>
            
            function defaultTheme() {
                
                document.getElementById("bgText").value = "#f2f2f2";
                document.getElementById("tabborderText").value = "#cecece";
                document.getElementById("tabhighlightText").value = "#f44343";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#f44343";
                document.getElementById("tabshadowactiveText").value = "#808080";
                document.getElementById("tabcolorText").value = "#ffffff";
                document.getElementById("fontcolorText").value = "#000000";
                document.getElementById("tabshadowText").value = "#c7c6c6";
                
            }
        </script>
        
        <script>
            
            function Theme1() {
                
                document.getElementById("bgText").value = "#d1d1d1";
                document.getElementById("tabborderText").value = "#ffffff";
                document.getElementById("tabhighlightText").value = "#5bc0de";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#777777";
                document.getElementById("tabcolorText").value = "#5bc0de";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#777777";
                
            }
        </script>
        
        <script>
            
            function Theme2() {
                
                document.getElementById("bgText").value = "#d1d1d1";
                document.getElementById("tabborderText").value = "#ffffff";
                document.getElementById("tabhighlightText").value = "#5cb85c";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#777777";
                document.getElementById("tabcolorText").value = "#5cb85c";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#777777";
                
            }
            
        </script>
        
        <script>
            
            function Theme3() {
                
                document.getElementById("bgText").value = "#d1d1d1";
                document.getElementById("tabborderText").value = "#ffffff";
                document.getElementById("tabhighlightText").value = "#f0ad4e";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#777777";
                document.getElementById("tabcolorText").value = "#f0ad4e";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#777777";
                
            }
            
        </script>
        
        <script>
            function Theme4() {
                
                document.getElementById("bgText").value = "#d1d1d1";
                document.getElementById("tabborderText").value = "#ffffff";
                document.getElementById("tabhighlightText").value = "#337ab7";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#777777";
                document.getElementById("tabcolorText").value = "#337ab7";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#777777";
                
            }
            
        </script>
        
        <script>
            
            function Theme5() {
                
                document.getElementById("bgText").value = "#d1d1d1";
                document.getElementById("tabborderText").value = "#ffffff";
                document.getElementById("tabhighlightText").value = "#d9534f";
                document.getElementById("tabcoloractiveText").value = "#ffffff";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#777777";
                document.getElementById("tabcolorText").value = "#d9534f";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#777777";
                
            }
            
        </script>
        
        <script>
            
            function plexTheme() {
                
                document.getElementById("bgText").value = "#8b8b8b";
                document.getElementById("tabborderText").value = "#1f1f1f";
                document.getElementById("tabhighlightText").value = "#cc7c2a";
                document.getElementById("tabcoloractiveText").value = "#f5bc32";
                document.getElementById("fontcoloractiveText").value = "#000000";
                document.getElementById("tabshadowactiveText").value = "#808080";
                document.getElementById("tabcolorText").value = "#1f1f1f";
                document.getElementById("fontcolorText").value = "#ffffff";
                document.getElementById("tabshadowText").value = "#c7c6c6";
                
            }
            
        </script>

        <script src='off/jquery213.min.js'></script>          

        <script src='off/jquery-ui1112.min.js'></script>

        <script>$( "#tagsForm" ).sortable({connectWith: ".sortable"});</script>

        <!-- jQuery -->
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

        <!-- Bootstrap -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- Bootstrap-Iconpicker Iconset for Font Awesome -->
        <script type="text/javascript" src="js/iconset-fontawesome-4.2.0.min.js"></script>

        <!-- Bootstrap-Iconpicker -->
        <script type="text/javascript" src="js/bootstrap-iconpicker.min.js"></script>
        
        <script src="off/jquery-2.2.2.min.js"></script>
        
        <script src="js/bootstrap-colorpicker.js"></script>

    </body>
    
</html>