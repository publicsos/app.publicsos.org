@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")

<div class="container-fluid">
    <div id="cesiumContainer"></div>
    <div class="toolbar">
        <button onclick="flyToMarkers()">Fly to Markers</button>
    </div>
    <link href="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

    <!-- Cesium JS -->
    <script src="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Cesium.js"></script>

    <script>
        // Your access token from https://cesium.com/ion/tokens
        Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJmMWYwZjFiMS01OGQ3LTRiNDctOTk1Mi03NDgwOWVhZTBjYjciLCJpZCI6MTM0NTU5LCJpYXQiOjE2ODE5NDEwMzN9.0kUdp5KVqgFEL8kZmqMIvcUyKGzzNDiIrliUNHJ2w5s';

        // Initialize the Cesium Viewer
        const viewer = new Cesium.Viewer('cesiumContainer', {
            terrainProvider: new Cesium.EllipsoidTerrainProvider(),
            infoBox: true,
            selectionIndicator: false,
            shadows: true,
            shouldAnimate: true,
            baseLayerPicker: false // Disable base layer picker to avoid token errors
        });

        // Remove default base layer (to avoid token errors if no valid token)
        viewer.scene.globe.enableLighting = false;

        // Your marker data
        const locationData = [
            {
                id: "21590547",
                title: "ANL",
                longitude: 27.6895,
                latitude: 46.2298,
                face: "East",
                iconUrl: "{{ asset("logo2.svg") }}",
                distance: 0.599854
            },
            {
                id: "21590684",
                title: "D4",
                longitude: 27.6736,
                latitude: 46.2201,
                face: "East",
                iconUrl: "{{ asset("logo2.svg") }}",
                distance: 0.705530
            },
            {
                id: "21590724",
                title: "D3",
                longitude: 27.6733,
                latitude: 46.2206,
                face: "East",
                iconUrl: "{{ asset("logo2.svg") }}",
                distance: 0.717102
            },
            {
                id: "21590560",
                title: "E7",
                longitude: 27.6743,
                latitude: 46.2253,
                face: "East",
                iconUrl: "{{ asset("logo2.svg") }}",
                distance: 0.726878
            },
            {
                id: "21590686",
                title: "D2",
                longitude: 27.6732,
                latitude: 46.2198,
                face: "East",
                iconUrl: "{{ asset("logo2.svg") }}",
                distance: 0.726942
            }
        ];

        // Function to add markers
        function addMarkersToMap(viewer, locations) {
            locations.forEach(location => {
                viewer.entities.add({
                    position: Cesium.Cartesian3.fromDegrees(location.longitude, location.latitude),
                    billboard: {
                        image: location.iconUrl,
                        scale: 1.0,
                        pixelOffset: new Cesium.Cartesian2(0, 0),
                        verticalOrigin: Cesium.VerticalOrigin.CENTER,
                        horizontalOrigin: Cesium.HorizontalOrigin.CENTER
                    },
                    label: {
                        text: `${location.title} (${location.face})`,
                        font: '12pt monospace',
                        style: Cesium.LabelStyle.FILL_AND_OUTLINE,
                        outlineWidth: 2,
                        verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
                        pixelOffset: new Cesium.Cartesian2(0, -15),
                        fillColor: Cesium.Color.WHITE,
                        outlineColor: Cesium.Color.BLACK,
                        showBackground: true,
                        backgroundColor: new Cesium.Color(0.165, 0.165, 0.165, 0.7)
                    }
                });
            });
        }

        // Function to fly to markers
        function flyToMarkers() {
            // Calculate the center point of all markers
            const centerLon = locationData.reduce((sum, loc) => sum + loc.longitude, 0) / locationData.length;
            const centerLat = locationData.reduce((sum, loc) => sum + loc.latitude, 0) / locationData.length;

            // Fly to the center point
            viewer.camera.flyTo({
                destination: Cesium.Cartesian3.fromDegrees(centerLon, centerLat, 2000.0),
                orientation: {
                    heading: Cesium.Math.toRadians(0.0),
                    pitch: Cesium.Math.toRadians(-45.0),
                    roll: 0.0
                },
                duration: 3
            });
        }

        // Add markers to the map
        addMarkersToMap(viewer, locationData);

        // Initial view setup
        flyToMarkers();
    </script>
</div>
@endsection
