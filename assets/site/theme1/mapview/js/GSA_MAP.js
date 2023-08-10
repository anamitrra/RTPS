var token = "?Token=" + mapjson.mapService[0].token;
var layerurl = mapjson.mapService[0].url;
require(["esri/map", "esri/SpatialReference", "esri/dijit/BasemapGallery", "esri/dijit/BasemapLayer", "esri/dijit/Basemap",
         "esri/layers/ArcGISDynamicMapServiceLayer", "esri/layers/ImageParameters",
         "esri/layers/ArcGISTiledMapServiceLayer",
         "esri/layers/FeatureLayer", "esri/geometry/webMercatorUtils",
         "esri/layers/GraphicsLayer", "esri/geometry/Point", "esri/geometry/Circle", "esri/units", "dojo/_base/event", "esri/geometry/geometryEngine",
         "esri/tasks/query",
         "esri/tasks/QueryTask","esri/lang",
         "esri/geometry/Extent",
         "dojo/dom-construct", "dojo/dom-style", "dojo/mouse",
         "dojo/dom-class",
         "dojo/dom", "esri/dijit/Scalebar","esri/dijit/HomeButton",
         "esri/symbols/SimpleMarkerSymbol",
         "esri/symbols/SimpleLineSymbol",
         "esri/symbols/SimpleFillSymbol", "esri/symbols/PictureMarkerSymbol",
         "esri/renderers/UniqueValueRenderer","esri/renderers/SimpleRenderer",
         "esri/symbols/Font",
         "esri/symbols/TextSymbol", "esri/tasks/GenerateRendererParameters", "esri/tasks/GenerateRendererTask",
        "esri/layers/LayerDrawingOptions","esri/tasks/UniqueValueDefinition",
         "esri/Color",
         "esri/graphic","esri/dijit/Search",
         "esri/InfoTemplate", "esri/dijit/InfoWindow", "esri/dijit/Print",
         "esri/tasks/PrintTemplate", "esri/config", "esri/request",
         "dojo/_base/array", "dojo/_base/connect",
         "dojox/grid/DataGrid",
         "dojo/data/ItemFileWriteStore", "dojo/data/ItemFileReadStore",
         "dijit/Dialog",
         "dijit/ColorPalette",
         "dojo/_base/lang",
         "esri/toolbars/navigation", "esri/dijit/Legend", "esri/dijit/Measurement", "esri/dijit/LayerList",
         "dojo/on", "esri/urlUtils",
         "dojo/parser",
         "dijit/registry", "dijit/TitlePane", "dijit/layout/ContentPane", "agsjs/dijit/TOC", "esri/dijit/LayerSwipe",
         "dojox/charting/Chart", "dojo/store/Memory", "dojox/charting/DataSeries","dojo/store/Observable","dojox/charting/StoreSeries",
        "dojox/charting/action2d/Highlight",
         "dojox/charting/action2d/Tooltip",
         "dojox/charting/plot2d/Columns","dojox/charting/plot2d/Bars",
         "dojox/charting/axis2d/Default",
         "dijit/Toolbar", "dijit/form/Select",
         "dijit/layout/ContentPane", "dijit/layout/TabContainer",
         "dijit/layout/BorderContainer", "dijit/form/ComboBox",
         "dojo/domReady!"
], function (
         Map, SpatialReference, BasemapGallery, BasemapLayer, Basemap,
         ArcGISDynamicMapServiceLayer, ImageParameters,
         ArcGISTiledMapServiceLayer,
         FeatureLayer, webMercatorUtils,
         GraphicsLayer, Point, Circle, units, event, geometryEngine,
         Query, QueryTask,esriLang,
         Extent,
         domConstruct, domstyle,
         mouse,
         domClass,
         dom, Scalebar, HomeButton,
         SimpleMarkerSymbol,
         SimpleLineSymbol,
         SimpleFillSymbol, PictureMarkerSymbol,
         UniqueValueRenderer,SimpleRenderer,
         Font, TextSymbol, GenerateRendererParameters, GenerateRendererTask,
        LayerDrawingOptions,UniqueValueDefinition,
         Color,
         Graphic,Search,
         InfoTemplate, InfoWindow, Print,
         PrintTemplate, esriConfig, esriRequest,
         arrayUtils, connect,
         DataGrid,
         ItemFileWriteStore, ItemFileReadStore,
         Dialog,
         ColorPalette,
         lang,
         Navigation, Legend, Measurement, LayerList,
         on, urlUtils,
         parser,
         registry, TitlePane, ContentPane, TOC, LayerSwipe,
         Chart, Memory, DataSeries, Observable,StoreSeries,
         Highlight, Tooltip, Columns, Bars, Default) {
    parser.parse();
  
    //var ind_ext = new Extent(68.09381543863502, 6.754367934041333, 97.41149826167937, 37.077610645887944, new SpatialReference({ wkid: 4326 }));
    var measure;
    var geometry_data = [];
    //var thematicgraphiclayer = new GraphicsLayer();
    var graphic = new Graphic();
   // var maskcirclelayer = new GraphicsLayer();
    //var stdtbk_graphlyr = new GraphicsLayer();
   // var sfs_stdtbk = new SimpleFillSymbol(SimpleFillSymbol.STYLE_NULL, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([0, 0, 0]), 2), new Color([0, 206, 209, 0.25]));
   // var maskcircleSymb = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SHORTDASHDOTDOT, new Color([105, 105, 105]), 2), new Color([255, 255, 255, 1]));
    var maskcircle = new Circle({
        center: [80, 19],
        geodesic: true,
        radius: 3000,
        radiusUnit: units.MILES
    });
    var map = new Map("map", {
        //basemap: "gray",
        center: [80, 23.5],
        zoom: 4,
        logo: false, showAttribution: false
        
    });
    var basemaps = [];
    var blank = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer", opacity: 0
    });
    var nobasemap = new Basemap({
        layers: [blank],
        title: "India",
        thumbnailUrl: "Images/basemaps images/india_bnd.png"
    });
    basemaps.push(nobasemap);

    var streetlayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer"
    });
    var streetbasemap = new Basemap({
        layers: [streetlayer],
        title: "Street",
        thumbnailUrl: "Images/basemaps images/street.png"
    });
    basemaps.push(streetbasemap);

    var topolayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer"
    });
    var topobasemap = new Basemap({
        layers: [topolayer],
        title: "Topo",
        thumbnailUrl: "Images/basemaps images/topo.png"
    });
    basemaps.push(topobasemap);

    var satellitelayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer"
    });
    var satellitebasemap = new Basemap({
        layers: [satellitelayer],
        title: "Satellite",
        thumbnailUrl: "Images/basemaps images/satellite.png"
    });
    basemaps.push(satellitebasemap);

    var basemapGallery = new BasemapGallery({
        showArcGISBasemaps: false,
        basemaps: basemaps,
        map: map
    }, basemapdiv);
    basemapGallery.on("error", function (msg) {
        console.log("basemap gallery error:  ", msg);
    });
    basemapGallery.startup();

    map.on("load", function () {
        basemapGallery.select("basemap_1");
    });
    var stateLayer = new FeatureLayer(layerurl + "/0"+ token, {
        mode: FeatureLayer.MODE_ONDEMAND,
        //infoTemplate: template,
        outFields: ["stname", "stcode11"],
        displayField: "stname"
       
    });
    var districtLayer = new FeatureLayer(layerurl + "/1" + token, {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["dtname", "dtcode11", "stname"],
        displayField: "dtname",

    });
    var blockLayer = new FeatureLayer(layerurl + "/2" + token, {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["block_name", "blk_lgdcode", "district", "state"],
        displayField: "block_name",

    });
    var gpLayer = new FeatureLayer(layerurl + "/3" + token, {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["gp_name", "gp_code"],
        displayField: "gp_name",

    });
   
    var drpdwn_value = $('#drpdwn li.selected').attr('data-value');
    
    stateLayer.on("load", function () {
        var query = new Query();
        query.where = "1=1";
        query.outFields = ["stname,stcode11"];
        var queryTask = new QueryTask(layerurl + "/" + 0 + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "state_drpdwn", "stname", "stcode11"), function (error) {
            alert("Map service is not available");
            return;
        });
        var s = $('.category_drpdwn').find('.drp')
        s[0].childNodes[0].data = $('#drpdwn li.selected')[0].innerText;
        stateLayer.on("mouse-over", dojo.partial(mouseoverfun));
        stateLayer.on("mouse-out", dojo.partial(mouseoutfun));
        districtLayer.on("mouse-over", dojo.partial(mouseoverfun));
        districtLayer.on("mouse-out", dojo.partial(mouseoutfun));
       blockLayer.on("mouse-over", dojo.partial(mouseoverfun));
       blockLayer.on("mouse-out", dojo.partial(mouseoutfun));
       gpLayer.on("mouse-over", dojo.partial(mouseoverfun));
       gpLayer.on("mouse-out", dojo.partial(mouseoutfun));
        
    });
    
    stateLayer.on("error", function (error) {
       
    });
    var home = new HomeButton({
        map: map
    }, "HomeButton");

    //stateLayer.on("load", function () {
    //    home.extent = stateLayer.fullExtent;
    //});
    home.startup();
   
    var MIS_rec = name =  MIS_UF = chartname= MIS_Fname  = null;
   // var xarray = [];
    var state_drpdwn_value = $('#state_drpdwn li.selected').attr('data-value');
    var dist_drpdwn_value = $('#dist_drpdwn li.selected').attr('data-value');
    var block_drpdwn_value = $('#block_drpdwn li.selected').attr('data-value');
    $('#stdist1,#stdist2')[0].innerHTML = "State";
   
   function ajaxcall(drpdwn_value) {
       state_drpdwn_value = $('#state_drpdwn li.selected').attr('data-value');
       dist_drpdwn_value = $('#dist_drpdwn li.selected').attr('data-value');
       block_drpdwn_value = $('#block_drpdwn li.selected').attr('data-value');
       if (state_drpdwn_value == 37)
       state_drpdwn_value=28
        var node = 'node';
         if (drpdwn_value == 'Performance') {
            if (state_drpdwn_value == 'select') {
               // MIS_UF = 'statecensuscode2011'; MIS_Fname = 'statename';
                MIS_UF = 'censuscode2011'; MIS_Fname = 'StateName';
                url = "http://10.248.113.102/netiay/Services/Service.svc/GetstatewiseMapdata"; data = {"uname":"rd_pmayg","pwd":"pmayg@123"};
                //url = "http://indiawater.gov.in/mRWSRestService/RestServiceImpl.svc/getStateWiseHabSchemeDetails"; data = "uid=9F0CE74B2243E528999D7932D6541948&pwd1=9C10195D326852CE85A242590524A80F&stateid=000&districtid=0000";
            }
            else if (state_drpdwn_value != 'select' && dist_drpdwn_value == 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'DistrictName';
                  url = "http://10.248.113.102/netiay/Services/Service.svc/GetdistrictwiseMapdata"; data = {"uname":"rd_pmayg","pwd":"pmayg@123","state_code":state_drpdwn_value};
           
            }
            else if (dist_drpdwn_value != 'select' && block_drpdwn_value == 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'BLockName';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetBlockwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": state_drpdwn_value, "Censusdist_code": dist_drpdwn_value };

            }
            else if (block_drpdwn_value != 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'PanchyatName';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetPanchyatwiseMapdata"; data = {"uname":"rd_pmayg","pwd":"pmayg@123","state_code":state_drpdwn_value,"Blocklgd_code":block_drpdwn_value};

            }
           
            node = 'DATA';
            name = "Performance";
        }
        
        var mis_fields1 = get_misfieldaliasnames(drpdwn_value);
        $(mis_fields1).each(function (index, item) {
            if ($(this)[0].alias == "Report Card Date")
                mis_fields1.splice(index, 1);
            return;
            });
       var count = mis_fields1.length;
       // if (count == 1)
           // hello('col-lg-12 col-md-12 col-sm-12 col-xs-12 three', count);
        //else if (count == 2)
        //    hello('col-lg-6 col-md-6 col-sm-12 col-xs-12 three', count);
        //else if (count == 3)
        //    hello('col-lg-4 col-md-4 col-sm-12 col-xs-12 three', count);
        //else if (count == 4)
        //    hello('col-lg-3 col-md-3 col-sm-6 col-xs-12 three', count);
        //else if (count == 5)
       //    hello('col-lg-1-5 col-md-1-5 col-sm-12 col-xs-12 three', count);
           if (count == 8)
              hello_one('col-lg-1-4 col-md-1-4 three_one', count);
        //else if(count==18)
        //    hello_one('col-lg-1-4 col-md-1-4 three_one', count);
       
        var water_row = document.getElementById('row_water');
        
        for (var i = 0; i < mis_fields1.length; i++) {
            water_row.childNodes[i].childNodes["0"].data = mis_fields1[i].alias;
            water_row.childNodes[i].dataset.value= mis_fields1[i].name;
            water_row.childNodes[i].dataset.title= mis_fields1[i].maptitle;
        }
        
        chartname = water_row.childNodes[0].dataset.title;

        $.ajax({
            type: 'POST',
            url: url,
            dataType: "xml",
            data: JSON.stringify(data),//JSON.stringify({"uname": "sbmgis", "pwd": "sbmgis@2017#"}),
            crossDomain: true,
            contentType: "application/json",//"application/json",
            success: function (dat) {
                MIScontent = dat.getElementsByTagName(node);
                if (MIScontent.length < 2 && MIScontent.length >0) {
                    alert("No records found");
                    map.removeAllLayers();
                    $('#chartNode')[0].innerHTML = "";
                    $('.stat_table').find('tbody').empty();
                    $('#info').css('display', 'none');
                    document.getElementById("bodyloadimg").style.display = "none";
                    return;
                }
                MIS_rec = [];
                for (var i = 0; i < MIScontent.length; i++) {

                    MIS_rec[i] = {};
                    var columns = MIScontent[i].getElementsByTagName("*");
                    for (var j = 0; j < columns.length; j++) {
                        if (MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes.length > 0) {
                            if (block_drpdwn_value != 'select' && columns[j].tagName == 'censuscode2011') {
                                MIS_rec[i][columns[j].tagName] = parseInt(MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes[0].data);
                            }
                            else
                            MIS_rec[i][columns[j].tagName] = MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes[0].data.trim();
                        }
                    }
                }
               
                //for (var o = 0; o < MIS_rec.length; o++) {
                //    for (var q = 0; q < mis_fields1.length; q++) {
                //        if (MIS_rec[o][mis_fields1[q]['name']] && Number(MIS_rec[o][mis_fields1[q]['name']]) != 0)
                //            water_row.children[q].children["0"].attributes[2].value = (Number(water_row.children[q].children["0"].attributes[2].value) + Number(MIS_rec[o][mis_fields1[q]['name']])).toFixed(2) / 2;
                //    }
                //}
              
                for (var q = 0; q < mis_fields1.length; q++) {
                    var cary = null;
                    cary = MIS_rec.slice();
                    var cnmae = null;
                    cname = mis_fields1[q].name;
                    cary = cary.filter(function (val) {
                        return val[cname] != 0;
                    });
                    var sum = 0;
                    cary.forEach(function (a, index) {
                        sum += parseFloat(cary[index][cname]);
                    });
                    //var avg = sum.toFixed(2) / cary.length;

                    var avg = sum.toFixed(2);
                    water_row.children[q].children["0"].attributes[2].value = avg;
                }

                gis_mis(MIS_rec, name);
            },
            error: function (err) {
                alert(err.statusText);
            }
        });
    }
   

   function gis_mis(MIS_rec,name) {
       
        //map.removeAllLayers();
       $('#info').css('display', 'none');
       $('#themetitle')["0"].innerHTML = chartname;
        map.removeLayer(stateLayer);
        map.removeLayer(districtLayer);
        map.removeLayer(blockLayer);
        map.removeLayer(gpLayer);
        var defaultSymbol = new SimpleFillSymbol().setStyle(SimpleFillSymbol.STYLE_NULL); defaultSymbol.outline.setStyle(SimpleLineSymbol.STYLE_NULL);
        //var class_Def = new UniqueValueDefinition();
       // class_Def.attributeField = "stcode11";
       // class_Def.baseSymbol = defaultSymbol;
       // class_Def.type = "uniqueValueDef"
        if (state_drpdwn_value == 'select') {
            var renderer = new UniqueValueRenderer(defaultSymbol, "stcode11");
           // $('#myid').css('display', 'none');
        }
        else if (state_drpdwn_value != 'select' && dist_drpdwn_value == 'select') {
            var renderer = new UniqueValueRenderer(defaultSymbol, "dtcode11");
           // $('#myid').css('display', 'block');
        }

        else if (dist_drpdwn_value != 'select' && block_drpdwn_value=='select') {
            var renderer = new UniqueValueRenderer(defaultSymbol, "blk_lgdcode");
            //$('#myid').css('display', 'block');
        }
        else if (block_drpdwn_value != 'select') {
            var renderer = new UniqueValueRenderer(defaultSymbol, "gp_code");
            //$('#myid').css('display', 'block');
        }
        
        if (MIS_rec) {
            var ma = null;
            var mi = null;
            for (var x = 0; x < MIS_rec.length; x++) {
                var f1 = MIS_rec[x][name];
                if (f1) {
                    if (mi == null && MIS_rec[x][name]) {
                        ma = f1;
                        if (Number(f1) != 0)
                            mi = f1;
                    }
                    else {
                        ma = Math.max(f1, ma);
                        if (Number(f1) != 0)
                            mi = Math.min(f1, mi);
                    }
                }
            }
            var loop_len;
            if (MIS_rec.length < 4)
                loop_len = MIS_rec.length;
            else
                loop_len = 4;
            var ra = parseInt((ma - mi) / loop_len);
            ma = parseFloat(ma);
            mi = parseFloat(mi);
            var checkarray1=[];
            var checkarray = [];
            var flag = 0;
           
            for(p = 0; p < MIS_rec.length; ++p) {
                if(MIS_rec[p][name] !== 0) {
                    flag = 1;
                    break;
                }
            }
            var MIS_rec_comp = MIS_rec.slice(0);
            var labelgrap = false;
            if(flag) {
                for (k = 0; k < geometry_data.length; k++) {
                
                    checkarray1.push(geometry_data[k][1]);
                    if (MIS_rec_comp.length > 0) {
                        labelgrap = false;
                        for (var q = 0; q < MIS_rec_comp.length; q++) {
                            labelgrap = false;
                            if (MIS_rec_comp[q][MIS_UF] == 28)
                                var geo_id = "37";
                            else
                                geo_id = MIS_rec_comp[q][MIS_UF];

                            if ((geometry_data[k][1]) == (geo_id)) {
                           
                                checkarray.push(geometry_data[k][1]);
                                if (MIS_rec_comp[q][name] == 0) {
                                    renderer.addValue(geo_id, new SimpleFillSymbol().setColor(new Color([255, 255, 255, 0])));
                                }
                                else if (MIS_rec_comp[q][name] >= mi && MIS_rec_comp[q][name] <= (mi + ra)) {
                                    renderer.addValue(geo_id, new SimpleFillSymbol().setColor(new Color([253, 174, 97, 0.70])));
                                }
                                else if (MIS_rec_comp[q][name] > (mi + ra) && MIS_rec_comp[q][name] <= (mi + (2 * ra))) {
                                    renderer.addValue(geo_id, new SimpleFillSymbol().setColor(new Color([255, 255, 191, 0.70])));
                                }
                                else if (MIS_rec_comp[q][name] > (mi + 2 * ra) && MIS_rec_comp[q][name] <= (mi + (3 * ra))) {
                                    renderer.addValue(geo_id, new SimpleFillSymbol().setColor(new Color([166, 217, 106, 0.70])));
                                }
                                else if (MIS_rec_comp[q][name] > (mi + 3 * ra) && MIS_rec_comp[q][name] <= (ma)) {
                                    renderer.addValue(geo_id, new SimpleFillSymbol().setColor(new Color([26, 150, 65, 0.70])));
                                }
                                labelgrap = true;
                                MIS_rec_comp.splice(q, 1);
                                q--;
                                break;
                            }
                        }
                        if (!labelgrap) {
                            renderer.addValue(geometry_data[k][1], new SimpleFillSymbol().setColor(new Color([255, 255, 255, 0])));
                        }
                    }
                    else {
                        myfunction();
                        break;
                    }

                }
                
            }
            else {
                myfunction();
               
            }
            
           
           
            function myfunction() {
                alert("No records found");
                for (j = 0; j < geometry_data.length; j++) {
                    renderer.addValue(geometry_data[j][1], new SimpleFillSymbol().setColor(new Color([255, 255, 255, 0])));

                }
                document.getElementById("bodyloadimg").style.display = "none";
            }
           
            //var params = new GenerateRendererParameters();
            //params.classificationDefinition = class_Def;
            //var generateRenderer = new GenerateRendererTask(stateLayer.url);
            //generateRenderer.execute(params, applyRenderer, errorHandler);

            //function errorHandler(err) {
                
            //    alert("error");
            //}
            //function applyRenderer(renderer) {
            //    // dynamic layer stuff
            //    var optionsArray = [];
            //    var drawingOptions = new LayerDrawingOptions();
            //    drawingOptions.renderer = renderer;
            //    // set the drawing options for the relevant layer
            //    // optionsArray index corresponds to layer index in the map service
            //    optionsArray[0] = drawingOptions;
            //    map.getLayer("state").setLayerDrawingOptions(optionsArray);
            //    map.getLayer("state").show();
            //    // create the legend if it doesn't exist
            //    //if (!app.hasOwnProperty("legend")) {
            //    //   // createLegend();
            //    //}
            //}

            if (state_drpdwn_value == 'select') {

                    //dynLayer.setRenderer(renderer);
                    stateLayer.setRenderer(renderer);
                    map.addLayers([stateLayer]);
                    stateLayer.show();
                   //dynLayer.show();
                    map.setExtent(stateLayer.fullExtent, true)
            }
            else if (state_drpdwn_value != 'select' && dist_drpdwn_value == 'select') {
                districtLayer.setRenderer(renderer);
                districtLayer.setScaleRange(59000000, 0);
                districtLayer.show();
                map.addLayers([districtLayer]);
                stateLayer.hide();
               
            }
            else if (dist_drpdwn_value != 'select' && block_drpdwn_value=='select') {
                blockLayer.setRenderer(renderer);
                blockLayer.setScaleRange(59000000, 0);
                blockLayer.show();
                map.addLayers([blockLayer]);
                stateLayer.hide();
                districtLayer.hide();
            }
            else if (block_drpdwn_value!='select') {
                gpLayer.setRenderer(renderer);
               gpLayer.setScaleRange(59000000, 0);
                gpLayer.show();
                map.addLayers([gpLayer]);
                stateLayer.hide();
                districtLayer.hide();
                blockLayer.hide();
            }
           stats_counter();
           chart(name,"a_z");
           document.getElementById("bodyloadimg").style.display = "none";
        }
        //add symbol for each possible value
         
           
    }
   
    //counter

    function stats_counter() {
        if ($('.counter').length > 0) {
            $('.counter').each(function (index) {
                increment($(this), parseFloat($(this).data('speed')));
            });
            function increment($this, speed) {
                var current = parseFloat($this.text());
                if (current < $this.data('to') - 1) {
                    parseFloat($this.text((current + 1).toFixed(3)));
                    if (current <= $this.data('to')) {
                        setTimeout(function () { increment($this, speed) }, 0.01);
                    }
                }
                else if (current < $this.data('to') - 0.01) {
                    parseFloat($this.text((current + 0.01).toFixed(3)));
                    if (current <= $this.data('to')) {
                        setTimeout(function () { increment($this, speed) }, 1);
                    }
                }
                else if (current < $this.data('to') - 0.0001) {
                    parseFloat($this.text((current + 0.001).toFixed(3)));
                    if (current <= $this.data('to')) {
                        setTimeout(function () { increment($this, speed) }, 1);
                    }
                }
            }
        }
    }
    //chart

    function chart(name, sort_type) {
        //chartname = name;
       var ary = []; 
        for (var q = 0; q < MIS_rec.length; q++) {
            ary[q] = {};
            ary[q]['value']=(Number(MIS_rec[q][name]));
            ary[q]['text'] = MIS_rec[q][MIS_Fname];
        }
        if (sort_type == 'a_z') {
            function compare(a, b) {
                if (a.text < b.text)
                    return -1;
                if (a.text > b.text)
                    return 1;
                return 0;
            }

            ary.sort(compare);
        }
        else if (sort_type == '9_0') {
            ary.sort(function (a, b) {
                return parseFloat(b.value) - parseFloat(a.value);
            });
        }
        else {
            ary.sort(function (a, b) {
                return parseFloat(a.value) - parseFloat(b.value);
            });

        }
        dojo.empty("chartNode");
        var chart_label = [];
        var data_store = new Observable( new Memory({ data: ary}));
        data_store.query({}).forEach(function (ary_data,item) {
            var jsonData = {};
            if (chart_label.length == 0) {
                jsonData["text"] = ary_data.text;
                jsonData["value"] = 1;
            }
            else {
                jsonData["text"] = ary_data.text;
                jsonData["value"] = chart_label[item - 1].value + 1;
            }
            chart_label.push(jsonData);
        }); 
        var chart = new Chart("chartNode");
        chart.addPlot("default", {
            type: "Columns",
            markers: false,
            gap: 1,
            hAxis: "x", vAxis: "y", label: true

        });
       chart.addAxis("x", {
           horizontal: false, labels: chart_label,
            rotation: 45, majorTicks: false, minorLabels: true,
            minorTicks: false, majorTickStep: 1, font: "normal normal bold 6pt Arial", dropLabels: false, labelSizeChange: true,
        });
        chart.addAxis("y", {
            vertical: true,
        });
       
        chart.addSeries("x", new StoreSeries(data_store, { query: {} }, "value"), { stroke: "#d56092", fill: "#d56092" }),
        new Highlight(chart, "default");
        new Tooltip(chart, "default", {
            text: function (event) {
                return chartname + '   ' + event.y + "%";
            }
        });
        chart.render();
        if (ary.length > 37)
            chart.resize(900, 250);
        else
            chart.resize(570, 240);
        create_table("top", name);
       
    }
    //create table
    function create_table(selection,name) {
        $('#stdist3')[0].innerHTML = chartname+" (%)";
        $('#stdist4')[0].innerHTML = chartname+" (%)";
        var stat_ary = null;
        stat_ary = MIS_rec.slice();
        stat_ary.forEach(function (a, index) {
            if (a[name] == 0) {
                delete stat_ary[index]
               
            }
        });
        if (selection == "top") {
            stat_ary.sort(function (a, b) {
                return parseFloat(b[name]) - parseFloat(a[name]);
            });

            var newary = stat_ary.slice(0, 5);

        }
        else {
            stat_ary.sort(function (a, b) {
                return parseFloat(a[name]) - parseFloat(b[name]);
            });
            var newary = stat_ary.slice(0, 5);
        }
       
        $('.stat_table').find('tbody').empty();
       
        for (var k = 0; k < newary.length; k++) {
            var trow = "<tr><td>" + newary[k][MIS_Fname] + "</td>";
            trow += "<td>" + newary[k][name] + "</td></tr>";
            $('.stat_table').find('tbody').append(trow);
        }
       

    }
    //click on tab
   

    //$(document).on('click', ".three", function () {
        
    //    $("div.three").removeClass("threeactive");
    //    $(this).addClass("threeactive");
    //    name = $(this)[0].dataset.value;
    //    chartname = $(this)[0].childNodes[0].data;
    //    gis_mis(MIS_rec, name);
    //});
    $(document).on('click', ".three_one", function () {
       
        $("div.three_one").removeClass("three_oneactive");
        $(this).addClass("three_oneactive");
        name = $(this)[0].dataset.value;
        chartname = $(this)[0].dataset.title;
        //$('#stdist3')[0].innerHTML = chartname+" (%)";
        //$('#stdist4')[0].innerHTML = chartname+" (%)";
        gis_mis(MIS_rec, name);
    });
    //statistic table option
    $(".nav-tabs li").click(function () {
        if ($(this)[0].textContent == "Top 5 States"){
            $('#stdist1')[0].innerHTML = "State";
            create_table("top",name);
        }
        else if($(this)[0].textContent == "Top 5 Districts"){
            $('#stdist1')[0].innerHTML = "District";
            create_table("top",name);
        }
        else if ($(this)[0].textContent == "Top 5 Blocks") {
            $('#stdist2')[0].innerHTML = "Block";
            create_table("top", name);
        }
        else if ($(this)[0].textContent == "Top 5 Gram panchayats") {
            $('#stdist2')[0].innerHTML = "Gram panchayat";
            create_table("top", name);
        }
        else if ($(this)[0].textContent == "Bottom 5 States") {
            $('#stdist2')[0].innerHTML = "State";
            create_table("bottom", name);
        }
        else if($(this)[0].textContent == "Bottom 5 Districts") {
            $('#stdist2')[0].innerHTML = "District";
            create_table("bottom", name);
        }
    
    else if ($(this)[0].textContent == "Bottom 5 Blocks") {
        $('#stdist2')[0].innerHTML = "Block";
        create_table("bottom", name);
    }
    else if ($(this)[0].textContent == "Bottom 5 Gram panchayats") {
        $('#stdist2')[0].innerHTML = "Gram panchayat";
        create_table("bottom", name);
    }
        
    });
    //click on sort buttons
    
    $(".sortnav li").click(function () {
        if ($(this)[0].id == "a_z")
            chart(name,"a_z");
        else if ($(this)[0].id == "9_0")
            chart(name, "9_0");
        else
            chart(name, "0_9");
    });

  //click on category dropdown
    $("#drpdwn li").click(function () {
        drpdwn_value = $(this)[0].attributes["0"].nodeValue;
        var s = $('.category_drpdwn').find('.drp')
        s[0].childNodes[0].data = $(this)[0].innerText;
        if (drpdwn_value == "districtwisereport") {
            distquery("");
            return;
        }
        else
        ajaxcall(drpdwn_value);

    });
    //search
    var search = new Search({
        enableInfoWindow: false,
        sources: [],
        map: map
    }, "");

    function doSearchState(stcode11) {
        if (stcode11=='28') {
            stcode11='37';
        }
        var highlightSymbol1;
        search.sources = [];
        var sources = search.sources;
        var selectedValue = stcode11;
       
        sources.push({
            featureLayer: stateLayer,
            // placeholder: "State",
            enableLabel: false,
            searchFields: ["stcode11"],
            displayField: "stname",
            exactMatch: false,
            name: "State",
            outFields: ["*"],
            //color: "#00ff00",
            //infoTemplate: new InfoTemplate("StateInfo")

        });
        search.set("stcode11", sources);
        search.sources[0].highlightSymbol = highlightSymbol1; //set the symbol for the highlighted symbol
        search.search(selectedValue);
        search.startup();

    }
    function doSearchDist(dtcode11) {
        var highlightSymbol1;
        search.sources = [];
        var sources = search.sources;
        var selectedValue = dtcode11;
       
        sources.push({
            featureLayer: districtLayer,
            // placeholder: "State",
            enableLabel: false,
            searchFields: ["dtcode11"],
            displayField: "dtname",
            exactMatch: false,
            name: "District",
            outFields: ["*"],
            //color: "#00ff00",
            //infoTemplate: new InfoTemplate("StateInfo")

        });
        search.set("dtcode11", sources);
        search.sources[0].highlightSymbol = highlightSymbol1; //set the symbol for the highlighted symbol
        search.search(selectedValue);
        search.startup();

    }

    function doSearchBlock(blk_lgdcode) {
        var highlightSymbol1;
        search.sources = [];
        var sources = search.sources;
        var selectedValue = blk_lgdcode;
       
        sources.push({
            featureLayer: blockLayer,
            // placeholder: "State",
            enableLabel: false,
            searchFields: ["blk_lgdcode"],
            displayField: "block_name",
            exactMatch: false,
            name: "Block",
            outFields: ["*"],
            //color: "#00ff00",
            //infoTemplate: new InfoTemplate("StateInfo")

        });
        search.set("blk_lgdcode", sources);
        search.sources[0].highlightSymbol = highlightSymbol1; //set the symbol for the highlighted symbol
        search.search(selectedValue);
        search.startup();

    }


    //click on state graphic
    var click_flag = "";
    stateLayer.on("click", function (evt) {
        if(measure==false){
            click_flag = "dist";
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
            $('#stdist1')[0].innerHTML="District";
            $('.dist_dropdown').css('visibility', 'visible');
            var stcode11 = esriLang.substitute(evt.graphic.attributes, '${stcode11}');
            distquery(stcode11);
            doSearchState(stcode11);
       
            //search 
        
            var listItems = $("#state_drpdwn li");
            listItems.each(function (idx, li) {
            
                if ($(li).attr('data-value') == stcode11) {
                    $(li).addClass("selected");
                    var s = $('.state_dropdown').find('.drp')
                    s[0].childNodes[0].data = li.innerText;
                }
                else
                    $(li).removeClass("selected");
            });
       
        }
    });

    // click on dist graphic

    districtLayer.on("click", function (evt) {
        if(measure==false){
            $('.dist_dropdown').css('visibility','visible');
            $('.block_dropdown').css('visibility','visible');
            click_flag = "dist";
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
            $('#stdist1')[0].innerHTML="Block";
        
            var dtcode11 = esriLang.substitute(evt.graphic.attributes, '${dtcode11}');
       
            doSearchDist(dtcode11);
            blockquery(dtcode11);
       
            //search 
        
            var listItems = $("#dist_drpdwn li");
            listItems.each(function (idx, li) {
            
                if ($(li).attr('data-value') == dtcode11) {
                    $(li).addClass("selected");
                    var s = $('.dist_dropdown').find('.drp')
                    s[0].childNodes[0].data = li.innerText;
                }
                else
                    $(li).removeClass("selected");
            });
       
        }
    });
    // click on block graphic

    blockLayer.on("click", function (evt) {
        if(measure==false){
            $('.block_dropdown').css('visibility','visible');
            click_flag = "dist";
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Gram panchayats';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Gram panchayats';
            $('#stdist1')[0].innerHTML="Gram panchayat";
        
            var blk_lgdcode = esriLang.substitute(evt.graphic.attributes, '${blk_lgdcode}');
       
            doSearchBlock(blk_lgdcode);
            gpquery(blk_lgdcode);
       
            //search 
        
            var listItems = $("#block_drpdwn li");
            listItems.each(function (idx, li) {
            
                if ($(li).attr('data-value') == blk_lgdcode) {
                    $(li).addClass("selected");
                    var s = $('.block_dropdown').find('.drp')
                    s[0].childNodes[0].data = li.innerText;
                }
                else
                    $(li).removeClass("selected");
            });
       
        }
    });

    
    //state dropdown
    
        var ul = document.getElementById('state_drpdwn');  // Parent
        ul.addEventListener('click', function (e) {
            $('.dist_dropdown').css('visibility','visible');
            $('.block_dropdown').css('visibility','hidden');
            var s = $('.dist_dropdown').find('.drp')
            s[0].childNodes[0].data = 'Select District';
            $('#dist_drpdwn li:not(:first)').remove();
            var listItems = $("#dist_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == 'select') {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
            $('#stdist1,#stdist2')[0].innerHTML = "District";
            //$('#myid').css('display', 'block');
            search.clear();
            click_flag = "dist";
            if (e.target.tagName === 'A') {
                //alert(e.path[1].dataset.value);
                var s = $('.state_dropdown').find('.drp')
                s[0].childNodes[0].data = e.target.innerHTML;
                var listItems = $("#state_drpdwn li");
                listItems.each(function (idx, li) {

                    if ($(li).attr('data-value') == e.target.parentNode.dataset.value) {
                        $(li).addClass("selected");

                    }
                    else
                        $(li).removeClass("selected");
                });
                if (e.target.parentNode.dataset.value == 'select') {
                    $('.dist_dropdown').css('visibility','hidden');
                    backtoextent();
                    $('#stdist1,#stdist2')[0].innerHTML = "State";
                }
                else {
                    doSearchState(e.target.parentNode.dataset.value);
                    distquery(e.target.parentNode.dataset.value);
                }
            }
        });
    

    //dist dropdown

   
        var ul = document.getElementById('dist_drpdwn');  // Parent
        ul.addEventListener('click', function (e) {
            $('.block_dropdown').css('visibility','visible');
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
            $('#stdist1,#stdist2')[0].innerHTML = "Block";
            //$('#myid').css('display', 'block');
            search.clear();
            click_flag = "dist";
            if (e.target.tagName === 'A') {
                //alert(e.path[1].dataset.value);
                var s = $('.dist_dropdown').find('.drp')
                s[0].childNodes[0].data = e.target.innerHTML;
                var listItems = $("#dist_drpdwn li");
                listItems.each(function (idx, li) {

                    if ($(li).attr('data-value') == e.target.parentNode.dataset.value) {
                        $(li).addClass("selected");

                    }
                    else
                        $(li).removeClass("selected");
                });
                if (e.target.parentNode.dataset.value == 'select') {
                    $('.block_dropdown').css('visibility','hidden');
                    search.clear();
                    map.removeLayer(blockLayer);
                    $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
                    $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
                    $('#stdist1,#stdist2')[0].innerHTML = "District";
                    distquery(state_drpdwn_value);
                    doSearchState(state_drpdwn_value);
                    click_flag = "dist";
                                        
                }
                else {
                    doSearchDist(e.target.parentNode.dataset.value);
                    blockquery(e.target.parentNode.dataset.value);
                }
            }
        });
    
   // block dropdown
        var ul = document.getElementById('block_drpdwn');  // Parent
        ul.addEventListener('click', function (e) {            
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Gram panchayats';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Gram panchayats';
            $('#stdist1,#stdist2')[0].innerHTML = "Gram panchayat";
            //$('#myid').css('display', 'block');
            search.clear();
            click_flag = "dist";
            if (e.target.tagName === 'A') {
                //alert(e.path[1].dataset.value);
                var s = $('.block_dropdown').find('.drp')
                s[0].childNodes[0].data = e.target.innerHTML;
                var listItems = $("#block_drpdwn li");
                listItems.each(function (idx, li) {

                    if ($(li).attr('data-value') == e.target.parentNode.dataset.value) {
                        $(li).addClass("selected");

                    }
                    else
                        $(li).removeClass("selected");
                });
                if (e.target.parentNode.dataset.value == 'select') {
                    search.clear();
                    map.removeLayer(gpLayer);
                    $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
                    $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
                    $('#stdist1,#stdist2')[0].innerHTML = "Block";
                    blockquery(dist_drpdwn_value);
                    doSearchDist(dist_drpdwn_value);
                    click_flag = "dist";
                                        
                }
                else {
                    doSearchBlock(e.target.parentNode.dataset.value);
                    gpquery(e.target.parentNode.dataset.value);
                }
            }
        });

   
   //home button
    home.on("home", function () {
        if (click_flag == "dist") {
            backtoextent();
        }
        else {
            home.extent = stateLayer.fullExtent;
        }
    });
    function backtoextent(){
        search.clear();
        map.removeLayer(districtLayer);
        map.removeLayer(blockLayer);
        $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 States';
        $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 States';
        $('#stdist1,#stdist2')[0].innerHTML = "State";
        $('.dist_dropdown').css('visibility','hidden');
        var listItems = $("#state_drpdwn li");
        listItems.each(function (idx, li) {

            if ($(li).attr('data-value') == 'select') {
                $(li).addClass("selected");
                var s = $('.state_dropdown').find('.drp')
                s[0].childNodes[0].data = li.innerText;
            }
            else
                $(li).removeClass("selected");
        });
        var listItems = $("#dist_drpdwn li");
        listItems.each(function (idx, li) {

            if ($(li).attr('data-value') == 'select') {
                $(li).addClass("selected");
                var s = $('.dist_dropdown').find('.drp')
                s[0].childNodes[0].data = li.innerText;
            }
            else
                $(li).removeClass("selected");
        });

        var query = new Query();
        query.where = "1=1";
        query.outFields = ["stname,stcode11"];
        var queryTask = new QueryTask(layerurl + "/0" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "state_drpdwn", 'stname', 'stcode11'));
        click_flag = "";
    }
   
    //dist geometry
    function distquery(value) {
        if (value=='28') {
            value='37';
        }
        var query = new Query();
        query.where = "stcode11 ='" + value + "'";
        query.outFields = ["dtname,dtcode11"];
        var queryTask = new QueryTask(layerurl + "/1"+ token);
        queryTask.execute(query, dojo.partial(create_dropdown, "dist_drpdwn", 'dtname', 'dtcode11'));
    }
    function blockquery(value) {
        var query = new Query();
        if (value != "")
            query.where = "dtcode11 ='" + value + "'";
        else
            query.where = "1=1";
        query.outFields = ["block_name,blk_lgdcode"];
        var queryTask = new QueryTask(layerurl + "/2" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "block_drpdwn", 'block_name', 'blk_lgdcode'));
    }
    function gpquery(value) {
       
        var query = new Query();
        if (value != "")
            query.where = "blk_lgdcode ='" + value + "'";
        else
            query.where = "1=1";
        query.outFields = ["gp_name,gp_code"];
        var queryTask = new QueryTask(layerurl + "/3" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "", 'gp_name', 'gp_code'));
    }

    //padding zero
    //function padToFour(number) {
    //    if (number<=9999) { number = ("000"+number).slice(-4); }
    //    return number;
    //}

  //mouse func graphic
    function mouseoverfun(evt){
       
        var temp = per = sname = scode = "";
        var myNode = document.getElementById("info");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
      
        if (evt.graphic._layer.name == 'State') {
                sname = esriLang.substitute(evt.graphic.attributes, '${stname}');
                scode = esriLang.substitute(evt.graphic.attributes, '${stcode11}');
            }
        else if (evt.graphic._layer.name == 'District') {
                sname = esriLang.substitute(evt.graphic.attributes, '${dtname}');
                scode = esriLang.substitute(evt.graphic.attributes, '${dtcode11}');
        }
        else if (evt.graphic._layer.name == 'Block') {
            sname = esriLang.substitute(evt.graphic.attributes, '${block_name}');
            scode = esriLang.substitute(evt.graphic.attributes, '${blk_lgdcode}');
        }
        else if (evt.graphic._layer.name == 'GP') {
            sname = esriLang.substitute(evt.graphic.attributes, '${gp_name}');
            scode = esriLang.substitute(evt.graphic.attributes, '${gp_code}');
        }
        for (p = 0; p < MIS_rec.length; p++) {
            var data = MIS_rec[p]
            if (scode == 37)
                scode = "28";
            if (scode == data[MIS_UF]) {
                per = data[name];
                //statevalue = data.statename;
            }
        }
        temp = "<p>" + sname + "</p><span>"+ chartname +" : "+ per + "%</span>";
        $('#info').append(temp);
        $('#info').css('display','block');
    }
    function mouseoutfun() {
        $('#info').css('display', 'none');
    }

    //create dropdown

    function create_dropdown(stdtdd,qname, qcode, fs) {
        geometry_data = [];
        var fet = fs.features;
        var tmpAry = new Array();
        for (var k = 0; k < fet.length; k++) {
            tmpAry[k] = new Array();
            tmpAry[k][0] = fet[k].attributes[qname];
           tmpAry[k][1] = fet[k].attributes[qcode];
        }
        tmpAry.sort();
        geometry_data = tmpAry;
      
        if (qname == 'stname') {
            $('#dist_drpdwn li:not(:first)').remove();
        }
        if (qname == 'dtname' || qname == 'stname') {
            $('#block_drpdwn li:not(:first)').remove();
        }
        
        if (qname == 'stname' || qname == 'dtname' || qname=='block_name') {
            var ul = document.getElementById(stdtdd);
        for (var p = 0; p <tmpAry.length; p++) {
            var li = document.createElement("li");
            li.setAttribute("data-value", tmpAry[p][1]);
            var a = document.createElement('a');
            var linkText = document.createTextNode(tmpAry[p][0]);
            a.appendChild(linkText);
            a.href = "#";
            li.appendChild(a);
            ul.appendChild(li);
            
        }
        }
       
        ajaxcall(drpdwn_value);
    }
   
    $('.measure a').click(function (evt) {
        
        if (measure == false) {
            map.infoWindow.hide();
            //dom.byId("measure").style.backgroundColor = "#99e6ff";
            measure = true;
            $(function () { $("#mdiv").dialog('open'); });
            measurement.clearResult();
        }
        else {
            measurement.setTool("location", false);
            measurement.setTool("area", false);
            measurement.setTool("distance", false);
            measurement.clearResult();
            
            measure = false;
            $(function () { $("#mdiv").dialog('close'); });
            map.graphics.clear();
        }
    });
    var mdiv = document.createElement("div");
    mdiv.setAttribute("style", "display:none; font-size:13px; ");
    mdiv.setAttribute("class", "dialogs");
    mdiv.setAttribute("align", 'center');
    mdiv.id = "mdiv";
    mdiv.innerHTML = "<div id='measurementDiv'></div>";
    document.body.appendChild(mdiv);
    $(function () {
        $("#mdiv")
                       .dialog({
                           "title": "Measurement",
                           autoOpen: false,
                           height: 240,
                           width: 375
                       })
                       .dialogExtend({
                           "closable": true, // enable/disable close button
                           //"maximizable": true, // enable/disable maximize button
                           "minimizable": true, // enable/disable minimize button
                           "collapsable": true, // enable/disable collapse button
                           "dblclick": "collapse", // set action on double click. false, 'maximize', 'minimize', 'collapse'
                           //"titlebar": "transparent", // false, 'none', 'transparent'
                           "minimizeLocation": "right" // sets alignment of minimized dialogues                           
                       });
    });
    $("#mdiv").dialog("option", "position", { my: "left top", at: "right top", of: $('#map') });
    measure = false;
    var measurement;
    measurement = new Measurement({
        map: map, defaultAreaUnit: units.SQUARE_KILOMETERS, defaultLengthUnit: units.KILOMETERS
    }, dom.byId("measurementDiv"));
    measurement.startup();
    measurement.on("tool-change", function (evt) {
        if (evt.toolName != null) {
            map.setInfoWindowOnClick(false);
            map.infoWindow.hide();
        }
        else if (evt.toolName == null) {
            map.setInfoWindowOnClick(true);
        }
    });
    
    $(function () {
        $("#mdiv").dialog({
            close: function (event, ui) {
                measurement.setTool("location", false);
                measurement.setTool("area", false);
                measurement.setTool("distance", false);
                measurement.clearResult();
               
                measure = false;
                map.graphics.clear();
            }
        });
    });
});