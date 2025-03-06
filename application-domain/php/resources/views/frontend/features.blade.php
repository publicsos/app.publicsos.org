@extends('frontend.layouts.app')
@section('title')
    Feature {{ app_name() }} - Emergency Response Service Management System.
@endsection
@section('content')
    <section class="bg-white dark:bg-gray-800">
        <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-600 dark:text-white sm:text-6xl">
                Features
            </h1>
            <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-white sm:px-16 sm:text-2xl xl:px-48">
                Discover the cover features of our Emergency Response Service Management System.
            </h2>
            <p class="mb-6 text-gray-500 dark:text-gray-400 md:text-lg">
                The Emergency Response System is designed to provide real-time situational awareness, effective coordination, and rapid response capabilities for emergency scenarios within building environments.
                By leveraging 3D models, geospatial data, and user management functionalities, the system ensures that first responders, building administrators, and security personnel have the tools they need to act swiftly and efficiently.
               </p>
            @include('frontend.includes.messages')
        </div>
    </section>

    <section class="antialiased bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6 lg:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-3xl font-extrabold tracking-tight leading-tight text-gray-900 dark:text-white sm:text-4xl">
                    Emergency Response System for Buildings Management
                </h2>
                <p class="mt-4 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-xl">
                    The Emergency Response System is designed to provide real-time situational awareness, effective coordination, and rapid response capabilities for emergency scenarios within building environments. By leveraging 3D models, geospatial data, and user management functionalities, the system ensures that first responders, building administrators, and security personnel have the tools they need to act swiftly and efficiently.
                </p>
            </div>
            <div class="p-4 mt-8 bg-gray-50 rounded-lg dark:bg-gray-800 sm:p-12 lg:mt-16">
                <div class="grid grid-cols-1 gap-8 sm:gap-12 lg:grid-cols-3">
                    <!-- Buildings Management -->
                    <div class="flex flex-col gap-4 items-start sm:flex-row sm:gap-5">
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-full shrink-0 dark:bg-gray-700 lg:h-24 lg:w-24"></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Buildings Management</h3>
                            <ul class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-lg">
                                <li><strong>Digital Twin Representation:</strong> Utilizes <strong>CesiumJS</strong> to render accurate 3D models of buildings and their surroundings.</li>
                                <li><strong>Floor Plan Visualization:</strong> Interactive mapping of each floor, showing rooms, hallways, and emergency exits.</li>
                                <li><strong>Structural Data Integration:</strong> Stores detailed information about building materials, fire suppression systems, and ventilation shafts.</li>
                                <li><strong>Real-time Sensor Data:</strong> Integrates with IoT sensors for detecting fire, gas leaks, or unauthorized access.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Emergency Alert & Response -->
                    <div class="flex flex-col gap-4 items-start sm:flex-row sm:gap-5">
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-full shrink-0 dark:bg-gray-700 lg:h-24 lg:w-24"></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Emergency Alert & Response</h3>
                            <ul class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-lg">
                                <li><strong>Automated Alert System:</strong> Sends real-time notifications to relevant emergency services and building occupants.</li>
                                <li><strong>Dynamic Evacuation Routes:</strong> Computes optimal exit routes based on hazard locations and blocked pathways.</li>
                                <li><strong>Incident Reporting & Tracking:</strong> Allows users to report incidents via a web or mobile interface, with GPS-tagged data.</li>
                                <li><strong>Command Center Dashboard:</strong> Provides a centralized control panel for monitoring emergencies and dispatching responders.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- User Management & Access Control -->
                    <div class="flex flex-col gap-4 items-start sm:flex-row sm:gap-5">
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-full shrink-0 dark:bg-gray-700 lg:h-24 lg:w-24"></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">User Management & Access Control</h3>
                            <ul class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-lg">
                                <li><strong>Role-Based Access Control (RBAC):</strong> Ensures only authorized personnel can access critical system features.</li>
                                <li><strong>Occupant Registry:</strong> Maintains a live database of residents, employees, and visitors.</li>
                                <li><strong>Check-in/Check-out System:</strong> Tracks user presence in the building for accountability during emergencies.</li>
                                <li><strong>First Responder Integration:</strong> Allows emergency teams to access building schematics, hazard data, and real-time occupancy details.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- 3D Visualization with CesiumJS -->
                    <div class="flex flex-col gap-4 items-start sm:flex-row sm:gap-5">
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-full shrink-0 dark:bg-gray-700 lg:h-24 lg:w-24"></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">3D Visualization with CesiumJS</h3>
                            <ul class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-lg">
                                <li><strong>Interactive Building Models:</strong> Provides a comprehensive view of structures, accessible from web and mobile platforms.</li>
                                <li><strong>Environmental Context:</strong> Displays surrounding geography, adjacent buildings, and nearby emergency service locations.</li>
                                <li><strong>Live Incident Mapping:</strong> Overlays hazards, responders' locations, and evacuation paths in real-time.</li>
                                <li><strong>Simulation & Training Mode:</strong> Enables emergency preparedness drills using realistic scenario simulations.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Technology Stack -->
                    <div class="flex flex-col gap-4 items-start sm:flex-row sm:gap-5">
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-full shrink-0 dark:bg-gray-700 lg:h-24 lg:w-24"></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Technology Stack</h3>
                            <ul class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400 sm:text-lg">
                                <li><strong>Frontend:</strong> Vue.js, CesiumJS</li>
                                <li><strong>Backend:</strong> Laravel, PostgreSQL (with PostGIS for geospatial data)</li>
                                <li><strong>APIs & Integrations:</strong> IoT device APIs, emergency services integration, mobile push notifications</li>
                                <li><strong>Security:</strong> Encrypted user data, multi-factor authentication, audit logging</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl text-center sm:py-16 lg:px-6">
            <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Emergency Alert & Response
            </h2>
            <p class="text-gray-500 dark:text-gray-400 sm:text-xl">Here are a few reasons why you should choose Public Sos</p>
            <div class="mt-8 space-y-8 md:grid md:grid-cols-3 md:gap-12 md:space-y-0 lg:mt-12 lg:grid-cols-3">
                <div>
                    <i class="fa fa-call"></i>
                    <h3 class="mb-2 text-xl font-bold dark:text-white">
                        Automated Alert System
                    </h3>
                    <p class="mb-4 text-gray-500 dark:text-gray-400">Sends real-time notifications using technologies like sms, what-up, lora-wan to relevant emergency services and building occupants.</p>
                </div>
                <div>
                    <i class="fa fa-call"></i>
                    <h3 class="mb-2 text-xl font-bold dark:text-white">Dynamic Evacuation Routes</h3>
                    <p class="mb-4 text-gray-500 dark:text-gray-400">
                        Computes optimal exit routes based on hazard locations and blocked pathways.
                    </p>
                </div>
                <div>
                    <i class="fa fa-call"></i>
                    <h3 class="mb-2 text-xl font-bold dark:text-white">
                        Incident Reporting & Tracking
                    </h3>
                    <p class="mb-4 text-gray-500 dark:text-gray-400">
                        Allows users to report incidents via a web or mobile interface, with GPS-tagged data
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('after-scripts')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endpush
