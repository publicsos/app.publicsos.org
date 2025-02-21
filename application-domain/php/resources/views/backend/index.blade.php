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
        Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJmMWYwZjFiMS01OGQ3LTRiNDctOTk1Mi03NDgwOWVhZTBjYjciLCJpZCI6MTM0NTU5LCJpYXQiOjE2ODE5NDEwMzN9.0kUdp5KVqgFEL8kZmqMIvcUyKGzzNDiIrliUNHJ2w5s';

        const viewer = new Cesium.Viewer('cesiumContainer', {
            terrainProvider: new Cesium.EllipsoidTerrainProvider(),
            infoBox: true,
            selectionIndicator: false,
            shadows: true,
            shouldAnimate: true
        });

        // Enable lighting effects
        viewer.scene.globe.enableLighting = true;

        const locationData = [
            {
                id: "21590547",
                title: "ANL",
                longitude: 27.6895,
                latitude: 46.2298,
                face: "East",
                height: 30, // Approximate height in meters
                distance: 0.599854
            },
            {
                id: "21590684",
                title: "D4",
                longitude: 27.6736,
                latitude: 46.2201,
                face: "East",
                height: 25,
                distance: 0.705530
            },
            {
                id: "21590724",
                title: "D3",
                longitude: 27.6733,
                latitude: 46.2206,
                face: "East",
                height: 25,
                distance: 0.717102
            },
            {
                id: "21590560",
                title: "E7",
                longitude: 27.6743,
                latitude: 46.2253,
                face: "East",
                height: 28,
                distance: 0.726878
            },
            {
                id: "21590686",
                title: "D2",
                longitude: 27.6732,
                latitude: 46.2198,
                face: "East",
                height: 25,
                distance: 0.726942
            }
        ];

        function addBuildingsToMap(viewer, locations) {
            locations.forEach(location => {
                // Add the building as a box
                viewer.entities.add({
                    name: location.title,
                    position: Cesium.Cartesian3.fromDegrees(location.longitude, location.latitude),
                    box: {
                        dimensions: new Cesium.Cartesian3(20.0, 20.0, location.height), // width, depth, height
                        material: Cesium.Color.LIGHTSTEELBLUE.withAlpha(0.7),
                        outline: true,
                        outlineColor: Cesium.Color.DARKBLUE
                    },
                    label: {
                        text: location.title,
                        font: '14pt monospace',
                        style: Cesium.LabelStyle.FILL_AND_OUTLINE,
                        outlineWidth: 2,
                        verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
                        pixelOffset: new Cesium.Cartesian2(0, -30),
                        fillColor: Cesium.Color.WHITE,
                        outlineColor: Cesium.Color.BLACK,
                        showBackground: true,
                        backgroundColor: new Cesium.Color(0.165, 0.165, 0.165, 0.7),
                        heightReference: Cesium.HeightReference.RELATIVE_TO_GROUND
                    }
                });
            });
        }

        function flyToMarkers() {
            const centerLon = locationData.reduce((sum, loc) => sum + loc.longitude, 0) / locationData.length;
            const centerLat = locationData.reduce((sum, loc) => sum + loc.latitude, 0) / locationData.length;

            viewer.camera.flyTo({
                destination: Cesium.Cartesian3.fromDegrees(centerLon, centerLat, 500.0),
                orientation: {
                    heading: Cesium.Math.toRadians(45.0),
                    pitch: Cesium.Math.toRadians(-35.0),
                    roll: 0.0
                },
                duration: 3
            });
        }

        // Add 3D buildings to the map
        addBuildingsToMap(viewer, locationData);

        // Initial view setup
        flyToMarkers();

        // Enable depth testing for proper 3D rendering
        viewer.scene.globe.depthTestAgainstTerrain = true;
    </script>

</div>
@endsection
