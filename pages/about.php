<?php session_start();
require_once '../common.php';
$tab_type = 'about';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>DGA - About</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
        <style type="text/css">
            h3{margin: 17px 0;}
            strong { font-weight:bold; color:#000}
            td { font-family:Arial; color:#000}
            .note{font-size: 15px;margin: 30px 0 50px 0;}
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
        </style>
    </head>
    <body>
        <div class="header">
            <div id="hdl">
                <font size="6px">Disease and Gene Annotations</font>
            </div>
            <?php require_once (ABSPATH . 'pages/header.php'); ?>
        </div>
        <div class="note" style="width: 50%; padding-left: 25%;">
            <h3>About DGA</h3>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">Disease and Gene Annotations (DGA) is collaborative effort, aiming to provide a comprehensive and integrated annotation to human genome by using computable, controlled vocabulary of Disease Ontology (DO), NCBI Gene Reference Into Function (GeneRIF), and molecular interaction networks.</p>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">The Disease Ontology was initially developed as part of the NUgene project starting in 2003 at Northwestern.</p>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">Built on the Gene Ontology (GO) Consortium and Open Biological and Biomedical Ontologies (OBO) Foundry, DO delineates a semantically computable structure of inherited, environmental and infectious human disease that is based on a manually inspected subset of the Unified Medical Language System (UMLS) and other terms outside UMLS. The DO is organized as a directed acyclic graph (DAG). Every DO term is unique and contains textual description and external references to well-established, well-adopted terminologies that contain disease and disease related concepts such as UMLS, Medical Subject Headings (MeSH), SNOMED, OMIM and International Classification of Diseases (ICD)-9 and ICD-10.</p>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">GeneRIF offers functional description to genes with high quality and frequency of update. GeneRIF is brief textual description (up to 250 characters) to gene provided by NCBI database. Every GeneRIF entry is associated a certain PubMed ID, showing biological evidences related to the description. NCBI also provides an open access to GeneRIF so that the community can contribute to GeneRIF production, which enables low mapping error of gene and high-frequent update.</p>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">With intelligent electric annotation program, DGA brings them together to build a comprehensive set of disease-to-gene relationships with high disease –gene coverage and keeps the resulting knowledge current responding to update of DO and GeneRIF.</p>
            <p style="line-height:1.5em;font-size:14px;text-indent:2em;">Further, DGA integrates various types of molecular interaction networks so that users can investigate the relationships between disease-related genes and infer associations between diseases.</p>
        </div>
        <?php require_once (ABSPATH . 'pages/footer.php'); ?>
        <script type="text/javascript" src="<?php echo SITEURI; ?>/inc/js/lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/lib/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo SITEURI;?>/inc/js/doa.js"></script>
        <script type="text/javascript">
            $(function(){
                setActiveTab('<?php echo $tab_type;?>');
                });
        </script>
    </body>
</html>
