@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
<?php

$buildings = \Modules\Domain\Models\Buildings\Building::all();

?>
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

        viewer.scene.globe.enableLighting = false;

        // Convert PHP buildings data to JavaScript
        const locationData = @json($buildings);

        async function initializeMap() {
            try {
                const osmBuildings = await Cesium.createOsmBuildingsAsync();
                viewer.scene.primitives.add(osmBuildings);

                // Add labels for buildings from database
                locationData.forEach(location => {
                    viewer.entities.add({
                        position: Cesium.Cartesian3.fromDegrees(location.longitude, location.latitude),
                        label: {
                            text: location.title,
                            font: '14pt monospace',
                            style: Cesium.LabelStyle.FILL_AND_OUTLINE,
                            outlineWidth: 2,
                            verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
                            pixelOffset: new Cesium.Cartesian2(0, 0),
                            fillColor: Cesium.Color.WHITE,
                            outlineColor: Cesium.Color.BLACK,
                            showBackground: true,
                            backgroundColor: new Cesium.Color(0.165, 0.165, 0.165, 0.7),
                        }
                    });
                });

                flyToMarkers();
            } catch (error) {
                console.error('Error loading OSM Buildings:', error);
            }
        }

        function flyToMarkers() {
            const centerLon = locationData.reduce((sum, loc) => sum + parseFloat(loc.longitude), 0) / locationData.length;
            const centerLat = locationData.reduce((sum, loc) => sum + parseFloat(loc.latitude), 0) / locationData.length;

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

        viewer.scene.globe.depthTestAgainstTerrain = true;
        initializeMap();
    </script>
</div>
@endsection
