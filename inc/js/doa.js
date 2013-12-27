var _searchUrl = '../ajax/Search.ajax.php';
var _jTreeUrl = '../ajax/JSTree.ajax.php';
var _diseaseDetailUrl = '../ajax/DiseaseDetail.php';
var _downloadUrl='../ajax/Download.ajax.php';
var _geneRelationUrl='../ajax/ajax.table_result.php';
var _getNewDataUrl='../ajax/search_other.ajax.php';
var _getInfoUrl='../ajax/get_info.ajax.php';
var _getTextUrl='../ajax/get_text.ajax.php';
var _searchWord = '';              
var _searchType = '';
var _sWord='';
var _sType='';
var _pWord='';
var _pType='';
var vis;
var _selectedType='';
var _lastNum = 20;
var _lastMax = 0;
var edge_checked = {};
var _selectedNodes = [];
var _treeSelectedNames = {};

var _colModel_d2g = [{
    display: 'No.',
    name : 'id',
    width : 30,
    sortable : false,
    align: 'center'
},
{
    display: 'Disease Name',
    name : 'dname',
    width : 150,
    sortable : true,
    align: 'left'
},
{
    display: 'Gene Symbol',
    name : 'symbol',
    width : 70,
    sortable : true,
    align: 'left'
},
{
    display: 'Chromosome',
    name : 'chromosome',
    width : 70,
    sortable : true,
    align: 'left'
},
{
    display: 'Map Location',
    name : 'maplocation',
    width : 120,
    sortable : false,
    align: 'left'
},
{
    display: 'PubMed ID',
    name : 'generifid',
    width : 90,
    sortable : false,
    align: 'left'
},
{
    display: 'GeneRIF Text',
    name : 'text',
    width: 500,
    sortable : false,
    align: 'left'
}
];

var _colModel_g2d = [
{
    display: 'No.',
    name : 'id',
    width : 30,
    sortable : false,
    align: 'center'
},
{
    display: 'Gene Symbol',
    name : 'symbol',
    width : 70,
    sortable : true,
    align: 'left'
},
{
    display: 'Disease Name',
    name : 'dname',
    width : 120,
    sortable : true,
    align: 'left'
},
{
    display: 'DOID',
    name : 'doid',
    width : 70,
    sortable : false,
    align: 'left'
},
{
    display: 'PubMed ID',
    name : 'generifid',
    width : 90,
    sortable : false,
    align: 'left'
},
{
    display: 'GeneRIF Text',
    name : 'text',
    width: 650,
    sortable : false,
    align: 'left'
}
];

var network_json;
var cw_options = {
    fitToScreen: true,
    swfPath: "../inc/swf/CytoscapeWeb",
    flashInstallerPath: "../inc/swf/playerProductInstall",
    flashAlternateContent: '<div class="ui-state-error ui-corner-all"><p>This content requires the Adobe Flash Player.</p><p><a href="http://get.adobe.com/flashplayer/"><img width="160" height="41" border="0" alt="Get Adobe Flash Player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg"></a></p></div>'
};

//Mapping network groups to node colors:
var nodeColorMapper = {
    attrName: "ngc", // nodeGroupCode
    entries: [
    {
        attrValue: "q",
        value: "#FF9086"
    },  // Gene nodes

    {
        attrValue: "r",
        value: "#FFFFFF"
    }   // Disease nodes
    ]
};

//Mapping network groups to edge colors:
var edgeColorMapper = {
    attrName: "egc",   //edgeGroupCode
    entries: [
    {
        attrValue: "genetic_interactions",
        value: "blue"
    },	//Genetic_interactions

    {
        attrValue: "co_expression",
        value: "green"
    },  //Co_expression//#FBD10A

    {
        attrValue: "co_localization",
        value: "#f4550b"
    },  //Co_localization

    {
        attrValue: "physical_interactions",
        value: "#cf2aea"
    },  //Physical_interactions

    {
        attrValue: "shared_protein_domains",
        value: "#00CCFF"
    },  //Shared_protein_domains
    ]
};

var cw_style = {
    nodes : {
        shape: "ELIPSE",
        color: {
            defaultValue: "#999999",
            discreteMapper: nodeColorMapper
        },
        opacity: 1,
        size : {
            defaultValue: 24, 
            continuousMapper : {
                attrName:"count", 
                minValue: 24, 
                maxValue: 48
            }
        },
        borderColor: "#808080",
        borderWidth: 1,
        label: {
            passthroughMapper: {
                attrName: "id"
            }
        },
        labelFontWeight: "bold",
        labelGlowColor: "#ffffff",
        labelGlowOpacity: 1,
        labelGlowBlur: 3,
        labelGlowStrength: 20,
        labelHorizontalAnchor: "center",
        labelVerticalAnchor: "bottom",
        selectionBorderColor: "#000000",
        selectionBorderWidth: 2,
        selectionGlowColor: "#ffff33",
        selectionGlowOpacity: 0.6,
        hoverBorderColor: "#000000",
        hoverBorderWidth: 2,
        hoverGlowColor: "#aae6ff",
        hoverGlowOpacity: 0.8
    },
    edges : {
        color: {
            defaultValue: "#999999",
            discreteMapper: edgeColorMapper
        },
        opacity: 1,
        //width : 4
        width : {
            continuousMapper : {
                attrName : "distance", 
                minValue :3, 
                maxValue: 6
            }
        }
    }
    
};

