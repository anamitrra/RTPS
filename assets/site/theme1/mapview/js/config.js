//var nicStreetMapUrl = "http://164.100.158.204/wbhdcl176/rest/services/Street/StreetMap/MapServer?token=Px2vHZCZOutT-d3ZEL0W9QzEYWtKEJewgn1sLw4wesvDfmoyYy6n9g5sHQpXVGps";
var nicStreetMapUrl = "https://mapservice.gov.in/mapserviceserv176/rest/services/Street/StreetMap/MapServer?token=3xBvK8zDzs-WdLSyjgAwLfkkNaJR-WdXZBVb9eYoWUvx9JOrNiIhR4RymOKY9bFby7ciIIRebKTmfNuzjFMfvw..";
var nicterrainurl = "https://webgis.nic.in/publishing/rest/services/bharatmaps/terrain/MapServer?token=yP5p3eRIw4y3ZGEwVMllGgFkSKapcqYlnO3eaCyBxMlebFBMZd-6I-lMfcggqU5zxvs9OmeMk3f_h6qK_gzdqg..";
var nicsatelliteurl = "https://rsgis.nic.in/ArcGIS/rest/services/nic_satellite/MapServer?token=nDiLdcrq3lLUP1t99__a4apvTA5Z_DG61vX4QNzike2mR9JuFMw9-KLAM6RvkFwvVo9REvp89PKPyTBNfu_ryg..";
var mapjson =
    {
        mapService:
            [{
                name: "adminmapService",
                
                url:"https://mapservice.gov.in/gismapservice/rest/services/BharatMapService/Admin_Boundary_GramPanchayat/MapServer",
                token: "qC7y3wpLmNoqtBlFJY3PHXnMHDeEhvCsI1SamwhCBPnA2Eb1Dz4Oq5_X-D2DpCWgRQEz83eWbWWx8R6_yzWT_w..",
                
                //main server
                // url: "https://mapservice.gov.in/gismapservice/rest/services/BharatMapService/Admin_Boundary_GramPanchayat/MapServer",
                // token: "QmqYyJrJSt_BpgMbzmLs7JlQBy5KaNPXFnSYPtkYV2T_DxME-k-DsHYJ8r_LgsJ7kWVSrPu6LqF6CiOyp11kjA..", 

                infoTemplates:
                    [{
                        id: "10",
                        visibility: true,
                        title: "Major Sports Complex",
                        fields:
                            [{
                                name: "name_infra",
                                alias: "Name"
                            }]
                    }]
            }]
    }