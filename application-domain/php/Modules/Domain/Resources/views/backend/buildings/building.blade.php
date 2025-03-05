@extends("backend.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("content")
    <!-- Cesium CSS -->
    <link href="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <i class="{{ $module_icon }}"></i> {{ __($module_title) }}
                <small class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="subtitle">
                    @lang(":module_name Management Dashboard", ['module_name' => Str::title($module_name)])
                    {{ $buildings->first()->title ?? '' }}
                </x-slot>

                <x-slot name="toolbar">
                    @can('add_'.$module_name)
                        <x-buttons.create
                            route="{{ route("backend.$module_name.create") }}"
                            title="{{ __('Create') }} {{ ucwords(Str::singular($module_name)) }}"
                            class="me-2"
                        />
                    @endcan

                    @can('restore_'.$module_name)
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle"
                                    type="button"
                                    data-coreui-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route("backend.$module_name.trashed") }}">
                                        <i class="fas fa-eye-slash me-2"></i> @lang("View trash")
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </x-slot>
            </x-backend.section-header>

            <!-- Tabs Navigation -->
            <ul class="mt-4 nav nav-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="map-tab" data-bs-toggle="tab" data-bs-target="#map"
                            type="button" role="tab" aria-controls="map" aria-selected="true">
                        <i class="fas fa-map me-1"></i> Map View
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#data"
                            type="button" role="tab" aria-controls="data" aria-selected="false">
                        <i class="fas fa-table me-1"></i> Building Data
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="mt-3 tab-content" id="dashboardTabsContent">
                <!-- Map Tab -->
                <div class="tab-pane fade show active" id="map" role="tabpanel" aria-labelledby="map-tab">
                    <div class="cesium-container-wrapper position-relative">
                        <div id="cesiumContainer" style="height: 70vh;"></div>
                        <div class="top-0 p-2 cesium-toolbar position-absolute end-0">
                            <button class="btn btn-primary btn-sm me-2" onclick="flyToMarkers()">
                                <i class="fas fa-map-marker-alt me-1"></i> Fly to Markers
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="resetView()">
                                <i class="fas fa-home me-1"></i> Reset View
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Tab -->
                <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buildings as $building)
                                    <tr>
                                        <td>{{ $building->id }}</td>
                                        <td>{{ $building->title ?? 'N/A' }}</td>
                                        <td>{{ $building->latitude ?? 'N/A' }}</td>
                                        <td>{{ $building->longitude ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route("backend.$module_name.show", $building->id) }}"
                                               class="btn btn-sm btn-info me-1"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route("backend.$module_name.edit", $building->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No buildings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cesium JS -->
    <script src="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Cesium.js"></script>


    <script>
        (function() {  // Wrap in IIFE to create a private scope
            Cesium.Ion.defaultAccessToken = "{{ config('services.cesium.token') }}";
            let viewer = null;
            let cesiumInitialized = false;

            async function initializeCesium() {
                if (cesiumInitialized) return;

                try {
                    const terrainProvider = await Cesium.createWorldTerrainAsync();

                    viewer = new Cesium.Viewer('cesiumContainer', {
                        terrainProvider: terrainProvider,
                        shouldAnimate: true,
                        scene3DOnly: true,
                        shadows: true,
                        timeline: false,
                        animation: false
                    });

                    await loadCesiumData(viewer);
                    cesiumInitialized = true;
                } catch (error) {
                    console.error("Error initializing Cesium:", error);
                    showError("Failed to initialize 3D map");
                }
            }

            async function loadCesiumData(viewerInstance) {
                try {
                    const osmBuildingsTileset = await Cesium.createOsmBuildingsAsync();
                    osmBuildingsTileset.style = new Cesium.Cesium3DTileStyle({
                        show: "${feature['building']} !== null",
                        color: {
                            conditions: [
                                ["${feature['building']} === 'hospital'", "rgba(0, 255, 0, 0.8)"],
                                ["${feature['building']} === 'school'", "rgba(255, 165, 0, 0.8)"],
                                ["${feature['building']} === 'church'", "rgba(0, 255, 0, 0.8)"],
                                ["${feature['building']} === 'police'", "rgba(0, 0, 255, 0.8)"],
                                ["${feature['building']} === 'fire'", "rgba(255, 0, 0, 0.8)"],
                                [true, "rgba(128, 128, 128, 0.5)"]
                            ]
                        }
                    });

                    viewerInstance.scene.primitives.add(osmBuildingsTileset);

                    const buildings = @json($buildings);
                    const validBuildings = buildings.filter(b =>
                        b.latitude && b.longitude &&
                        !isNaN(b.latitude) && !isNaN(b.longitude)
                    );

                    if (!validBuildings.length) {
                        showWarning("No valid building coordinates found");
                        return;
                    }

                    validBuildings.forEach(building => {
                        addBuildingEntity(building, viewerInstance);
                    });

                    flyToMarkers();
                } catch (error) {
                    console.error("Error loading Cesium data:", error);
                    showError("Failed to load building data");
                }
            }

            function addBuildingEntity(building, viewerInstance) {
                const position = Cesium.Cartesian3.fromDegrees(building.longitude, building.latitude, 100);
                const description = `
                    <div class="p-2 building-info" style="background: #545454">
                        <table class="table mb-0 table-sm">
                            <tr><th>ID:</th><td>${building.id}</td></tr>
                            <tr><th>Title:</th><td>${building.title || 'N/A'}</td></tr>
                            <tr><th>Location:</th><td>${building.latitude}, ${building.longitude}</td></tr>
                            <tr>
                                <th>Actions:</th>
                                <td>
                                    <a href="/admin/buildings/${building.id}" class="btn btn-sm btn-info me-1" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="/admin/buildings/${building.id}/edit" class="btn btn-sm btn-warning" target="_blank">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                `;

                viewerInstance.entities.add({
                    id: `building-${building.id}`,
                    name: building.title || `Building #${building.id}`,
                    position: position,
                    point: {
                        pixelSize: 10,
                        color: Cesium.Color.BLUE,
                        outlineColor: Cesium.Color.WHITE,
                        outlineWidth: 2
                    },
                    label: {
                        text: building.title || `Building #${building.id}`,
                        font: '14px sans-serif',
                        verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
                        pixelOffset: new Cesium.Cartesian2(0, -20)
                    },
                    description: description
                });
            }

            function flyToMarkers() {
                if (!viewer || !viewer.entities) {
                    console.warn("Viewer not initialized yet");
                    return;
                }
                const entities = viewer.entities.values;
                if (entities.length > 0) {
                    viewer.flyTo(entities, {
                        duration: 2,
                        offset: new Cesium.HeadingPitchRange(0, Cesium.Math.toRadians(-45), 1000)
                    });
                }
            }

            function resetView() {
                if (!viewer || !viewer.entities) {
                    console.warn("Viewer not initialized yet");
                    return;
                }
                flyToMarkers();
            }

            function showError(message) {
                alert(message); // Replace with your preferred notification system
            }

            function showWarning(message) {
                console.warn(message);
                alert(message); // Replace with your preferred notification system
            }

            // Expose functions to global scope for button onclick handlers
            window.flyToMarkers = flyToMarkers;
            window.resetView = resetView;

            // Initialize when map tab is first shown
            document.getElementById('map-tab').addEventListener('shown.bs.tab', function (e) {
                if (!cesiumInitialized) {
                    initializeCesium();
                }
            });

            // Initialize immediately if map tab is active on page load
            if (document.getElementById('map-tab').classList.contains('active')) {
                initializeCesium();
            }
        })();
    </script>

@endsection
