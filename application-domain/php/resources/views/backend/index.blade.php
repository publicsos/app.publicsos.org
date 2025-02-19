@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
<script src="https://cdnjs.cloudflare.com/ajax/libs/cesium/1.111.0/Cesium.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cesium/1.111.0/Widgets/widgets.min.css" rel="stylesheet">

<div class="container">
    <div id="cesiumContainer" style="width: 100%; height: 600px; position: relative;"></div>

    <div class="toolbar" style="padding: 10px; background-color: rgba(255, 255, 255, 0.9); position: absolute; top: 10px; left: 10px; z-index: 999; border-radius: 4px;">
        <h3>Bucharest, Romania</h3>
        <button onclick="flyToPalace()">Palace of Parliament</button>
        <button onclick="flyToAthenaeum()">Romanian Athenaeum</button>
        <button onclick="flyToHerastrau()">Herastrau Park</button>
    </div>

    <script>
        // Set your Cesium Ion access token here
        Cesium.Ion.defaultAccessToken = 'YOUR_CESIUM_ION_ACCESS_TOKEN';

        async function initCesium() {
            try {
                // Initialize the Cesium Viewer with terrain and imagery
                const terrainProvider = await Cesium.createWorldTerrainAsync();

                const viewer = new Cesium.Viewer('cesiumContainer', {
                    terrainProvider: terrainProvider,
                    baseLayerPicker: false,
                    geocoder: false,
                    homeButton: false,
                    sceneModePicker: true,
                    navigationHelpButton: false,
                    animation: false,
                    timeline: false,
                    fullscreenButton: false
                });

                // Set up Bing Maps aerial imagery using Cesium Ion
                viewer.imageryLayers.removeAll();
                viewer.imageryLayers.addImageryProvider(
                    new Cesium.IonImageryProvider({ assetId: 2 })
                );

                // Define landmark locations
                const locations = {
                    PALACE_OF_PARLIAMENT: { longitude: 26.0875, latitude: 44.4275, height: 500 },
                    ROMANIAN_ATHENAEUM: { longitude: 26.0971, latitude: 44.4412, height: 500 },
                    HERASTRAU_PARK: { longitude: 26.0789, latitude: 44.4697, height: 500 }
                };

                // Initial view of Bucharest
                viewer.camera.flyTo({
                    destination: Cesium.Cartesian3.fromDegrees(26.1025, 44.4268, 5000),
                    orientation: {
                        heading: Cesium.Math.toRadians(0.0),
                        pitch: Cesium.Math.toRadians(-45.0),
                        roll: 0.0
                    }
                });

                // Function to add markers for landmarks
                function addMarker(position, name) {
                    viewer.entities.add({
                        position: Cesium.Cartesian3.fromDegrees(position.longitude, position.latitude),
                        billboard: {
                            image: 'https://via.placeholder.com/32',
                            verticalOrigin: Cesium.VerticalOrigin.BOTTOM
                        },
                        label: {
                            text: name,
                            font: '14px sans-serif',
                            horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
                            verticalOrigin: Cesium.VerticalOrigin.TOP,
                            pixelOffset: new Cesium.Cartesian2(0, -10)
                        }
                    });
                }

                // Add markers for each landmark
                addMarker(locations.PALACE_OF_PARLIAMENT, 'Palace of Parliament');
                addMarker(locations.ROMANIAN_ATHENAEUM, 'Romanian Athenaeum');
                addMarker(locations.HERASTRAU_PARK, 'Herastrau Park');

                // Functions for the buttons to fly to specific locations
                function flyToLocation(location) {
                    viewer.camera.flyTo({
                        destination: Cesium.Cartesian3.fromDegrees(location.longitude, location.latitude, location.height),
                        orientation: {
                            heading: Cesium.Math.toRadians(0.0),
                            pitch: Cesium.Math.toRadians(-45.0),
                            roll: 0.0
                        }
                    });
                }

                // Button functions for user interaction
                window.flyToPalace = () => flyToLocation(locations.PALACE_OF_PARLIAMENT);
                window.flyToAthenaeum = () => flyToLocation(locations.ROMANIAN_ATHENAEUM);
                window.flyToHerastrau = () => flyToLocation(locations.HERASTRAU_PARK);

            } catch (error) {
                console.error('Error initializing Cesium:', error);
            }
        }

        // Initialize the Cesium viewer on page load
        initCesium();
    </script>
</div>
@endsection
