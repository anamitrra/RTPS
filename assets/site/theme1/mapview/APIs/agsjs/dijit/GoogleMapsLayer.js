﻿/*built on 2012-07-18*/
dojo.provide("agsjs.layers.GoogleMapsLayer");
dojo.declare("agsjs.layers.GoogleMapsLayer",
esri.layers.Layer,
{
    constructor: function (a) {
        a = a || {};
        this.tileInfo = new esri.layers.TileInfo({ rows: 256, cols: 256, dpi: 96, origin: { x: -20037508.342787, y: 20037508.342787 },
            spatialReference: { wkid: 102100 },
            lods: [{ level: 0, resolution: 156543.033928, scale: 591657527.591555 },
{ level: 1, resolution: 78271.5169639999, scale: 295828763.795777 },
{ level: 2, resolution: 39135.7584820001, scale: 147914381.897889 },
{ level: 3, resolution: 19567.8792409999, scale: 73957190.948944 },
{ level: 4, resolution: 9783.93962049996, scale: 36978595.474472 },
{ level: 5, resolution: 4891.96981024998, scale: 18489297.737236 },
{ level: 6, resolution: 2445.98490512499, scale: 9244648.868618 },
{ level: 7, resolution: 1222.99245256249, scale: 4622324.434309 },
{ level: 8, resolution: 611.49622628138, scale: 2311162.217155 },
{ level: 9, resolution: 305.748113140558, scale: 1155581.108577 },
{ level: 10, resolution: 152.874056570411, scale: 577790.554289 },
{ level: 11, resolution: 76.4370282850732, scale: 288895.277144 },
{ level: 12, resolution: 38.2185141425366, scale: 144447.638572 },
{ level: 13, resolution: 19.1092570712683, scale: 72223.819286 },
{ level: 14, resolution: 9.55462853563415, scale: 36111.909643 },
{ level: 15, resolution: 4.77731426794937, scale: 18055.954822 },
{ level: 16, resolution: 2.38865713397468, scale: 9027.977411 },
{ level: 17, resolution: 1.19432856685505, scale: 4513.988705 },
{ level: 18, resolution: 0.597164283559817, scale: 2256.994353 },
{ level: 19, resolution: 0.298582141647617, scale: 1128.497176 },
{ level: 20, resolution: 0.149291070823808, scale: 564.248588}]
        });
        this.fullExtent = new esri.geometry.Extent({ xmin: -20037508.34, ymin: -20037508.34, xmax: 20037508.34, ymax: 20037508.34, spatialReference: { wkid: 102100} });
        this.initialExtent = new esri.geometry.Extent({ xmin: -20037508.34, ymin: -20037508.34, xmax: 20037508.34, ymax: 20037508.34, spatialReference: { wkid: 102100} });
        this.opacity = a.opacity || 1;
        this._mapOptions = a.mapOptions || {};
        this._apiOptions = dojo.mixin({ sensor: false }, a.apiOptions || {});
        this._gmap = null;
        this.loaded = true;
        this.onLoad(this)
    }, getGoogleMapInstance: function () {
        return this._gmap
    },
    _setMap: function (e, a) {
        this._map = e;
        var d = {
            position: "absolute", top: "0px", left: "0px", width: "0px", height: "0px"
        };
        var c = dojo.create("div", {}, a);
        if (this.id) { c.id = this.id } dojo.style(c, d);
        this._element = c;
        var g = dojo.create("div", {}, c);
        dojo.style(g, d);
        dojo.style(g, "width", (e.width || a.offsetWidth) + "px");
        dojo.style(g, "height", (e.height || a.offsetHeight) + "px"); this._gmapDiv = g; var b = dojo.create("div", {}, e.id);
        b.id = "gmaps_top_" + g.id; dojo.style(b, d);
        this._topDiv = b;
        var f = dojo.create("div", {}, e.id);
        f.id = "gmaps_controls_" + g.id;
        dojo.style(f, dojo.mixin(d, { top: "5px", left: "5px" }));
        this._controlDiv = f;
        this._connects = [];
        this._connects.push(dojo.connect(this, "onVisibilityChange", this, this._visibilityChangeHandler));
        this._connects.push(dojo.connect(this, "onOpacityChange", this, this._opacityChangeHandler));
        this.visible = (this.visible === undefined) ? true : this.visible;
        if (this.visible) { this._initGMap() } return c
    }, _unsetMap: function (b, a) {
        dojo.forEach(this._connects, dojo.disconnect, dojo);
        if (this._streetView) { this._streetView.setVisible(false) }
        if (google && google.maps && google.maps.event) {
            if (this._gmapTypeChangeHandle) {
                google.maps.event.removeListener(this._gmapTypeChangeHandle)
            }
            if (this._svVisibleHandle) { google.maps.event.removeListener(this._svVisibleHandle) }
        }
        if (this._element) {
            this._element.parentNode.removeChild(this._element)
        } dojo.destroy(this._element);
        if (this._controlDiv) {
            this._controlDiv.parentNode.removeChild(this._controlDiv)
        } dojo.destroy(this._controlDiv);
        if (this._topDiv) {
            this._topDiv.parentNode.removeChild(this._topDiv)
        } dojo.destroy(this._topDiv)
    }, _initGMap: function () {
        window.google = window.google || {};
        if (window.google && google.maps) {
            var d = this._map.extent;
            var b = this._mapOptions.center || this._esriPointToLatLng(d.getCenter());
            var c = this._map.getLevel();
            var i = dojo.mixin({ center: b, zoom: (c > -1) ? c : 1, panControl: false, mapTypeControl: false, zoomControl: false }, this._mapOptions);
            if (i.mapTypeId) {
                i.mapTypeId = this._getGMapTypeId(i.mapTypeId)
            }
            else { i.mapTypeId = google.maps.MapTypeId.ROADMAP }
            var e = new google.maps.Map(this._gmapDiv, i);
            if (c < 0) {
                dojo.connect(this._map, "onLoad", dojo.hitch(this, function () { this._setExtent(d) }))
            }
            this._gmap = e;
            this._setExtent(d);
            this._extentChangeHandle = dojo.connect(this._map, "onExtentChange", this, this._extentChangeHandler);
            this._panHandle = dojo.connect(this._map, "onPan", this, this._panHandler);
            this._resizeHandle = dojo.connect(this._map, "onResize", this, this._resizeHandler);
            this._mvHandle = dojo.connect(this._map, "onMouseMove", dojo.hitch(this, this._moveControls));
            this._gmapTypeChangeHandle = google.maps.event.addListener(this._gmap, "maptypeid_changed", dojo.hitch(this, this._mapTypeChangeHandler));
            this._gmapTiltChangeHandle = google.maps.event.addListener(this._gmap, "tilt_changed", dojo.hitch(this, this._mapTiltChangeHandler))
        }
        else {
            if (agsjs.onGMapsApiLoad) { dojo.connect(agsjs, "onGMapsApiLoad", this, this._initGMap) }
            else {
                agsjs.onGMapsApiLoad = function () { }; dojo.connect(agsjs, "onGMapsApiLoad", this, this._initGMap);
                var f = document.createElement("script"); f.type = "text/javascript";
                var h = window.location.protocol;
                if (h.toLowerCase().indexOf("http") == -1) { h = "http:" }
                var a = h + "//maps.googleapis.com/maps/api/js?callback=agsjs.onGMapsApiLoad";
                for (var g in this._apiOptions) {
                    if (this._apiOptions.hasOwnProperty(g)) {
                        a += "&" + g + "=" + this._apiOptions[g]
                    }
                } f.src = a; if (document.getElementsByTagName("head").length > 0) {
                    document.getElementsByTagName("head")[0].appendChild(f)
                }
                else { document.body.appendChild(f) }
            }
        }
    },
    setOpacity: function (b) {
        if (this._gmapDiv) {
            b = Math.min(Math.max(b, 0), 1);
            var a = this._gmapDiv.style;
            if (typeof a.opacity !== "undefined") {
                a.opacity = b
            } else {
                if (typeof a.filters !== "undefined") { a.filters.alpha.opacity = Math.floor(100 * b) }
                else { if (typeof a.filter !== "undefined") { a.filter = "alpha(opacity:" + Math.floor(b * 100) + ")" } }
            }
        }
        this.opacity = b
    },
    setMapTypeId: function (a) {
        if (this._gmap) {
            this._gmap.setMapTypeId(this._getGMapTypeId(a));
            this._mapTypeChangeHandler()
        } else {
            this._mapOptions.mapTypeId = a
        } return
    },
    setMapStyles: function (a) {
        if (this._styleOptions) { a = a || [] }
        if (a) {
            if (this._gmap) {
                this._styleOptions = a;
                this._gmap.setOptions({ styles: a })
            }
            else { this._mapOptions.styles = a }
        } return
    },
    onMapTypeChange: function (a) { },
    _getGMapTypeId: function (a) {
        if (google && google.maps) {
            switch (a) {
                case agsjs.layers.GoogleMapsLayer.MAP_TYPE_ROADMAP: return google.maps.MapTypeId.ROADMAP;
                case agsjs.layers.GoogleMapsLayer.MAP_TYPE_HYBRID: return google.maps.MapTypeId.HYBRID;
                case agsjs.layers.GoogleMapsLayer.MAP_TYPE_SATELLITE: return google.maps.MapTypeId.SATELLITE;
                case agsjs.layers.GoogleMapsLayer.MAP_TYPE_TERRAIN: return google.maps.MapTypeId.TERRAIN
            }
        }
        return a
    },
    _opacityChangeHandler: function (a) { this.setOpacity(a) },
    _visibilityChangeHandler: function (a) {
        if (a) {
            esri.show(this._gmapDiv);
            esri.show(this._controlDiv);
            this.visible = true; if (this._gmap) {
                google.maps.event.trigger(this._gmap, "resize");
                this._panHandle = this._panHandle || dojo.connect(this._map, "onPan", this, this._panHandler);
                this._extentChangeHandle = this._extentChangeHandle || dojo.connect(this._map, "onExtentChange", this, this._extentChangeHandler);
                this._setExtent(this._map.extent)
            } else { this._initGMap() }
        } else {
            if (this._gmapDiv) {
                esri.hide(this._gmapDiv);
                esri.hide(this._controlDiv);
                this.visible = false; if (this._gmap) {
                    this._map.setExtent(this._latLngBoundsToEsriExtent(this._gmap.getBounds()))
                }
                if (this._streetView) { this._streetView.setVisible(false) } if (this._panHandle) {
                    dojo.disconnect(this._panHandle);
                    this._panHandle = null
                }
                if (this._extentChangeHandle) {
                    dojo.disconnect(this._extentChangeHandle);
                    this._extentChangeHandle = null
                }
            }
        }
    },
    _resizeHandler: function (c, a, b) {
        dojo.style(this._gmapDiv, {
            width: this._map.width + "px", height: this._map.height + "px"
        });
        google.maps.event.trigger(this._gmap, "resize")
    },
    _extentChangeHandler: function (a, d, c, b) {
        if (c) { this._setExtent(a) } else { this._gmap.setCenter(this._esriPointToLatLng(a.getCenter())) }
    },
    _panHandler: function (a, b) { if (this._gmap.getTilt() == 0) { this._gmap.setCenter(this._esriPointToLatLng(a.getCenter())) } },
    _mapTypeChangeHandler: function () {
        this._checkZoomLevel();
        this.onMapTypeChange(this._gmap.getMapTypeId())
    },
    _checkZoomLevel: function () {
        var g = this._gmap.getMapTypeId();
        var c = this._gmap.mapTypes;
        var f = null;
        for (var a in c) { if (c.hasOwnProperty(a) && a == g) { f = c[a]; break } }

        if (f != null) {
            var b = f.minZoom; var e = f.maxZoom; var d = this._map.getLevel();
            if (e !== undefined && d > e) { this._map.setLevel(e) } if (b != undefined && d < b) { this._map.setLevel(b) }
        }
    },
    _setExtent: function (b) {
        var a = this._esriPointToLatLng(b.getCenter());
        var c = this._map.getLevel(); this._gmap.fitBounds(this._esriExtentToLatLngBounds(b.expand(0.5)));
        this._gmap.setCenter(a); this._checkZoomLevel()
    },
    _moveControls: function () {
        if (this._mvHandle) {
            if (!this._gmap) {
                dojo.disconnect(this._mvHandle);
                this._mvHandle = null
            } else {
                if (!this._svMoved) {
                    this._streetView = this._gmap.getStreetView();
                    if (this._streetView) {
                        var a = dojo.query('.gmnoprint img[src*="cb_scout_sprite"]',
this._gmapDiv);
                        if (a.length > 0) {
                            dojo.forEach(a, function (d, c) { dojo.place(d.parentNode.parentNode, this._controlDiv) },
this);
                            this._svMoved = true;
                            this._svVisibleHandle = google.maps.event.addListener(this._streetView, "visible_changed", dojo.hitch(this, this._streetViewVisibilityChangeHandler))
                        }
                    }
                    else { this._svMoved = true }
                }
                if (!this._rotateMoved) {
                    var b = dojo.query('.gmnoprint img[src*="rotate"]', this._gmapDiv);
                    if (b.length > 0) {
                        dojo.forEach(b, function (d, c) {
                            dojo.place(d.parentNode.parentNode, this._controlDiv);
                            dojo.style(d, "position", "absolute"); dojo.style(d, "left", "20px")
                        },
this);
                        this._rotateMoved = true
                    }
                }
                if (this._rotateMoved && this._svMoved) {
                    dojo.disconnect(this._mvHandle); this._mvHandle = null
                }
            }
        }
    },
    _streetViewVisibilityChangeHandler: function () {
        if (this._streetView) {
            var a = this._streetView.getVisible();
            this._toggleEsriControl(a); this.onStreetViewVisibilityChange(a)
        }
    },
    _mapTiltChangeHandler: function () {
        var a = this._gmap.getTilt(); if (a == 45) {
            dojo.place(this._gmapDiv, this._topDiv); this._map.disableMapNavigation()
        }
        else { if (a == 0) { dojo.place(this._gmapDiv, this._element); this._map.enableMapNavigation() } }
    },
    _toggleEsriControl: function (a) {
        if (a) {
            this._isZoomSliderDefault = this._map.isZoomSlider;
            this._map.hideZoomSlider(); this._map.disableMapNavigation()
        } else {
            if (this._isZoomSliderDefault) { this._map.showZoomSlider() }
            this._map.enableMapNavigation()
        }
    }, onStreetViewVisibilityChange: function (a) { },
    _esriPointToLatLng: function (b) { var a = esri.geometry.webMercatorToGeographic(b); return new google.maps.LatLng(a.y, a.x) },
    _esriExtentToLatLngBounds: function (b) {
        var a = esri.geometry.webMercatorToGeographic(b);
        return new google.maps.LatLngBounds(new google.maps.LatLng(a.ymin, a.xmin, true), new google.maps.LatLng(a.ymax, a.xmax, true))
    },
    _latLngBoundsToEsriExtent: function (b) {
        var a = new esri.geometry.Extent(b.getSouthWest().lng(), b.getSouthWest().lat(), b.getNorthEast().lng(), b.getNorthEast().lat());
        return esri.geometry.geographicToWebMercator(a)
    }
});
dojo.mixin(agsjs.layers.GoogleMapsLayer, { MAP_TYPE_SATELLITE: "satellite",
    MAP_TYPE_HYBRID: "hybrid", MAP_TYPE_ROADMAP: "roadmap",
    MAP_TYPE_TERRAIN: "terrain",
    MAP_STYLE_GRAY: [{ featureType: "all",
        stylers: [{ saturation: -80 }, { lightness: 20}]
    }],
    MAP_STYLE_LIGHT_GRAY: [{ featureType: "all",
        stylers: [{ saturation: -80 }, { lightness: 60}]
    }],
    MAP_STYLE_NIGHT: [{ featureType: "all", stylers: [{ invert_lightness: "true"}]}]
});
require(["esri/dijit/BasemapGallery"], function (a) {
    esri.dijit.BasemapGallery.prototype._original_postMixInProperties = esri.dijit.BasemapGallery.prototype.postMixInProperties;
    esri.dijit.BasemapGallery.prototype._original_startup = esri.dijit.BasemapGallery.prototype.startup;
    dojo.extend(esri.dijit.BasemapGallery, { google: null, _googleLayers: [], toggleReference: false, postMixInProperties: function () {
        if (!this._OnSelectionChangeListenerExt) {
            this._onSelectionChangeListenerExt = dojo.connect(this, "onSelectionChange", this, this._onSelectionChangeExt)
        }
        if (this.google != undefined) {// && (this.showArcGISBasemaps || this.basemapsGroup)) {
            this.basemaps.push(new esri.dijit.Basemap({ id: "google_road", layers: [new esri.dijit.BasemapLayer({ type: "GoogleMapsRoad" })], title: "Google Road",
                thumbnailUrl: dojo.moduleUrl("agsjs.dijit", "images/googleroad.png")
            }));
            this.basemaps.push(new esri.dijit.Basemap({ id: "google_satellite", layers: [new esri.dijit.BasemapLayer({ type: "GoogleMapsSatellite" })], title: "Google Satellite",
                thumbnailUrl: dojo.moduleUrl("agsjs.dijit", "images/googlesatellite.png")
            }));
            this.basemaps.push(new esri.dijit.Basemap({ id: "google_hybrid", layers: [new esri.dijit.BasemapLayer({ type: "GoogleMapsHybrid" })], title: "Google Hybrid",
                thumbnailUrl: dojo.moduleUrl("agsjs.dijit", "images/googlehybrid.png")
            }))
        }
        if (this.loaded) {
            this._onLoadExt()
        }
        else { this._onLoadListenerExt = dojo.connect(this, "onLoad", this, this._onLoadExt) }
        if (this._original_postMixInProperties) { this._original_postMixInProperties() }
    },
        startup: function () {
            this._startupCalled = true; this._original_startup();
            if (!this._onGalleryClickListenerExt) {
                this._onGalleryClickListenerExt = dojo.connect(this.domNode, "click", this, this._onGalleryClickExt)
            }
        },
        _onLoadExt: function () {
            if (this._onLoadListenerExt) {
                dojo.disconnect(this._onLoadListenerExt)
            } if (this.toggleReference) {
                dojo.forEach(this.basemaps, function (b) {
                    var c = b.getLayers();
                    if (c.length) { this._processReferenceLayersExt(b) }
                    else { c.then(dojo.hitch(this, this._processReferenceLayersExt, b)) }
                }, this)
            }
        },
        _processReferenceLayersExt: function (c) {
            var f = c.getLayers();
            var d = false, e = true;
            dojo.forEach(f, function (g) { if (g.isReference) { d = true; if (g.visibility === false) { e = false } } });
            if (d && this.toggleReference) {
                c.title += '<input type="checkbox"  disabled ' + (e ? "checked" : "") + "/>";
                c._hasReference = true
            } else { c._hasReference = false } var b = 0; dojo.forEach(this.basemaps, function (g) {
                if (g._hasReference == undefined) {
                    b -= this.basemaps.length
                } if (g._hasReference) { b++ }
            }, this); if (b >= 0) { if (b > 0 && this._startupCalled) { this.startup() } }
        },
        _onSelectionChangeExt: function () {
            var c = this.getSelected();
            var b;
            if (c) {
                if (this._googleLayers.length > 0) {
                    dojo.forEach(this._googleLayers, function (f) { this.map.removeLayer(f) }, this);
                    this._googleLayers.length = 0
                } dojo.query("input", this.domNode).forEach(function (f) { f.disabled = true });
                var e = c.getLayers();
                dojo.forEach(e, function (f) {
                    if (f.type && f.type.indexOf("GoogleMaps") > -1) {
                        var g = agsjs.layers.GoogleMapsLayer.MAP_TYPE_ROADMAP;
                        if (f.type == "GoogleMapsSatellite") { g = agsjs.layers.GoogleMapsLayer.MAP_TYPE_SATELLITE }
                        else { if (f.type == "GoogleMapsHybrid") { g = agsjs.layers.GoogleMapsLayer.MAP_TYPE_HYBRID } }
                        this.google = this.google || {};
                        this.google.mapOptions = this.google.mapOptions || {};
                        this.google.mapOptions.mapTypeId = g;
                        b = new agsjs.layers.GoogleMapsLayer(this.google);
                        this.map.addLayer(b, 0);
                        this._googleLayers.push(b)
                    }
                }, this)
            }
            var d = this._checkSelectedNode();
            if (!d && this._startupCalled) {
                this.startup();
                this._checkSelectedNode()
            }
        },
        _checkSelectedNode: function () {
            var c = false; var b = this.getSelected().getLayers();
            dojo.query(".esriBasemapGallerySelectedNode", this.domNode).forEach(function (d) {
                c = true; dojo.query("input", d).forEach(function (e) {
                    e.disabled = false;
                    dojo.forEach(b, function (f) { if (f.isReference) { f.visibility = e.checked } },
this)
                },
              this)

            }, this); return c
        },
        _onGalleryClickExt: function (b) {
            var c = b.target;
            if (c.tagName.toLowerCase() == "input") {
                this._setReferenceVis(c.checked)
            }
            else {
                if (dojo.hasClass(c.parentNode, "esriBasemapGalleryLabelContainer")) {
                    c.parentNode.parentNode.firstChild.click()
                }
            }
        },
        _setReferenceVis: function (b) {
            dojo.forEach(this.map.layerIds, function (d) {
                var c = this.map.getLayer(d); if (c._basemapGalleryLayerType == "reference") {
                    if (b) { c.show() }
                    else { c.hide() }
                }
            }, this)
        }, getGoogleLayers: function () { return this._googleLayers }
    })
});
