@extends('contactform/master')

@section('header')

<!-- <link type="text/css"  href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" rel="stylesheet">
<link type="text/css"  href="https://cdn.datatables.net/2.3.2/css/dataTables.tailwindcss.css" rel="stylesheet"> -->

<link type="text/css"  href="{{asset('css/dataTables.dataTables.min.css')}}" rel="stylesheet">
<link type="text/css"  href="{{asset('css/dataTables.tailwindcss.css')}}" rel="stylesheet"> 

<!-- <link type="text/css"  href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> -->

<style>
  .btnDisabled {
    background-color: #cccccc;
    /* Example: Gray background */
    color: #666666;
    /* Example: Darker gray text */
    cursor: not-allowed;
    /* Change cursor to "not allowed" symbol */
    opacity: 0.7;
    /* Optional: Make it slightly transparent */
  }
</style>

@endsection

@section('content')
<div class="py-12">
  <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">


        <div class="flex float-right" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
              <a href="{{route('dashboard')}}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                Dashboard
              </a>
            </li>         
            <li aria-current="page">
              <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Contact List</span>
              </div>
            </li>
          </ol>
        </div>


        <form class="grid  md:grid-cols-4 md:gap-6 mx-auto pb-4 mb-10 mt-6">
          <div class="flex mb-5">
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name" />
          </div>
          <div class="flex mb-5">
            <input type="text" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="email" />
          </div>
          <div class="flex mb-5">
            <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              <option value="">Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div class="flex mb-5">
            <button type="button" onclick="getContact.getContactList()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
              <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
              </svg>
            </button>
          </div>

        </form>
 

        <div class="relative shadow-md sm:rounded-lg">
          <table class="w-full text-sm text-left rtl:text-right text-black-500 dark:text-gray-400 table table-bordered contact-data-table">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Phone</th>
                <th scope="col" class="px-6 py-3">Gender</th>
                <th scope="col" class="px-6 py-3">Profile</th>
                <th scope="col" class="px-6 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>



        <!-- Start Other Details Modal -->
        <div id="detail-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
          <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                  Contact Details Of: <span id="spanContactName" class="text-blue-500 font-bold"></span>
                </h3>                
              </div>
              <!-- Modal body -->
              <div class="p-4 md:p-5 space-y-4">

                <div class="grid grid-cols-1 gap-4">
                  <div class="p-4 bg-gray-100 rounded">
                    <h3 class="text-lg font-semibold mb-2 underline">Merged Data</h3>

                    <div id="divMergedData">
                      <div class="flex items-center space-x-2">
                        <span class="text-blue-500 font-bold">Email:</span>
                        <span id="spanMergedEmail"></span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="text-blue-500 font-bold">Phone:</span>
                        <span id="spanMergedPhone"></span>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <div class="p-4 bg-gray-100 rounded">
                    <h3 class="text-lg font-semibold mb-2 underline">Custom Field Data</h3>
                    <div id="divCustomFieldData">                      
                    </div>                 
                  </div>
                </div>

                 <div class="grid grid-cols-1 gap-4">
                  <div class="p-4 bg-gray-100 rounded">
                    <h3 class="text-lg font-semibold mb-2 underline">Additionl File</h3>
                    <div id="divAdditionalFile">
                      <a href="#" target="_blank" id="linkFile" class="font-semibold text-blue-600 dark:text-blue-500 hover:underline">Click here to check file</a>
                    </div>
                  </div>
                </div>

              </div>
              <!-- Modal footer -->
              <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">                
                <button type="button" onClick="getContact.closeDetailModal()" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Other Details Modal -->





        <!-- Start Merge Modal -->
        <form>
          <div id="small-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                  <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Selected Contact: <span class="text-blue-500" id="selContactName"></span>
                  </h3>                  
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">

                  <div class="mb-5 grid md:grid-cols-1">
                    <div id="divMrgContactDropDwn">

                    </div>
                    <span>Please select contact to merge</span>
                  </div>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                  <button id="btnMerge" onClick="getContact.mergeContact()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                  <button type="button" id="btnClose" onClick="getContact.closeModal()" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Exit</button>

                  <input type="hidden" name="sel_contact_form_id" id="sel_contact_form_id" value="0">

                </div>
                <div style="display: none;" class="mrgInfodiv p-4 md:p-5 mb-5 grid md:grid-cols-1">
                  <div class="bg-green-100 border-l-4 border-blue-100 text-blue-700 p-4 rounded-lg">
                    <p class="text-lg font-semibold">Info!</p>
                    <p id="mrgInfoMsg"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
          <!-- End Merge Modal -->


      </div>
    </div>
  </div>
</div>


@endsection

@section('footer')

<!-- <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> -->


<script type="text/javascript" src="{{asset('js/dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/dataTables.tailwindcss.js')}}"></script>

<!-- <script type="text/javascript" src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.3.2/js/dataTables.tailwindcss.js"></script> -->

<!-- <script src="https://cdn.tailwindcss.com"></script> -->

<script type="text/javascript" src="{{asset('contact/listcontactform.js')}}"></script>

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> -->

<script>
  $(document).ready(function() {
    getContact.getContactList();
  });
</script>
@endsection