$(function(){
    var show_g2g_nav='<span><a class="view" id="tv_g2d" href="javascript:void(0)">Gene - Disease</a>|<a class="view" id="tv_g2g" href="javascript:void(0)">Gene - Gene</a></span>';  
    $('#sidebar1 .loading').show();
    $('ul#topnav li#table_view').addClass('ui-state-active');
    $('.sctable td a').live('click', function(e){
        if($('#sidebar1 li').hasClass('selected')){
            $('#sidebar1 li').removeClass('selected');
        }
        $('#topnav .ui-tabs-selected').removeClass('ui-tabs-selected');
        $('#topnav .ui-state-active').removeClass('ui-state-active');
        $('#topnav #table_view').addClass('ui-tabs-selected');// initial show tableview
        $('#topnav #table_view').addClass('ui-state-active');
        $('.disease_detail,#network_container').hide();
        var searchWord = $(e.target).closest('tr').find('input').val();
        var searchType = $(e.target).closest('tr').find('input').attr('id') == 'dn2' ? 'd2g' : 'g2d';
        showSearchResult(searchWord, searchType);
        
        //Store the searchWord and searchType
        $('#network_container').attr('class',searchWord+'_'+searchType);
        
        if (searchType == 'd2g'){
            loadTreeView(searchWord);
            loadDiseaseDetail(searchWord);
            if($('#table_view span').length>0)
                $('#table_view span').remove();
            $('#table_view').append(show_g2g_nav);
        }else{
            loadTreeView('disease');
            loadDiseaseDetail('disease');
            //loadTreeView('rheumatoid arthritis');
            //loadTreeView('leukemia');
            // loadTreeView('astrocytoma');
            $('#table_view span').remove();
        }
    });
    
    $('#sidebar1').css({
        'overflow' : 'auto'
    });
    //event for the jstree
    $('#sidebar1 li a').live('click', function(e){
        //        var count = _selectedNodes.length;
        //        for (var i = 0; i < count; i++){
        //            if ($('#sidebar1 li#' + _selectedNodes[i]).hasClass('selected')){
        //                $('#sidebar1 li#' + _selectedNodes[i]).removeClass('selected');
        //            }
        //        }
        //     _selectedNodes = [];

        var id = $(e.target).attr('id');
        var name = trimString($(e.target).text());
        var searchType=$('#network_container').attr('class').split('_')[1];
        if(searchType=='g2d'){
            return;
        }
        //only for networkview
        if($('#network_container').css('display')!='none'&&window.vis){
            var nodeColor;
            var nodeId=name;
            if(vis.node(nodeId)){
                if($('#li_' + id).hasClass('highlighted')){
                    $('#li_' + id).removeClass('highlighted');
                    nodeColor = '#FFFFFF';
                }else{
                    $('#sidebar1 li').each(function(){
                        if($(this).hasClass('highlighted')){
                            $(this).removeClass('highlighted').addClass('selected');
                            var name=trimString($(this).find('a').eq(0).text());
                            var id = $(this).find('a').eq(0).attr('id');
                            _treeSelectedNames[id] = name; 
                            if(vis.node(name)){
                                visBypass(name,'#FFFFFF');                            
                            }
                        }                                 
                    });              
                    $('#li_' + id).addClass('highlighted');
                    nodeColor = '#e6f755';
                }    
                visBypass(nodeId,nodeColor);    
            }
        }
        if(!$('#li_' + id).hasClass('selected')){
            $('#li_' + id).addClass('selected');
            _treeSelectedNames[id] = name;      
        }else{
            $('#li_' + id).removeClass('selected');
            delete _treeSelectedNames[id];
        } 
    });
     
    //navbar added  networkview   
    $('.content #topmenu #topnav li').live('click', function(e){
        $('#topnav .ui-tabs-selected').removeClass('ui-tabs-selected');
        $('#topnav .ui-state-active').removeClass('ui-state-active');
        $(e.target).closest('li').addClass('ui-tabs-selected');
        $(e.target).closest('li').addClass('ui-state-active');
        
        switch($(e.target).closest('li').attr('id')){
            case 'table_view': {
                $('#disease_detail,#network_container').hide();
                clearTreeview();
                $('#result_container').show();
                break;
            }
            case 'detail_view': {
                $('#result_container,#network_container').hide();
                clearTreeview();
                $('#disease_detail').show();
                break;
            }
            case 'network_view': {
                $('#result_container,#disease_detail').hide();          
                $('#network_container').show();
                resetTreeview();
                break;
            }
        } 
    });
    
    $('#disease_detail #accordion-west h3').live('click', function(e){
        var dn = $(e.target).closest('h3').attr('id');
        $.ajax({
            type: 'POST',
            url: '',
            dataType: "JSON",
            data: {
                'disease_name': dn
            },
            async: false,
            success: function(data){
                $(e.target).closest('div').find('p').text(data.name);
            }
        });
    });
    
    $('.detail_div').live('click', function(e){
        var index = $(e.target).closest('.detail_div').attr('id').split('_')[2];
        var status = $(e.target).closest('.detail_div').children('div');
        var name = status.children('.detail_title').text();
        var html = '';
        if (status.hasClass('detail_closed')){
            $(e.target).closest('.detail_div').removeClass('closed').addClass('open');
            status.removeClass('detail_closed').addClass('detail_open');
            if ($('.disease_detail').find('#detail_info_' + index).length == 0){
                $.ajax({
                    type: 'POST',
                    url: _diseaseDetailUrl,
                    dataType: "JSON",
                    data: {
                        'disease_name': name,
                        'index': index
                    },
                    async: false,
                    success: function(data){
                        var html = makeDetailHtml(data);
                        $(e.target).closest('div').find('p').text(data.name);
                        $(e.target).closest('.detail_div').after(html);
                        $('.disease_detail').find('#detail_info_' + index).slideToggle('slow');
                    }
                });
            } else {
                $('.disease_detail').find('#detail_info_' + index).slideToggle('slow');
            }    
        } else {
            $(e.target).closest('.detail_div').removeClass('open').addClass('closed');
            status.removeClass('detail_open').addClass('detail_closed');
            $('.disease_detail').find('#detail_info_' + index).slideToggle('slow');
        }
    //alert(index + ":" + name);
    });
    
    //show tableview g2g
    $('#table_view span a').live('click',function(){  
        if($(this).attr('id')=='tv_g2g'){
            $('#result_container #result_1').hide();
            var search_word=$('#network_container').attr('class').split('_')[0];
            var search_type=$('#network_container').attr('class').split('_')[1];
            var type=$('#result_2 select').find('option:selected').text();
            loadGeneRelation(search_word, search_type, type , 0 );
            $('#result_container #result_2').show();
        }
        else{
            $('#result_container #result_2').hide();
            $('#result_container #result_1').show();
            $('#result_container .loading').hide();
        }        
    });
    
    //click to download the data 
    $('.dwn').live('click',function(){  
        var search_word=$('#network_container').attr('class').split('_')[0];
        var search_type=$('#network_container').attr('class').split('_')[1];
        $('input[name="query"]').attr('value',search_word);
        $('input[name="qtype"]').attr('value',search_type);
        var form_name=$(this).parent('form').attr('name');
        if(form_name=='exportmappingresultids_02'){
            var interactionType=$('#result_2 select').find('option:selected').text();
            $('input[name="type"]').attr('value',interactionType);
        }
        var download_form=document.forms[form_name];
        download_form.submit();
    });  
    
    //click to show the networkview
    $('#network_view').live('click',function(){
        var searchWord=$('#network_container').attr('class').split('_')[0];
        var searchType=$('#network_container').attr('class').split('_')[1];
        $('#result_container,#disease_detail').hide();
        loadNetworkView(searchWord, searchType);
        $('#waiting').hide();
    });

    //chang the edge
    //    var gd=$('#network_container').attr('class').split('_')[1];
    //    $('#choosebox input:eq(0)').attr('value',gd);    
    //    var total_type = 8;
    //    for(var i=0;i<total_type;i++){
    //           var type = $('#choosebox input:eq('+i+')').attr('value').replace(/\s/g,'');
    //           edge_checked[type] = true;
    //    }
        
    $('.chooseitem input').live('click',function(){
        // edge_checked['g2d'] = true;
        // edge_checked['d2g'] = true;
        for(var i=0;i<6;i++){
            var type = $('.chooseitem input:eq('+i+')').attr('value').replace(/\s/g,'');
            var checked = true;
            if ($('.chooseitem input:eq('+ i +')').attr("checked")!="checked"){
                //if($('#choosebox input:eq('+i+')').attr("checked")===undefined){
                checked = false;
            }
            edge_checked[type] = checked;
        }
        _lastFilter = function(edge){
            return edge_checked[edge.data.egc.replace(/\s/g,'')];
        };
        vis.filter("edges", _lastFilter, true);
    });
    
    $('#exportBtn').live('click',function(){
        var index=$('#export').get(0).selectedIndex;
        var format=$('#export').get(0).options[index].text;
        //alert(format);
        vis.exportNetwork(format, '../ajax/export.php?type='+format);   
    });
    
    $('#reSetNum').live('click',function(){
        var Num=parseInt($('#num').text());
        var searchWord=$('#network_container').attr('class').split('_')[0];  
        var searchType=$('#network_container').attr('class').split('_')[1];
        reLoadNetworkView(searchWord ,searchType ,Num);
    });
        
    //slider event when Cytoscapeweb is loaded
    $('#network_container').bind('start',function(){
        var searchwords = $('#network_container').attr('class').split('_')[0].split('|');
        var searchtype=$('#network_container').attr('class').split('_')[1];
        var defaultvalue = 20;
        var secondValue = 30;
        var minValue = 20;
        var maxValue=( searchtype=='d2g' ? 50 : 100 );
        if(!$("#slider").slider("option","disabled")){
            $( "#slider" ).slider( "option", "min", minValue); 
            $( "#slider" ).slider( "option", "max", maxValue);
        }
        var nodes=[];
        var Max=0;
        if(window.vis){
            for (var i=0;i < searchwords.length;i++){
                if(searchwords[i]!=''&&vis.node(searchwords[i])){
                    nodes[i]=vis.firstNeighbors([searchwords[i]]).neighbors.length;
                    if(nodes[i]>Max){
                        Max=nodes[i];
                    }   
                }
            }      
        }
        if(($('#network_container').css('display')!='none')&&window.vis){
            if(Max < defaultvalue){                    
                $('#slider').slider("option","max",defaultvalue);                   
                $("#slider").slider("option","min", 0);
                $("#slider").slider("option","value", Max );
                $("#num").text($("#slider").slider("value"));
                $("#slider").slider("option","disabled", true );
                $('#reSetNum').attr('disabled',true);
                if(Max==0){
                    $('#numLabel').text('no results!');
                }else{
                    $('#numLabel').text('it has shown all the results!');
                }         
            }else{
                if(Max==_lastMax){
                    return;
                }else{
                    _lastMax=Max;  
                }
                var tempValue=$( "#slider" ).slider("value");
                if(tempValue == defaultvalue){
                    $("#slider").slider("option","value", secondValue );
                }
                if(Max < tempValue){
                    $('#numLabel').text('it has shown all the results!');                  
                }else{
                    $('#numLabel').text('the default value is 20.');  
                }
                $("#num").text( $( "#slider" ).slider( "value" ) );
            }
        }
    });


    //handler for the g2g table
    $('#result_2 .table_detail th').live('click',function(){
        var type=$('#result_2 select').find('option:selected').text();
        if(type=='show all'){
            type='showall'
        }
        var index=$(this).index();
        $('#result_2 #'+type+' th').removeClass('sort_th');
        $('.tip').remove();
        $(this).addClass('sort_th');
        sortTableview(type,index+1);
        setStyle(type);
    });
            
    //set style for the g2g tableview
    /*
    $('#result_2 table th').live('mouseover',function(){
        if($(this).parent().css('display')=='none'){
            return;
        }
        var text,t;
        if($(this).parent().find('tr').length<2)
           return;
        if(!$(this).hasClass('sort_th')){
            text='click to sort by ';
            t=3500;
        }else{
            text='rows sorted by ';
            t=2500;
        }
        var tip='<div class="tip">'+text+$(this).text()+'</div>';
        $('#result_2').append(tip);
        setTimeout( function(){$('.tip').remove()} , t );
    });    
    $('#result_2 table th').live('mouseout' , function(){
        if($(this).parent().css('display')=='none'){
            return;
        }        
        $('.tip').remove();
    });    
    $('#result_2 table th').live('mousemove' , function(e){
        if($(this).parent().css('display')=='none'){
            return;
        }        
        var topPosition = e.pageY-105 + 5;
        var leftPosition = e.pageX-250 + 5;
        $('.tip').css({'top' :  topPosition +'px','left' : leftPosition +'px'});
    });   
     */
        
    $('#result_2 select').live('change',function(){
        var Index=$("#result_2 select").get(0).selectedIndex; 
        var type=$('#result_2 select').find('option:selected').text();
        //alert(type+'_'+Index);
        var searchWord=$('#network_container').attr('class').split('_')[0];
        var searchType=$('#network_container').attr('class').split('_')[1];
        if(Index==5){
            loadGeneRelation(searchWord, searchType, type , 1 );     
        }else
        {        
            loadGeneRelation(searchWord, searchType, type , 0 );        
        }   
    });
   
    $('#lastpage').live('click',function(){
        var type=$('#result_2 select').find('option:selected').text();
        var index=$('#'+type).attr('index');
        showNextPage(type , parseInt(index)-1 , 0 );
    });
     
    $('#nextpage').live('click',function(){
        var type=$('#result_2 select').find('option:selected').text();
        var index=$('#'+type).attr('index');
        showNextPage(type , parseInt(index)+1 , 1);
    });
      
    //set style for g2g table
    $('#result_container .tr_item').live('mouseover',function(){
        $(this).attr('color',$(this).css('background-color'));        
        $(this).css('background-color','#C6E2FF');    
    });
    
    $('#result_container .tr_item').live('mouseout',function(){
        $(this).css('background-color',$(this).attr('color'));    
    });
    
    $('#table_result_1 td  div .doid').live('click',function(event){
        var s=$(this).text();
        if ($('.goterms').length > 0){
            $('.goterms').remove();
        }
        var GoTermsHtml = '<div class="goterms" id=' +s+ ' style="position: relative;z-index:12; top:' + event.pageX +'px; left:'+ event.pageY + 'px;"></div>';
        $('#result_1').append(GoTermsHtml);
        showGOTerms(s,event.pageX,event.pageY,5);    
    });
   
    $('.showMoreItems').live('click',function(){
        var s=$(this).parent().find('.genesymbol').text();
        var moreGoterms=getMoreGoterms(s,20);
        $(this).hide();
        $(this).parent().find('span').remove();         
        $(this).parent().append(moreGoterms);
        $(this).remove();
    });  
    
    $('.chooseitem span').live('mouseover',function(){
        $(this).parent().css('background-color', '#F6F6F6');
        //var index= $(this).parent().parent().index();
        var type=$.trim($(this).parent().find('input').val());
        var edges=vis.edges();
        var nodes=vis.nodes();
        var bypass={
            nodes:{},
            edges:{}
        };        
        var props ={
            opacity : 1
        };
        var _props={
            opacity: 0.08
        };
        var nodesArray=[];
        var edgesArray=[];
        $.each(nodes,function(i,e){
            var n=e.data.id;
            bypass["nodes"][n]=_props;         
        });
        $.each(edges,function(i,e){
            var c=e.data.id;
            bypass["edges"][c]=_props;    
            if($.trim(e.data.egc)==type){
                var t=e.data.target;
                var s=e.data.source;             
                nodesArray.push(t);
                nodesArray.push(s);
                edgesArray.push(c);              
            }          
        });
        vis.visualStyleBypass(bypass);
        $.each(nodesArray,function(i,e){
            bypass["nodes"][e]=props;           
        });
        $.each(edgesArray,function(i,e){
            bypass["edges"][e]=props;           
        });
        vis.visualStyleBypass(bypass);         
    });
    
    $('.chooseitem span').live('mouseout',function(){
        $(this).parent().css('background-color', '#FFF');
        var edges=vis.edges();
        var nodes=vis.nodes();
        var props ={
            opacity : 1
        };
        var bypass={
            nodes:{},
            edges:{}
        };
        $.each(nodes,function(i,e){
            var n=e.data.id;
            bypass["nodes"][n]=props;         
        });  
        $.each(edges,function(i,e){
            var c=e.data.id;
            bypass["edges"][c]=props;         
        });        
        vis.visualStyleBypass(bypass);
    });
        
});


