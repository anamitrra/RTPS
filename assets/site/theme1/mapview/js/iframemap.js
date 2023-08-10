var token = "?Token=" + mapjson.mapService[0].token;
var layerurl = mapjson.mapService[0].url;
require(["esri/map", "esri/SpatialReference", "esri/dijit/BasemapGallery", "esri/dijit/BasemapLayer", "esri/dijit/Basemap",
         "esri/layers/ArcGISDynamicMapServiceLayer", "esri/layers/ImageParameters",
         "esri/layers/ArcGISTiledMapServiceLayer",
         "esri/layers/FeatureLayer", "esri/geometry/webMercatorUtils",
         "esri/layers/GraphicsLayer", "esri/geometry/Point", "esri/geometry/Circle", "esri/units", "dojo/_base/event", "esri/geometry/geometryEngine",
         "esri/tasks/query",
         "esri/tasks/QueryTask", "esri/lang",
         "esri/geometry/Extent",
         "dojo/dom-construct",  "dojo/mouse",
         "dojo/dom-class",
         "dojo/dom", "esri/dijit/Scalebar", "esri/dijit/HomeButton",
         "esri/symbols/SimpleMarkerSymbol",
         "esri/symbols/SimpleLineSymbol",
         "esri/symbols/SimpleFillSymbol", "esri/symbols/PictureMarkerSymbol",
         "esri/renderers/UniqueValueRenderer", "esri/renderers/SimpleRenderer",
         "esri/symbols/Font",
         "esri/symbols/TextSymbol", "esri/tasks/GenerateRendererParameters", "esri/tasks/GenerateRendererTask",
        "esri/layers/LayerDrawingOptions", "esri/tasks/UniqueValueDefinition",
         "esri/Color", "dijit/TooltipDialog",
         "esri/graphic", "esri/dijit/Search",
         "esri/InfoTemplate", "esri/dijit/InfoWindow", "esri/dijit/Print",
         "esri/tasks/PrintTemplate", "esri/config", "esri/request",
         "dojo/_base/array", "dojo/_base/connect",
         "dojox/grid/DataGrid",
         "dojo/data/ItemFileWriteStore", "dojo/data/ItemFileReadStore",
         "dijit/Dialog",
         "dijit/ColorPalette",
         "dojo/_base/lang",
         "esri/toolbars/navigation", "esri/dijit/Legend", "esri/dijit/Measurement", "esri/dijit/LayerList",
         "dojo/on", "esri/urlUtils", "dojo/dom-style", "dijit/popup",
         "dojo/parser",
         "dijit/registry", "dijit/TitlePane", "dijit/layout/ContentPane", "agsjs/dijit/TOC", "esri/dijit/LayerSwipe",
         "dojox/charting/Chart", "dojo/store/Memory", "dojox/charting/DataSeries", "dojo/store/Observable", "dojox/charting/StoreSeries",
        "dojox/charting/action2d/Highlight",
         "dojox/charting/action2d/Tooltip",
         "dojox/charting/plot2d/Columns", "dojox/charting/plot2d/Bars",
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
         Query, QueryTask, esriLang,
         Extent,
         domConstruct,
         mouse,
         domClass,
         dom, Scalebar, HomeButton,
         SimpleMarkerSymbol,
         SimpleLineSymbol,
         SimpleFillSymbol, PictureMarkerSymbol,
         UniqueValueRenderer, SimpleRenderer,
         Font, TextSymbol, GenerateRendererParameters, GenerateRendererTask,
        LayerDrawingOptions, UniqueValueDefinition,
         Color,TooltipDialog,
         Graphic, Search,
         InfoTemplate, InfoWindow, Print,
         PrintTemplate, esriConfig, esriRequest,
         arrayUtils, connect,
         DataGrid,
         ItemFileWriteStore, ItemFileReadStore,
         Dialog,
         ColorPalette,
         lang,
         Navigation, Legend, Measurement, LayerList,
         on, urlUtils,domStyle,dijitPopup,
         parser,
         registry, TitlePane, ContentPane, TOC, LayerSwipe,
         Chart, Memory, DataSeries, Observable, StoreSeries,
         Highlight, Tooltip, Columns, Bars, Default) {
    parser.parse();

   
    var measure;
    var geometry_data = [];
    var dialog;
    var polygraphlayer = new GraphicsLayer();
    var maskcirclelayer = new GraphicsLayer();
    var stdtbk_graphlyr = new GraphicsLayer();
    var polylblgraphlayer = new GraphicsLayer();
    var sfs_stdtbk = new SimpleFillSymbol(SimpleFillSymbol.STYLE_NULL, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([0, 0, 0]), 2), new Color([0, 206, 209, 0.25]));
    var maskcircleSymb = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SHORTDASHDOTDOT, new Color([105, 105, 105]), 2), new Color([255, 255, 255, 1]));
    var maskcircle = new Circle({
        center: [80, 19],
        geodesic: true,
        radius: 3000,
        radiusUnit: units.MILES
    });
    var imageParameters = new ImageParameters();
    imageParameters.format = "png32";
    var ind_ext = new Extent(66.62, 5.23, 98.87, 38.59, new SpatialReference({ wkid: 4326 }));
    var dlayer = new ArcGISDynamicMapServiceLayer(layerurl + token, { imageParameters: imageParameters, showAttribution: false });
    dlayer.hide();
    var map = new Map("map", {
        center: [80, 23.5], slider: false,
        zoom: 4,
        logo: false, showAttribution: false
    });
    map.on("load", function () {
        map.addLayers([dlayer, polygraphlayer, polylblgraphlayer]);
        map.addLayer(maskcirclelayer);
        map.addLayer(stdtbk_graphlyr);
    });
    //################################### basemap #########################
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
        basemapGallery.select("basemap_0");
    });
    
    dialog = new TooltipDialog({
        id: "tooltipDialog",
        style: "position: absolute; font: normal normal normal 10pt Helvetica;z-index:100;"
    });
    dialog.startup();
    var drpdwn_value = $('#drpdwn li.selected').attr('data-value');

    map.setInfoWindowOnClick(false);
    map.disableMapNavigation();
    dlayer.on("load", function () {
        var query = new Query();
        query.where = "1=1";
        query.outFields = ["stname,stcode11"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/" + 0 + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "state_drpdwn", "stname", "stcode11"), function (error) {
            console.log(error);
            return;
        });
        var s = $('.category_drpdwn').find('.drp')
        s[0].childNodes[0].data = $('#drpdwn li.selected')[0].innerText;
    });
    dlayer.on("error", function (evt) {
        if (!evt.error._ssl) alert("Please use Google Chrome browser");
    });
    //var home = new HomeButton({
    //    map: map
    //}, "HomeButton");
    //home.startup();

    var MIS_rec = name = MIS_UF = chartname = MIS_Fname = matched_data = GIS_UF = GIS_UFname = null;
    var state_drpdwn_value = $('#state_drpdwn li.selected').attr('data-value');
    var dist_drpdwn_value = $('#dist_drpdwn li.selected').attr('data-value');
    var block_drpdwn_value = $('#block_drpdwn li.selected').attr('data-value');
    $('#stdist1,#stdist2')[0].innerHTML = "State";
    var mis_fields1 = get_misfieldaliasnames(drpdwn_value);
    $(mis_fields1).each(function (index, item) {
        if ($(this)[0].alias == "Report Card Date")
            mis_fields1.splice(index, 1);
        return;
    });
    var count = mis_fields1.length;
    if (count == 8)
        hello_one('col-lg-1-4 col-md-1-4 three_one', count);

    var water_row = document.getElementById('row_water');
    
    for (var i = 0; i < mis_fields1.length; i++) {
        water_row.childNodes[i].childNodes["0"].data = mis_fields1[i].alias;
        water_row.childNodes[i].dataset.value = mis_fields1[i].name;
        water_row.childNodes[i].dataset.title = mis_fields1[i].maptitle;
    }
    //########################################## ajaxcall ####################
    function ajaxcall(drpdwn_value) {
        polygraphlayer.clear();
        document.getElementById("bodyloadimg").style.display = "block";
        state_drpdwn_value = $('#state_drpdwn li.selected').attr('data-value');
        dist_drpdwn_value = $('#dist_drpdwn li.selected').attr('data-value');
        block_drpdwn_value = $('#block_drpdwn li.selected').attr('data-value');
        if (state_drpdwn_value == 37)
            state_drpdwn_value = 28
        var node = 'node';
        if (drpdwn_value == 'Performance') {
            if (state_drpdwn_value == 'select') {
                // MIS_UF = 'statecensuscode2011'; MIS_Fname = 'statename';
                MIS_UF = 'censuscode2011'; MIS_Fname = 'StateName'; GIS_UF = 'stcode11'; GIS_UFname = 'stname';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetstatewiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123" };
                //url = "http://indiawater.gov.in/mRWSRestService/RestServiceImpl.svc/getStateWiseHabSchemeDetails"; data = "uid=9F0CE74B2243E528999D7932D6541948&pwd1=9C10195D326852CE85A242590524A80F&stateid=000&districtid=0000";
            }
            else if (state_drpdwn_value != 'select' && dist_drpdwn_value == 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'DistrictName'; GIS_UF = 'dtcode11'; GIS_UFname = 'dtname';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetdistrictwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": state_drpdwn_value };

            }
            else if (dist_drpdwn_value != 'select' && block_drpdwn_value == 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'BLockName'; GIS_UF = 'blk_lgdcode'; GIS_UFname = 'block_name';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetBlockwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": state_drpdwn_value, "Censusdist_code": dist_drpdwn_value };

            }
            else if (block_drpdwn_value != 'select') {
                MIS_UF = 'censuscode2011'; MIS_Fname = 'PanchyatName'; GIS_UF = 'gp_code', GIS_UFname = 'gp_name';
                url = "http://awaassoft.nic.in/netiay/Services/Service.svc/GetPanchyatwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": state_drpdwn_value, "Blocklgd_code": block_drpdwn_value };

            }

            node = 'DATA';
        }
        if ($("div.three_oneactive").length == 0) {
            chartname = "Performance";
            name = "Performance";
        }


        $.ajax({
            type: 'POST',
            url: url,
            dataType: "xml",
            data: JSON.stringify(data),//JSON.stringify({"uname": "sbmgis", "pwd": "sbmgis@2017#"}),
            crossDomain: true,
            contentType: "application/json",//"application/json",
            success: function (dat) {
                MIScontent = dat.getElementsByTagName(node);
                //if (MIScontent.length <= 0) {
                //    alert("No records found");
                //    $('#chartNode')[0].innerHTML = "";
                //    $('.stat_table').find('tbody').empty();
                //    $('#info').css('display', 'none');
                //    document.getElementById("bodyloadimg").style.display = "none";
                //    return;
                //}
                MIS_rec = [];
                for (var i = 0; i < MIScontent.length; i++) {

                    MIS_rec[i] = {};
                    var columns = MIScontent[i].getElementsByTagName("*");
                    for (var j = 0; j < columns.length; j++) {
                        if (MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes.length > 0) {
                            if (block_drpdwn_value != 'select' && columns[j].tagName == 'censuscode2011') {
                                MIS_rec[i][columns[j].tagName] = (MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes[0].data);
                            }
                            else
                                MIS_rec[i][columns[j].tagName] = MIScontent[i].getElementsByTagName(columns[j].tagName)[0].childNodes[0].data.trim();
                        }
                    }
                }

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
                    //if (q == 0) {
                    //    var avg = sum.toFixed(2) / cary.length;
                    //    water_row.children[q].children["0"].innerText = avg.toFixed(2);
                    //}
                    //else
                        water_row.children[q].children["0"].innerText = sum;
                    //for counter 
                    // water_row.children[q].children["0"].attributes[2].value = avg;
                }

                gis_mis(MIS_rec, name);
            },
            error: function (err) {
                alert(err.statusText);
            }
        });
    }

    //####################################### Rendering ################
    function gis_mis(MIS_rec, name) {
        //$('#info').css('display', 'none');
        $('#themetitle')["0"].innerHTML = chartname;
        matched_data = [];
        polygraphlayer.clear();
        maskcirclelayer.clear();
        stdtbk_graphlyr.clear();
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

            var MIS_rec_comp = MIS_rec.slice(0);

                for (k = 0; k < geometry_data.length; k++) {
                    var polygraphsfs = new SimpleFillSymbol();
                    polygraphsfs.setOutline(new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([64, 64, 64]), 1.2));
                        for (var q = 0; q < MIS_rec_comp.length; q++) {
                            if (MIS_rec_comp[q][MIS_UF]) {
                                if (MIS_rec_comp[q][MIS_UF] == 28) {
                                    var geo_id = "37";
                                }
                                else {
                                    geo_id = (MIS_rec_comp[q][MIS_UF]).toString();
                                }

                                if (parseInt(geometry_data[k][GIS_UF]) == parseInt(geo_id)) {

                                    if (MIS_rec_comp[q][name] == 0) {
                                        polygraphsfs.setColor(new Color([255, 255, 255, 0]));
                                    }
                                    else if (MIS_rec_comp[q][name] >= mi && MIS_rec_comp[q][name] <= (mi + ra)) {
                                        polygraphsfs.setColor(new Color([253, 174, 97, 1]));
                                        matched_data.push({
                                            code: MIS_rec_comp[q][MIS_UF],
                                            name: geometry_data[k][GIS_UFname],
                                            value: MIS_rec_comp[q][name]
                                        });
                                        // matched_data.push([MIS_rec_comp[q][MIS_UF], MIS_rec_comp[q][name], geometry_data[k][GIS_UFname]]);
                                    }
                                    else if (MIS_rec_comp[q][name] > (mi + ra) && MIS_rec_comp[q][name] <= (mi + (2 * ra))) {
                                        polygraphsfs.setColor(new Color([255, 255, 191, 1]));
                                        matched_data.push({
                                            code: MIS_rec_comp[q][MIS_UF],
                                            name: geometry_data[k][GIS_UFname],
                                            value: MIS_rec_comp[q][name]
                                        });
                                        // matched_data.push([MIS_rec_comp[q][MIS_UF], MIS_rec_comp[q][name], geometry_data[k][GIS_UFname]]);
                                    }
                                    else if (MIS_rec_comp[q][name] > (mi + 2 * ra) && MIS_rec_comp[q][name] <= (mi + (3 * ra))) {
                                        polygraphsfs.setColor(new Color([166, 217, 106, 1]));
                                        matched_data.push({
                                            code: MIS_rec_comp[q][MIS_UF],
                                            name: geometry_data[k][GIS_UFname],
                                            value: MIS_rec_comp[q][name]
                                        });
                                        // matched_data.push([MIS_rec_comp[q][MIS_UF], MIS_rec_comp[q][name], geometry_data[k][GIS_UFname]]);
                                    }
                                    else if (MIS_rec_comp[q][name] > (mi + 3 * ra) && MIS_rec_comp[q][name] <= (ma)) {
                                        polygraphsfs.setColor(new Color([26, 150, 65, 1]));
                                        matched_data.push({
                                            code: MIS_rec_comp[q][MIS_UF],
                                            name: geometry_data[k][GIS_UFname],
                                            value: MIS_rec_comp[q][name]
                                        });
                                        // matched_data.push([MIS_rec_comp[q][MIS_UF], MIS_rec_comp[q][name], geometry_data[k][GIS_UFname]]);
                                    }
                                    // labelgrap = true;
                                    var graphic = new Graphic(geometry_data[k]["geometry"], polygraphsfs, geometry_data[k])
                                    polygraphlayer.add(graphic);

                                    MIS_rec_comp.splice(q, 1);
                                    q--;
                                    break;
                                }
                            }
                        }

                }                
                if (polygraphlayer.graphics.length <= 0) {
                    alert("No records found");
                    $('#chartNode')[0].innerHTML = "";
                    $('.stat_table').find('tbody').empty();
                    document.getElementById("bodyloadimg").style.display = "none";
                    return;
                }
            // }
            //stats_counter();
            chart(name, "a_z");
            document.getElementById("bodyloadimg").style.display = "none";
        }

        

    }
    polygraphlayer.on("mouse-over", dojo.partial(mouseoverfun));
    polygraphlayer.on("mouse-out", dojo.partial(mouseoutfun));
    /////////////////////////////////////////////////////############### counter ###########////////////////////////////////////////////////////////
    function stats_counter() {
        if ($('.counter').length > 0) {
            $('.counter').each(function (index) {
                increment($(this), parseFloat($(this).data('speed')));
            });
            function increment($this, speed) {
                var current = parseFloat($this.text());
                if (current < $this.data('to') - 100000) {
                    parseFloat($this.text(current + 100000));
                    if (current <= $this.data('to')) {
                        setTimeout(function () { increment($this, speed) }, 1);
                    }
                }
                else if (current < $this.data('to') - 1) {
                    parseFloat($this.text(current + 1));
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
    //########################################### chart ##########################################

    function chart(name, sort_type) {
        //chartname = name;
        var ary = [];
        for (var q = 0; q < matched_data.length; q++) {
            ary[q] = {};
            ary[q]['value'] = (Number(matched_data[q].value));
            ary[q]['text'] = matched_data[q].name;
            
            //ary[q]['value'] = (Number(matched_data[q][name]));
           // ary[q]['text'] = matched_data[q][MIS_Fname];
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
        var data_store = new Observable(new Memory({ data: ary }));
        data_store.query({}).forEach(function (ary_data, item) {
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
            vertical: true, fixLower: "major", fixUpper: "major", min: 0, minorTicks: false
        });

        chart.addSeries("x", new StoreSeries(data_store, { query: {} }, "value"), { stroke: "#ff7416", fill: "#ff7416" }),
        new Highlight(chart, "default");
        new Tooltip(chart, "default", {
            text: function (event) {
                if (chartname == 'Performance')
                    return chartname + '   ' + event.y + "%";
                else
                    return chartname + '   ' + event.y;
            }
        });
        chart.render();
        //if(ary.length < 10)
         //   chart.resize(200, 250);
        if (ary.length > 37)
            chart.resize(900, 250);

        else
            chart.resize(570, 240);
        create_table("top", name);

    }
    //################################################ create table ##################################################
    function create_table(selection, name) {
        if (chartname == 'Performance') {
            $('#stdist3')[0].innerHTML = chartname + " (%)";
            $('#stdist4')[0].innerHTML = chartname + " (%)";
        }
        else {
            $('#stdist3')[0].innerHTML = chartname;
            $('#stdist4')[0].innerHTML = chartname;
        }

        var stat_ary = null;
       
        stat_ary = matched_data.slice();
        stat_ary.forEach(function (a, index) {
            if (a.value == 0) {
                delete stat_ary[index]
            }
        });
        if (selection == "top") {
            stat_ary.sort(function (a, b) {
                return parseFloat(b.value) - parseFloat(a.value);
            });

            var newary = stat_ary.slice(0, 5);

        }
        else {
            stat_ary.sort(function (a, b) {
                return parseFloat(a.value) - parseFloat(b.value);
            });
            var newary = stat_ary.slice(0, 5);
        }

        $('.stat_table').find('tbody').empty();

        for (var k = 0; k < newary.length; k++) {
            var trow = "<tr><td>" + newary[k].name + "</td>";
            trow += "<td>" + newary[k].value + "</td></tr>";
            $('.stat_table').find('tbody').append(trow);
        }


    }
    //############################################### click on tab ###############################
    $(document).on('click', ".three_one", function () {

        $("div.three_one").removeClass("three_oneactive");
        $(this).addClass("three_oneactive");
        name = $(this)[0].dataset.value;
        chartname = $(this)[0].dataset.title;
        gis_mis(MIS_rec, name);
    });
    //####################################### statistic table option ########################################
    $(".nav-tabs li").click(function () {
        if ($(this)[0].textContent == "Top 5 States") {
            $('#stdist1')[0].innerHTML = "State";
            create_table("top", name);
        }
        else if ($(this)[0].textContent == "Top 5 Districts") {
            $('#stdist1')[0].innerHTML = "District";
            create_table("top", name);
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
        else if ($(this)[0].textContent == "Bottom 5 Districts") {
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
    //########################################### click on sort buttons ############################################

    $(".sortnav li").click(function () {
        if ($(this)[0].id == "a_z")
            chart(name, "a_z");
        else if ($(this)[0].id == "9_0")
            chart(name, "9_0");
        else
            chart(name, "0_9");
    });

    //################################### click on category dropdown ####################################
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
   

    //############################################# click on  graphic ##################################################
    var click_flag = "";
    polygraphlayer.on("click", function (evt) {
        event.preventDefault();
        if (GIS_UF == 'stcode11') {
            document.getElementById("bodyloadimg").style.display = "block";
            if (measure == false) {
                click_flag = "dist";
                $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
                $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
                $('#stdist1')[0].innerHTML = "District";
                $('.dist_dropdown').css('visibility', 'visible');
                var stcode11 = evt.graphic.attributes[GIS_UF];
                _setStateDropdownItem(evt);
                if (stcode11 == '28') {
                    stcode11 = '37';
                }
                _selectstate(stcode11);
            }
        }
           
        else if (GIS_UF == 'dtcode11') {
            document.getElementById("bodyloadimg").style.display = "block";
        if (measure == false) {
            $('.dist_dropdown').css('visibility', 'visible');
            $('.block_dropdown').css('visibility', 'visible');
            click_flag = "dist";
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
            $('#stdist1')[0].innerHTML = "Block";

            var dtcode11 = evt.graphic.attributes[GIS_UF];
            _setDistrictDropdownItem(evt);
            _selectdistrict(dtcode11);
        }
            }
            
        else if (GIS_UF == 'blk_lgdcode') {
        document.getElementById("bodyloadimg").style.display = "block";
        if (measure == false) {
            $('.block_dropdown').css('visibility', 'visible');
            click_flag = "dist";
            $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Gram panchayats';
            $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Gram panchayats';
            $('#stdist1')[0].innerHTML = "Gram panchayat";

            var blk_lgdcode = evt.graphic.attributes[GIS_UF];
            _setBlockDropdownItem(evt);
            _selectblock(blk_lgdcode);
        }
        }
        function leftPad(number, targetLength) {
            var output = number + '';
            while (output.length < targetLength) {
                output = '0' + output;
            }
            return output;
        }
            
    });

   
    //############################ state dropdown click #######################################

    var ul = document.getElementById('state_drpdwn');  // Parent
    ul.addEventListener('click', function (e) {
        document.getElementById("bodyloadimg").style.display = "block";
        $('.dist_dropdown').css('visibility', 'visible');
        $('.block_dropdown').css('visibility', 'hidden');
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
      
        click_flag = "dist";
        if (e.target.tagName === 'A') {
            //alert(e.path[1].dataset.value);
            var value = e.target.parentNode.dataset.value;
            _setStateDropdownItem(value);
            if (value == 'select') {
                $("div.three_one").removeClass("three_oneactive");
                dlayer.setDefaultLayerDefinitions();
                $('.dist_dropdown').css('visibility', 'hidden');
                $('.block_dropdown').css('visibility', 'hidden');
                click_flag = "";
                var query = new Query();
                query.where = "1=1";
                query.outFields = ["stname,stcode11"];
                query.returnGeometry = true;
                var queryTask = new QueryTask(layerurl + "/" + 0 + token);
                queryTask.execute(query, dojo.partial(create_dropdown, "state_drpdwn", "stname", "stcode11"), function (error) {
                    console.log(error);
                    return;
                });
                $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 States';
                $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 States';
                $('#stdist1,#stdist2')[0].innerHTML = "State";
                //backtoextent();
            }
            else {
                click_flag = 'dist';
                _selectstate(value);
            }
        }
    });

    

    //##################################### dist dropdown click ##################################
    var ul = document.getElementById('dist_drpdwn');  // Parent
    ul.addEventListener('click', function (e) {
        document.getElementById("bodyloadimg").style.display = "block";
        $('.block_dropdown').css('visibility', 'visible');
        $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
        $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
        $('#stdist1,#stdist2')[0].innerHTML = "Block";
        //$('#myid').css('display', 'block');
        
        click_flag = "dist";
        var s = $('.block_dropdown').find('.drp')
        s[0].childNodes[0].data = 'Select Block';
        $('#block_drpdwn li:not(:first)').remove();
        var listItems = $("#block_drpdwn li");
        listItems.each(function (idx, li) {

            if ($(li).attr('data-value') == 'select') {
                $(li).addClass("selected");

            }
            else
                $(li).removeClass("selected");
        });
        if (e.target.tagName === 'A') {
            //alert(e.path[1].dataset.value);
            var value = e.target.parentNode.dataset.value;
            _setDistrictDropdownItem(value);
            if (value == 'select') {
                dlayer.setDefaultLayerDefinitions();
                click_flag = "dist";
                $('.block_dropdown').css('visibility', 'hidden');
                var query = new Query();
                var st = $("#state_drpdwn li.selected")[0].dataset.value;
                query.where = "stcode11 ='" + st + "'";
                query.outFields = ["dtname,dtcode11"];
                query.returnGeometry = true;
                var queryTask = new QueryTask(layerurl + "/1" + token);
                queryTask.execute(query, dojo.partial(create_dropdown, "dist_drpdwn", 'dtname', 'dtcode11'));
                var layerDefinitions = [];
                layerDefinitions[0] = "stcode11='" + st + "'";
                layerDefinitions[1] = "stcode11='" + st + "'";
                layerDefinitions[2] = "stcode11='" + st + "'";
                layerDefinitions[3] = "stcode11='" + st + "'";
                dlayer.setLayerDefinitions(layerDefinitions);
                var query = new Query();
                query.where = "stcode11='" + st + "'";
                query.returnGeometry = true;
                var queryTask = new QueryTask(layerurl + "/" + 0 + token);
                queryTask.execute(query, dojo.partial(stfun_ext));
                $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
                $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
                $('#stdist1,#stdist2')[0].innerHTML = "District";
            }
            else {
                _selectdistrict(value);
            }
        }
    });

    //############################################### block dropdown click ################################
    var ul = document.getElementById('block_drpdwn');  // Parent
    ul.addEventListener('click', function (e) {
        document.getElementById("bodyloadimg").style.display = "block";
        $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Gram panchayats';
        $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Gram panchayats';
        $('#stdist1,#stdist2')[0].innerHTML = "Gram panchayat";
        //$('#myid').css('display', 'block');
      
        click_flag = "dist";
        if (e.target.tagName === 'A') {
            //alert(e.path[1].dataset.value);
            var value = e.target.parentNode.dataset.value;
            _setBlockDropdownItem(value);
            if (value == 'select') {
                dlayer.setDefaultLayerDefinitions();
                click_flag = "dist";
                var query = new Query();
                var dt = $("#dist_drpdwn li.selected")[0].dataset.value;
                query.where = "dtcode11 ='" + dt + "'";
                query.outFields = ["block_name,blk_lgdcode"];
                query.returnGeometry = true;
                var queryTask = new QueryTask(layerurl + "/2" + token);
                queryTask.execute(query, dojo.partial(create_dropdown, "block_drpdwn", 'block_name', 'blk_lgdcode'));
                var layerDefinitions = [];
                layerDefinitions[0] = "dtcode11='" + dt + "'";
                layerDefinitions[1] = "dtcode11='" + dt + "'";
                layerDefinitions[2] = "dtcode11='" + dt + "'";
                layerDefinitions[3] = "dtcode11='" + dt + "'";
                dlayer.setLayerDefinitions(layerDefinitions);
                var query = new Query();
                query.where = "dtcode11='" + dt + "'";
                query.returnGeometry = true;
                var queryTask = new QueryTask(layerurl + "/" + 1 + token);
                queryTask.execute(query, dojo.partial(stfun_ext));
                $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
                $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
                $('#stdist1,#stdist2')[0].innerHTML = "Block";
            }
            else {
                _selectblock(value);
            }
        }
    });

    function _setStateDropdownItem(evt) {
        var s = $('.state_dropdown').find('.drp')
        if (typeof evt == "string") {
           
            var listItems = $("#state_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
            s[0].childNodes[0].data = $('#state_drpdwn li.selected')[0].textContent;
        }
        else {
            s[0].childNodes[0].data = evt.graphic.attributes[GIS_UFname];
            var listItems = $("#state_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt.graphic.attributes[GIS_UF]) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
        }
        
    }
    function _setDistrictDropdownItem(evt) {
       
        var s = $('.dist_dropdown').find('.drp')
        if (typeof evt == "string") {
            
            var listItems = $("#dist_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
            s[0].childNodes[0].data = $('#dist_drpdwn li.selected')[0].textContent;
        }
        else {
            s[0].childNodes[0].data = evt.graphic.attributes[GIS_UFname];
            var listItems = $("#dist_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt.graphic.attributes[GIS_UF]) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
        }
        
    }
    function _setBlockDropdownItem(evt) {
    
        var s = $('.block_dropdown').find('.drp')
        if (typeof evt == "string") {
           
            var listItems = $("#block_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
            s[0].childNodes[0].data = $('#block_drpdwn li.selected')[0].textContent;
        }
        else {
            s[0].childNodes[0].data = evt.graphic.attributes[GIS_UFname];
            var listItems = $("#block_drpdwn li");
            listItems.each(function (idx, li) {

                if ($(li).attr('data-value') == evt.graphic.attributes[GIS_UF]) {
                    $(li).addClass("selected");

                }
                else
                    $(li).removeClass("selected");
            });
        }
        
    }
    function _selectstate(value) {
        if (value == '28') {
            value = '37';
        }

        var layerDefinitions = [];
        layerDefinitions[0] = "stcode11='" + value + "'";
        layerDefinitions[1] = "stcode11='" + value + "'";
        layerDefinitions[2] = "stcode11='" + value + "'";
        layerDefinitions[3] = "stcode11='" + value + "'";
        dlayer.setLayerDefinitions(layerDefinitions);
        var query = new Query();
        query.where = "stcode11 ='" + value + "'";
        query.outFields = ["dtname,dtcode11"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/1" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "dist_drpdwn", 'dtname', 'dtcode11'));

        var query = new Query();
        query.where = "stcode11='" + value + "'";
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/" + 0 + token);
        queryTask.execute(query, dojo.partial(stfun_ext));
    }
    function _selectdistrict(value) {
        var layerDefinitions = [];
        layerDefinitions[0] = "dtcode11='" + value + "'";
        layerDefinitions[1] = "dtcode11='" + value + "'";
        layerDefinitions[2] = "dtcode11='" + value + "'";
        layerDefinitions[3] = "dtcode11='" + value + "'";
        dlayer.setLayerDefinitions(layerDefinitions);
        var query = new Query();
        query.where = "dtcode11 ='" + value + "'";
        query.outFields = ["block_name,blk_lgdcode"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/2" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "block_drpdwn", 'block_name', 'blk_lgdcode'));

        var query = new Query();
        query.where = "dtcode11='" + value + "'";
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/" + 1 + token);
        queryTask.execute(query, dojo.partial(stfun_ext));
    }
    function _selectblock(value) {
        var layerDefinitions = [];
        layerDefinitions[0] = "blk_lgdcode='" + value + "'";
        layerDefinitions[1] = "blk_lgdcode='" + value + "'";
        layerDefinitions[2] = "blk_lgdcode='" + value + "'";
        layerDefinitions[3] = "blk_lgdcode='" + value + "'";
        dlayer.setLayerDefinitions(layerDefinitions);
        var query = new Query();
        var query = new Query();
        query.where = "blk_lgdcode ='" + value + "'";
        query.outFields = ["gp_name,gp_code"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/3" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "", 'gp_name', 'gp_code'));

        var query = new Query();
        query.where = "blk_lgdcode ='" + value + "'";
        query.outFields = ["block_name,blk_lgdcode"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/2" + token);
        queryTask.execute(query, dojo.partial(stfun_ext));
    }
    //############################## home button#################
    //home.on("home", function () {
    //   // if (click_flag == "dist") {
    //        backtoextent();
    //   // }
       

    //});
    function backtoextent() {
        polygraphlayer.clear();
        maskcirclelayer.clear();
        stdtbk_graphlyr.clear();
        dlayer.setDefaultLayerDefinitions();
        $("div.three_one").removeClass("three_oneactive");
        $('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 States';
        $('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 States';
        $('#stdist1,#stdist2')[0].innerHTML = "State";
        $('.dist_dropdown').css('visibility', 'hidden');
        $('.block_dropdown').css('visibility', 'hidden');
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
        var listItems = $("#block_drpdwn li");
        listItems.each(function (idx, li) {

            if ($(li).attr('data-value') == 'select') {
                $(li).addClass("selected");
                var s = $('.block_dropdown').find('.drp')
                s[0].childNodes[0].data = li.innerText;
            }
            else
                $(li).removeClass("selected");
        });

        var query = new Query();
        query.where = "1=1";
        query.outFields = ["stname,stcode11"];
        query.returnGeometry = true;
        var queryTask = new QueryTask(layerurl + "/0" + token);
        queryTask.execute(query, dojo.partial(create_dropdown, "state_drpdwn", 'stname', 'stcode11'));
        click_flag = "";
    }

    //################################## mouse over graphic   #############################
    function mouseoverfun(evt) {
        var temp = per = sname = scode = "";
        var myNode = document.getElementById("info");
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
        sname = evt.graphic.attributes[GIS_UFname];
        scode = evt.graphic.attributes[GIS_UF];
        for (p = 0; p < MIS_rec.length; p++) {
            var data = MIS_rec[p]
            if (scode == 37)
                scode = "28";
            if (parseInt(scode) == parseInt(data[MIS_UF])) {
                per = data[name];
                //statevalue = data.statename;
            }
        }
        if (chartname == 'Performance')
            temp = "<p>" + sname + "</p><span>" + chartname + " : " + per + "%</span>";
        else
            temp = "<p>" + sname + "</p><span>" + chartname + " : " + per + "</span>";
        dialog.setContent(temp);
        domStyle.set(dialog.domNode, "opacity", 1);
        dijitPopup.open({
            popup: dialog,
            x: evt.pageX,
            y: evt.pageY
        });
       // $('#info').append(temp);
       // $('#info').css('display', 'block');
    }
    function mouseoutfun() {
        dijitPopup.close(dialog);
       // $('#info').css('display', 'none');
    }

    //######################################## create dropdown ########################

    function create_dropdown(stdtdd, qname, qcode, fs) {
        geometry_data = [];
        var extent = null;
        var fet = fs.features;
        var i = 0;
        var tmpAry = new Array();
        for (var k = 0; k < fet.length; k++) {
           // var geometry = fet[k].geometry;
           // var ext = null;
            //ext = geometry.getExtent();
          //  //if (geometry instanceof Point)
          //  //    ext = new Extent(geometry.x - 0.0005, geometry.y - 0.0005, geometry.x + 0.0005, geometry.y + 0.0005, geometry.spatialReference);
          //// else if (geometry instanceof Extent) ext = geometry;
          //  //else ext = geometry.getExtent();
            //if (extent) {
           //     if (ext) extent = extent.union(ext);
           // }
           // else
            //    if (ext) extent = new Extent(ext);
           
            tmpAry[i] = new Array();
            tmpAry[i][qname] = fet[k].attributes[qname];
            tmpAry[i][qcode] = fet[k].attributes[qcode];
            tmpAry[i]["geometry"] = fet[k].geometry;
            i++;
        }
        // map.setExtent(stateLayer.fullExtent, false);
        if (click_flag == "") {
            map.setExtent(ind_ext,true);
        }
       // else
         //   map.setExtent(extent, true);
        tmpAry.sort();
        geometry_data = tmpAry;

        if (qname == 'stname') {
            $('#dist_drpdwn li:not(:first)').remove();
        }
        if (qname == 'dtname' || qname == 'stname' || qname == 'block_name') {
            $('#block_drpdwn li:not(:first)').remove();
        }

        if (qname == 'stname' || qname == 'dtname' || qname == 'block_name') {
            var ul = document.getElementById(stdtdd);
            function compare(a, b) {
                if (a[qname] < b[qname])
                    return -1;
                if (a[qname] > b[qname])
                    return 1;
                return 0;
            }

            tmpAry.sort(compare);
            for (var p = 0; p < tmpAry.length; p++) {
                var li = document.createElement("li");
                li.setAttribute("data-value", tmpAry[p][qcode]);
                var a = document.createElement('a');
                var linkText = document.createTextNode(tmpAry[p][qname]);
                a.appendChild(linkText);
                a.href = "#";
                li.appendChild(a);
                ul.appendChild(li);

            }
        }

        ajaxcall(drpdwn_value);
    }
    ///################################# masking ######################################
    function stfun_ext(fs) {
        //var graphic = new Graphic(geometryEngine.difference(maskcircle, webMercatorUtils.webMercatorToGeographic(fs.features[0].geometry)), maskcircleSymb);
        //maskcirclelayer.clear();
        //maskcirclelayer.add(graphic);
        map.setExtent(fs.features[0].geometry.getExtent(), true);
        var grs = fs.features[0];
        grs.setSymbol(sfs_stdtbk);
        stdtbk_graphlyr.clear();
        stdtbk_graphlyr.add(grs);
    }
    //############################################# measure tool ################################
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