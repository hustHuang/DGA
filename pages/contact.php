<?php
session_start();
require_once '../common.php';
$tab_type = 'contact';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>DGA - Contact</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
        <style type="text/css">
            strong { font-weight:bold; color:#000}
            td { font-family:Arial; color:#000}
            .note{margin: 50px auto;}
            .table_line { margin-left:20px; margin-right:20px;}
            .table_line td { border-bottom:1px solid #e7e8ea;} 
            .table_line .td01 {padding:7px 14px 7px 14px; line-height:18px; color:#3c3e3d;}
            .table_line .td02 {padding:7px 14px 7px 14px; text-align:right; word-break:keep-all;}
            .table_line .td100 {padding:7px 14px 7px 14px; text-align:right; width:100px;  }
            .table_line .td03 { border-top:2px solid #CCD6DF; color:#4F678D;padding:7px 14px 7px 14px; font-weight:bold;}
            .table_line .td03 b { color:#000;}
            .table_line .td04 { border-bottom:2px solid #CCD6DF;padding:3px 14px 3px 14px; color:#4F678D; }
            .table_line .title { position:relative; background-color:#f4f5f7; border-bottom:none; color:#000; padding:6px 14px 6px 14px; font-weight:bold;}
            .table_line .date { padding:7px 14px 7px 14px;}
            .news_info_title { text-align:center; font-size:16px; color:#000; font-weight:bold; line-height:25px; padding-top:20px; padding-bottom:6px;}
            .news_contd { padding:9px 14px 9px 14px;}
            .news_contd p { line-height:24px; margin-bottom:8px; margin-top:8px;}
            .table_line a { color:#00F}
            .table_line .leader tr{border: none;}
            .table_line .leader tr td{border: none;text-align: left;padding-top: 12px;}
        </style>
    </head>
    <body>
        <div class="header">
            <div id="hdl">
                <font size="6px">Disease and Gene Annotations</font>
            </div>
            <?php require_once (ABSPATH . 'pages/header.php'); ?>
        </div>
        <div class="note" style="width: 80%; padding-left: 10%" align="center">
            <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_line">
                <tr>
                    <td class="td01">&nbsp;</td>
                    <td colspan="3" class="td01"><strong>Address: </strong><br/>
                        Northwestern University Biomedical Informatics Center<br/>
                        Feinberg School of Medicine<br/>
                        Northwestern University<br/>
                        750 Lake Shore Drive<br/>
                        Rubloff Building 11-162<br/>
                        Chicago, IL 60611<br/>
                        Phone: +1-312-503-3229</td>
                </tr>
                <tr> 
                    <td class="td01">&nbsp;</td>
                    <td colspan="3" class="td01"><strong>Group leaders: </strong><br/>
                        <table class="leader">
                            <tr><td>Prof. Warren Kibbe : <a href="mailto:wakibbe@northwestern.edu">Email</a></td><td style="padding-left: 25px;"> Prof. Simon Lin : <a href="mailto:S-Lin2@northwestern.edu">Email</a></td></tr>
                            <tr><td>Prof. Wei Xu : <a href="mailto:xuwei@hust.edu.cn">Email</a></td><td style="padding-left: 25px;">Prof. Tian Xia : <a href="mailto:tianxia@northwestern.edu">Email</a></td></tr>
                        </table>           
                    </td>
                </tr>
                <tr> 
                    <td class="td01">&nbsp;</td>
                    <td colspan="3" class="td01"><strong>Developing team: </strong><br/><br/>
                        Huisong Wang : <a href="mailto:whuisong89@gmail.com">Email</a><br/><br/>
                        Dong Fu , Kegui Huang , Zhifeng Lin , Jun Liu</td>
                </tr>
                <tr> 
                    <td class="td01">&nbsp;</td>
                    <td colspan="3" class="td01"><strong>Please send comments regarding: </strong><br/>
                        General development and scientific contents to Prof. Dr. Warren Kibbe, Prof. Simon Lin, Prof. Tian Xia ;<br />
                            Technical computing, textmining and user interfaces to Prof. Xu Wei
                    </td>
                </tr>
            </table>
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