function getMoreGoterms(s,count){
    var newGoterms;
    $.ajax({
        type: 'POST',
        url: '../ajax/tableview_goTerm.ajax.php',
        dataType: "JSON",
        data: {
            id:s,
            count:count
        },     
        async: false,   
        success:function(d){
            newGoterms=makeGOTerms(d);   
        }
    });
    return newGoterms;
}

function makeGOTerms(data){
    var GoIds=data.GO_ID.split('|');
    var GTS=data.GoTerm.split('|');
    var goterms='<span>';
    var s=GoIds.length;   
    for(var i=0;i<s;i++){
        goterms+='<a target="_blank" href="http://www.ebi.ac.uk/QuickGO/GTerm?id='+GoIds[i]+'">'+GTS[i]+'</a>'+(i==(s-1) ? ' ':' | ');
    }
    goterms+='</span>';
    return goterms;
}



function showGOTerms(s,x,y,count){
    var html='';
    $.ajax({
        type: 'POST',
        url: '../ajax/tableview_goTerm.ajax.php',
        //url:'../data/GoTerms.json',
        dataType: "JSON",
        data: {
            id:s
            ,
            count:count
        },     
        async: false,   
        success:function(d){
            html+='<p style="margin:5px 5px 0px 5px;"><b>Gene Symbol : </b><label class="genesymbol">'+s+'</label><br/><b>Link to&nbsp; NCBI : </b><a target="_blank" href="http://www.ncbi.nlm.nih.gov/gene/'+d.GeneID+'">' + d.GeneID + '</a><br/>'+'<b>GO Terms : </b>'+ makeGOTerms(d) + '<label class="showMoreItems">MORE...</label></p>';           
        } 
    });
    $('.goterms').dialog({
        title: s,
        width: 465,
        height: 275,
        left: x,
        top: y,
        cancelBtn: false,
        iconTitle:false,
        rang: true,
        html:html
    }); 
    $('.goterms').trigger("click");   
}

