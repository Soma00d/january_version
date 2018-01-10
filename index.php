<!DOCTYPE html>
<html>
    <head>
        <title>Test Bench User interface</title>
        <link rel="stylesheet" type="text/css" href="css/application.css">
        <script src="lib/jquery-3.1.1.min.js"></script>
        <script src="lib/jquery-ui.js"></script>
        
    </head>
    <body>
        <div class="overlay_choice hidden">
            <div class="content_overlay">
                <div class="title">Have you already implemented Calibration Firmware ?</div>
                <div class="bouton_grey go_calib">Yes</div>
                <div class="bouton_grey go_download">No</div>
            </div>
        </div>
        <div id="header">
            <?php include('template/global/header.html'); ?>
        </div>
        
        <div id="main_container">
            <div id="content_role" class="page_content active">
                <?php include('template/global/role.html'); ?>
            </div>
            <div id="content_login" class="page_content">
                <?php include('template/global/login.html'); ?>
            </div>
            
            <!-- REPAIR MODE ---------------------------------->
            <div id="content_home" class="page_content">
                <?php include('template/repair/homepage.html'); ?>
            </div>
            <div id="content_hometest" class="page_content">
                <?php include('template/repair/hometest.html'); ?>
            </div>
            <div id="content_pretest" class="page_content">
                <?php include('template/repair/pretest.html'); ?>
            </div>
            <div id="content_finaltest" class="page_content ">
                <?php include('template/repair/finaltest.html'); ?>
            </div>
            <div id="content_calibration" class="page_content">
                <?php include('template/repair/calibration.html'); ?>
            </div>
            <div id="content_print" class="page_content">
                <?php include('template/repair/print.html'); ?>                
            </div>
            <div id="content_download" class="page_content">
                <?php include('template/repair/download.html'); ?>                
            </div>
            <div id="content_testfwdownload" class="page_content">
                <?php include('template/repair/testfwdownload.html'); ?>                
            </div>
            <div id="content_testfwdownloadfinal" class="page_content">
                <?php include('template/repair/testfwdownloadfinal.html'); ?>                
            </div>
            <div id="content_fctfwdownloadfinal" class="page_content">
                <?php include('template/repair/fctfwdownloadfinal.html'); ?>                
            </div>
            <div id="content_history" class="page_content">
                <?php include('template/repair/history.html'); ?>                
            </div>
            <!------------------------------------------------->
            
            <!-- ENGINEERING MODE ---------------------------------->
            <div id="content_homeE" class="page_content">
                <?php include('template/engineering/homepage.html'); ?>
            </div>
            <div id="content_toolbox" class="page_content">
                <?php include('template/engineering/toolbox.html'); ?>
            </div>
            
            <!------------------------------------------------->
        </div>
        
        <div id="footer">
            <?php include('template/global/footer.html'); ?>
        </div>
                
        <script src="js/function.js"></script>
        <script src="js/integration.js"></script>
    </body>
</html>