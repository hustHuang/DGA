<?php
require_once '../common.php';
require_once (ABSPATH . 'class/Search.class.php');
$tab_type = 'search';
$search_type = $_REQUEST['st'];
$search_word = $_REQUEST['qn'];
if ($search_word == '' || is_null($search_word)){
    echo "No key word are input.";
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>DGA - Search Result</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/layout-default-latest.css" />
        <style type="text/css">
            /* neutralize pane formatting BEFORE loading UI Theme */
            .ui-layout-pane ,
            .ui-layout-content {
                background:	none;
                border:		0;
                padding:	0;
                overflow:	visible;
            }
        </style>
        
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/slider.css" />     
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/jquery.ui.all.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/jquery.ui.core.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/jquery.ui.slider.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/flexigrid.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/autocomplete.css"/>
        <style type="text/css">
            p				{ margin:		1em 0; }
            /* use !important to override UI theme styles */
            .grey		  { background:	#999 !important; }
            .outline		  { /*border:		1px dashed #F00 !important;*/ }
            .add-padding	  { padding:		10px !important; }
            .no-padding         { padding:		0px !important;}
            .add-scrollbar	  { overflow:		auto; }
            .no-scrollbar	  { overflow:		hidden; }
            .allow-overflow	  { overflow:visible;height:33px;text-align: center;line-height: 35px;font-size:16px;}
            .full-height	  { height:		100%; }
            button               { cursor:		pointer; }
        </style>
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURI;?>/inc/style/do.css" />
    </head>
    <body>
        <div class="ui-layout-north ui-widget-content add-padding">
            <div class="header">
                <?php require_once (ABSPATH . 'pages/header.php'); ?>
            </div>
            <div class="clear"></div>
            <div class="main2">
            <div id="scbar_left">
                <table class="sctable">
                    <tbody>
                        <tr>
                            <td class="scbar_icon_td"></td>
                            <td class="scbar_txt_td"><input class="xg1" name="qn" id="dn2" autocomplete="on" type="text"></input></td>
                            <td class="scbar_btn_td"><a name="searchsubmit" id="scbar_btn_dg2" class="pn pnc" value="true"><strong class="xi2 xs0">To Gene</strong></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="scbar_right">
                <table class="sctable">
                    <tbody>
                        <tr>
                            <td class="scbar_icon_td"></td>
                            <td class="scbar_txt_td"><input class="xg1" name="qn" id="gn2" autocomplete="on" type="text"></input></td>
                            <td class="scbar_btn_td"><a name="searchsubmit" id="scbar_btn_gd2" class="pn pnc" value="true"><strong class="xi2 xs0">To Disease</strong></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                <div class="clear"></div>
            </div>
        </div> 
        <div id="tabs-west" class="ui-layout-west no-padding no-scrollbar">
            <ul class="allow-overflow">Disease Terms Tree
            </ul>
            <div class="ui-layout-content ui-widget-content no-scrollbar" style="border-top: 0;">
                <div id="tab-panel-west-1" class="full-height no-padding add-scrollbar">
                    <div class="ui-tabs-panel outline">
                        <div class="sidebar1" id="sidebar1">
                            <div class="loading" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="tabs-center" class="ui-layout-center no-padding">
            <div class="content">
                <div class="topmenu" id="topmenu">
                    <ul id="topnav">
                        <li id="table_view" class="ui-state-default ui-corner-top ui-tabs-selected">
                            <a class="view" id="tv" href="javascript:void(0)">Table View</a>
                           <!-- <span>
                                <a class="view" id="tv_g2d" href="javascript:void(0)">Gene to Disease</a>|
                                <a class="view" id="tv_g2g" href="javascript:void(0)">Gene to Gene</a>
                            </span>-->
                        </li>
                        <li id="network_view" class="ui-state-default ui-corner-top ">
                            <a class="view" id="nwv" href="javascript:void(0)">Network View</a>
                        </li>
                        <li id="detail_view" class="ui-state-default ui-corner-top">
                            <a class="view" id="dv" href="javascript:void(0)">Disease Detail</a>
                        </li>                        
                    </ul>
                </div>
                <div class="result_container" id="result_container" style="display: none;">
                    <div class="loading" style="display: none;"></div>
                </div>
                <div class="disease_detail" id="disease_detail" style="display:none;">
                </div>
                <div  id="network_container" style="display: none;">
                    <div id="waiting" style="display: none;"></div>                           
                </div>
            </div>
        </div>
        <div id="ui-copyright" class="ui-layout-south ui-widget-content add-padding">Copyright@ITEC.HUST</div>
        
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery-1.7.1.js"></script> 
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery-ui-1.8.17.custom.min.js"></script> 
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/json2.min.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/AC_OETags.min.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/cytoscapeweb.min.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.layout-latest.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.layout.callbacks.min-latest.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/inputvalue.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/flexigrid.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/lhgdialog.min.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.ui.core.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.ui.slider.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/doa.js"></script>
        <script>
            $(function(){
                setActiveTab('<?php echo $tab_type;?>');
                $("#gn2").inputLabel('<?php echo DEFAULT_GENE; ?>');
                $("#dn2").inputLabel('<?php echo DEFAULT_DISEASE; ?>');
                
                pageLayout = $("body").layout({
                      west__size:            .20
                    , north__size:           .15
                    , south__resizable:      false
                    , south__closable:       false
                    , west__onresize:        $.layout.callbacks.resizePaneAccordions // west accordion a child of pane
                    , onclose:      function(){ reSizeCytoscapeweb(); }
                    , onopen:       function(){ reSizeCytoscapeweb(); }
                });
                pageLayout.panes.west.tabs({
                    show:   $.layout.callbacks.resizePaneAccordions // resize tab2-accordion when tab is activated
                });             
                var search_word = "<?php echo $search_word;?>";
                var search_type = '<?php echo $search_type;?>';
                showSearchResult(search_word, search_type);
                $('#network_container').attr('class',search_word+'_'+search_type);              
                var show_g2g_nav = '<span><a class="view" id="tv_g2d" href="javascript:void(0)">Gene-Disease</a>|<a class="view" id="tv_g2g" href="javascript:void(0)">Gene-Gene</a></span>';
                if (search_type=='d2g'){
                    selectedNodes = loadTreeView(search_word);
                    loadDiseaseDetail(search_word);
                    if($('#table_view span').length>0)
                       $('#table_view span').remove();
                       $('#table_view').append(show_g2g_nav);                 
                } else if(search_type=='g2d') {
                    selectedNodes = loadTreeView('disease');
                    loadDiseaseDetail('disease');
                    $('#table_view span').remove();
                }   
                $('#gn2').autocomplete('<?php echo SITEURI . '/ajax/autocomplete.php?type=g' ?>', {
                    mustMatch: false,
                    matchContains: true,
                    multiple: true,
                    multipleSeparator: '<?php echo STRING_SEPARATOR;?>',
                    max: 20
                });
                
                $('#dn2').autocomplete('<?php echo SITEURI . '/ajax/autocomplete.php?type=d' ?>', {
                    mustMatch: false,
                    matchContains: true,
                    multiple: true,
                    multipleSeparator: '<?php echo STRING_SEPARATOR;?>',
                    max: 20
                });
            });
            
        </script>
    </body>
</html>