function setActiveTab(tab){
    $('#nv li.active').removeClass('active');
    switch(tab){
        case 'search':
            $('#nv li#tab_search').addClass('active');
            break;
        case 'batch':
            $('#nv li#tab_batch').addClass('active');
            break;
        case 'tutorials':
            $('#nv li#tab_tutorials').addClass('active');
            break;
        case 'download':
            $('#nv li#tab_download').addClass('active');
            break;
        case 'about':
            $('#nv li#tab_about').addClass('active');
            break;
        case 'contact':
            $('#nv li#tab_contact').addClass('active');
            break;
        default:
            $('#nv li#tab_search').addClass('active');
            break; 
    }
}

function loadTreeView(search_word){
    $.ajax({
        type: 'POST',
        url: _jTreeUrl,
        dataType: "JSON",
        data: {
            'search_word': search_word
        },
        async: false,
        success: function(initdata){
            $('#sidebar1 .loading').hide();
            if ($('#dttree').length == 0){
                var termTreeHtml = '<div class="treeview" id="dttree"></div>';
                $('#sidebar1').prepend(termTreeHtml);
            }
            $("#dttree").jstree({
                "themes" : {
                    "theme" : "default",
                    "dots" : false,
                    "icons" : false
                },
                "json_data" : {
                    "data": [{
                        "attr" : {
                            "id" : "li_4"
                        },
                        "data": {
                            "title":"disease",
                            "attr":{
                                "id":"4", 
                                "href": "javascript:void(0);",
                                "class":"treenode"
                            }
                        },
                        "state": "closed"
                    }],
                    "ajax" : {
                        "url" : "../ajax/TreeNode.ajax.php",
                        "data" : function(n) {
                            return {
                                term_id : n.attr("id") == null? "0" : n.attr("id").split('_')[1]
                            };
                        }
                    },
                    "progressive_render" : true
                },
                "plugins" : [ "themes", "json_data", "contextmenu"],
                "core":{
                    "initially_load": initdata.initially_load,
                    "initially_open": initdata.initially_open
                }
            }).bind("open_node.jstree", function (event, data) {
                if((data.inst._get_parent(data.rslt.obj)).length) {
                    data.inst.open_node(data.inst._get_parent(data.rslt.obj), false,true);
                }
            }).bind('after_open.jstree', function (e, data) {
                var count = _selectedNodes.length;
                for (var i = 0; i < count; i++){
                    if (!$('#sidebar1 li#' + _selectedNodes[i]).hasClass('selected')){
                        $('#sidebar1 li#' + _selectedNodes[i]).addClass('selected');
                    }
                }
            });
            var count = initdata.queried_ids.length;
            for (var i = 0; i < count; i++){
                _selectedNodes[i] = initdata.queried_ids[i];
            }
        }
    });
}



function searchNode(){
    var searchWord = '';
    for (var key in _treeSelectedNames){
        searchWord += _treeSelectedNames[key] + '|';
    }
    _treeSelectedNames = {};
    var searchType = 'd2g';
    $('#network_container').hide();
    showSearchResult(searchWord, searchType);
    loadTreeView(searchWord);
    loadDiseaseDetail(searchWord);
    //store the searchword and searchtype
    $('#network_container').attr('class',searchWord+'_'+searchType);
}

function clearTreeview(){
    //    if($('#sidebar1 li').hasClass('highlighted')){
    //       $('#sidebar1 li').removeClass('highlighted');
    //    }
    $('#sidebar1 li').each(function(){
        if($(this).hasClass('highlighted')){
            var name=trimString($(this).find('a').eq(0).text());
            var id = $(this).find('a').eq(0).attr('id');
            _treeSelectedNames[id] = name; 
            $(this).removeClass('highlighted').addClass('selected');        
        }    
    });
}

//make treenode be red,remove the hightlighted nodecolor
function resetTreeview(){
    var searchWord=$('#network_container').attr('class').split('_')[0];
    var searchType=$('#network_container').attr('class').split('_')[1];
    if(_sWord == searchWord && _sType == searchType&&window.vis) {//search only when search words changes
        $('#sidebar1 li').each(function(i){
            var name=trimString($(this).find('a').eq(0).text());
            var id = $(this).find('a').eq(0).attr('id');
            if(name!=''&&vis.node(name)){
                visBypass(name,'#FFFFFF');
                if(!$(this).hasClass('selected')){
                    $(this).removeClass('highlighted').addClass('selected');
                    _treeSelectedNames[id] = name;
                //alert(i);
                }   
            }                    
        }); 
    }
}


function visBypass(id , nodecolor){
    var fnbs=vis.firstNeighbors([id]);
    var bypass = {
        nodes: { }, 
        edges: { }
    };
    var props = {
        color: nodecolor
    };   
    for(var i=0;i<fnbs.neighbors.length;i++){
        bypass["nodes"][fnbs.neighbors[i].data.id] = props;
        vis.visualStyleBypass(bypass);     
    }            
}


//var id = $(e.target).attr('id');
//var name = trimString($(e.target).text());

function makeDetailHtml(data){
    var count = 0;
    var i = 0;
    var html = '<div style="display: none; " class="detail_info" id="detail_info_' + data.index + '">'
    // id
    html += '<p class="detail_id">id: DOID:' + data.info.id + '</p>';
    // name
    html += '<p class="detail_name">name: ' + data.info.name + '</p>';
    //def
    if (data.info.definition != null){
        // html += '<p class="detail_def">def: ' + data.info.definition + '</p>';
        var s=data.info.definition;
        if(s.match('url')){
            var t1=s.substring(s.lastIndexOf('[')+1,s.lastIndexOf(']'));
            var t2=s.substring(0,s.lastIndexOf('['));
            var txt=t2.substring(1,t2.length-2);
            html += '<p class="detail_def">def: '+ txt+'url:';
            if(t1.match(',')){
                var t=t1.split(',');
                count=t.length;
                for(i=0;i < count;i++){
                    if(t[i].match('http')){
                        html+='<a href="'+makeUrl(t[i])+ '" target="blank" style="text-decoration:underline;">'+makeUrl(t[i])+'</a>'+(i==(count-1)? '&nbsp;':'&nbsp;|&nbsp;');   
                    }
                }     
            }else{
                html +='<a href="'+makeUrl(t1)+ '" target="blank" style="text-decoration:underline;">'+makeUrl(t1)+'</a>';
            }
            html+='</p>';
        }
    } 
    // alt_id
    if (data.info.alt_ids != null){
        count = data.info.alt_ids.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_alt_id">alt_id: DOID:' + data.info.alt_ids[i].alt_id + '</p>';
        }
    } 
    // subset
    if (data.info.subsets != null){
        count = data.info.subsets.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_subset">subset: ' + data.info.subsets[i].subset + '</p>';
        }
    }
    // synonym
    if (data.info.synonyms != null){
        count = data.info.synonyms.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_synonym">synonym: ' + data.info.synonyms[i].synonym + '</p>';
        }
    }   
    // xref
    if (data.info.xrefs != null){
        count = data.info.xrefs.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_xref">xref: ' + data.info.xrefs[i].xref_name + ':' + data.info.xrefs[i].xref_value + '</p>';
        }
    } 
    // relations
    if (data.info.relations != null){
        count = data.info.relations.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_relation">' + data.info.relations[i].relation + ': ' + data.info.relations[i].term2_id + '</p>';
        }
    }
    html += '</div>';
    return html;
}

