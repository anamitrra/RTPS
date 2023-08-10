var token_var = "?Token=" + mapjson.mapService[0].token;
require(["esri/map", "esri/SnappingManager", "esri/SpatialReference", "esri/dijit/BasemapGallery", "esri/dijit/BasemapLayer", "esri/dijit/Basemap",
    "esri/layers/ArcGISDynamicMapServiceLayer", "esri/layers/ImageParameters",
    "esri/layers/ArcGISTiledMapServiceLayer",
    "esri/layers/FeatureLayer", "esri/geometry/webMercatorUtils",
    "esri/layers/GraphicsLayer", "esri/geometry/Point", "esri/geometry/Circle", "esri/units", "dojo/_base/event", "esri/geometry/geometryEngine",
    "esri/tasks/GeometryService",
    "esri/tasks/query",
    "esri/tasks/QueryTask",
    "esri/dijit/HomeButton",
    "esri/dijit/FeatureTable",
    "esri/geometry/Extent",
    "dojo/dom-construct", "dojo/dom-style", "dojo/mouse",
    "dojo/dom-class",
    "dojo/dom", "esri/dijit/Scalebar",
    "esri/symbols/SimpleMarkerSymbol",
    "esri/symbols/SimpleLineSymbol",
    "esri/symbols/SimpleFillSymbol", "esri/symbols/PictureMarkerSymbol",
    "esri/renderers/UniqueValueRenderer",
    "esri/symbols/Font",
    "esri/symbols/TextSymbol",
    "esri/Color",
    "esri/graphic", "esri/renderers/ClassBreaksRenderer",
    "esri/InfoTemplate", "esri/dijit/InfoWindow", "esri/dijit/Print",
    "esri/tasks/PrintTemplate", "esri/config", "esri/request",
    "dojo/_base/array", "dojo/_base/connect",
    "dojox/grid/DataGrid", "dojox/grid/EnhancedGrid",
    "dojo/data/ItemFileWriteStore", "dojo/data/ItemFileReadStore",
    "dijit/Dialog",
    "dijit/ColorPalette",
    "dojo/_base/lang",
    "esri/toolbars/navigation", "esri/dijit/Legend", "esri/dijit/Measurement", "esri/dijit/LayerList",
    "dojo/on", "esri/urlUtils",
    "dojo/keys",
    "dojo/parser", "esri/sniff",
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
    Map, SnappingManager, SpatialReference, BasemapGallery, BasemapLayer, Basemap,
    ArcGISDynamicMapServiceLayer, ImageParameters,
    ArcGISTiledMapServiceLayer,
    FeatureLayer, webMercatorUtils,
    GraphicsLayer, Point, Circle, units, event, geometryEngine,
    GeometryService,
    Query, QueryTask, HomeButton,
    FeatureTable,
    Extent,
    domConstruct, domstyle,
    mouse,
    domClass,
    dom, Scalebar,
    SimpleMarkerSymbol,
    SimpleLineSymbol,
    SimpleFillSymbol, PictureMarkerSymbol,
    UniqueValueRenderer,
    Font, TextSymbol,
    Color,
    Graphic, ClassBreaksRenderer,
    InfoTemplate, InfoWindow, Print,
    PrintTemplate, esriConfig, esriRequest,
    arrayUtils, connect,
    DataGrid, EnhancedGrid,
    ItemFileWriteStore, ItemFileReadStore,
    Dialog,
    ColorPalette,
    lang,
    Navigation, Legend, Measurement, LayerList,
    on, urlUtils, keys,
    parser, has,
    registry, TitlePane, ContentPane, TOC, LayerSwipe,
    Chart, Memory, DataSeries, Observable, StoreSeries,
    Highlight, Tooltip, Columns, Bars, Default) {
	parser.parse();
	var measure;
	var full = false;
	var zin = zout = false;
	var navToolbar;
	var polygraphlayer = new GraphicsLayer();
	var polylblgraphlayer = new GraphicsLayer();
	var pointgraphlayer = new GraphicsLayer();
	var maskcirclelayer = new GraphicsLayer();
	var stdtbk_graphlyr = new GraphicsLayer();
	map = new Map("mapDiv", {
		basemap: "streets",
		logo: false,
		// minZoom: 4,
		slider: true, showAttribution: false
	});
	var home = new HomeButton({
		map: map
	}, "HomeButton");
	home.startup();

	var basemaps = [];
	var blank = new BasemapLayer({
		url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer", opacity: 0
	});
	var nobasemap = new Basemap({
		layers: [blank],
		title: "India",
		thumbnailUrl: "images/basemap images/india_bnd.png"
	});
	basemaps.push(nobasemap);

	var nicstreetlayer = new BasemapLayer({
		url: nicStreetMapUrl
	});
	var nicstreetbasemap = new Basemap({
		layers: [nicstreetlayer],
		title: "NIC Street",
		thumbnailUrl: "images/basemap images/NIC_Street.png"
	});
	basemaps.push(nicstreetbasemap);

	var streetlayer = new BasemapLayer({
		url: "http://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer"
	});
	var streetbasemap = new Basemap({
		layers: [streetlayer],
		title: "Street",
		thumbnailUrl: "images/basemap images/street.png"
	});
	basemaps.push(streetbasemap);

	var topolayer = new BasemapLayer({
		url: "http://services.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer"
	});
	var topobasemap = new Basemap({
		layers: [topolayer],
		title: "Topo",
		thumbnailUrl: "images/basemap images/topo.png"
	});
	basemaps.push(topobasemap);

	var satellitelayer = new BasemapLayer({
		url: "http://services.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer"
	});
	var satellitebasemap = new Basemap({
		layers: [satellitelayer],
		title: "Satellite",
		thumbnailUrl: "images/basemap images/satellite.png"
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
	//map.on("load", function () {
	//    basemapGallery.select("basemap_0");
	//});
	var sfs_stdtbk = new SimpleFillSymbol(SimpleFillSymbol.STYLE_NULL, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([0, 0, 0]), 2), new Color([0, 206, 209, 0.25]));
	var maskcircleSymb = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SHORTDASHDOTDOT, new Color([105, 105, 105]), 2), new Color([255, 255, 255, 1]));
	var maskcircle = new Circle({
		center: [80, 19],
		geodesic: true,
		radius: 3000,
		radiusUnit: units.MILES
	});
	var ind_ext = new Extent(66.62, 5.23, 98.87, 38.59, new SpatialReference({ wkid: 4326 }));
	map.setExtent(ind_ext, true);

	hello_one('col-lg-15 col-md-15 col-sm-15 col-xs-15 three_one', 10);
	var arr = ["Resolution Uploaded", "Verified Priority List", "Aadhar Registered Beneficiary", "Sanction with Geotagged", "First Installment", "Second Installment", "Last Installment", "House Completed", "Mason Trained", "Delayed Houses"];
	//hello_one('col-lg-3 col-md-3 col-sm-3 col-xs-3 three_one', 8);
	//var arr = ["Resolution Uploaded", "Verified Priority List", "Aadhar Registered Beneficiary", "Sanction with Geotagged", "First Installment", "Second Installment", "Last Installment", "House Completed"];
	var water_row = document.getElementById('row_water');

	for (var i = 0; i < arr.length; i++) {
		water_row.childNodes[i].childNodes["0"].data = arr[i];
		water_row.childNodes[i].dataset.value = arr[i];
		water_row.childNodes[i].dataset.title = arr[i];
		document.getElementById('themetitle').textContent = "Performance";
	}
	$(document).on('click', ".three_one", function () {
		map.graphics.clear();
		maskcirclelayer.clear();
		map.infoWindow.hide();
		$(".three_one").css('border', '');
		$(this).css({
			"border-color": "white",
			"border-style": "groove"
		});
		$("div.three_one").removeClass("three_oneactive");
		$(this).addClass("three_oneactive");
		name = $(this)[0].dataset.value;
		category = $(this)[0].dataset.value;
		//gis_mis(MIS_rec, name);\
		document.getElementById("bodyloadimg").style.display = "block";
		document.getElementById('themetitle').textContent = $(this)[0].dataset.value;
		ajaxfun($(this)[0].dataset.value);
	});
	map.on("layers-add-result", function (evt) {
	});

	var imageParameters = new ImageParameters();
	imageParameters.format = "png32";
	var adminurl = mapjson.mapService[0].url;
	var admin = new ArcGISDynamicMapServiceLayer(adminurl + token_var, { imageParameters: imageParameters });
	admin.setVisibleLayers([0, 1]);
	map.addLayer(maskcirclelayer);
	map.addLayer(stdtbk_graphlyr);
	var mulbuf = new GraphicsLayer();
	map.addLayer(mulbuf);
	var ptbufferSymbol = new SimpleFillSymbol(SimpleFillSymbol.STYLE_SOLID, new SimpleLineSymbol(SimpleLineSymbol.STYLE_SOLID, new Color([255, 0, 0, 0.65]), 2),
		new Color([255, 0, 0, 0.35]));
	var pointfeatureLayer = new GraphicsLayer();
	var toilets_graphlayer = new GraphicsLayer();
	map.addLayers([admin, pointfeatureLayer, toilets_graphlayer]);
	map.addLayer(maskcirclelayer);
	map.addLayer(stdtbk_graphlyr);
	var mdiv = document.createElement("div");
	mdiv.setAttribute("style", "display:none; font-size:13px; ");
	mdiv.setAttribute("class", "dialogs");
	mdiv.setAttribute("align", 'center');
	mdiv.id = "mdiv";
	mdiv.innerHTML = "<div id='measurementDiv' ></div>";
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
				"collapsable": false, // enable/disable collapse button
				"dblclick": "collapse", // set action on double click. false, 'maximize', 'minimize', 'collapse'
				//"titlebar": "transparent", // false, 'none', 'transparent'
				"minimizeLocation": "right" // sets alignment of minimized dialogues                           
			});
	});
	$("#mdiv").dialog("option", "position", { my: "left top", at: "right top" });
	$("#mdiv").parent().draggable({
		containment: '#mapDiv'
	});
	$('#mapDiv').append($("#mdiv").parent());
	measure = false;
	var measurement;
	measurement = new Measurement({
		map: map,
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
	on(dom.byId("measure"), "click", function () {
		if (measure == false) {
			map.infoWindow.hide();
			dom.byId("measure").style.backgroundColor = "#99e6ff";
			measure = true;
			$(function () { $("#mdiv").dialog('open'); });
			measurement.clearResult();
		}
		else {
			measurement.setTool("location", false);
			measurement.setTool("area", false);
			measurement.setTool("distance", false);
			measurement.clearResult();
			dom.byId("measure").style.backgroundColor = "";
			measure = false;
			$(function () { $("#mdiv").dialog('close'); });
			map.graphics.clear();
		}
	});
	$(function () {
		$("#mdiv").dialog({
			close: function (event, ui) {
				measurement.setTool("location", false);
				measurement.setTool("area", false);
				measurement.setTool("distance", false);
				measurement.clearResult();
				dom.byId("measure").style.backgroundColor = "";
				measure = false;
				map.graphics.clear();
			}
		});
	});

	//############################## home button#################
	home.on("home", function () {
		//if (click_flag == "dist") {
		backtoextent();
		// }
	});
	function backtoextent() {
		document.getElementById("bodyloadimg").style.display = "block";
		pointfeatureLayer.clear(); toilets_graphlayer.clear();
		stdtbk_graphlyr.clear();
		map.graphics.clear();
		$("div.three_one").removeClass("three_oneactive");
		document.getElementById('themetitle').textContent = "Performance";
		category = 'Performance';
		//document.getElementById("search").value = "";
		admin.setDefaultLayerDefinitions();
		while (statedd.options.length > 0) {
			statedd.removeOption(0);
		}
		document.getElementById("distdd").style.visibility = "hidden";
		document.getElementById("blkdd").style.visibility = "hidden";
		map.setExtent(admin.fullExtent, true);
		// admin.setDefaultLayerDefinitions();
		$('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 States';
		$('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 States';
		$('#stdist1,#stdist2')[0].innerHTML = "State";
		var query = new Query();
		query.where = "1=1";
		query.outFields = ["STNAME,STCODE11"];
		var queryTask = new QueryTask(adminurl + "/" + 0 + token_var);
		queryTask.execute(query, dojo.partial(stdtbkfun, "STNAME", "STCODE11", "Select State", "statedd"));
		//ajaxfun($('.three_oneactive')[0].dataset.value);
		return;
	}

	function extentHistoryChangeHandler() {
		if (!full)
			$("#zoomfullext a").attr("disabled", false);
		else
			$("#zoomfullext a").attr("disabled", true);
		full = false;
		if (navToolbar.isFirstExtent())
			$("#zoomprev a").attr("disabled", true);
		else
			$("#zoomprev a").attr("disabled", false);
		if (navToolbar.isLastExtent())
			$("#zoomnext a").attr("disabled", true);
		else
			$("#zoomnext a").attr("disabled", false);
	}

	map.on("load", function () {
		var query = new Query();
		query.where = "1=1";
		query.outFields = ["STNAME,STCODE11"];
		var queryTask = new QueryTask(adminurl + "/" + 0 + token_var);
		queryTask.execute(query, dojo.partial(stdtbkfun, "STNAME", "STCODE11", "Select State", "statedd"));
	});
	//if (window.location.href.indexOf('?blkcode=') > 0) {
	//	var url_string = window.location.href;
	//	var url = new URL(url_string);
	//	var blk_code = url.searchParams.get("blkcode");
	//	blkdd.attr("value", blk_code);
	//}
	var statedd = registry.byId("statedd");
	var distdd = registry.byId("distdd");
	var blkdd = registry.byId("blkdd");
	var gpdd = registry.byId("gpdd");
	var stdtbkdd = registry.byId(stdtbkdd);

	registry.byId("statedd").on("change", function (evt) {
		document.getElementById("bodyloadimg").style.display = "block";
		//map.setExtent(exte, false);
		map.graphics.clear();
		//document.getElementById("search").value = "";
		dom.byId("distdd").selectedIndex = 0;
		document.getElementById("blkdd").style.visibility = "hidden";
		dom.byId("gpdd").style.visibility = "hidden";
		while (distdd.options.length > 0) {
			distdd.removeOption(0);
		}
		while (blkdd.options.length > 0) {
			blkdd.removeOption(0);
		}
		var value = statedd.value;
		if (value == "select") {
			pointfeatureLayer.clear(); toilets_graphlayer.clear();
			stdtbk_graphlyr.clear();
			document.getElementById("distdd").style.visibility = "hidden";
			document.getElementById("blkdd").style.visibility = "hidden";
			map.setExtent(admin.fullExtent, true);
			admin.setDefaultLayerDefinitions();
			if ($('.three_oneactive').length == 0)
				ajaxfun("Performance")
			else
				ajaxfun($('.three_oneactive')[0].dataset.value);
			return;
		}
		var layerDefinitions = [];
		layerDefinitions[0] = "stcode11='" + value + "'";
		layerDefinitions[1] = "stcode11='" + value + "'";
		layerDefinitions[2] = "stcode11='" + value + "'";
		layerDefinitions[3] = "stcode11='" + value + "'";
		admin.setLayerDefinitions(layerDefinitions);
		var query = new Query();
		query.where = "stcode11 ='" + value + "'";
		query.outFields = ["dtname,dtcode11"];
		var queryTask = new QueryTask(adminurl + "/" + 1 + token_var);
		queryTask.execute(query, dojo.partial(stdtbkfun, "dtname", "dtcode11", "Select District", "distdd"));
		//ajaxcall();
		dom.byId("distdd").style.visibility = "visible";
		var query = new Query();
		query.where = "stcode11='" + value + "'";
		query.returnGeometry = true;
		var queryTask = new QueryTask(adminurl + "/" + 0 + token_var);
		queryTask.execute(query, dojo.partial(stfun_ext));
	});

	on(registry.byId("distdd"), "change", function (evt) {
		document.getElementById("bodyloadimg").style.display = "block";
		dom.byId("gpdd").style.visibility = "hidden";
		toilets_graphlayer.clear();
		while (blkdd.options.length > 0) {
			blkdd.removeOption(0);
		}
		dom.byId("blkdd").selectedIndex = 0;
		var value = distdd.value;

		if (value == "select") {
			pointfeatureLayer.clear(); toilets_graphlayer.clear();
			stdtbk_graphlyr.clear();
			var stvalue = statedd.value;
			document.getElementById("blkdd").style.visibility = "hidden";
			var query = new Query();
			query.where = "stcode11 = '" + stvalue + "'";
			query.returnGeometry = true;
			var queryTask = new QueryTask(adminurl + "/" + 0 + token_var);
			queryTask.execute(query, dojo.partial(stfun_ext));
			admin.setDefaultLayerDefinitions();
			var layerDefinitions = [];
			layerDefinitions[0] = "stcode11='" + stvalue + "'";
			layerDefinitions[1] = "stcode11='" + stvalue + "'";
			layerDefinitions[2] = "stcode11='" + value + "'";
			layerDefinitions[3] = "stcode11='" + value + "'";
			admin.setLayerDefinitions(layerDefinitions);
			if ($('.three_oneactive').length == 0)
				ajaxfun("Performance")
			else
				ajaxfun($('.three_oneactive')[0].dataset.value);
			return;
		}
		var layerDefinitions = [];
		layerDefinitions[0] = "dtcode11='" + value + "'";
		layerDefinitions[1] = "dtcode11='" + value + "'";
		layerDefinitions[2] = "dtcode11='" + value + "'";
		layerDefinitions[3] = "dtcode11='" + value + "'";
		admin.setLayerDefinitions(layerDefinitions);
		var query = new Query();
		query.where = "dtcode11 ='" + value + "'";
		query.outFields = ["block_name,block_lgd"];
		var queryTask = new QueryTask(adminurl + "/" + 2 + token_var);
		queryTask.execute(query, dojo.partial(stdtbkfun, "block_name", "block_lgd", "Select Block", "blkdd"));
		//ajaxfun($('.three_oneactive')[0].dataset.value);
		var query = new Query();
		query.where = "dtcode11='" + value + "'";
		query.returnGeometry = true;
		var queryTask = new QueryTask(adminurl + "/" + 1 + token_var);
		queryTask.execute(query, dojo.partial(dtbkfun_ext));

		dom.byId("blkdd").style.visibility = "visible";

	});

	on(registry.byId("blkdd"), "change", function (evt) {
		document.getElementById("bodyloadimg").style.display = "block";
		toilets_graphlayer.clear();
		while (gpdd.options.length > 0) {
			gpdd.removeOption(0);
		}
		dom.byId("gpdd").selectedIndex = 0;
		var value = blkdd.value;

		if (value == "select") {
			stdtbk_graphlyr.clear();
			var query = new Query();
			query.where = "dtcode11 = '" + distdd.value + "'";
			query.returnGeometry = true;
			var queryTask = new QueryTask(adminurl + "/" + 1 + token_var);
			queryTask.execute(query, dojo.partial(dtbkfun_ext));
			if ($('.three_oneactive').length == 0)
				ajaxfun("Performance")
			else
				ajaxfun($('.three_oneactive')[0].dataset.value);
			return;
		}
		var layerDefinitions = [];
		layerDefinitions[0] = "blk_lgdcod='" + value + "'";
		layerDefinitions[1] = "blk_lgdcod='" + value + "'";
		layerDefinitions[2] = "blk_lgdcod='" + value + "'";
		layerDefinitions[3] = "blk_lgdcod='" + value + "'";
		admin.setLayerDefinitions(layerDefinitions);
		var query = new Query();
		query.where = "blk_lgdcod ='" + value + "'";
		query.outFields = ["gp_name,gp_code"];
		var queryTask = new QueryTask(adminurl + "/" + 3 + token_var);
		queryTask.execute(query, dojo.partial(stdtbkfun, "gp_name", "gp_code", "Select GP", "gpdd"));
		//ajaxfun($('.three_oneactive')[0].dataset.value);
		var query = new Query();
		query.where = "block_lgd='" + value + "'";
		query.returnGeometry = true;
		var queryTask = new QueryTask(adminurl + "/" + 2 + token_var);
		queryTask.execute(query, dojo.partial(dtbkfun_ext));

		//dom.byId("gpdd").style.visibility = "visible";
	});

	function stdtbkfun(name, code, stdtbk, stdtbkdd, fs) {
		var stdtbkdd = registry.byId(stdtbkdd);
		while (stdtbkdd.firstChild) {
			stdtbkdd.removeChild(stdtbkdd.firstChild);
		}
		var option = document.createElement('option');
		option.value = "select";
		option.text = stdtbk;
		stdtbkdd.addOption(option);
		var fet = fs.features;
		var tmpAry = new Array();
		for (var k = 0; k < fet.length; k++) {
			tmpAry[k] = new Array();
			tmpAry[k][0] = fet[k].attributes[name];
			tmpAry[k][1] = fet[k].attributes[code];
		}
		tmpAry.sort();
		//                             while (distdd.options.length > 0) {
		//                                 distdd.options[0] = null;
		//                             }
		for (var o = 0; o < tmpAry.length; o++) {
			var op = new Option(tmpAry[o][0], tmpAry[o][1]);
			stdtbkdd.addOption(op); //[o + 1] = op;
		}
		if ($('.three_oneactive').length == 0)
			ajaxfun("Performance");
		else
			ajaxfun($('.three_oneactive')[0].dataset.value);
		//gettotalcount();
	}

	function dtbkfun_ext(fs) {
		map.setExtent(fs.features[0].geometry.getExtent(), true);
		var grs = fs.features[0];
		grs.setSymbol(sfs_stdtbk);
		stdtbk_graphlyr.clear();
		stdtbk_graphlyr.add(grs);
	}
	function stfun_ext(fs) {
		//maskcirclelayer.clear();
		map.setExtent(fs.features[0].geometry.getExtent(), true);
		var grs = fs.features[0];
		grs.setSymbol(sfs_stdtbk);
		stdtbk_graphlyr.clear();
		stdtbk_graphlyr.add(grs);
	}

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
		featmouseclickfun(event);
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

	var giscode = null;
	function featmouseclickfun(evt) {

		if ($('.three_oneactive').length == 0)
			var mission = "Performance";
		else
			var mission = $('.three_oneactive')[0].dataset.value;

		if (statedd.value == 'select') {
			giscode = "STCODE11";
			statedd.attr("value", evt.graphic.attributes[giscode]);
		}
		else if (statedd.value != 'select' && distdd.value == 'select') {
			giscode = "dtcode11";
			distdd.attr("value", evt.graphic.attributes[giscode]);
		}
		else if (statedd.value != 'select' && distdd.value != 'select' && blkdd.value == 'select') {
			giscode = "block_lgd";
			blkdd.attr("value", evt.graphic.attributes[giscode]);
		}
	}
	var featureCollection = {
		"layerDefinition": null,
		"featureSet": {
			"features": [],
			"geometryType": "esriGeometryPolygon"
		}
	};
	featureCollection.layerDefinition = {
		"geometryType": "esriGeometryPolygon",
		"objectIdField": "ObjectID",
		"fields": [{
			"name": "ObjectID",
			"alias": "ObjectID",
			"type": "esriFieldTypeOID"
		}, {
			"name": "parameter",
			"alias": "Parameter",
			"type": "esriFieldTypeDouble"
		}, {
			"name": "code",
			"alias": "Code",
			"type": "esriFieldTypeString"
		}]
	};

	featureLayer = new FeatureLayer(featureCollection, {
		id: 'buildingFeatures'
	});
	map.addLayers([featureLayer]);
	var graphlayerhandle = on.pausable(featureLayer, "mouse-out", assetmouseoutfun);
	featureLayer.on("mouse-over", dojo.partial(assetmouseoverfun, graphlayerhandle));
	featureLayer.on("click", dojo.partial(assetmouseclickfun, graphlayerhandle));

	var graphlayerhandle = on.pausable(toilets_graphlayer, "mouse-out", assetmouseoutfun);
	toilets_graphlayer.on("mouse-over", dojo.partial(assetmouseoverfun, graphlayerhandle));
	toilets_graphlayer.on("click", dojo.partial(assetmouseclickfun, graphlayerhandle));

	var water_row = document.getElementById('row_water');

	function ajaxfun(category) {
		featureLayer.clear();
		dom.byId("legendDiv").innerHTML = "";
		if (typeof registry.byId("legendDiv") != "undefined") {
			registry.byId("legendDiv").destroyRecursive(true);
		}
		dojo.empty("chartNode");
		$('.stat_table').find('tbody').empty();
		for (var i = 0; i < arr.length; i++) {
			water_row.children[i].children["0"].innerText = 0;
		}
		chartdata = [];
		if (statedd.value == 'select') {
			url = "https://rhreporting.nic.in/netiay/Services/Service.svc/GetstatewiseMapdataRMT"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123" };
			//url = "https://awaassoft.nic.in/netiay/Services/Service.svc/GetstatewiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123" };
		}
		else if (statedd.value != 'select' && distdd.value == 'select') {
			url = "https://rhreporting.nic.in/netiay/Services/Service.svc/GetdistrictwiseMapdataRMT"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value), "Censusdist_code": (distdd.value == '718' ? '650' : distdd.value) };
			//url = "https://awaassoft.nic.in/netiay/Services/Service.svc/GetdistrictwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value) };
		}
		else if (statedd.value != 'select' && distdd.value != 'select' && blkdd.value == '' || blkdd.value == 'select') {
			url = "https://rhreporting.nic.in/netiay/Services/Service.svc/GetBlockwiseMapdataRMT"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value), "Censusdist_code": (distdd.value == '718' ? '650' : distdd.value) };
			//url = "https://awaassoft.nic.in/netiay/Services/Service.svc/GetBlockwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value), "Censusdist_code": distdd.value };
		}
		else if (statedd.value != 'select' && distdd.value != 'select' && blkdd.value != 'select') {
			url = "https://rhreporting.nic.in/netiay/Services/Service.svc/GetPanchyatwiseMapdataRMT"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value), "Blocklgd_code": blkdd.value, "Censusdist_code": (distdd.value == '718' ? '650' : distdd.value) };
			//url = "https://awaassoft.nic.in/netiay/Services/Service.svc/GetPanchyatwiseMapdata"; data = { "uname": "rd_pmayg", "pwd": "pmayg@123", "state_code": (statedd.value == '37' ? '28' : statedd.value), "Blocklgd_code": blkdd.value };
		}
		node = 'DATA';
		block_drpdwn_value = blkdd.value;
		$.ajax({
			type: 'POST',
			url: url,
			dataType: "xml",
			data: JSON.stringify(data),//JSON.stringify({"uname": "sbmgis", "pwd": "sbmgis@2017#"}),
			crossDomain: true,
			contentType: "application/json",//"application/json",
			success: function (dat) {
				featureLayer.clear();
				MIS_rec = dat;
				MIScontent = dat.documentElement.childNodes;
				MIS_rec = [];
				var arr = []; arr[0] = arr[1] = arr[2] = arr[3] = arr[4] = arr[5] = arr[6] = arr[7] = arr[8] = arr[9] = 0;
				//var arr = []; arr[0] = arr[1] = arr[2] = arr[3] = arr[4] = arr[5] = arr[6] = arr[7] = 0;
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
				if (MIS_rec.length == 0) {
					document.getElementById("bodyloadimg").style.display = "none";
					alert('No Records Found');
					//backtoextent();
					return;
				}
				for (var i = 0; i < MIS_rec.length; i++) {
					arr[0] += Number(MIS_rec[i].ResulutionUploaded);
					arr[1] += Number(MIS_rec[i].vrifiedPrioritylist);
					arr[2] += Number(MIS_rec[i].AdharseedingOfRegsteredBen);
					arr[3] += Number(MIS_rec[i].SanctionwithGeotaged);
					arr[4] += Number(MIS_rec[i].FirstIntalment);
					arr[5] += Number(MIS_rec[i].SecondIntalment);
					arr[6] += Number(MIS_rec[i].LastIntalment);
					arr[7] += Number(MIS_rec[i].HouseComplited);
					arr[8] += Number(MIS_rec[i].RMTAchivement);
					arr[9] += Number(MIS_rec[i].DelayedHouses);
				}

				$(".sortnav li").removeClass("active");
				$('.sortnav li:first').addClass('active');
				$(".statnav li").removeClass("active");
				$('.statnav li:first').addClass('active');
				for (var i = 0; i < arr.length; i++) {
					if (!isNaN(arr[i])) {
						water_row.children[i].children["0"].innerText = arr[i].toLocaleString('en-IN');
					}
					else {
						water_row.children[i].children["0"].innerText = 0;
					}
				}
				if ($('.three_oneactive').length == 0)
					subcategory = "Performance";
				else
					subcategory = $('.three_oneactive')[0].dataset.value;

				themefun(subcategory);
				return;

			},
			error: function (err) {
				alert(err.statusText);
			}

		});
	}
	var MIS_rec = null;
	var pointfeatureCollection = {
		"layerDefinition": null,
		"featureSet": {
			"features": [],
			"geometryType": "esriGeometryPoint"
		}
	};
	pointfeatureCollection.layerDefinition = {
		"geometryType": "esriGeometryPoint",
		"objectIdField": "ObjectID",
		"type": "Feature Layer",
		"typeIdField": "",
		//"drawingInfo": {
		//    "renderer": {
		//        "type": "simple",
		//        "symbol": highlightpointSymbol,
		//    }
		//},
		"fields": [{
			"name": "ObjectID",
			"alias": "ObjectID",
			"type": "esriFieldTypeOID"
		}
		],
		"types": [],
		"capabilities": "Query"
	};
	var pointfeatureLayer = new FeatureLayer(pointfeatureCollection,
		{
			id: 'csvLayer'
		});
	var graphlayerhandle = on.pausable(pointfeatureLayer, "mouse-out", assetmouseoutfun);
	pointfeatureLayer.on("mouse-over", dojo.partial(assetmouseoverfun, graphlayerhandle));
	pointfeatureLayer.on("click", dojo.partial(assetmouseclickfun, graphlayerhandle));
	map.addLayers([pointfeatureLayer]);
	var category = 'Performance';
	var chartdata = [];
	function themefun(category) {
		map.infoWindow.hide();
		if (category.toLowerCase() == 'resolution uploaded') {
			subcat = 'ResulutionUploaded';
			subcat1 = 'ResulutionUploadedPercentage';
		}
		else if (category.toLowerCase() == 'verified priority list') {
			subcat = 'vrifiedPrioritylist';
			subcat1 = 'vrifiedPrioritylistPercentage';
		}
		else if (category.toLowerCase() == 'aadhar registered beneficiary') {
			subcat = 'AdharseedingOfRegsteredBen';
			subcat1 = 'AdharseedingOfRegsteredBenPercentage';
		}
		else if (category.toLowerCase() == 'sanction with geotagged') {
			subcat = 'SanctionwithGeotaged';
			subcat1 = 'SanctionwithGeotagedPercentage';
		}
		else if (category.toLowerCase() == 'first installment') {
			subcat = 'FirstIntalment';
			subcat1 = 'FirstIntalmentPercentage';
		}
		else if (category.toLowerCase() == 'second installment') {
			subcat = 'SecondIntalment';
			subcat1 = 'SecondIntalmentPercentage';
		}
		else if (category.toLowerCase() == 'last installment') {
			subcat = 'LastIntalment';
			subcat1 = 'LastIntalmentPercentage';
		}
		else if (category.toLowerCase() == 'house completed') {
			subcat = 'HouseComplited';
			subcat1 = 'HouseComplitedPercentage';
		}
		else if (category.toLowerCase() == 'performance') {
			subcat = 'Performance';
			subcat1 = 'PerformancePercentage';
		}
		else if (category.toLowerCase() == 'mason trained') {
			subcat = 'RMTAchivement';
			subcat1 = 'RMTAchivementPercentage';
		}
		else if (category.toLowerCase() == 'delayed houses') {
			subcat = 'DelayedHouses';
			subcat1 = 'DelayedHousesPercentage';
		}
		var giscode = miscode = layerurl = misname = gisname = null;
		if (statedd.value == 'select') {
			giscode = "STCODE11"; miscode = 'censuscode2011'; misname = "StateName"; gisname = "STNAME";
			layerurl = adminurl + "/0" + token_var;
		}
		else if (distdd.value == 'select') {
			giscode = "dtcode11"; miscode = 'censuscode2011'; misname = "DistrictName"; gisname = "dtname";
			layerurl = adminurl + "/1" + token_var;
		}
		else if (blkdd.value == 'select') {
			giscode = "block_lgd"; miscode = 'censuscode2011'; misname = "BLockName"; gisname = "block_name";
			layerurl = adminurl + "/2" + token_var;
		}
		else if (gpdd.value == 'select') {
			giscode = "gp_code"; miscode = 'censuscode2011'; misname = "PanchyatName"; gisname = "gp_name";
			layerurl = adminurl + "/3" + token_var;
		}
		var query = new Query();
		var content = '<table>';

		if (statedd.value == 'select') {
			query.where = "1=1";
			query.outFields = [giscode, "STNAME"];
			content += "<tr><td><b>State</td></b>" + "<td><b>: </b></td><td>${STNAME}</td></tr>";
		}
		else if (distdd.value == 'select') {
			//query.where = "stcode11='" + statedd.value + "'";
			query.outFields = [giscode, "stname", "dtname"];
			content += "<tr><td><b>State</td></b>" + "<td><b>: </b></td><td>${stname}</td></tr>";
			content += "<tr><td><b>District</td></b>" + "<td><b>: </b></td><td>${dtname}</td></tr>";
			query.where = "stcode11 = '" + statedd.value + "'";
		}
		else if (blkdd.value == 'select') {
			query.where = "dtcode11='" + distdd.value + "'";
			query.outFields = [giscode, "state", "district", "block_name"];
			content += "<tr><td><b>State</td></b>" + "<td><b>: </b></td><td>${state}</td></tr>";
			content += "<tr><td><b>District</td></b>" + "<td><b>: </b></td><td>${district}</td></tr>";
			content += "<tr><td><b>Block</td></b>" + "<td><b>: </b></td><td>${block_name}</td></tr>";
		}
		else if (gpdd.value == 'select') {
			query.where = "blk_lgdcod ='" + blkdd.value + "'";
			query.outFields = [giscode, "gp_name"];
			content += "<tr><td><b>GP</td></b>" + "<td><b>: </b></td><td>${gp_name}</td></tr>";
		}
		content += "<tr><td><b>" + category + "</td></b>" + "<td><b>: </b></td><td>${" + subcategory + "}%</td></tr>";
		//content += "<tr><td><b>Comp Count</td></b>" + "<td><b>: </b></td><td>${"+subcategory+"}</td></tr>";
		content += '</table>';
		var infoTemplate = new InfoTemplate("Information", content);
		featureLayer.setInfoTemplate(infoTemplate);
		query.returnGeometry = true;
		var queryTask = new QueryTask(layerurl);
		queryTask.execute(query, function (fs) {
			featureSet = [];
			if (fs.features.length) {
				for (var i = 0; i < fs.features.length; i++) {
					for (var j = 0; j < MIS_rec.length; j++) {
						if (statedd.value == 'select') {
							if (MIS_rec[j]["censuscode2011"] == '28')
								MIS_rec[j]["censuscode2011"] = '37';
							//else if (MIS_rec[j]["censuscode2011"] == '36') {
							//    MIS_rec[j]["censuscode2011"] = '40';
							//}
							//else if (MIS_rec[j]["censuscode2011"] == '34') {
							//    MIS_rec[j]["censuscode2011"] = '42';

							//}

						}
						if (Number(fs.features[i].attributes[giscode]) == Number(MIS_rec[j]["censuscode2011"])) {
							fs.features[i].attributes[category] = MIS_rec[j][subcat1];
							var graphic = new Graphic(fs.features[i].geometry);
							graphic.setAttributes(fs.features[i].attributes);
							featureSet.push(graphic);
							break;
						}
					}
				}

				var a5 = [];
				for (var i = 0; i < MIS_rec.length; i++) {
					a5.push(parseFloat(MIS_rec[i][subcat1]).toFixed(2));
					chartdata.push({
						name: MIS_rec[i][misname],
						code: MIS_rec[i][miscode],
						value: MIS_rec[i][subcat1],
					});
				}

				chart(subcat, 'a_z', chartdata);
				create_table("top", category, chartdata);
				var res_g_zero = a5.filter(checkGreaterzero);
				res_g_zero = res_g_zero.filter(function (item, i, ar) { return ar.indexOf(item) === i; })
				var serie6 = new geostats(res_g_zero);
				var res = [];
				function checkGreaterzero(a5) {
					return a5 > 0;
				}
				if (category == 'Delayed Houses') {
					//res.push(["0", "0"]);
					res.push(["0.00", "0.05"]);
					res.push(["0.06", "0.10"]);
					res.push(["0.11", "0.15"]);
					res.push(["0.16", "0.19"]);
					res.push(["0.20", "1.00"]);
					var symbol = new SimpleFillSymbol();
					symbol.setColor(new Color([150, 150, 150, 0.5]));
					var renderer = new ClassBreaksRenderer(symbol, category);
					if (res[0])
						renderer.addBreak(res[0][0].trim(), res[0][1].trim(), new SimpleFillSymbol().setColor(new Color([26, 150, 65, 1])));
					//renderer.addBreak(res[0][0].trim(), res[0][1].trim(), new SimpleFillSymbol().setColor(new Color([0, 0, 0, 0.5])));
					if (res[1])
						renderer.addBreak(res[1][0].trim(), res[1][1].trim(), new SimpleFillSymbol().setColor(new Color([166, 217, 106, 1])));
					if (res[2])
						renderer.addBreak(res[2][0].trim(), res[2][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 255, 191, 1])));
					if (res[3])
						renderer.addBreak(res[3][0].trim(), res[3][1].trim(), new SimpleFillSymbol().setColor(new Color([253, 174, 97, 1])));
					if (res[4])
						renderer.addBreak(res[4][0].trim(), res[4][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 78, 56, 1])));
						//if (res[5])
						//    renderer.addBreak(res[5][0].trim(), res[5][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 78, 56, 1])));
					else
						renderer.addBreak(res[1][0].trim(), res[1][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 78, 56, 1])));
				}
				else {

					res.push(["0", "0"]);
					res.push(["0.01", "20.00"]);
					res.push(["20.00", "40.00"]);
					res.push(["40.00", "60.00"]);
					res.push(["60.00", "80.00"]);
					res.push(["80.00", "100.00"]);
					var symbol = new SimpleFillSymbol();
					symbol.setColor(new Color([150, 150, 150, 0.5]));
					var renderer = new ClassBreaksRenderer(symbol, category);
					if (res[0])
						renderer.addBreak(res[0][0].trim(), res[0][1].trim(), new SimpleFillSymbol().setColor(new Color([0, 0, 0, 0.5])));
					if (res[1])
						renderer.addBreak(res[1][0].trim(), res[1][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 78, 56, 1])));
					if (res[2])
						renderer.addBreak(res[2][0].trim(), res[2][1].trim(), new SimpleFillSymbol().setColor(new Color([253, 174, 97, 1])));
					if (res[3])
						renderer.addBreak(res[3][0].trim(), res[3][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 255, 191, 1])));
					if (res[4])
						renderer.addBreak(res[4][0].trim(), res[4][1].trim(), new SimpleFillSymbol().setColor(new Color([166, 217, 106, 1])));
					if (res[5])
						renderer.addBreak(res[5][0].trim(), res[5][1].trim(), new SimpleFillSymbol().setColor(new Color([26, 150, 65, 1])));
					else
						renderer.addBreak(res[1][0].trim(), res[1][1].trim(), new SimpleFillSymbol().setColor(new Color([255, 78, 56, 1])));
				}
				var defaultSymbol = new SimpleFillSymbol().setStyle(SimpleFillSymbol.STYLE_NULL);
				defaultSymbol.outline.setStyle(SimpleLineSymbol.STYLE_NULL);

				featureLayer.applyEdits(featureSet, null, null);
				featureLayer.setRenderer(renderer);
				featureLayer.refresh();
				var legend = new Legend({
					map: map,
					layerInfos: [{
						layer: featureLayer,
						defaultSymbol: false,
						title: category + ' ' + 'in %'
					}]
				}, "legendDiv");
				legend.startup();

				document.getElementById("bodyloadimg").style.display = "none";
			}
			else {
				alert('No Records Found');
				document.getElementById("bodyloadimg").style.display = "none";
				return;
			}
		});
	}
	//########################################### chart ##########################################

	function chart(name, sort_type, matched_data) {
		var ary = [];
		for (var q = 0; q < matched_data.length; q++) {
			ary[q] = {};
			if (Number(matched_data[q].value) >= 0) {
				ary[q]['value'] = (Number(matched_data[q].value));
				ary[q]['text'] = matched_data[q].name;
				ary[q]['code'] = matched_data[q].code;
			}
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
			//minorTicks: false, majorTickStep: 1, font: font_res, dropLabels: false, labelSizeChange: true,
			minorTicks: false, majorTickStep: 1, font: "normal normal bold 6pt Arial", dropLabels: false, labelSizeChange: true,
		});
		chart.addAxis("y", {
			//vertical: true, fixLower: "major", fixUpper: "major", min: 0, minorTicks: false,title: "Percentage(%)"
			vertical: true, fixLower: "major", fixUpper: "major", min: 0, minorTicks: false
		});

		chart.addSeries("x", new StoreSeries(data_store, { query: {} }, "value"), { stroke: "#ff7416", fill: "#ff7416" }),
			new Highlight(chart, "default");
		new Tooltip(chart, "default", {
			text: function (event) {
				return event.chart.series["0"].source.objects[event.x].text + '   ' + event.y.toLocaleString('en-IN') + '%';
			}
		});
		chart.render();
		//chart.resize(document.getElementById("chart_div").offsetWidth, 250);
		if (ary.length > 37)
			chart.resize(900, 250);
		else
			chart.resize(570, 240);
	}
	//################################################ create table ##################################################
	function create_table(selection, name, matched_data) {
		if (statedd.value == 'select') {
			$('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 States';
			$('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 States';
			$('#stdist1')[0].innerHTML = "State";
			$('#stdist2')[0].innerHTML = "State";
		}
		else if (distdd.value == 'select') {
			$('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Districts';
			$('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Districts';
			$('#stdist1')[0].innerHTML = "District";
			$('#stdist2')[0].innerHTML = "District";
		}
		else if (distdd.value != 'select' && blkdd.value == 'select') {
			$('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 Blocks';
			$('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 Blocks';
			$('#stdist1')[0].innerHTML = "Block";
			$('#stdist2')[0].innerHTML = "Block";
		}
		else if (distdd.value != 'select' && blkdd.value != 'select' || gpdd.value == 'select') {
			$('#statone')["0"].childNodes["0"].innerHTML = 'Top 5 GPs';
			$('#stattwo')["0"].childNodes["0"].innerHTML = 'Bottom 5 GPs';
			$('#stdist1')[0].innerHTML = "Gram Panchayat";
			$('#stdist2')[0].innerHTML = "Gram Panchayat";
		}
		$('#stdist3')[0].innerHTML = name + ' ' + 'in %';
		$('#stdist4')[0].innerHTML = name + ' ' + 'in %';

		var stat_ary = null;

		stat_ary = matched_data.slice();

		for (i = 0; i < stat_ary.length; i++) {
			if (stat_ary.value == 0) {
				stat_ary.splice(i, 1);
				i--;
			}
		}
		if (selection == "top") {
			stat_ary.sort(function (a, b) {
				return parseFloat(b.value) - parseFloat(a.value);
			});

			var newary = stat_ary.slice(0, (stat_ary.length < 5 ? stat_ary.length : 5));

		}
		else {
			stat_ary.sort(function (a, b) {
				return parseFloat(a.value) - parseFloat(b.value);
			});
			var newary = stat_ary.slice(0, (stat_ary.length < 5 ? stat_ary.length : 5));
		}


		$('.stat_table').find('tbody').empty();
		for (var k = 0; k < newary.length; k++) {
			var trow = "<tr><td>" + newary[k].name + "</td>";

			trow += "<td>" + Number(newary[k].value).toLocaleString('en-IN') + "</td></tr>";

			$('.stat_table').find('tbody').append(trow);
		}


	}
	//####################################### statistic table option ########################################
	$(".nav-tabs li").click(function () {
		if ($(this)[0].textContent == "Top 5 States") {
			$('#stdist1')[0].innerHTML = "State";
			create_table("top", category, chartdata);
		}
		else if ($(this)[0].textContent == "Top 5 Districts") {
			$('#stdist1')[0].innerHTML = "District";
			create_table("top", category, chartdata);
		}
		else if ($(this)[0].textContent == "Top 5 Blocks") {
			$('#stdist2')[0].innerHTML = "Block";
			create_table("top", category, chartdata);
		}
		else if ($(this)[0].textContent == "Top 5 GPs") {
			$('#stdist2')[0].innerHTML = "Gram Panchayat";
			create_table("top", category, chartdata);
		}
		else if ($(this)[0].textContent == "Top 5 Villages") {
			$('#stdist2')[0].innerHTML = "Village";
			create_table("top", category, chartdata);
		}
		else if ($(this)[0].textContent == "Bottom 5 States") {
			$('#stdist2')[0].innerHTML = "State";
			create_table("bottom", category, chartdata);
		}
		else if ($(this)[0].textContent == "Bottom 5 Districts") {
			$('#stdist2')[0].innerHTML = "District";
			create_table("bottom", category, chartdata);
		}

		else if ($(this)[0].textContent == "Bottom 5 Blocks") {
			$('#stdist2')[0].innerHTML = "Block";
			create_table("bottom", category, chartdata);
		}
		else if ($(this)[0].textContent == "Bottom 5 GPs") {
			$('#stdist2')[0].innerHTML = "Gram Panchayat";
			create_table("bottom", category, chartdata);
		}
		else if ($(this)[0].textContent == "Bottom 5 Villages") {
			$('#stdist2')[0].innerHTML = "Village";
			create_table("bottom", category, chartdata);
		}

	});
	//########################################### click on sort buttons ############################################

	$(".sortnav li").click(function () {
		if ($(this)[0].id == "a_z")
			chart(name, "a_z", chartdata);
		else if ($(this)[0].id == "9_0")
			chart(name, "9_0", chartdata);
		else
			chart(name, "0_9", chartdata);
	});
	map.on("load", function () {
		//after map loads, connect to listen to mouse move & drag events
		map.on("mouse-move", showCoordinates);
		map.on("mouse-drag", showCoordinates);
	});

	function showCoordinates(evt) {
		//the map is in web mercator but display coordinates in geographic (lat, long)
		var mp = webMercatorUtils.webMercatorToGeographic(evt.mapPoint);
		//display mouse coordinates
		dom.byId("info").innerHTML = mp.y.toFixed(5) + ", " + mp.x.toFixed(5);
	}
});