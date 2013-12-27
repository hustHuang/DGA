<?php session_start();
require_once '../common.php';
$tab_type = 'search';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>DGA - Search</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/autocomplete.css"/>
        <style type="text/css">
        .bubbleInfo {
            position: relative;
            width:100%;
            height:100%;
        }
        .trigger {
           position:relative;
        }
        /* Bubble pop-up */

        .popup {
        	position:absolute;
                float: left;
                margin-top:-40px;
                padding-bottom: 2px;
        	display: none;
        	z-index: 30;
        	border-collapse: collapse;
        }

        .popup td.corner {
        	height: 15px;
        	width: 19px;
                margin: 0px;
                padding: 0px;
        }

        .popup td#topleft { background-image: url('../inc/image/bubbles/bubble-1.png'); }
        .popup td.top { background-image: url('../inc/image/bubbles/bubble-2.png'); }
        .popup td#topright { background-image: url('../inc/image/bubbles/bubble-3.png'); }
        .popup td.left { background-image: url('../inc/image/bubbles/bubble-4.png'); }
        .popup td.right { background-image: url('../inc/image/bubbles/bubble-5.png'); }
        .popup td#bottomleft { background-image: url('../inc/image/bubbles/bubble-6.png'); }
        .popup td.bottom { background-image: url('../inc/image/bubbles/bubble-7.png'); text-align:center;}
        .popup td.bottom img { display: block; margin: 0 auto; }
        .popup td#bottomright { background-image: url('../inc/image/bubbles/bubble-8.png'); }

        .popup table.popup-contents {
                    font-size: 12px;
                    line-height: 1.2em;
                    background-color: #fff;
                    color: #666;
                    font-family: Verdana,"Times New Roman";
        	}            
            
            
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div id="hdl">
                    <font size="6px">Disease and Gene Annotations </font>
                </div>
                <?php require_once (ABSPATH . 'pages/header.php'); ?>
            </div>
            <div class="clear"></div>

            <div class="main">
                <form id="formd2g" action="<?php echo SITEURI;?>/pages/result.php" method="post">
                    <input type="hidden" name="st" value="d2g"/>
                    <div class="bubbleInfo">
                            <div id="scbar_left" class="trigger">
                                <table class="sctable">
                                    <tbody>
                                        <tr>
                                            <td class="scbar_icon_td"></td>
                                            <td class="scbar_txt_td"><input class="xg1" name="qn" id="dn" autocomplete="on" type="text"></input></td>
                                            <td class="scbar_btn_td"><button type="submit" name="searchsubmit" id="scbar_btn_dg" class="pn pnc" value="true"><strong class="xi2 xs0">To Gene</strong></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <table id="dpop" class="popup" style="margin-left:65px;">
                                    <tbody><tr>
                                            <td id="topleft" class="corner"></td>
                                            <td class="top"></td>
                                            <td id="topright" class="corner"></td>
                                    </tr>

                                    <tr>
                                            <td class="left"></td>
                                            <td>
                                                <div class="popup-contents" style="width:175px;height:72px;">
                                                    <p style="font-size:14px;">Search genes by inputting single or multiple disease names</p>
                                                </div>
                                            </td>
                                            <td class="right"></td>    
                                    </tr>
                                    <tr>
                                            <td class="corner" id="bottomleft"></td>
                                            <td class="bottom"><img width="30" height="27" alt="popup tail" src="../inc/image/bubbles/bubble-tail2.png"/></td>
                                            <td id="bottomright" class="corner"></td>
                                    </tr></tbody>
                            </table>                    
                    </div>
                </form>
                <form id="formg2d" action="<?php echo SITEURI;?>/pages/result.php" method="post">
                    <input type="hidden" name="st" value="g2d"/>
                    <div class="bubbleInfo">
                            <div id="scbar_right" class="trigger">
                                <table class="sctable">
                                    <tbody>
                                        <tr>
                                            <td class="scbar_icon_td"></td>
                                            <td class="scbar_txt_td"><input class="xg1" name="qn" id="gn" autocomplete="on" type="text"></input></td>
                                            <td class="scbar_btn_td"><button type="submit" name="searchsubmit" id="scbar_btn_gd" class="pn pnc" value="true"><strong class="xi2 xs0">To Disease</strong></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <table id="dpop" class="popup" style="margin-left:715px;">
                                    <tbody><tr>
                                            <td id="topleft" class="corner"></td>
                                            <td class="top"></td>
                                            <td id="topright" class="corner"></td>
                                    </tr>

                                    <tr>
                                            <td class="left"></td>
                                            <td>
                                                <div class="popup-contents" style="width:175px;height:72px;">
                                                    <p style="font-size:14px;">Search diseases by inputting single or multiple gene symbols</p>
                                                </div>
                                            </td>
                                            <td class="right"></td>    
                                    </tr>
                                    <tr>
                                            <td class="corner" id="bottomleft"></td>
                                            <td class="bottom"><img width="30" height="27" alt="popup tail" src="../inc/image/bubbles/bubble-tail2.png"/></td>
                                            <td id="bottomright" class="corner"></td>
                                    </tr></tbody>
                            </table>                          
                    </div>
                </form>
                            <!--<div id="tabs-west" style="width:320px;height:400px;margin-left:100px;overflow: scroll;display:none;">-->
                                <!--<ul class="allow-overflow">Disease Terms Tree</ul>-->                         
                                                            
                                              <!--<div class="sidebar1" id="sidebar1">-->
                                                    <!--<div class="loading" style="display: none;"></div>-->
                                                <!--</div>-->
                                                                    
                             <!--</div>--> 
                             <div id="disease" style="height:46px;"></div>
                <div class="clear"></div>
                
            </div>
            <?php require_once (ABSPATH . 'pages/footer.php'); ?>
        </div>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/inputvalue.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/doa.js"></script>
        <script type="text/javascript">
            $(function(){
                setActiveTab('<?php echo $tab_type;?>');
                $("#gn").inputLabel('<?php echo DEFAULT_GENE; ?>');
                $("#dn").inputLabel('<?php echo DEFAULT_DISEASE; ?>');
                $('#dn').focus(function(){
                    var t=$("#dn").val();
                    $("#dn").val("").val(t);
                });
                $('#scbar_btn_gd').live('click', function(){
                });
                
                $('#gn').autocomplete('<?php echo SITEURI . '/ajax/autocomplete.php?type=g' ?>', {
                    mustMatch: false,
                    matchContains: true,
                    multiple: true,
                    multipleSeparator: '<?php echo STRING_SEPARATOR;?>',
                    max: 20
                });
                
                $('#dn').autocomplete('<?php echo SITEURI . '/ajax/autocomplete.php?type=d' ?>', {
                    mustMatch: false,
                    matchContains: true,
                    multiple: true,
                    multipleSeparator: '<?php echo STRING_SEPARATOR;?>',
                    max: 20
                }).result(function(event, data, formatted) {
                    /*$('#tabs-west').css('display', 'block');
                    $('#sidebar1 .loading').show();
                    loadTreeView(data+'|');*/
                    $.ajax({
                    type: 'POST',
                    url: '../ajax/DiseaseDetail.php',
                    dataType: "JSON",
                    data: {
                        'disease_name': data[0],
                        'index': 0
                    },
                    async: false,
                    success: function(data){
                        $('#detail_info_0').remove();
                        var html = makeDetailHtmls(data);
                        $('#disease').after(html);                      
                        $('#detail_info_0').slideToggle('slow');
                    }
                });
});
                
                $('.bubbleInfo').each(function (){
                    var distance = 10;
                    var time = 250;
                    var hideDelay = 500;

                    var hideDelayTimer = null;

                    var beingShown = false;
                    var shown = false;
                    var trigger = $('.trigger', this);
                    var info = $('.popup', this).css('opacity', 0);


                    $([trigger.get(0), info.get(0)]).mouseover(function () {
                        if (hideDelayTimer) clearTimeout(hideDelayTimer);
                        if (beingShown || shown) {
                            // don't trigger the animation again
                            return;
                        } else {
                            // reset position of info box
                            beingShown = true;
                            info.css({
                                top: -90,
                                left: -33,
                                display: 'block'
                            }).animate({
                                top: '-=' + distance + 'px',
                                opacity: 1
                            }, time, 'swing', function() {
                                beingShown = false;
                                shown = true;
                            });
                        }

                        return false;
                    }).mouseout(function () {
                        if (hideDelayTimer) clearTimeout(hideDelayTimer);
                        hideDelayTimer = setTimeout(function () {
                            hideDelayTimer = null;
                            info.animate({
                                top: '-=' + distance + 'px',
                                opacity: 0
                            }, time, 'swing', function () {
                                shown = false;
                                info.css('display', 'none');
                            });
                        }, hideDelay);
                        return false;
                    });
                });
   
            });
        </script>
    </body>
</html>