// create url for disease difinition
function makeUrl(s){
    var ts=s.replace('\\','');
    var arr=ts.split(':');
    var url=arr[1]+':'+arr[2];  
    return url;        
}
    
    
function showSearchResult(searchWord, searchType){
    $('#topnav .ui-tabs-selected').removeClass('ui-tabs-selected');
    $('#topnav .ui-state-active').removeClass('ui-state-active');
    $('#topnav #table_view').addClass('ui-tabs-selected');
    $('#topnav #table_view').addClass('ui-state-active');
    $('.disease_detail').hide();
    $('#result_container').show(); 
    if (_searchWord == searchWord && _searchType == searchType){
        $('#network_container').hide();
        return;      
    } 
    _searchWord = searchWord;      
    _searchType = searchType;
    $('#result_container .result').remove();          
    $('#network_container .network_content').remove();
    var tableHtml = '<div id="result_1" class="result" style="display:none"><table id="table_result_1"></table>'+
    '<form  name="exportmappingresultids_01" action="../ajax/Download.ajax.php" method="post"><input name="exportType" type="hidden" value="data" /><input class="dwn" name="exportids" type="button" value="download the search result" /><input name="query" type="hidden" value="" /><input name="qtype" type="hidden" value="" /></form>'+
    '</div>'+
    '<div id="result_2" class="result" style="display:none">'+
    '&nbsp;<span class="relselect">select the interaction type :</span>&nbsp;<select id="relselectbox" name="relationType" class="relselect">'+
    '<option value="">co_expression</option>'+
    '<option value="">co_localization</option>'+
    '<option value="">physical_interactions</option>'+
    '<option value="">genetic_interactions</option>'+
    '<option value="">shared_protein_domains</option>'+
    // '<option value="">show all</option>'+  
    '</select>'+
    '<div id="table_box"></div>'+
    '<form name="exportmappingresultids_02" action="../ajax/Download.ajax.php" method="post">'+
    '<input name="exportType" type="hidden" value="rel_data" />&nbsp;<input class="dwn" name="exportids" type="button" value="download the search result" /><input name="query" type="hidden" value="" /><input name="qtype" type="hidden" value="" /><input name="type" type="hidden" value="" />'+
    '<span class="pbtn"><label id="loadLabel">Loading...</label><input id="lastpage" class="pageBtn"  type="button" value="Previous Page"/>&nbsp;<label id="pageIndex"></label>&nbsp;<input id="nextpage" class="pageBtn" type="button" value="Next Page"/></span>'+
    '</form>'+
    '</div>';
    $('#result_container').append(tableHtml);
    $('#result_container .loading').show();
    loadSearchResult(searchWord, searchType);
    $('#result_container #result_1').show();
}

function loadSearchResult(searchWord, searchType){
    var colModel_now = "";
    if (searchType == 'd2g'){
        colModel_now = _colModel_d2g;
    } else if (searchType == 'g2d'){
        colModel_now = _colModel_g2d;
    }
    
    $('#result_container .loading').hide();
    $("#result_container #table_result_1").flexigrid({
        url: '../ajax/Search.ajax.php',
        method: 'POST',
        dataType: 'json',
        colModel : colModel_now, 
        nowrap : false,
        striped : true,
        usepager: true, 
        useRp: true, 
        rp: 15,
        showTableToggleBtn: true, 
        query: searchWord,
        qtype: searchType,
        height: 480,
        sortname: "symbol",
        sortorder: "asc",
        nomsg: 'No Mapping Found.'
    });
}

//load the table for relations betweeen genes
function loadGeneRelation(searchWord, searchType ,Type ,Sel){
    if (_pWord == searchWord && _pType == searchType&&_selectedType==Type) 
        return; 
    _pWord = searchWord;
    _pType = searchType;
    _selectedType=Type;
    $('#result_2 .table_detail').hide();
    var id;
    if(Type=='show all'){
        id='showall';
    }else{
        id=Type;
    }       
    var l=$('#result_2 #'+id).length;
    if(l>0){
        $('#result_2 #'+id).show();     
        $('#pageIndex').text($('#'+id).attr('index'));
        $('#result_2 form,#result_2 .relselect').show();
        return;
    }
    $('#result_2 .relselect,#result_2 form').hide();
    $('#result_container .loading').show();
    $.ajax({
        type:'POST',
        url:_geneRelationUrl,
        dataType: "JSON",
        data:{
            query: searchWord,
            qtype: searchType,
            type: Type,
            sel: Sel,
            m:0,
            n:0
        } ,
        async: false,
        success:function(data){
            $('#result_container .loading').hide();
            $('.relselect').show();
            $('#result_container #result_2 form').show();
            var tableHtml = '<table class="table_detail" id="'+id+'" border=0 bgcolor="white" cellSpacing=3 cellpadding=5 width=98%>';
            tableHtml += '<tbody><tr bgcolor="#dde6f3"><th class="sort_th" align="left" >Gene1_Name</th><th align="left">Gene2_Name</th><th align="left">Network</th><th align="left">Type</th><th align="left">Weight</th></tr>';
            if(data.table_list_count > 0){
                // $.each(eval('(' + data.table_list_data+ ')'),function(i,e){
                $.each(data.table_list_data,function(i,e){
                    var stream = makeTableItem(i,e);
                    tableHtml += stream;                   
                });
            }
            tableHtml+= '</tbody></table>';
            $('#result_container #result_2 #table_box').append(tableHtml);
            //$('#result_2 #table_box table #'+Type).attr('index', 1);
            $('#'+Type).attr('m',data.m);
            $('#'+Type).attr('n',data.n);
            $('#'+Type).attr('index',1);
            $('#pageIndex').text('');
            if(data.table_list_count<50){
                $('.pageBtn').hide();
            }else{
                $('.pageBtn').hide();
                $('#nextpage').show();
            }
        }
    });
    sortTableview('co_expression',1);
    setStyle(Type);
}

function makeG2gNextItems(searchWord, searchType ,Type, m , n){
    if(Type=='show all'||$('#'+Type+' tr').length<=1){
        return;
    }
    $('.pbtn #loadLabel').show();
    $.ajax({
        type:'POST',
        url:_geneRelationUrl,
        dataType: "JSON",
        data:{
            query: searchWord,
            qtype: searchType,
            type: Type,
            m:m,
            n:n        	
        },
        async: false,
        success:function(data){
            var newTableItems='';
            if(data.table_list_count> 0){
                $.each(data.table_list_data,function(i,e){
                    var stream = makeTableItem(i,e);
                    if(typeof(stream)!=undefined&&stream){
                        newTableItems+=stream; 
                    }         
                });
            }
            $('#'+Type+' tbody').append(newTableItems);
            //alert($('#'+Type+' tr').length);
            if(data.table_list_count<50){
                $('#nextpage').hide();
                $('#lastpage').show();
            }
            $('#'+Type).attr('m',data.m);
            $('#'+Type).attr('n',data.n);
            $('.pbtn #loadLabel').hide();
        }
    });  
}

