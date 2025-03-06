@extends("frontend.layouts.app")
@section("title")
{{ app_name() }} - Emergency Response Service Management System.
@endsection
@section("content")
<section class="bg-white dark:bg-gray-800">
   <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
      <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-600 dark:text-white sm:text-6xl">
         Contact Team
      </h1>
      <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-white sm:px-16 sm:text-2xl xl:px-48">
         Emergency Response Service Management System
      </h2>
      @include("frontend.includes.messages")
   </div>
</section>
<section class="bg-white dark:bg-gray-900" style="padding-bottom: 10vh">

    <div class="pt-20 pb-20 mb-20 space-x-8 space-y-8 text-center md:grid md:grid-cols-3 md:gap-12 md:space-y-0 lg:grid-cols-3">
        <div>
           <div class="flex justify-center items-center mx-auto mb-4 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800 lg:h-16 lg:w-16">
              <svg class="w-5 h-5 text-gray-600 dark:text-gray-500 lg:h-8 lg:w-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                 <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                 <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
              </svg>
           </div>
           <p class="mb-2 text-xl font-bold dark:text-white">Email us:</p>
           <p class="mb-3 text-gray-500 dark:text-gray-400">Email us for general queries, including marketing and partnership opportunities.</p>
           <a href="mailto:stefan@izdrail.com" class="font-semibold text-primary-600 hover:underline dark:text-white">info@publicsos.org</a>
        </div>
        <div>
           <div class="flex justify-center items-center mx-auto mb-4 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800 lg:h-16 lg:w-16">
              <svg class="w-5 h-5 text-gray-600 dark:text-gray-500 lg:h-8 lg:w-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                 <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
              </svg>
           </div>
           <p class="mb-2 text-xl font-bold dark:text-white">Call us:</p>
           <p class="mb-3 text-gray-500 dark:text-gray-400">Call us to speak to a member of our team. We are always happy to help.</p>
           <span class="font-semibold text-primary-600 dark:text-white">+44 (744) 821-8899</span>
        </div>
        <div>
           <div class="flex justify-center items-center mx-auto mb-4 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800 lg:h-16 lg:w-16">
              <svg class="w-5 h-5 text-gray-600 dark:text-gray-500 lg:h-8 lg:w-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path>
              </svg>
           </div>
           <p class="mb-2 text-xl font-bold dark:text-white">Support</p>
           <p class="mb-3 text-gray-500 dark:text-white">Email us for general queries, including marketing and partnership opportunities.</p>
        </div>
     </div>
</section>
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endsection
