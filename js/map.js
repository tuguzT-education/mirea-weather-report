import '/open_layers/ol.js'

const extent = ol.proj.transformExtent([-180, -85, 180, 85], 'EPSG:4326', 'EPSG:3857')

const map = new ol.Map({
	target: 'map',
	layers: [
		new ol.layer.Tile({
			source: new ol.source.OSM(),
			extent: extent
		}),
		new ol.layer.Tile({
			source: new ol.source.XYZ({
				url:
					'https://tile.openweathermap.org/map/temp_new/' +
					'{z}/{x}/{y}.png?appid=746233f13b8578f62fe9dc1730285e03'
			}),
			extent: extent
		})
	],
	view: new ol.View({
		center: [0, 0],
		zoom: 0,
		extent: extent
	})
})
