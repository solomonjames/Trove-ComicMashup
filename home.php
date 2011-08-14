<?php

require_once dirname(__FILE__) . "/config/boot.php";

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.3.0/build/cssreset/reset-min.css">
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.3.0/build/cssbase/base-min.css">
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.3.0/build/cssfonts/fonts-min.css">
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.3.0/build/cssgrids/grids-min.css">
        <link href='http://fonts.googleapis.com/css?family=Lobster+Two:700italic' rel='stylesheet' type='text/css'>
        <link href='/css/main.css' rel='stylesheet' type='text/css'>
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
        
        <script type="text/javascript" src="/js/app.js"></script>
    </head>

    <body>
        <div id="wrapper" class="yui3-g">
            <div id="header" class="yui3-u-1">
                <h1 class="logo">Trovic</h1>
                <h3 class="slogan">"Let your trove, become your comic"</h3>
            </div>
            
            <div id="content">
                <img id="ajaxLoader" src="/ajax-loader.gif" style="display:none;"/>
                
                <div id="comic"></div>
                
                <button id="getRandom" onClick="return App.getRandom();" type="button">Random</button>
            </div>
        </div>
    </body>

</html>