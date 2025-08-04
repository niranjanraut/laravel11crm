@extends('contactform/master')

@section('content')
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Contact Form</span>
              </div>
            </li>
          </ol>
        </div>

        <form class="max-w-3xl mx-auto mt-5" id="addContactForm">
          <div>
            <div class="mb-5 grid md:grid-cols-2 md:gap-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
              </div>
              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="example@gmail.com" required />
              </div>
            </div>


            <div class="mb-5 grid md:grid-cols-2 md:gap-6">
              <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone <span class="text-red-500">*</span></label>
                <input type="tel" id="phone" name="phone" maxlength="14" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
              </div>
              <div>
                <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender <span class="text-red-500">*</span></label>

                <div class="flex">
                  <div class="flex items-center me-4">
                    <input id="male" type="radio" value="male" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="male" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Male</label>
                  </div>
                  <div class="flex items-center me-4">
                    <input id="female" type="radio" value="female" name="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="female" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Female</label>
                  </div>
                </div>
              </div>
            </div>


            <div class="mb-5 grid md:grid-cols-2 md:gap-6">
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="profile_image">Profile image</label>
                <input id="profile_image" name="profile_image" type="file" onChange="addContact.readURL(this)" accept=".png, .jpg, .jpeg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help"> PNG, JPG, JPEG (MAX. 1MB).</p>
              </div>
              <div>
                <img class="rounded-sm w-36 h-36" id="imgProfile" src="{{asset('images/no_image_small.png')}}" alt="profile image loading...">
              </div>
            </div>

            <div class="mb-5 grid md:grid-cols-2 md:gap-6">
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Additional file</label>
                <input id="additional_file" name="additional_file" type="file" accept=".pdf" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="doc_input_help"> PDF (MAX. 1MB).</p>
              </div>
              <div id="divLinkFile" style="display: none;">
                <a href="#" target="_blank" id="linkFile" class="font-semibold text-blue-600 dark:text-blue-500 hover:underline">Click here to check file</a>
              </div>
            </div>

            <!-- Custom Field Div -->
            <div class="mb-5 grid md:grid-cols-2 md:gap-6" id="divCustomFields">

            </div>

            <button type="button" id="btnSubmit" onClick="addContact.save()" class="mb-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            <a href="{{url('/listcontact')}}" class="mb-5 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">View</a>

            <svg style="display: none;" aria-hidden="true" role="status" class="clsSubmitLoader inline w-6 h-6 me-3 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
              <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2" />
            </svg>
          </div>
          <input type="hidden" name="contact_form_id" id="contact_form_id" value="{{$contact_form_id}}">

          <!-- Error message -->
          <div style="display: none;" class="errordiv mb-5 grid md:grid-cols-1">
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
              <svg class="shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
              </svg>
              <span class="sr-only">Danger</span>
              <div>
                <span class="font-medium">Ensure that these requirements are met:</span>
                <ul class="mt-1.5 list-disc list-inside" id="validation-errors">

                </ul>
              </div>
            </div>
          </div>

          <!-- Success message -->
          <div style="display: none;" class="successdiv bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            <p class="text-lg font-semibold">Success!</p>
            <p id="cntSuccessMsg"></p>
          </div>

        </form>


        @include('contactform.fieldschema')


      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="{{asset('contact/addcontactform.js') }}"></script>
@endsection

@section('footer')
<script>
  $(document).ready(function() {
    addContact.getContactDetails();
    addContact.showCustomFields('{{$contact_form_id}}');
  });
</script>
@endsection