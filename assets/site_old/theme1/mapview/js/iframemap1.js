var token = "?Token=" + mapjson.mapService[0].token;
var layerurl = mapjson.mapService[0].url;
require(["esri/map",
         "esri/layers/ArcGISDynamicMapServiceLayer", "dojo/on",         
         "dojo/domReady!"
], function (
         Map, 
         ArcGISDynamicMapServiceLayer,on) {
    //parser.parse();
    var map = new Map("map", {basemap:"streets",
        center: [80, 23.5]
    });
    var dlayer = new ArcGISDynamicMapServiceLayer(layerurl+token);
	map.addLayer(dlayer);
    dlayer.on("error", function (evt) {
        if (!evt.error._ssl) alert("Please use Google Chrome browser");
    });    
});