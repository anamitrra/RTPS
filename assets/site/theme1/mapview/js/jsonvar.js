function get_lyrvisibility(id) {
	for (var i = 0; i < mapjson.mapService[0].infoTemplates.length; i++) {
		if (mapjson.mapService[0].infoTemplates[i].id == id)
			return mapjson.mapService[0].infoTemplates[i].visibility;
	}
	return false;
}

function get_lyraliasname(id) {
	for (var i = 0; i < mapjson.mapService[0].infoTemplates.length; i++) {
		if (mapjson.mapService[0].infoTemplates[i].id == id)
			return mapjson.mapService[0].infoTemplates[i].title;
	}
	return false;
}

function get_fieldaliasnames(id) {
	for (var i = 0; i < mapjson.mapService[0].infoTemplates.length; i++) {
		if (mapjson.mapService[0].infoTemplates[i].id == id) {
			return JSON.parse(JSON.stringify(mapjson.mapService[0].infoTemplates[i].fields));
		}
	}
	return false;
}


function get_misfieldaliasnames(category) {
	for (var i = 0; i < mapjson.categories.length; i++) {
		if (mapjson.categories[i].category == category) {
			return JSON.parse(JSON.stringify(mapjson.categories[i].fields));// mapjson.mistable[i].fields.slice(0);
		}
	}
	return false;
}
function get_misfieldaliasnames_year(year) {
	for (var i = 0; i < mapjson.timeperiod.length; i++) {
		if (mapjson.timeperiod[i].year == year) {
			return JSON.parse(JSON.stringify(mapjson.timeperiod[i].fields));// mapjson.mistable[i].fields.slice(0);
		}
	}
	return false;
}

function get_rawmaterials(parentcategory, category) {
	for (var i = 0; i < mapjson.categories.length; i++) {
		if (mapjson.categories[i].name == 'raw_materials') {
			for (var j = 0; j < mapjson.categories[i].subcategories.length; j++) {
				if (mapjson.categories[i].subcategories[j].name == parentcategory) {
					for (var k = 0; k < mapjson.categories[i].subcategories[j].subcategories.length; k++) {
						if (mapjson.categories[i].subcategories[j].subcategories[k].name == category) {
							return JSON.parse(JSON.stringify(mapjson.categories[i].subcategories[j].subcategories[k].subcategories));
						}

					}
				}
				//else if (mapjson.categories[i].subcategories[j].name == parentcategory && category == "non_veg_cat") {
				//    return JSON.parse(JSON.stringify(mapjson.categories[i].subcategories[j].subcategories));
				//}
			}
		}
		else if (mapjson.categories[i].name == 'food_processing_units') {
			for (var j = 0; j < mapjson.categories[i].subcategories.length; j++) {
				if (mapjson.categories[i].subcategories[j].name == parentcategory) {
					for (var k = 0; k < mapjson.categories[i].subcategories[j].subcategories.length; k++) {
						if (mapjson.categories[i].subcategories[j].subcategories[k].name == category) {
							return JSON.parse(JSON.stringify(mapjson.categories[i].subcategories[j].subcategories[k].subcategories));
						}

					}
				}
				//else if (mapjson.categories[i].subcategories[j].name == parentcategory && category == "non_veg_cat") {
				//    return JSON.parse(JSON.stringify(mapjson.categories[i].subcategories[j].subcategories));
				//}
			}
		}


	}
	return false;
}