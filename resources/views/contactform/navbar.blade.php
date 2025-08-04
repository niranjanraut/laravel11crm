<nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">

    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{url('dashboard')}}" class="flex items-center space-x-3 rtl:space-x-reverse">            
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">CRM</span>
        </a>
        <button data-collapse-toggle="navbar-multi-level" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-multi-level" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            
                <li>
                    <a href="{{route('dashboard')}}" class="{{ request()->is('dashboard') ? 'md:text-blue-700' : '' }} block py-2 px-3 text-gray-900 rounded-sm md:bg-transparent md:p-0 md:hover:text-blue-700 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent">Dashboard</a>
                </li>
                
                <li>
                    <a href="{{route('contactform')}}" class="{{ request()->is('contactform') ? 'md:text-blue-700' : '' }} block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" >Contact Form</a>
                </li>
                <li>
                    <a href="{{route('listcontact')}}" class="{{ request()->is('listcontact') ? 'md:text-blue-700' : '' }} block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact List</a>
                </li>
                <li>
                    <a href="{{route('customfields')}}" class="{{ request()->is('customfields') ? 'md:text-blue-700' : '' }} block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Custom Field</a>
                </li>
                <li>
                    <div class="border-b-2 p-1 border-black">
                    <a id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="cursor-pointer flex font-bold items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        {{ Auth::user()->name }}
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg>
                    </a>
                    </div>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul style="display: none;" class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="{{route('profile.edit')}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    Profile</a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                            
                            @csrf
                            <a href="{{route('logout')}}"
                            onclick="event.preventDefault(); this.closest('form').submit();"                               
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            Sign out</a>

                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>        
    </div>
</nav>