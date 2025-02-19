<footer class="p-4 bg-gray-100 dark:bg-gray-800 sm:p-20">
    <div class="mx-auto max-w-screen-xl text-center">

        <a class="flex justify-center items-center text-2xl font-semibold text-gray-900 dark:text-white" href="/">

            <img style="height:20vh" src="{{ asset("logo2.svg") }}" alt="{{ app_name() }} Logo" />
        </a>

        <ul class="flex flex-wrap justify-center items-center mb-6 text-gray-900 dark:text-white">
            <li>
                <a class="mx-2 hover:underline md:mx-3" href="#">@lang("About")</a>
            </li>
            <li>
                <a class="mx-2 hover:underline md:mx-3" href="{{ route("privacy") }}" wire:navigate.hover>
                    @lang("Privacy")
                </a>
            </li>
            <li>
                <a class="mx-2 hover:underline md:mx-3" href="{{ route("terms") }}" wire:navigate.hover>
                    @lang("Terms")
                </a>
            </li>
            <li>
                <a class="mx-2 hover:underline md:mx-3" href="#">@lang("FAQs")</a>
            </li>
            <li>
                <a class="mx-2 hover:underline md:mx-3" href="#">@lang("Contact")</a>
            </li>
        </ul>




        <x-frontend.footer-credit />
    </div>
</footer>
