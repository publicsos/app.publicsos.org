@extends('frontend.layouts.app')

@section('title') {{ __($module_title) }} @endsection

@section('content')


<link href="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

<!-- Cesium JS -->
<script src="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Cesium.js"></script>

<!-- Set the initial camera view to look at a specific position (Manhattan coordinates) -->
<script>
    const initialPosition = Cesium.Cartesian3.fromDegrees(
        27.66887633714821,  // Longitude
        46.22688182800248,  // Latitude
        260 // Altitude
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
        Cesium.Ion.defaultAccessToken = '{{ config('services.cesium.token') }}';

        // Initialize the Cesium viewer
        const viewer = new Cesium.Viewer("cesiumContainer", {
            terrain: Cesium.Terrain.fromWorldTerrain(),
        });

        // Define an async function to load the tileset and items
        async function loadCesiumData() {
            // Load OSM buildings asynchronously
            const osmBuildingsTileset = await Cesium.createOsmBuildingsAsync();


            const excludedBuildingIds = [
                '228676745',
                '208701537',
                '1093429208',
                '208697037'
            ];


            osmBuildingsTileset.style = new Cesium.Cesium3DTileStyle({
                show: `${Cesium.FEATURES_LENGTH} > 0 && (${
            excludedBuildingIds.map(id => `${Cesium.FEATURE_ID_PROPERTY} !== '${id}'`).join(' && ')
        })`,
            });

            // Apply custom styling
            osmBuildingsTileset.style = new Cesium.Cesium3DTileStyle({
                color: {
                    conditions: [
                        ["${feature['building']} === 'hospital'", "rgba(0, 165, 0, 1)"], // Green
                        ["${feature['building']} === 'school'", "rgba(255, 165, 0, 1)"], // Orange
                        ["${feature['building']} === 'church'", "rgba(0, 165, 0, 1)"], // Orange
                        ["${feature['building']} === 'police'", "rgba(0, 0, 255, 1)"], // Blue
                        ["${feature['building']} === 'fire'", "rgba(255, 0, 0, 1)"], // Red
                        [true, "rgba(128, 128, 128, 0.5)"] // Gray for others
                    ]
                },

            });

            viewer.scene.primitives.add(osmBuildingsTileset);



            // Fly to the initial position
            viewer.scene.camera.flyTo({
                destination: initialPosition,
                material: Cesium.Color.RED.withAlpha(0.5),
                orientation: {
                    heading: Cesium.Math.toRadians(45),
                    pitch: Cesium.Math.toRadians(-45),
                },
                duration: 0,
            });

            // Pass the PHP array of buildings to JavaScript
            const buildings = @json($buildings);

            // Create markers for each building
            buildings.forEach(building => {
                const { id, latitude, longitude, title } = building;

                // Format building information HTML for the popup
                const description = `
                    <div class="building-info">
                        <table style="background:#ddd;color:#fff" class="cesium-infoBox-defaultTable">
                            <tr>
                                <th>ID:</th>
                                <td>${id}</td>
                            </tr>
                             <tr>
                                <th>TItle:</th>
                                <td>${title}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>${latitude}, ${longitude}</td>
                            </tr>
                        </table>
                    </div>
                `;

                // Add a box entity for each building to the Cesium map with popup information
                viewer.entities.add({
                    id: `building-${id}`,
                    name: name || `Building #${id}`,
                    position: Cesium.Cartesian3.fromDegrees(longitude, latitude, 100),
                    box: {
                        dimensions: new Cesium.Cartesian3(15, 15, 15.0),
                        material: Cesium.Color.BLUE.withAlpha(0.5),
                        outline: true,
                        outlineColor: Cesium.Color.BLACK,
                    },
                    description: description, // This adds HTML content to the popup
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
                        heading: Cesium.Math.toRadians(45),
                        pitch: Cesium.Math.toRadians(-45),
                    },
                    duration: 1.5,
                });
            }
        }
    </script>
</div>

@endsection