function showNextPage(Type,index,direction){
    var searchWord=$('#network_container').attr('class').split('_')[0];
    var searchType=$('#network_container').attr('class').split('_')[1];
    var m=$('#'+Type).attr('m');
    var n=$('#'+Type).attr('n');
    if(direction==1){
        if($('#'+Type+' tr').length<(parseInt(index)*50+1)){
            makeG2gNextItems(searchWord, searchType ,Type , parseInt(m) , parseInt(n)); 
        }         
        $('#lastpage').show();    
    }else if(direction==0){
        if(index==1){
            $('#lastpage').hide(); 	
        }
        $('#nextpage').show();
    }
    $('#'+Type+' tr').show();
    $('#'+Type+' tr:lt('+parseInt((index-1)*50+1)+')').hide();
    $('#'+Type+' tr:gt('+parseInt(index*50)+')').hide();
    $('#'+Type+' tr:eq(0)').show();
    $('#'+Type).attr('index',index);
    setStyle(Type);
    $('#pageIndex').text(index);
}

//show the table row
function makeTableItem(i,e){
    //var item = '<tr class="tr_item" bgcolor="' + (i % 2 == 0 ? '#ECECEC' : '#FFFFFF') + '">' + 
    var item = '<tr class="tr_item">'+
    '<td style="width: 15%;">' + e.g1Symbol + '</td>' + 
    '<td style="width: 15%;">' + e.g2Symbol + '</td>' ;
    if(!isNaN(parseInt(e.pubmedid))){
        item+= '<td style="width: 20%;">' + '<a style="text-decoration:none;" target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed?term='+e.pubmedid+'">'+e.network+'</a>' + '</td>'; 
    }else{
        item+= '<td style="width: 20%;">' + '<a style="text-decoration:none;" target="_blank" href="'+e.pubmedid+'">'+e.network+'</a>' + '</td>'; 
    }
    item+='<td style="text-align: left;width: 13%;">'+e.type + '</td>'+'<td style="text-align: left;width: 13%;">'+e.weight + '</td></tr>';
    return item; 
}

//sort the rows by alphabet
function  sortTableview(type,index){
    var rows=$('#result_2 #'+type).find('tr').has('td').get();
    //alert(rows);
    rows.sort(function(a,b){
        var key_a=$(a).children('td').eq(index-1).text();
        key_a=$.trim(key_a);
        var key_b=$(b).children('td').eq(index-1).text();
        key_b=$.trim(key_b);
        if(key_a > key_b) return   1;
        if(key_a < key_b) return  -1;
        return 0
    });
    $.each(rows,function(i,e){
        $('#result_2 #'+type).append(e);
    //       if(i%2==1){
    //         $(e).css('background','#ECECEC');
    //       }else{
    //         $(e).css('background','#FFFFFF');  
    //       }
    });
    //setStyle(type); 
    var page=$('#'+type).attr('index');
    $('#'+type+' tr').show();
    $('#'+type+' tr:lt('+parseInt((page-1)*50+1)+')').hide();
    $('#'+type+' tr:gt('+parseInt(page*50)+')').hide();
    $('#'+type+' tr:eq(0)').show();
}


function setStyle(type){
    var tr=$('#'+type).find('tr');
    for(var i=1;i<tr.length;i++){
        var t=tr[i]
        if(i%2==0){
            $(t).css('background','#ECECEC');
        }else{
            $(t).css('background','#FFFFFF');
        }	
    }
}


