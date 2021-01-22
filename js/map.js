import '/open_layers/ol.js';

const extent = ol.proj.transformExtent([-180, -85, 180, 85], 'EPSG:4326', 'EPSG:3857');

const view = new ol.View({
	center: [0, 0],
	zoom: 0,
	extent: extent
});

const OSMLayer = new ol.layer.Tile({
	preload: Infinity,
	source: new ol.source.OSM(),
	extent: extent
});

const layerGroup = new ol.layer.Group({
	layers: [
		OSMLayer,
		new ol.layer.Tile({
			preload: Infinity,
			source: new ol.source.XYZ({
				urls:
					'https://tile.openweathermap.org/map/temp_new/' +
					'{z}/{x}/{y}.png?appid=746233f13b8578f62fe9dc1730285e03'
			}),
			extent: extent
		})
	]
});

const map = new ol.Map({
	target: 'map',
	layers: layerGroup,
	view: view
});
