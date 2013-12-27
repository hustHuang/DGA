<?php
session_start();
require_once '../common.php';
$tab_type = 'tutorials';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>DGA -Tutorials</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
    </head>
    <body>
        <div class="header">
            <div id="hdl">
                <font size="6px">Disease and Gene Annotations</font>
            </div>
            <?php require_once (ABSPATH . 'pages/header.php'); ?>
        </div>
        <div id="tutorial_box" style="height:auto;background-color:#FFF;padding-top: 25px">
            <div class="tutorial_step">
                <b>Getting started</b><br/>
                <p>This tutorial with text descriptions and screenshots guides you how to use Disease and Gene Annotations through searching two diseases: "Primary Breast Cancer" and "Chronic Leukemia".To ensure some parts of the system work well,you need to install the latest version of flash player.We also recommend you to view this site in Firefox for better visual effects.</p>
            </div>
            <div style="margin: 15px auto;text-align: center;">
                <iframe width="650" height="485" src="http://www.youtube.com/embed/XxzIzwJ0-lo" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="tutorial_step">
                <b>Two options to search</b><br/>
                <p>There are two options to search,the one highlighted by red frame in this screenshot is to search genes by inputting single/multiple disease names;the one highlighted by blue frame is to search diseases by inputting single/multiple genes.</p>
                <p>User can get hint by moving mouse to a magnifier icon in front of text field.</p>
                <img class="" src="../inc/image/Tutorials/1.png "/>
            </div>
            <div class="tutorial_step">
                <b>Input suggestion box</b><br/>
                <p class="">Both text fields provide suggestion box function. Users can receive hints when inputting letters. For example,  we try to input "primary breast cancer" here. Inputting letters "breast can" will give text suggestions.</p>
                <img class="" src="../inc/image/Tutorials/autocompletion.png"/>
            </div>
            <div class="tutorial_step">
                <b>Multiple input words</b><br/>
                <p class="">HDOA allows to search multiple disease/gene names. So, we then input letters "leuk". It will suggest diseases which contain letters "leuk". We choose "chronic leukemia" (CML) in our example.</p>
                <img class="" src="../inc/image/Tutorials/autocompletion M.png "/>
            </div>
            <div class="tutorial_step">
                <b>Search results</b><br/>
                <p class="">Clicking "To Gene" button, now we are going to result which shows genes related to "primary breast cancer" and "chronic leukemia".</p> 
                <p>The result is in default shown in a table view where each line shows a  <a target="_blank" href="http://www.ncbi.nlm.nih.gov/projects/GeneRIF/GeneRIFhelp.html">GeneRIF</a> evidence about certain gene associated to one of input diseases. </p>
                <p>For example, the first gene here is "ABL1" which is involved in CML documented by an article (Pubmed ID <a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/12149456">12149456</a>)</p>
                <p>In the left panel, the input diseases will be highlighted in red colors in a Disease Ontology Tree.</p>
                <img class="" src="../inc/image/Tutorials/tableveiw results.png "/>
            </div>
            <div class="tutorial_step">
                <b> Re-search and download results</b><br/>
                <p class="">If a user can re-search HDOA, select DO tree terms and click right mouse button. The "Search All Select Terms" button will be shown. Click this button, then a user re-search genes related to these diseases. </p>
                <p class="">To save the search results, a user just needs to go to the bottom of result table and click the "download the search result" button.</p>
                <img class="" src="../inc/image/Tutorials/re-search and download.png "/>
            </div>
            <div class="tutorial_step">
                <b>View interactions between genes</b><br/>
                <p class="">A user can also investigate the interactions between these resulting disease-related genes by click "Table View"->"Gene-Gene" tab.</p>
                <img class="" src="../inc/image/Tutorials/genetable1.png "/>
            </div>
            <div class="tutorial_step">
                <b>Interactions of the genes</b><br/>
                <p class="">The gene-gene table shows the multiple types of interactions of the genes. A user can sort the table by clicking the column tab. </p>
                <img class="" src="../inc/image/Tutorials/genetable2.png "/>
            </div>
            <div class="tutorial_step">
                <b>Review disease detail</b><br/>
                <p class="">In this tab, a user can review detail information about the disease documented by Disease Ontology. A user need to click the small arrow to see the content.</p>
                <img class="" src="../inc/image/Tutorials/disease details.png "/>
            </div>
            <div class="tutorial_step">
                <b>Review networkview</b><br/>
                <p class="">Clicking "networkview" tab, a user can see the search results in network visualization. The network can be re-layout and export in various modes and formats. A user can also choose to see specific gene-gene interaction types.</p>
                <p>Clicking node/edge in the network, a user can see details for node/edge</p>
                <img class="" src="../inc/image/Tutorials/networkview.png"/>
            </div>
            <div class="tutorial_step">
                <b>Interaction between networkview and ontology tree</b><br/>
                <p class="">The network is interactive to Disease Ontology tree. Clicking any disease term in the Disease Ontology tree, the genes related to the disease term will be highlighted.</p>
                <img class="" src="../inc/image/Tutorials/interactive networkview.png"/>
            </div>
            <div class="tutorial_step">
                <b>Show more results in networkview</b><br/>
                <p class="">In default, HDOA only show 20 gene nodes in the network view for sake of clear visualization.  A user can also choose to show more nodes and resize the network by setting the slide bar and clicking "GO" button.</p>
                <img class="" src="../inc/image/Tutorials/load more nodes.png"/>
            </div>
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
