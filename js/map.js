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

const openWeatherMapLayers = {
	temp: new ol.layer.Tile({
		preload: Infinity,
		source: new ol.source.XYZ({
			url:
				'https://tile.openweathermap.org/map/temp_new/' +
				'{z}/{x}/{y}.png?appid=746233f13b8578f62fe9dc1730285e03'
		}),
		extent: extent
	}),
	clouds: new ol.layer.Tile({
		preload: Infinity,
		source: new ol.source.XYZ({
			url:
				'https://tile.openweathermap.org/map/clouds_new/' +
				'{z}/{x}/{y}.png?appid=746233f13b8578f62fe9dc1730285e03'
		}),
		extent: extent
	}),
	wind: new ol.layer.Tile({
		preload: Infinity,
		source: new ol.source.XYZ({
			url:
				'https://tile.openweathermap.org/map/wind_new/' +
				'{z}/{x}/{y}.png?appid=746233f13b8578f62fe9dc1730285e03'
		}),
		extent: extent
	}),
};

const map = new ol.Map({
	target: 'map',
	layers: [OSMLayer],
	view: view
});

let currentLayer;
const selector = document.getElementById('map_layer_selector');
selector.addEventListener('change', function() {
	map.removeLayer(currentLayer);
	if (selector.value !== '') {
		currentLayer = openWeatherMapLayers[selector.value];
		map.addLayer(currentLayer);
	}
});

selector.appendChild(new Option('Температура', 'temp'));
selector.appendChild(new Option('Облака', 'clouds'));
selector.appendChild(new Option('Скорость ветра', 'wind'));
