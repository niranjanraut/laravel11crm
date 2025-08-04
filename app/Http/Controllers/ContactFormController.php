<?php

namespace App\Http\Controllers;
use App\CRMmanager\ContactClassManager;
use Illuminate\Http\Request;
use App\Models\ContactForm;

class ContactFormController extends Controller
{
    //
    protected $ContactFormController;

    public function __construct(ContactClassManager $ContactClassManager)
    {
        $this->ContactFormController = $ContactClassManager;
    }

    public function getContactForm(Request $request)
    {           
        $data = [];  
        $contact_form_id = $request->input('id',0);
        $data['contact_form_id'] = $contact_form_id;

        return view('contactform.getform',$data);            
    }

    public function getCustomFields(Request $request)
    {           
        $data = [];  
        $fieldTypes = $this->ContactFormController->getFieldTypes();
      
        $data["fieldTypes"] = $fieldTypes;          
        return view('contactform.customfields',$data);            
    }

    public function listContact(Request $request)
    {           
        //dd($this->ContactFormController->test());

        /*if ($request->ajax()) {

            //$data = ContactForm::query();
            $data = ContactForm::select('contact_form_id','name','email','phone','gender');

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
       
                            $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
      
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }*/

        return view('contactform.listform');            
    }

    public function getContact(Request $request)
    {           
        return $this->ContactFormController->getContactList($request);
    }
    
