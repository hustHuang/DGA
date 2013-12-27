<?php
session_start();
require_once '../common.php';
$tab_type = 'batch';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>DGA - Batch Query</title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURI; ?>/inc/style/do.css"/>
        <style type="text/css">
            .note {margin: 30px auto;width: 800px;font-size: 15px;}
            .code {margin: 10px auto;width: 800px;font-size: 12px;background-color:#DDDDDD;}
        </style>
    </head>
    <body>
        <div class="header">
            <div id="hdl">
                <font size="6px">Disease and Gene Annotations</font>
            </div>
            <?php require_once (ABSPATH . 'pages/header.php'); ?>
        </div>
        <div class="note">
            <p>Our system provides a web service to search disease-gene mapping between Disease Ontology and Gene.</p>
            <p>You have two ways to search the mapping. Retrieve Gene information from disease or Retrieve disease information from Gene info, by setting parameter "searchType".</p>
            <p>We give an example client in Java. You can use other programming language as well.</p>
        </div>
        <div class="code">
            <pre>
import java.io.IOException;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.httpclient.params.HttpMethodParams;

public class MappingSearchClient {
	private static final String SEARCH_SERVICE_URL = "http://hdoa.nubic.northwestern.edu/batchservice.php";

	public static void main(String[] args) {
		try {
			HttpClient client = new HttpClient();
                        // Set this string for your application
			client.getParams().setParameter(HttpMethodParams.USER_AGENT, "DOAF Client Example");
			
			PostMethod method = new PostMethod(SEARCH_SERVICE_URL);
			
			// Configure the form parameters
			// searchType defines the method to search. 
			// "d2g" searches Gene information from disease 
			// and "g2d" searches disease information from Gene.
			// searchWord defines the key word to search.
			method.setParameter("searchType", "d2g");
			method.setParameter("searchWord", "primary breast cancer");
			
			int statusCode = client.executeMethod(method);
			if (statusCode != -1){
				try {
					String contents = method.getResponseBodyAsString();
					method.releaseConnection();
					System.out.println(contents);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		} catch (HttpException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
            </pre>
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