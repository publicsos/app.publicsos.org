@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }} - Emergency Response Service Management System.
@endsection



@section("content")

    <section class="bg-white dark:bg-gray-800">
        <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-600 dark:text-white sm:text-6xl">
                Public SOS
            </h1>
            <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-white sm:px-16 sm:text-2xl xl:px-48">
                Emergency Response Service Management System
            </h2>
            @include("frontend.includes.messages")
        </div>
    </section>
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl lg:py-16">
           <div class="grid gap-8 items-center mb-8 lg:mb-24 lg:grid-cols-12 lg:gap-12">
              <div class="col-span-6 text-center sm:mb-6 lg:mb-0 lg:text-left">
                 <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white md:text-5xl xl:text-6xl">

                    <span class="block xl:inline">Public Safety</span>
                     <span class="block xl:inline">SOS</span>
                 </h1>
                 <p class="mx-auto mb-6 max-w-xl text-gray-500 dark:text-gray-400 md:text-lg lg:mx-0 xl:mb-8 xl:text-xl">

                 </p>

              </div>
              <div class="col-span-6">
                <iframe class="mx-auto w-full max-w-xl h-64 rounded-lg sm:h-96" width="560" height="315" src="https://www.youtube.com/embed/IVdxG3b0hpg?si=E39RpPARmrA-DoCv" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
           </div>

        </div>
     </section>
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Executive Summary -->
            <div class="mb-12">
                <h2 class="mb-4 text-4xl font-bold text-gray-600 dark:text-white">I. Executive Summary</h2>
                <div class="max-w-none text-3xl text-gray-600 prose dark:text-white dark:prose-invert">
                    <p>This plan outlines the strategy and operational framework for a incident management team, adhering to the Public Safety and rescue service guidelines.</p>
                    <br />
                    <p>The plan emphasis's the integration of the Incident Command System (ICS), inter-agency partnerships, and advanced technology to ensure effective and safe response to emergencies.</p>
                    <br />
                    <p>It addresses key areas such as preparedness, incident management, safety protocols, and continuous improvement through training and policy development.</p>
                </div>
            </div>
        </div>
    </section>



    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Introduction -->
            <div class="mb-12" style="margin-bottom:6vh">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white" style="margin-bottom:3vh">II. Introduction</h2>
                <div class="max-w-none prose dark:prose-invert">
                    <h3 class="mb-3 text-3xl font-bold text-gray-600 dark:text-white">Purpose</h3>
                    <p class="mb-6 text-gray-600 dark:text-white">To establish a robust framework for managing emergency incidents, ensuring the safety of personnel, protection of communities, and preservation of natural resources.</p>

                    <h3 class="mt-6 mb-3 text-2xl font-bold text-gray-600 dark:text-white">Objectives</h3>
                    <ul class="pl-6 space-y-2 text-gray-600 dark:text-white list-disc-none">
                        <li>Implement and maintain an effective Incident Command System (ICS) tailored for emergency incidents.</li>
                        <li>Foster strong partnerships with land management agencies and environmental specialists.</li>
                        <li>Ensure comprehensive training and adherence to safety protocols.</li>
                        <li>Leverage technology and data for improved incident prediction and response.</li>
                        <li>Develop and refine policies based on operational experience.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-24 mx-auto max-w-screen-lg text-center sm:px-12">
            <!-- Timeline -->
            <div class="mt-12 mb-12">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">Implementation Timeline</h2>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Year 1 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Year 1: Initial Setup</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Establish key partnerships</li>
                            <li>Develop ICS protocols</li>
                            <li>Apply for EU funding</li>
                            <li>Implement training programmes</li>
                        </ul>
                    </div>

                    <!-- Year 2 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Year 2: Implementation</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Deploy communication systems</li>
                            <li>Conduct emergency response drills</li>
                            <li>Launch prediction system</li>
                            <li>Secure additional funding</li>
                        </ul>
                    </div>

                    <!-- Years 3-5 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Years 3-5: Expansion</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Expand aerial capabilities</li>
                            <li>Implement AI-based models</li>
                            <li>Standardise procedures</li>
                            <li>Refine operations</li>
                        </ul>
                    </div>

                    <!-- Year 5+ -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-white">Year 5+: Sustainability</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-white">
                            <li>Long-term training programmes</li>
                            <li>International collaboration</li>
                            <li>European leadership</li>
                            <li>Continuous improvement</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Technical Considerations -->
            <div class="mb-12" style="margin-top:3vh">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">Technical Considerations</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-600 dark:text-white">Weather Effects</h3>
                        <p class="text-gray-600 dark:text-white">Understanding and predicting the impact of weather conditions on emergency response capabilities.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-600 dark:text-white"">Topography</h3>
                        <p class="text-gray-600 dark:text-white">Assessing the influence of topography on incident management, utilising topographical maps for strategic planning.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-600 dark:text-white">Response Development</h3>
                        <p class="text-gray-600 dark:text-white">Applying knowledge of incident behaviour and development to predict and manage emergency situations.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold text-gray-600 dark:text-white">Prediction System</h3>
                        <p class="text-gray-600 dark:text-gray-400">Utilising the Emergency Prediction System (EPS) to forecast and inform operational decisions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endsection