function trimString(str){
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function loadDiseaseDetail(searchWord){
    $('#tabs-center #disease_detail').contents().remove();
    var html = '';
    var genes = searchWord.split('|');
    for (var i = 0; i < genes.length; i++){
        if (genes[i] == ''|| genes[i] == null){
            continue;
        }
        var name = trimString(genes[i]);
        //        html += '<h3 id="' + genes[i] + '"><span></span><a href="#">' + genes[i] + '</a></h3><div><p></p></div>';
        html += '<div id="detail_div_' + i + '" class="detail_div closed"><div class="detail_closed"><span class="detail_title">' + name + '</span></div></div>';
    //        html += '<div style="display: none; " class="d" id="d_' + i + '">' + 
    //'<div class="a">David Cook - Light On</div>' +  
    //'<div class="a">Pink - So What</div>' + 
    //'<div class="a">Leona Lewis - Better in Time</div>' + 
    //'<div class="a">T.I. - Whatever You Like</div>' + 
    //'</div>';
    }   
    $('#tabs-center #disease_detail').append(html);
}

//load the initial networkview
function loadNetworkView(searchWord, searchType){
    if (_sWord == searchWord && _sType == searchType) 
        return; 
    _sWord = searchWord;
    _sType = searchType;

    var maxValue;
    var minValue = 20;
    var defaultValue = 20;
    if(searchType=='d2g'){
        maxValue=50;
    }else{
        maxValue=100;   
    }
    $('#waiting').show();
    $.ajax({
        type: 'POST',
        url: _getNewDataUrl,
        //url:_testUrl,         //FOR TEST
        dataType: "JSON",
        //dataType: "script",   //FOR TEST
        data: {
            'query': searchWord, 
            'qtype': searchType, 
            'view' : 'nv',
            'count': defaultValue
        },
        async: false,
        success: function(data){
            var chooseHtml= '<div class="choosebox network_content pane ui-layout-east" id="choosebox">'+
            '<div id="chooseTitle"><span>Choose Interaction Type</span></div>'+
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:#999999;"><input class="choosetype" type="checkbox" name="reltype" value ="d2g" checked="checked"><span>Gene Disease</span>'+
            '</div>'+
            '</div>'+                    
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:blue;"><input class="choosetype" type="checkbox" name="reltype" value ="genetic_interactions" checked="checked"><span>Genetic Interactions</span>'+
            '</div>'+
            '</div>'+
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:#cf2aea;"><input class="choosetype" type="checkbox" name="reltype" value ="physical_interactions" checked="checked"><span>Physical Interactions</span>'+
            '</div>'+
            '</div>'+
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:green;"><input class="choosetype" type="checkbox" name="reltype" value ="co_expression" checked="checked"><span>Coexpression</span>'+
            '</div>'+
            '</div>'+
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:#f4550b;"><input class="choosetype" type="checkbox" name="reltype" value ="co_localization" checked="checked"><span>Colocalization</span>'+
            '</div>'+
            '</div>'+
            '<div class="chooseitem">'+
            '<div class="choosecolor" style="height:30px;line-height:30px;color:#00CCFF;"><input class="choosetype" type="checkbox" name="reltype" value ="shared_protein_domains" checked="checked" ><span>Shared Protein Domains</span>'+
            '</div>'+
            '</div>'+
            '<div id="setNumBox"><div id="SNB_title"><span>set maximuwork size for each query</span></div><p id="numLabel"></p><table id="sl" ><tr><td style="width:15%"><label id="num"></label></td><td style="width:60%"><div id="slider"></div></td><td style="width:20%"><input type="button" id="reSetNum" value="GO"></input></td></tr></table>'+
            '<div id="reloading" style="display:none"></div></div>'+
            '</div>';
            var nwvhtml = '<div class="tools network_content">'+
            '<span>Change Network Layout: </span>'+
            '<select name="layout" id="layout" onchange="layout(this)">' + 
            '<option value="1">ForceDirected</option>' + 
            '<option value="2">Circle</option>' + 
            '<option value="3">Radial</option>' + 
            '<option value="4">Tree</option>' + 
            '</select>'+
            '&nbsp;&nbsp;&nbsp;&nbsp;<span>Select the format to export: </span>'+
            '<select name="export" id="export">' + 
            '<option value="">xgmml</option>' +
            '<option value="">png</option>' + 
            '<option value="">sif</option>' + 
            '<option value="">svg</option>' + 
            '<option value="">pdf</option>' +
            '<option value="">graphml</option>' + 
            '</select>'+
            '&nbsp;&nbsp;<input id="exportBtn" type="button" value="Export"></input>' + 
            '</div>' +
            '<div id="container" class="network_content"><div id="show_networkview" class="network_content pane ui-layout-center"></div>'+chooseHtml+'</div>';
            $("#network_container").append(nwvhtml);
            //initialize the choosebox
            var gd=$('#network_container').attr('class').split('_')[1];
            $('.chooseitem input:eq(0)').attr('value',gd);            
            var total_type = 6;
            for(var i=0;i<total_type;i++){
                var type = $('.chooseitem input:eq('+ i +')').attr('value').replace(/\s/g,'');
                edge_checked[type] = true;
            }
            //initialize the silder
            $( "#slider" ).slider({
                value: defaultValue,
                min: minValue,
                max: maxValue,
                step: 1,
                slide: function( e, ui ){
                    $("#num").text(ui.value);
                }
            });
            $("#num").text( $( "#slider" ).slider( "value" ) );
            $('#container').layout({
                east__resizable:  false
                ,
                east__size: 205
                ,
                onclose:      function(){
                    reSizeCytoscapeweb();
                }
                ,
                onopen:       function(){
                    reSizeCytoscapeweb();
                }
            });
            $('#numLabel').text('the default value is '+defaultValue.toString());
            //initialize the reset button
            $('#reSetNum').attr('disabled',false);
            //var d=eval('(' + data+ ')');         //FOR TEST
            //makeCytoscapeWebView("show_networkview",d.cw_node_data,d.cw_edge_data); //FOR TEST
            makeCytoscapeWebView("show_networkview",eval('(' + data.cw_node_data + ')'),eval('(' + data.cw_edge_data + ')'));
            $('#waiting').hide();         
        }
    });
}

//load the node number restricted networkview
function reLoadNetworkView(searchWord, searchType, Nmax){
    if(Nmax==_lastNum){
        return;
    }
    _lastNum=Nmax;
    $('#reloading').show();
    $.ajax({
        type: 'POST',
        url: _getNewDataUrl,
        //url:_testUrl,         //FOR TEST
        dataType: "JSON",
        //dataType: "script",   //FOR TEST
        data: {
            'query': searchWord, 
            'qtype': searchType, 
            'count': Nmax
        },
        async: false,
        success:function(data){
            //initialize the choosebox
            var gd=$('#network_container').attr('class').split('_')[1];
            $('.chooseitem input:eq(0)').attr('value',gd);            
            var total_type = 6;
            for(var i=0;i<total_type;i++){
                var type = $('.chooseitem input:eq('+ i +')').attr('value').replace(/\s/g,'');
                edge_checked[type] = true;
            }
            $('.chooseitem input').each(function(){    
                $(this).attr("checked","checked");  
            });
            //initialize the reset button
            $('#reSetNum').attr('disabled',false);
            //initialize the select form
            $('#layout').get(0).selectedIndex = 0; 
            //$('#layout').get(0).options[0].selected = true;
            //$('#layout').get(0).value=1;
            $('#export').get(0).selectedIndex = 0;
            //var d=eval('(' +data+ ')');         //FOR TEST
            //makeCytoscapeWebView("show_networkview",d.cw_node_data ,d.cw_edge_data); //FOR TEST
            makeCytoscapeWebView("show_networkview",eval('(' + data.cw_node_data + ')'),eval('(' + data.cw_edge_data + ')'));
            $('#reloading').hide();
        }
    });
}


//create the CytoscapeWebView
function makeCytoscapeWebView(id, nodes_data, edges_data){
    network_json = {
        dataSchema : 
        {
            nodes :[
            {
                name : "count", 
                type : "number"
            },{
                name : "ngc",
                type : "string"
            }],           
            edges : [ 
            {
                name : "distance", 
                type : "number"
            },
            {
                name : "egc",
                type : "string"
            }]
        },
        data : 
        {
            nodes : nodes_data,
            edges : edges_data
        }    
    };
    vis = new org.cytoscapeweb.Visualization(id, cw_options);
    vis.ready(function(){ 
        if (!vis.hasListener('click', 'nodes')){
            vis.addListener('click', 'nodes', function(event){
                handle_click(event);
            });
        }
        if (!vis.hasListener('click', 'edges')){
            vis.addListener('click', 'edges', function(event){
                handle_click(event);
            });
        }
        $('#network_container').trigger('start');
        resetTreeview();
    });
    vis.draw({
        network: network_json,
        visualStyle : cw_style,
        panZoomControlVisible: true,
        edgesMerged: false,
        nodeLabelsVisible: true,
        edgeLabelsVisible: false,
        nodeTooltipsEnabled: false,
        edgeTooltipsEnabled: false,
        layout : {
            name : "ForceDirected",
            options : {
        //weightAttr:"distance"
        }
        }
    });
}

//CytoscapeWebView click event
function handle_click(event){
    if (event.group != 'nodes' && event.group != 'edges')
        return;
    var _id = event.target.data.id;
    var _group = event.group;
    var _viewtype = $('#network_container').attr('class').split('_')[1];
    var _ngc='';
    var _egc='';
    var htmlInfo;
    var showId;
    var a = event.target.data.id.split('-')[0];
    var b = event.target.data.id.split('-')[1];
    if(_group == 'nodes'){
        _ngc=event.target.data.ngc;
    }
    if(_group == 'edges'){
        _egc=event.target.data.egc.replace(/\s/g,'');
    }
    if(_group == 'edges'&&_egc=='d2g'){
        showId=_id;
        $.ajax({
            type: 'POST',
            url: _getTextUrl,
            dataType: 'JSON',
            data:{
                'id':a,
                'name':b
            },
            async: false,
            success: function(data){
                htmlInfo='<table style="margin-left:5px;margin-top:5px"><tr><td><b>Query</b></td><td><b>&nbsp;Result</b></td></tr>'+'<tr><td><b>Disease:</b>' + event.target.data.id.split('-')[0]+'</td><td><b>&nbsp;Gene:</b>'+event.target.data.id.split('-')[1]+'</td></tr>';
                htmlInfo += '<tr bgcolor="#ECECEC"><td><b>PubMedID</b></td><td><b>&nbsp;GeneRIF</b></td></tr>';
                if(data.table_list_count > 0){
                    $.each(data.table_list_data,function(i,e){
                        var stream = makeTableItems(i,e);
                        htmlInfo += stream;
                    });
                }
                htmlInfo += '</table>';
            }              
        });
    //htmlInfo='<table style="margin-left:5px;margin-top:5px"><tr><td><b>Query</b></td><td><b>&nbsp;Result</b></td></tr>'+'<tr><td><b>Disease:</b>' + event.target.data.id.split('-')[0]+'</td><td><b>&nbsp;Gene:</b>'+event.target.data.id.split('-')[1]+'</td></tr></table>';     
    }
    else if(_group == 'edges'&&_egc=='g2d')
    {
        showId=_id;
        htmlInfo='<table style="margin-left:5px;margin-top:5px"><tr><td><b>Query</b></td><td><b>&nbsp;Result</b></td></tr>'+'<tr><td><b>Gene:</b>' + event.target.data.id.split('-')[0]+'</td><td><b>&nbsp;Disease:</b>'+event.target.data.id.split('-')[1]+'</td></tr></table>';
    }
    else
    {
        $.ajax({
            type: 'POST',
            url: _getInfoUrl,
            dataType: 'JSON',
            data:{
                id:_id
                ,
                group:_group
                ,
                qtype:_viewtype
                ,
                ngc:_ngc
            },
            async: false,
            success: function(data){
                if(_group == 'nodes'){
                    showId=_id;
                    if((_ngc=='q'&&_viewtype=='d2g')||(_ngc=='r'&&_viewtype=='g2d')){
                        showId='Disease-'+_id;
                        var s=data.definition;
                        if(s.match('url')){
                            var t1=s.substring(s.lastIndexOf('[')+1,s.lastIndexOf(']'));
                            var t2=s.substring(0,s.lastIndexOf('['));
                            var txt=t2.substring(2,t2.length-3); //there is only one linebreak
                            var def=txt+'url:';
                            if(t1.match(',')){
                                var t=t1.split(',');
                                var n=t.length;
                                for(var i=0;i < n;i++){
                                    if(t[i].match('http')){
                                        def+='<a href="'+makeUrl(t[i]).replace('\\','')+ '" style="text-decoration:underline;" target="_blank">'+makeUrl(t[i]).replace('\\','')+'</a>'+(i==(n-1)? '&nbsp;':'&nbsp;|&nbsp;');
                                    }                          
                                }     
                            }else{
                                def +='<a href="'+makeUrl(t1).replace('\\','')+ '" style="text-decoration:underline;" target="_blank">'+makeUrl(t1).replace('\\','')+'</a>';
                            }
                        }
                        htmlInfo='<p style="margin-left:5px"><span><b>DOID:</b> ' + data.DOID.toString() + '</span><br />' + '<span><b>Name:</b> ' + data.name + '</span><br />' + '<span>' +(data.definition==''? '':('<b>Definition:</b>'+def)) +'</span></p>';                              
                    }
                    else if((_ngc=='q'&&_viewtype=='g2d')||(_ngc=='r'&&_viewtype=='d2g')){
                        showId='Gene-'+_id;
                        htmlInfo='<p style="margin-left:5px;margin-top:5px;">'+'<b>FullName :</b> '+data.FullName+'<br/><b>Symbol :</b>'+data.Symbol+'<br/><b>Type :</b> '+data.Type+ '<br/><b>Chromsome :</b> '+data.Chromsome.toString()+'<br/><b>MapLocation :</b> '+data.MapLocation+'<br/><b>Description :</b> '+data.Description+'<br/><span><b>GOTerms :</b> </span>'+makeGOTerms(data,5)+'...<br/><b>Link to NCBI : </b><a  href="http://www.ncbi.nlm.nih.gov/gene/'+data.GeneID.toString()+'" style="text-decoration:underline;" target="_blank">'+data.GeneID.toString()+'</a></p>';                            
                    }      
                }
                else if(_group == 'edges'){
                    showId=_id;
                    htmlInfo='<p style="margin-left:5px;margin-top:5px;"><b>Gene1:</b>&nbsp;' + event.target.data.target+'<br/><b>Gene2 :</b>&nbsp;'+event.target.data.source+'<br/><b>relationship:</b>&nbsp;'+event.target.data.egc+'</p><table style="margin-left:5px;margin-top:5px;border-top:1px solid #CCC;"><tr><td><b>network</b></td><td style="padding-left:8px;"><b>Weight</b></td></tr>';
                    $.each(data,function(i,e){
                        var url=e.pubmedid;
                        if(!isNaN(parseInt(url))){
                            htmlInfo+='<tr><td><a style="text-decoration:underline;" target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed?term='+e.pubmedid+'">'+e.network+'</a></td><td style="padding-left:8px;">'+e.weight+'</td></tr>';                                                         
                        }else{                        
                            htmlInfo+='<tr><td><a style="text-decoration:underline;" target="_blank" href="'+url+'">'+e.network+'</a></td><td style="padding-left:8px;">'+e.weight+'</td></tr>';   
                        }
                    });
                    htmlInfo+='</table>';                     
                }
            }              
        });
    }
    if ($('.clicked_target').length>0){
        $('.clicked_target').remove();
    }
    var divHtml = '<div class="clicked_target" id=' + _id + ' style="position: relative;z-index: 12; top:' + (event.target.y - 600) +'px; left:'+ event.target.x + 'px; width: 2px;height: 2px;"></div>';
    $('#show_networkview').append(divHtml);
    showTargetDialog(event.mouseX , event.mouseY ,showId, htmlInfo);
}

function makeTableItems(i,e){
    var item = '<tr bgcolor="#ECECEC">' + 
    '<td>' + '<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed?term='+e.generifid+'">'+e.generifid+'</a>' + '</td>' + 
    '<td>' + e.text + '</td></tr>';
    return item; 
}

function showTargetDialog(x , y ,title, html){
    var left = $('#show_networkview').offset().left + x + (x > 300 ? -310 : 10);
    var height = $('#show_networkview').offset().top + y;
    var top = height + 260 > $(window).height() ? height - 160 : height + 40;
    $('.clicked_target').dialog({
        title: title,
        width: 450,
        height: 275,
        left:left,
        top:top,
        cancelBtn: false,
        iconTitle:false,
        rang: true,
        html:html
    }); 
    $('.clicked_target').trigger("click");
}

function layout(layout){
    var selectedValue = layout.selectedIndex;
    var layoutType = layout.options[selectedValue].text;
    var vis_network = vis.networkModel();
    var vis_visualStyle = vis.visualStyle();
    vis.draw({
        network: vis_network,
        visualStyle : vis_visualStyle,
        layout : {
            name : layoutType,
            options : {
        //weightAttr:"distance"
        }
        }
    });   
    $('.chooseitem input').each(function(){    
        $(this).attr("checked","checked");  
    });
}

function  reSizeCytoscapeweb(){
    var w=$('#container').width()-$('#choosebox').width();
    if($('#show_networkview').width() != w){
       $('#show_networkview').width(w);
    }
    var h=$('#container').height();
    if($('#show_networkview').height()!=h){
        $('#show_networkview').height(h);
        $('#choosebox').height(h);
    }
    if($('#choosebox').css('display')=='none'){
        if($('#show_networkview').width()<$('#container').width()){
           $('#show_networkview').width($('#container').width());
        }
    }
}

function clear() {
    //document.getElementById("info").innerHTML = "";
    $("#info").html("");
}

function print(msg) {
    //document.getElementById("info").innerHTML += "<p>" + msg + "</p>";
    $("#info").html("<p>"+msg+"</p>");
}

function makeDetailHtmls(data){
    var count = 0;
    var i = 0;
    var html = '<div style="display:none;width:320px;margin-left:76px;" class="detail_info" id="detail_info_' + data.index + '">'
    // id
    html += '<p class="detail_id" style="font-size:13px;">id: DOID:' + data.info.id + '</p>';
    // name
    html += '<p class="detail_name" style="font-size:13px;">name: ' + data.info.name + '</p>';
    //def
    if (data.info.definition != null){
        // html += '<p class="detail_def">def: ' + data.info.definition + '</p>';
        var s=data.info.definition;
        if(s.match('url')){
            var t1=s.substring(s.lastIndexOf('[')+1,s.lastIndexOf(']'));
            var t2=s.substring(0,s.lastIndexOf('['));
            var txt=t2.substring(1,t2.length-2);
            html += '<p class="detail_def" style="font-size:13px;">def: '+ txt+'url:';
            if(t1.match(',')){
                var t=t1.split(',');
                count=t.length;
                for(i=0;i < count;i++){
                    if(t[i].match('http')){
                        html+='<a href="'+makeUrl(t[i])+ '" target="blank" style="text-decoration:underline;">'+makeUrl(t[i])+'</a>'+(i==(count-1)? '&nbsp;':'&nbsp;|&nbsp;');   
                    }
                }     
            }else{
                html +='<a href="'+makeUrl(t1)+ '" target="blank" style="text-decoration:underline;">'+makeUrl(t1)+'</a>';
            }
            html+='</p>';
        }
    } 

    // relations
    if (data.info.relations != null){
        count = data.info.relations.length;
        for (i = 0; i < count; i++){
            html += '<p class="detail_relation" style="font-size:13px;">' + data.info.relations[i].relation + ': ' + data.info.relations[i].term2_id + '</p>';
        }
    }
    html += '</div>';
    return html;
}