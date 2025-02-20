<div>
    <x-frontend.header-block :title="$title" />

        <section class="bg-white dark:bg-gray-800">
        <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
            <div class="flex justify-center m-6">
                <img class="h-24 rounded" src="{{ asset('logo2.svg') }}" alt="{{ app_name() }}" />
            </div>
            <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white sm:text-6xl">
                Plan de Afaceri pentru SOS Public
            </h1>
            <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-2xl xl:px-48">
                Sistem de Management pentru Serviciul de Răspuns la Urgențe
            </h2>
            @include("frontend.includes.messages")
        </div>
    </section>

    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <!-- Rezumat Executiv -->
            <div class="mb-12">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">I. Rezumat Executiv</h2>
                <div class="max-w-none prose dark:prose-invert">
                    <p>Acest plan de afaceri prezintă strategia și cadrul operațional pentru o echipă de management al incidentelor, respectând ghidurile serviciului de pompieri și salvare din UK/UE.</p>
                    <p>Planul pune accentul pe integrarea Sistemului de Comandă a Incidentelor (ICS), parteneriatele inter-agenții și tehnologia avansată pentru a asigura un răspuns eficient și sigur la incendiile de vegetație.</p>
                    <p>Acesta abordează domenii cheie precum pregătirea, managementul incidentelor, protocoalele de siguranță și îmbunătățirea continuă prin instruire și dezvoltarea politicilor.</p>
                </div>
            </div>

            <!-- Introducere -->
            <div class="mb-12">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">II. Introducere</h2>
                <div class="max-w-none prose dark:prose-invert">
                    <h3 class="mb-3 text-2xl font-bold">Scop</h3>
                    <p>Stabilirea unui cadru robust pentru gestionarea incidentelor de urgență, asigurând siguranța personalului, protecția comunităților și conservarea resurselor naturale.</p>

                    <h3 class="mt-6 mb-3 text-2xl font-bold">Obiective</h3>
                    <ul class="pl-6 space-y-2 list-disc">
                        <li>Implementarea și menținerea unui Sistem de Comandă a Incidentelor (ICS) adaptat pentru incidente de urgență.</li>
                        <li>Dezvoltarea parteneriatelor puternice cu agențiile de gestionare a terenurilor și specialiștii în mediu.</li>
                        <li>Asigurarea instruirii complete și respectarea protocoalelor de siguranță.</li>
                        <li>Utilizarea tehnologiei și datelor pentru îmbunătățirea predicției și răspunsului la incidente.</li>
                        <li>Dezvoltarea și rafinarea politicilor bazate pe experiența operațională.</li>
                    </ul>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mb-12">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">Calendar de Afaceri</h2>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Anul 1 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Anul 1: Configurare Inițială</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Stabilirea parteneriatelor cheie</li>
                            <li>Dezvoltarea protocoalelor ICS</li>
                            <li>Aplicarea pentru finanțare UE</li>
                            <li>Implementarea programelor de instruire</li>
                        </ul>
                    </div>

                    <!-- Anul 2 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Anul 2: Implementare</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Implementarea sistemelor de comunicare</li>
                            <li>Desfășurarea exercițiilor de răspuns</li>
                            <li>Lansarea sistemului de predicție</li>
                            <li>Asigurarea finanțării suplimentare</li>
                        </ul>
                    </div>

                    <!-- Anii 3-5 -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Anii 3-5: Expansiune</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Extinderea capabilităților aeriene</li>
                            <li>Implementarea modelelor bazate pe AI</li>
                            <li>Standardizarea procedurilor</li>
                            <li>Rafinarea operațiunilor</li>
                        </ul>
                    </div>

                    <!-- Anul 5+ -->
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Anul 5+: Sustenabilitate</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li>Programe de instruire pe termen lung</li>
                            <li>Colaborare internațională</li>
                            <li>Leadership european</li>
                            <li>Îmbunătățire continuă</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Considerații Tehnice -->
            <div class="mb-12">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">Considerații Tehnice</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Efectele Vremii</h3>
                        <p class="text-gray-600 dark:text-gray-400">Înțelegerea și predicția impactului condițiilor meteorologice asupra comportamentului focului.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Topografie</h3>
                        <p class="text-gray-600 dark:text-gray-400">Evaluarea influenței topografiei asupra răspândirii focului, utilizând hărți topografice pentru planificarea strategică.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Dezvoltarea Incendiului</h3>
                        <p class="text-gray-600 dark:text-gray-400">Aplicarea cunoștințelor despre comportamentul și dezvoltarea focului pentru a prezice răspândirea și intensitatea.</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-800">
                        <h3 class="mb-3 text-xl font-bold">Sistem de Predicție</h3>
                        <p class="text-gray-600 dark:text-gray-400">Utilizarea WPS pentru prognozarea comportamentului focului și informarea deciziilor operaționale.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