    public function saveContact(Request $request){

        //unset($request['gender']);

        /*
        array:7 [ // app\Http\Controllers\ContactFormController.php:68
        "contact_form_id" => "0"
        "name" => null
        "email" => null
        "phone" => null
        "gender" => null
        "profile_image" => null
        "additional_file" => null
        ]
         */       
                  
          $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',     
            'gender' => 'required',     
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:1000',     
            'additional_file' => 'nullable|mimes:pdf|max:1000',     
           
        ], [
            'name.required' => 'Name field is required',
            'email.required' => 'Email field is required',
            'email.email' => 'Email must be a valid email address',
            'phone.required' => 'Phone field is required',
            'gender.required' => 'Gender field is required',

            /*'profile_image.required' => 'Profile image field is required',  
            'profile_image.image' => 'Profile image must be an image file',                    
            'profile_image.mimes' => 'Profile image must be in JPEG, JPG, PNG',                    
            'profile_image.max' => 'Profile image file size require less than 1 MB',*/                  
        ]);

        $arrSavedDet = $this->ContactFormController->saveContactDetails($request);
        return response()->json($arrSavedDet);
    }

    public function getContactDetails(Request $request)
    {   
        $contact_form_id = $request->input('contact_form_id',0);

        $details = $this->ContactFormController->fetchContactDetails($contact_form_id);
        //dd(response()->json($resDetails));

        $arrDetails = array();
     
        $arrDetails["name"] = isset($details->name) ? $details->name : "";
        $arrDetails["email"] = isset($details->email) ? $details->email : "";
        $arrDetails["phone"] = isset($details->phone) ? $details->phone : "";        
        $arrDetails["gender"] = isset($details->gender) ? $details->gender : "";             
        $arrDetails["profile_image_url"] = isset($details->profile_image) ? 
        url(ContactClassManager::CONTACT_PROFILE_IMAGE_FOLDER."/".$details->profile_image) : url('images/no_image_small.png');

        $arrDetails["additional_file_url"] = isset($details->additional_file) ? 
        url(ContactClassManager::CONTACT_ADDITIONAL_FILE_FOLDER."/".$details->additional_file) : false;
        
        //dd($arrDetails);
        return response()->json($arrDetails);
    }

    public function getMergeDetails(Request $request)
    {
        $contact_form_id = $request->input('contact_form_id',0);

        $arrDetails = array();

        $arrRes = $this->ContactFormController->getMergeContactDropdownDetails($contact_form_id);

        //$arrDetails["rawHTML"] = $rawHTML;
        return response()->json($arrRes);
    }

    public function mergeContact(Request $request)
    {   
        $arrDetails = array();
        $isMerged = $this->ContactFormController->handleMergeContact($request);

        $arrDetails["isMerged"] = $isMerged;
        return response()->json($arrDetails);        
    }

    public function deleteContact(Request $request)
    {   
        $arrDetails = array();
        $contact_form_id = $request->input('contact_form_id');

        $arrDetails = $this->ContactFormController->handleDeleteContact($contact_form_id);

        return response()->json($arrDetails); 
    }

    public function saveCustomFields(Request $request)
    {
         $validatedData = $request->validate([
            'field_name' => 'required',
            'field_type' => 'required',           
        ], [
            'field_name.required' => 'Field name is required',
            'field_type.required' => 'Field type is required',              
        ]);

        $arrSavedDet = $this->ContactFormController->saveCustomFieldsDetails($request);
        return response()->json($arrSavedDet);
    }

    public function getCustomField(Request $request)
    {
        return $this->ContactFormController->getCustomFieldList($request);
    }

    public function deleteCustomField(Request $request)
    {
        $arrDetails = array();
        $custom_field_id = $request->input('custom_field_id');

        $arrDetails = $this->ContactFormController->handleDeleteCustomField($custom_field_id);

        return response()->json($arrDetails); 
    }

    public function getCustomFieldDetails(Request $request)
    {
        $custom_field_id = $request->input('custom_field_id',0);

        $details = $this->ContactFormController->fetchCustomFieldDetails($custom_field_id);
              
        $arrDetails = array();
     
        $arrDetails["custom_field_id"] = $details->custom_field_id ?? 0; 
        $arrDetails["field_name"] = $details->field_name ?? "";
        $arrDetails["field_type"] = $details->field_type_id ?? 0; 
        
        return response()->json($arrDetails);
    }   

    public function showCustomFields(Request $request)
    {        
        $contact_form_id = $request->input('contact_form_id',0);
   
        $customFields = $this->ContactFormController->getAvailableCustomFields();

        $arrAvailableFields = array();        
        
        foreach($customFields as $objCustomFeld)
        {   
            $arrCustomFields = array();
            $custom_field_id = $objCustomFeld->custom_field_id;
            $field_name = $objCustomFeld->field_name;
            $field_type_name = $objCustomFeld->field_type_name;

            //get custom field value
            $custom_field_value = $this->ContactFormController->getCustomFieldValue($custom_field_id,$contact_form_id);
            
            $arrCustomFields["custom_field_id"] = $custom_field_id;
            $arrCustomFields["field_name"] = $field_name;
            $arrCustomFields["field_type_name"] = $field_type_name;            
            $arrCustomFields["custom_field_value"] = $custom_field_value;
            
            $arrAvailableFields[] = $arrCustomFields;
        }

        return response()->json($arrAvailableFields);
    }

    public function getContactOtherDetails(Request $request)
    {
        $arrDetails = array();

        $contact_form_id = $request->input('contact_form_id',0);
      
        //get contact merged details
        $mergedDetails = $this->ContactFormController->getContactMergedDetails($contact_form_id);

        //get contact custom field details 
        $customFieldDetails = $this->ContactFormController->getContactCustomFieldDetails($contact_form_id);

        //get contact details
        $details = $this->ContactFormController->fetchContactDetails($contact_form_id);
          
        $arrDetails["mergedDetails"] = $mergedDetails;
        $arrDetails["customFieldDetails"] = $customFieldDetails;
        $arrDetails["name"] = isset($details->name) ? $details->name : "";
        $arrDetails["additional_file_url"] = isset($details->additional_file) ? 
        url(ContactClassManager::CONTACT_ADDITIONAL_FILE_FOLDER."/".$details->additional_file) : false;

        return response()->json($arrDetails);
    }
}
