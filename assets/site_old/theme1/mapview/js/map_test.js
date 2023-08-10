
var token_var = "?Token=" + mapjson.mapService[0].token;
var baseurl = $('#base').val();
// console.log(baseurl);
var token_var = "?Token=qC7y3wpLmNoqtBlFJY3PHXnMHDeEhvCsI1SamwhCBPnA2Eb1Dz4Oq5_X-D2DpCWgRQEz83eWbWWx8R6_yzWT_w..";



//main serevr

// var token_var = "?Token=QmqYyJrJSt_BpgMbzmLs7JlQBy5KaNPXFnSYPtkYV2T_DxME-k-DsHYJ8r_LgsJ7kWVSrPu6LqF6CiOyp11kjA..";
require(["esri/map", "esri/SpatialReference", "esri/dijit/BasemapGallery", "esri/dijit/BasemapLayer", "esri/dijit/Basemap",
    "esri/layers/ArcGISDynamicMapServiceLayer", "esri/layers/ImageParameters",
    "esri/layers/ArcGISTiledMapServiceLayer",
    "esri/layers/FeatureLayer", "esri/geometry/webMercatorUtils",
    "esri/layers/GraphicsLayer", "esri/geometry/Point", "esri/geometry/Circle", "esri/units", "dojo/_base/event", "esri/geometry/geometryEngine",
    "esri/tasks/query",
    "esri/tasks/QueryTask", "esri/lang",
    "esri/geometry/Extent",
    "dojo/dom-construct", "dojo/dom-style", "dojo/mouse",
    "dojo/dom-class",
    "dojo/dom", "esri/dijit/Scalebar", "esri/dijit/HomeButton",
    "esri/symbols/SimpleMarkerSymbol",
    "esri/symbols/SimpleLineSymbol",
    "esri/symbols/SimpleFillSymbol", "esri/symbols/PictureMarkerSymbol",
    "esri/renderers/UniqueValueRenderer", "esri/renderers/SimpleRenderer",
    "esri/symbols/Font",
    "esri/symbols/TextSymbol", "esri/tasks/GenerateRendererParameters", "esri/tasks/GenerateRendererTask",
    "esri/layers/LayerDrawingOptions", "esri/tasks/UniqueValueDefinition",
    "esri/Color",
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
    "dojo/on", "esri/urlUtils", "agsjs/dijit/TOC",
    "dojo/parser",
    "dijit/registry", "dijit/TitlePane", "dijit/layout/ContentPane", "esri/dijit/LayerSwipe",
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
    domConstruct, domstyle,
    mouse,
    domClass,
    dom, Scalebar, HomeButton,
    SimpleMarkerSymbol,
    SimpleLineSymbol,
    SimpleFillSymbol, PictureMarkerSymbol,
    UniqueValueRenderer, SimpleRenderer,
    Font, TextSymbol, GenerateRendererParameters, GenerateRendererTask,
    LayerDrawingOptions, UniqueValueDefinition,
    Color,
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
    on, urlUtils, TOC,
    parser,
    registry, TitlePane, ContentPane, LayerSwipe,
    Chart, Memory, DataSeries, Observable, StoreSeries,
    Highlight, Tooltip, Columns, Bars, Default) {
    parser.parse();

    var ind_ext = new Extent(68.09381543863502, 6.754367934041333, 97.41149826167937, 37.077610645887944, new SpatialReference({ wkid: 4326 }));
    var sfs = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID,
        new Color([98, 194, 204]), 2), new Color([184, 134, 222, 0.25]));
    var map = new Map("mapDiv", {
        basemap: "streets",
        logo: false, showAttribution: false
    });
    map.setExtent(ind_ext, true);
    // Code from map js

    //var adminurl = "https://mapservice.gov.in/gismapservice/rest/services/BharatMapService/State_Boundary/MapServer";
    var adminurl = mapjson.mapService[0].url;
   // console.log(adminurl);
    // console.log(mapjson);


    dlayer = new ArcGISDynamicMapServiceLayer(adminurl + token_var);
    map.addLayers([dlayer]);
    dlayer.setVisibleLayers([0, 1, 2, 3]);
    // dlayer.on("error", dlayererror);

    //

    function dlayererror() {
        //alert("Service is not available");
    }
    map.on("click", function (evt) {
        // alert(webMercatorUtils.xyToLngLat(evt.mapPoint.x, evt.mapPoint.y));
    });
    function OpenPopup(reg_no) {

        var district_code = (reg_no.substring(0, 2) == "RJ") ? reg_no.substring(5, 7) : reg_no.substring(3, 5);
        var url = 'http://awaassoft.nic.in/netiay/benificiary_detail_1.aspx?reg_no=' + reg_no + '&f1=' + reg_no.substring(0, 2) + '&f2=' + district_code + '&f3=O';
        var url_New = 'http://awaassoft.nic.in/netiay/benificiary_detail_1.aspx?reg_no=' + reg_no + '&f1=' + reg_no.substring(0, 2) + '&f2=' + district_code + '&f3=N';
        window.open((reg_no.length == 9) ? url_New : url, "_blank", "toolbar=no,scrollbars=yes,menubar=no,resizable=no,status=no,top=200,left=500,width=1000,height=700,location=no");

        return false;
    }
    $('#complete_det').click(function () {
        OpenPopup(reg_no);
    })
    var reg_no;
    function OpenMarkerPopup(reg_no) {
        //  var stcode = Getstatecode(reg_no);
        var district_code = (reg_no.substring(0, 2) == "RJ") ? reg_no.substring(5, 7) : reg_no.substring(3, 5);
        //var url = 'http://localhost:56094/D1POPUP.html?code=' + State_Code +'&regNo='+ reg_no+'&flag=i';
        //var url_New = 'http://localhost:56094/D1POPUP.html?code=' + State_Code + '&regNo=' + reg_no + '&flag=i';
        var url = 'https://awaassoft.nic.in/netiay/D1POPUP.html?code=' + State_Code + '&regNo=' + reg_no + '&flag=i';
        var url_New = 'https://awaassoft.nic.in/netiay/D1POPUP.html?code=' + State_Code + '&regNo=' + reg_no + '&flag=i';
        window.open((reg_no.length == 9) ? url_New : url, "_blank", "toolbar=no,scrollbars=yes,menubar=no,resizable=no,status=no,top=200,left=500,width=500,height=400,location=no");
        return false;
    }

    var basemaps = [];
    var blank = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer", opacity: 0
    });
    var nobasemap = new Basemap({
        layers: [blank],
        title: "India",
        thumbnailUrl: baseurl + "assets/site/theme1/mapview/Images/basemapimages/india_bnd.png"
    });
    basemaps.push(nobasemap);
    var streetlayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer"
    });
    var streetbasemap = new Basemap({
        layers: [streetlayer],
        title: "Street",
        thumbnailUrl: baseurl + "assets/site/theme1/mapview/Images/basemapimages/Street.png"
    });
    basemaps.push(streetbasemap);
    var topolayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer"
    });
    var topobasemap = new Basemap({
        layers: [topolayer],
        title: "Topo",
        thumbnailUrl: baseurl + "assets/site/theme1/mapview/Images/basemapimages/Topo.png"
    });
    basemaps.push(topobasemap);
    var satellitelayer = new BasemapLayer({
        url: "http://services.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer"
    });
    var satellitebasemap = new Basemap({
        layers: [satellitelayer],
        title: "Satellite",
        thumbnailUrl: baseurl + "assets/site/theme1/mapview/Images/basemapimages/Satellite.png"
    });
    basemaps.push(satellitebasemap);
    var basemaptp = new TitlePane({ title: "Switch Basemap", closable: false, open: false });
    dom.byId("basemappane").appendChild(basemaptp.domNode);
    basemaptp.startup();
    var basemapcp = new ContentPane({ style: "width:170px; height:220px; overflow:auto;" });
    basemapcp.startup();
    basemaptp.setContent(basemapcp);
    var basemapdiv = domConstruct.create("div");
    basemapcp.setContent(basemapdiv);
    var basemapGallery = new BasemapGallery({
        showArcGISBasemaps: false,
        basemaps: basemaps,
        map: map
    }, basemapdiv);
    basemapGallery.on("error", function (msg) {
        console.log("basemap gallery error:  ", msg);
    });
    basemapGallery.startup();
    // alert('mkmkmk');
    // map.on("layers-add-result", function (evt) {
    // alert('pppp');
    ajaxcall();
    // console.log(featureLayer)
    // });

    var sfs_quer = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID,
        new Color([98, 194, 204]), 2), new Color([0, 206, 209, 0.25]));
    var sms_quer = new SimpleMarkerSymbol(SimpleMarkerSymbol.STYLE_CIRCLE, 10,
        new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID,
            new Color([153, 102, 255]), 1), new Color([59, 26, 206, 1]));
    var sls_quer = new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([0, 206, 209, 1]), 3);

    var sms_highlight = new SimpleMarkerSymbol(SimpleMarkerSymbol.STYLE_SQUARE, 10,
        new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID,
            new Color([255, 0, 0]), 2), new Color([59, 26, 206, 1]));
    var sls_highlight = new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([255, 134, 222, 1]), 4);
    var sfs_highlight = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID,
        new Color([255, 194, 204]), 3), new Color([184, 134, 222, 0.25]));

    function assetmouseclickfun(asset_graphlayerhandle, event) {
        asset_graphlayerhandle.pause();
         reg_no = event.graphic.attributes.regNo;
    // console.log(reg_no);
    //   // alert(reg_no);
       
                    // $.ajax({
                    //     type: 'POST',
                       
                    //     url: 'http://10.177.15.95/rtps/site/pfcmap/all_pop',
                    //     data: { reg_no },
                    //     success: function (data) {
                    //      //console.log(data);
                    //     }
                    // });
            
        createPopup(reg_no);//assets_layer\
        //OpenMarkerPopup('MP1071760');
    }
    //createPopup();
    // Popup Function
    var BeneficiaryName, BeneficiaryFather_HusbandName;
    function createPopup(regno) {

        console.log(regno);

        var content = '<table><tr><td></td><td><center><span style="font-size:12pt;font-weight:600;">' + regno + '</span></center></td></tr></table>';
        $("#identifydialog").html(content);
        opendialogbox();

        //alert(regno);
        // jsonData = JSON.stringify({
        //     // regNo: details[i].regNo
        //     regNo: regno
        // });
        //  alert(jsonData);
        /*$.ajax({
            type: "GET",
            // contentType: "application/json; charset=utf-8",
            // url: 'https://rhreporting.nic.in/netiay/Services/Service.svc/getInspectData',
            // url: 'http://10.177.15.95/rtps/site/pfcmap/all_pop?rno='+regno,
            // url: 'https://rurban.gov.in/index.php/Map/getPopupData?rno='+regno,
            url: document.getElementById('base').value + "/site/pfcmap/all_pop?rno=" + regno,
            // data: jsonData,
            // dataType: "json",
            
            success: function (response) {
                // var obj = JSON.parse(response.d);
               // 
                var obj = JSON.parse(response);
                // console.log(obj.name);

                //datastring = '<br/><b>Registration No:</b>' + obj.RegNo + '<br/><b>Beneficiary Name:</b>' + obj.BeneficiaryName + '<br/><b>Father/Husband Name:</b>' + obj.BeneficiaryFather_HusbandName + '<br/><b>Village:</b>' + obj.VillageName + '<br/><b>Panchayat:</b>' + obj.PanchayatName + '<br/><b>Block:</b>' + obj.BlockName + '<br/><b>District:</b>' + obj.DistrictName + '<br/><b>House Status:</b>' + obj.HouseStatus + '<br/><b>Admin Sanctioned Date:</b>' + obj.AdminSanctiondate + '<br/><b>Amount Released:</b>' + obj.AmountReleased + '<br/><button onclick="OpenPopup(\'' + obj.RegNo + '\')" class="button"><span>View Complete Details</span></button>';
                //infowindow.setContent(datastring);
                //infowindow.open(map, marker);
                var content = '<table><tr><td></td><td><center><span style="font-size:12pt;font-weight:600;">' + regno +'</span></center></td></tr>';
                // content += "<tr><td><b>" + obj.meta_data[1] + ":</td></b>" + "<td><b> </b></td>" + "<td>" + obj.name + "</td></tr>";
               

                // content += "<tr><td><b>Cluster Name:</td></b>" + "<td><b> </b></td>" + "<td>" + obj.clusterName + "</td></tr>";
                // content += "<tr><td><b>District Name:</td></b>" + "<td><b> </b></td>" + "<td>" + obj.districtName + "</td></tr>";
                // content += "<tr><td><b>State Name:</td></b>" + "<td><b> </b></td>" + "<td>" + obj.stateName + "</td></tr>";
                // content += "<tr><td colspan='2'><img style='height:200px;width:400px;' src='data:image/jpg;charset=utf8;base64," + obj.imgData + "'/></td></tr>";
                // $("#element").append($("<img />").attr('src', 'data:image/png;charset=utf8;base64,' + data.image));
                // content += "<tr><td><b>Block:</td></b>" + "<td><b>: </b></td>" + "<td>" + obj.BlockName + "</td></tr>";
                // content += "<tr><td><b>District:</td></b>" + "<td><b>: </b></td>" + "<td>" + obj.DistrictName + "</td></tr>";
                // content += "<tr><td><b>House Status:</td></b>" + "<td><b>: </b></td>" + "<td>" + obj.HouseStatus + "</td></tr>";
                // content += "<tr><td><b>Admin Sanctioned Date:</td></b>" + "<td><b>: </b></td>" + "<td>" + obj.AdminSanctiondate + "</td></tr>";
                // content += "<tr><td><b>Amount Released:</td></b>" + "<td><b>: </b></td>" + "<td>" + obj.AmountReleased + "</td></tr>";
                content += '</table>';


                //$("#identifydialog").empty();
                $("#identifydialog").html(content);
                opendialogbox();
            },
            error: function (a, b, c) {
                alert(a.responseText);
            }
        });*/
    }
    function assetmouseoutfun() {
        map.infoWindow.hide();
    }
    function assetmouseoverfun(asset_graphlayerhandle, event) {
        asset_graphlayerhandle.resume();
        map.infoWindow.clearFeatures();
        var graphic12 = event.graphic;
        map.infoWindow.setContent(graphic12.getContent());
        map.infoWindow.setTitle(graphic12.getTitle());
        map.infoWindow.show(event.screenPoint, map.getInfoWindowAnchor(event.screenPoint));
    }
    var assets_layer = new GraphicsLayer();
    map.addLayer(assets_layer);
    function getParamValuesByName(querystring) {
        var qstring = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < qstring.length; i++) {
            var urlparam = qstring[i].split('=');
            if (urlparam[0] == querystring) {
                return urlparam[1];
            }
        }
    }
    function ajaxcall() {

        document.getElementById("bodyloadimg").style.display = "block";
        //var url = "http://awaassoft.nic.in/netiay/Services/Service.svc/MobileInspectionMapService";
        // var url = 'https://rhreporting.nic.in/netiay/Services/Service.svc/MobileInspectionMapService';
        // url = "http://10.177.15.95/rtps/site/pfcmap/all_map";
        url = document.getElementById('base').value + "/site/pfcmap/all_map";

        // url = "https://rurban.gov.in/index.php/Map/latLongServiceUrl";

        $.ajax({
            type: 'POST',
            url: url,
            dataType: "JSON",

            // data: JSON.stringify(jsonData),//JSON.stringify({"uname": "sbmgis", "pwd": "sbmgis@2017#"}),
            crossDomain: true,
            contentType: "application/json",//"text/plain",// "application/x-www-form-urlencoded; charset=UTF-8",// ,//"application/json",

            success: function (response) {

                MIS_rec = response;
                assets_layer.clear();
                var extent, ext;
                var total_points = [];

                //  console.log(MIS_rec);
                for (var i = 0; i < MIS_rec.length; i++) {
                    var pt = new Point(Number(MIS_rec[i].longitude), Number(MIS_rec[i].Lattitude), new SpatialReference({ wkid: 4326 }));

                    //  var id=

                    var pictureMarkerSymbol = new PictureMarkerSymbol(baseurl + "assets/site/theme1/mapview/Images/basemapimages/bluemarker.png", 25, 30);

                    //pictureMarkerSymbol.id = "newID";
                    

                    // if (MIS_rec[i].HouseStatus == "Completed") {
                    //     pictureMarkerSymbol = new PictureMarkerSymbol(baseurl+"assets/site/theme1/mapview/Images/basemapimages/greenmarker.png", 25, 30);
                    // }
                    // else if (MIS_rec[i].HouseStatus == "Ongoing" || MIS_rec[i].HouseStatus == "Initiated" || MIS_rec[i].HouseStatus == "Before Initiated") {
                    //     pictureMarkerSymbol = new PictureMarkerSymbol(baseurl+"assets/site/theme1/mapview/Images/basemapimages/yellowmarker.png", 25, 30);
                    // }
                    var attr = MIS_rec[i];
                    //  console.log(attr);

                    assets_layer.add(new Graphic(pt, pictureMarkerSymbol, attr, null));
                    //assets_layer.add(new Graphic(pt, pictureMarkerSymbol, attr));
                    var geometry = pt;
                    if (geometry instanceof Point) {
                        ext = new Extent(geometry.x - 0.055, geometry.y - 0.055, geometry.x + 0.055, geometry.y + 0.0055, geometry.spatialReference);
                        
                    }
                    else if (geometry instanceof Extent) {
                        ext = geometry;
                    }
                    else {
                        ext = geometry.getExtent();
                    }

                    if (extent) {
                        if (ext)
                            extent = extent.union(ext);
                    }
                    else {
                        if (ext)
                            extent = new Extent(ext);
                    }
                }


                // $("pictureMarkerSymbol").on("click", function () {

                //     $.ajax({
                //         type: 'POST',
                       
                //         url: 'http://10.177.15.95/rtps/site/pfcmap/all_pop',
                //         data: { pt },
                //         success: function (data) {
                //             console.log(data);
                //           //  $('#verifyEMAIL_notice').hide();
                //            // $('#re_verifyEMAIL_notice').show();
                //         }
                //     });
                // });
                // console.log(img);
               // pictureMarkerSymbol.onclick=alert("hi");
            //    console.log(document.getElementById("pic"));
               // document.getElementById("newID").onclick =alert("hi");
                map.setExtent(extent, true);
                document.getElementById("bodyloadimg").style.display = "none";
            },
            error: function (err) {
                document.getElementById("bodyloadimg").style.display = "none";
                // alert(err.statusText);
            }

            

        });

        
    }


    var graphlayerhandle = on.pausable(assets_layer, "mouse-out", assetmouseoutfun);
    assets_layer.on("click", dojo.partial(assetmouseclickfun, graphlayerhandle));

   

});