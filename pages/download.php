<?php
session_start();
require_once '../common.php';
$tab_type = 'download';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>DGA - Download</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
    </head>
    <body>
        <div class="header">
            <div id="hdl">
                <font size="6px">Disease and Gene Annotations</font>
            </div>
            <?php require_once (ABSPATH . 'pages/header.php'); ?>
        </div>
        <div id="tabs-download" style="text-align: center; padding-bottom: 29%;margin-top: 25px;">
            
            <form name="exportmappingresultall" action="../ajax/Download.ajax.php" method="POST">
                <input name="exportType" type="hidden" value="all" />
                <font size="3px">You can download all mapping result as a .obo file. Please press the button.</font><input name="exportall" type="submit" value="Download" style="font-size: 12px;" />
            </form>
            <br/>
            
            <form name="exportmappingresultids" action="../ajax/Download.ajax.php" method="POST">
                <input name="exportType" type="hidden" value="ids" />
                <font size="3px">Download the mapped IDs(including DOID, Gene ID and  PubMed ID) result.</font><input name="exportids" type="submit" value="Download" style="font-size: 12px;" />
            </form>
        </div>
        <?php require_once (ABSPATH . 'pages/footer.php'); ?>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/doa.js"></script>
        <script type="text/javascript">
            $(function(){
                setActiveTab('<?php echo $tab_type; ?>');
            });
        </script>
    </body>
</html>
