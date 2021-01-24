import '/open_layers/ol.js';

const OWM_API_KEY = '746233f13b8578f62fe9dc1730285e03';
const extent = ol.proj.transformExtent([-180, -85, 180, 85], 'EPSG:4326', 'EPSG:3857');
const layerNames = {
	temp: 'Температура',
	clouds: 'Облачность',
	wind: 'Скорость ветра',
	precipitation: 'Осадки'
};

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

const OWMLayers = [];
for (const key in layerNames) {
	OWMLayers[key] = new ol.layer.Tile({
		preload: Infinity,
		source: new ol.source.XYZ({
			url:
				'https://tile.openweathermap.org/map/' + key +
				'_new/{z}/{x}/{y}.png?appid=' + OWM_API_KEY
		}),
		extent: extent
	});
}

const popup = document.getElementById('map_popup');
const popup_close = document.getElementById('map_popup_close');
const popup_content = document.getElementById('map_popup_content');
const overlay = new ol.Overlay({
	element: popup
});

const map = new ol.Map({
	target: 'map',
	layers: [OSMLayer],
	view: view,
	overlays: [overlay]
});

popup_close.onclick = function () {
	overlay.setPosition(undefined);
	return false;
};
map.on('singleclick', function (event) {
	const coordinate = event.coordinate;
	const lonLat = ol.proj.toLonLat(coordinate);
	const latitude = lonLat[1];
	const longitude = lonLat[0];
	popup_content.innerHTML = '<span class="fa fa-spinner fa-spin fa-fw"></span>';
	overlay.setPosition(coordinate);

	const request = new XMLHttpRequest();
	const url = 'https://api.openweathermap.org/data/2.5/weather?units=metric&lang=ru' +
		'&lat=' + latitude + '&lon=' + longitude + '&appid=' + OWM_API_KEY;
	request.open('GET', url, true);
	request.onreadystatechange = function () {
		if (request.readyState === 4 && request.status === 200) {
			const json = JSON.parse(request.responseText);
			popup_content.innerHTML =
				'Широта: ' + latitude + '<br>Долгота: ' + longitude +
				'<br>Температура: ' + json.main.temp + ' °C' +
				'<br>Облачность: ' + json.clouds.all + '%' +
				'<br>Скорость ветра: ' + json.wind.speed + ' м/с' +
				'<br>Влажность: ' + json.main.humidity + '%';
		} else {
			popup_content.innerHTML = 'Не удалось получить<br>данные с сервера!';
		}
	};
	request.send();
});

let currentLayer;
const selector = document.getElementById('map_layer_selector');
selector.addEventListener('change', function() {
	map.removeLayer(currentLayer);
	const key = selector.value;
	if (OWMLayers.hasOwnProperty(key)) {
		currentLayer = OWMLayers[key];
		map.addLayer(currentLayer);
	}
});

for (const key in layerNames) {
	selector.appendChild(new Option(layerNames[key], key));
}
