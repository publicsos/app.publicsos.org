@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs />
@endsection

@section("content")
<link href="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

<!-- Cesium JS -->
<script src="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Cesium.js"></script>

<!-- Set the initial camera view to look at a specific position (Manhattan coordinates) -->
<script>
    const initialPosition = Cesium.Cartesian3.fromDegrees(
        27.66887633714821,  // Longitude
        46.22688182800248,  // Latitude
        127 // Altitude
    );
</script>

<?php
    $buildings = \Modules\Domain\Models\Buildings\Building::all();
?>

<div class="container-fluid">
    <div id="cesiumContainer" style="height: 100vh;"></div>
    <div class="toolbar">
        <button onclick="flyToMarkers()">Fly to Markers</button>
    </div>

    <script>
        // Set your Cesium Ion access token
        Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJmMWYwZjFiMS01OGQ3LTRiNDctOTk1Mi03NDgwOWVhZTBjYjciLCJpZCI6MTM0NTU5LCJpYXQiOjE2ODE5NDEwMzN9.0kUdp5KVqgFEL8kZmqMIvcUyKGzzNDiIrliUNHJ2w5s';

        // Initialize the Cesium viewer
        const viewer = new Cesium.Viewer("cesiumContainer", {
            terrain: Cesium.Terrain.fromWorldTerrain(),
        });

        // Define an async function to load the tileset and items
        async function loadCesiumData() {
            // Load OSM buildings asynchronously
            const osmBuildingsTileset = await Cesium.createOsmBuildingsAsync();
            viewer.scene.primitives.add(osmBuildingsTileset);

            // Fly to the initial position
            viewer.scene.camera.flyTo({
                destination: initialPosition,
                orientation: {
                    heading: Cesium.Math.toRadians(20),
                    pitch: Cesium.Math.toRadians(-20),
                },
                duration: 0,
            });

            // Pass the PHP array of buildings to JavaScript
            const buildings = @json($buildings);

            // Create markers for each building
            buildings.forEach(building => {
                const { latitude, longitude, altitude } = building;

                // Example: Add a point for each building to the Cesium map
                viewer.entities.add({
                    position: Cesium.Cartesian3.fromDegrees(longitude, latitude, 25),
                    point: {
                        color: Cesium.Color.RED,
                        pixelSize: 10,
                    },
                });
            });
        }

        // Call the async function
        loadCesiumData();

        // Fly to the markers (can be triggered by a button click)
        function flyToMarkers() {
            const positions = @json($buildings).map(building => Cesium.Cartesian3.fromDegrees(building.longitude, building.latitude, building.altitude));
            if (positions.length > 0) {
                viewer.scene.camera.flyTo({
                    destination: positions[0],
                    orientation: {
                        heading: Cesium.Math.toRadians(20),
                        pitch: Cesium.Math.toRadians(-20),
                    },
                    duration: 1.5,
                });
            }
        }
    </script>
</div>

@endsection
