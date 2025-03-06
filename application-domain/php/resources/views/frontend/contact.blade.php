@extends("frontend.layouts.app")
@section("title")
Contact our team - {{ app_name() }} - Emergency Response Service Management System.
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
<section class="bg-white dark:bg-gray-900">
    <div class="px-4 py-8 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
        <div class="grid gap-16 lg:grid-cols-3">
            <div class="col-span-2">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white md:text-4xl lg:mb-8">Get in touch with us</h2>
                <form id="my-form" action="https://formspree.io/f/mrbpzpzn" method="POST" class="space-y-8">
                    <div class="grid grid-cols-1 gap-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white" for="email">Your email address <span class="text-xs text-gray-500 dark:text-gray-400">(So we can reply to you)</span></label>
                        <div class="flex">
                            <div class="relative w-full">
                                <input class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-cyan-500 dark:focus:ring-cyan-500" id="email" name="email" placeholder="name@example.com" required="" type="email">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white" for="topic">Topic</label>
                        <div class="flex">
                            <div class="relative w-full">
                                <select class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-cyan-500 dark:focus:ring-cyan-500" id="topic" name="topic">
                                    <option>Select a topic</option>
                                    <option value="General">General Inquiry</option>
                                    <option value="Technical">Technical Support</option>
                                    <option value="Emergency">Emergency Services</option>
                                    <option value="Feedback">Feedback</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white" for="subject">Subject</label>
                        <div class="flex">
                            <div class="relative w-full">
                                <input class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-cyan-500 dark:focus:ring-cyan-500" id="subject" name="subject" placeholder="Let us know how we can help you" required="">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 sm:col-span-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white" for="message">Your message</label>
                        <textarea class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-cyan-500 dark:focus:ring-cyan-500" id="message" name="message" placeholder="Leave a comment..." rows="6" required></textarea>
                        <div class="flex gap-2 items-center mt-4">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" id="terms-checkbox" required>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-300" for="terms-checkbox">By submitting this form, you confirm that you have read and agree to our <a class="font-normal text-gray-900 underline hover:no-underline dark:text-white" href="#">Terms of Service</a> and <a class="font-normal text-gray-900 underline hover:no-underline dark:text-white" href="#">Privacy Statement</a>.</label>
                        </div>
                    </div>
                    <button type="submit" id="my-form-button" class="px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg sm:w-fit hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send message</button>
                    <p id="my-form-status" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </form>
            </div>
            <div>
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Contact Information</h3>
                <div class="space-y-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex justify-center items-center mr-3 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 text-md dark:text-white">Email us:</h4>
                        </div>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">Email us for general queries, including marketing and partnership opportunities.</p>
                        <a href="mailto:info@publicsos.org" class="font-semibold text-primary-600 hover:underline dark:text-white">info@publicsos.org</a>
                    </div>
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex justify-center items-center mr-3 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 text-md dark:text-white">Call us:</h4>
                        </div>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">Call us to speak to a member of our team. We are always happy to help.</p>
                        <span class="font-semibold text-primary-600 dark:text-white">+44 (744) 821-8899</span>
                    </div>
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex justify-center items-center mr-3 w-10 h-10 bg-gray-100 rounded-lg dark:bg-gray-800">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 text-md dark:text-white">Support</h4>
                        </div>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">Technical support and emergency assistance available 24/7.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
@push('after-scripts')
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endpush
