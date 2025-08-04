<?php

namespace App\CRMmanager;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactForm;
use App\Models\CustomField;
use App\Models\FieldType;
use App\Models\ContactCustomField;
use Yajra\DataTables\DataTables;

class ContactClassManager
{
    CONST CONTACT_PROFILE_IMAGE_FOLDER = "contact/profileimages";
    CONST CONTACT_ADDITIONAL_FILE_FOLDER = "contact/additionalfiles";

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function test()
    {
        dd("inn test");
    }
    
    public function saveContactDetails($request)
    {
        $profileImageName = null;
        $additionalFileName = null;
        $showAdditionFile = false;
        $additionalFileUrl = "";
        $arrRet = array();

        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $gender = $request->input('gender');
        $loggedinUserID = isset(Auth::user()->id) ? Auth::user()->id : 0;
                       
        $contact_form_id = $request->input('contact_form_id',0);

        if($contact_form_id > 0){
            //update
            $contactDetails = ContactForm::find($contact_form_id);
            $message = "Contact details updated successfully.";
            $contactDetails->updated_by = $loggedinUserID;
        }else{
            //insert
            $contactDetails = new ContactForm(); 
            $message = "Contact details saved successfully.";
            $contactDetails->created_by = $loggedinUserID;        
        }

        //check profile image is selected and upload it
        if ($request->hasFile('profile_image')) {       
            
            $imagePath = public_path(self::CONTACT_PROFILE_IMAGE_FOLDER);
            
            $profileImageName = time().'.'.request()->profile_image->getClientOriginalExtension();        
            request()->profile_image->move($imagePath, $profileImageName); 
            $contactDetails->profile_image  = $profileImageName;

            if($contact_form_id > 0)
            {
                 $previousImage = ContactForm::find($contact_form_id)->profile_image;
                //unlink previous uploaded image
                $this->unlinkFileData($imagePath,$previousImage);
            }
            
        }

        //check additional file is selected and upload it
        if ($request->hasFile('additional_file')) {    
            $showAdditionFile = true;
            $filePath = public_path(self::CONTACT_ADDITIONAL_FILE_FOLDER);
                      

            $additionalFileName = time().'.'.request()->additional_file->getClientOriginalExtension();        
            request()->additional_file->move($filePath, $additionalFileName);
            $contactDetails->additional_file = $additionalFileName; 

            $additionalFileUrl = url(ContactClassManager::CONTACT_ADDITIONAL_FILE_FOLDER."/".$additionalFileName);

             if($contact_form_id > 0)
            {
                 $previousFile = ContactForm::find($contact_form_id)->additional_file;
                 //unlink previous uploaded file
                 $this->unlinkFileData($filePath,$previousFile);
            }
        }
              
        $contactDetails->name = $name;
        $contactDetails->email = $email;
        $contactDetails->phone = $phone;
        $contactDetails->gender = $gender;
      
        $isSaved = $contactDetails->save(); 
        $lastInsertedId = $contactDetails->contact_form_id;

        if($isSaved == true)
        {   
            //insert custom fields
            $this->handleInsertCustomFields($lastInsertedId,$request);
        }        

        $arrRet["isSaved"] = $isSaved;
        $arrRet["lastInsertedId"] = $lastInsertedId;
        $arrRet["message"] = $message;
        $arrRet["showAdditionFile"] = $showAdditionFile;
        $arrRet["additional_file_url"] = $additionalFileUrl;

        return $arrRet;
    }

    public function handleInsertCustomFields($contact_form_id,$request)
    {
         //get unique custom fields
        $resCustomFields = $this->getUniqueCustomFields();
        
        //delete previously added custom fields
        if(count($resCustomFields) > 0) 
        $isDeleted = $this->deleteContactCustomField($contact_form_id);

        foreach($resCustomFields as $objCustomFields)
        {   
            $customField_name = strtolower($objCustomFields->field_type_name);
            $arrCurCustomField = $request->input($customField_name,[]);
          
             foreach($arrCurCustomField as $id => $fieldValue)
            {
              //echo "<hr> value = ".$fieldValue."| custom_field_id = ".$id;
              $arrConCustomFieldValue = array();
              $arrConCustomFieldValue["custom_field_value"] = $fieldValue;
              $arrConCustomFieldValue["custom_field_id"] = $id;
              $arrConCustomFieldValue["contact_form_id"] = $contact_form_id;

              $this->addContactCustomField($arrConCustomFieldValue);
            } 

        }
    }

    public function unlinkFileData($path,$file)
    {   
        if($file == null || $file == "") return false;

        $filePath = $path."/".$file;
        
        if (file_exists($filePath))
        return unlink($filePath);
        
    }

    public function getContactList($request)
    {   

        $name = trim($request->input('name'));
        $email = trim($request->input('email'));
        $gender = trim($request->input('gender'));
       
        $contacts = ContactForm::select('contact_form_id','name','email','phone','gender',
                                        'profile_image','is_master_contact')
                                ->where("is_merged",0);

        //apply serch filter
        if ($request->filled('name')) {
            //$contacts->where("name", $name);
            $contacts->where('name', 'LIKE', "%".$name."%");
        }
        if ($request->filled('email')) {
            $contacts->where('email', 'LIKE', "%".$email."%");
            //$contacts->where("email", $email);
        }
        if ($request->filled('gender')) {
            $contacts->where("gender", $gender);
        }

        //<a href="{{url('getemp?id='.$emp_det_id )}}">Edit</a> | 
                
        return Datatables::of($contacts)
                ->addIndexColumn()
                ->editColumn('profile_image', function($contacts) {
                    
                  $profile_image_url =  isset($contacts->profile_image) ? 
                      url(ContactClassManager::CONTACT_PROFILE_IMAGE_FOLDER."/".$contacts->profile_image) : 
                      url('images/no_image_small.png');  

                   return '<img class="rounded-sm w-18 h-18" id="imgProfile" src="'.$profile_image_url.'" alt="profile image loading...">';
                })
                ->addColumn('action', function($contacts){
                    return $this->getContactListAction($contacts);

                })
                ->rawColumns(['profile_image','action'])
                ->make(true);
    }

    public function getContactListAction($contacts)
    {
        $contact_form_id = $contacts->contact_form_id;

        $action = '<div class="inline-flex rounded-md shadow-xs">';

        $action .= '<a href="'.url('contactform?id='.$contact_form_id).'" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        Edit </a>';

        if($contacts->is_master_contact == 0)
        $action .= '<a href="javascript:;" onClick="getContact.getMergeDetails('.$contact_form_id.')" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        Merge</a>';
        
        $action .= '<a href="javascript:;" onClick="getContact.deleteContact('.$contact_form_id.')" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        Delete</a>';
        
        $action .= '<a href="javascript:;" onClick="getContact.getContactOtherDetails('.$contact_form_id.')" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        View Details</a>';

        $action .= '</div>';

        return $action;
    }

    public function fetchContactDetails($contact_form_id)
    {
        $details = ContactForm::where('contact_form_id',$contact_form_id)->first();
        return $details;
    }

    public function getMergeContactDropdownDetails($contact_form_id)
    {   
        $result = array();

         $contacts = ContactForm::select('contact_form_id','name')
                    ->where("contact_form_id","!=",$contact_form_id)
                    ->where("is_merged",0)
                    ->where("is_master_contact",0)
                    ->get();

           $rawHTML = '<select id="master_contact_form_id" name="master_contact_form_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0">Select</option>';         
           foreach($contacts as $contact)         
           {
                $rawHTML .= '<option value="'.$contact->contact_form_id.'"> '.$contact->name.' </option>';
           }    
            $rawHTML .= '</select>';

            $name = ContactForm::find($contact_form_id)->name;

            $result["rawHTML"] = $rawHTML;
            $result["name"] = $name;
          return $result;  
    }

    public function handleMergeContact($request)
    {
        
         $master_contact_form_id = $request->input('master_contact_form_id',0);
         $sel_contact_form_id = $request->input('sel_contact_form_id',0);

        //get master contact details
        $masterContactDet = $this->fetchContactDetailsForMerge($master_contact_form_id);

        //get selected contact details for merge
        $selContactDet = $this->fetchContactDetailsForMerge($sel_contact_form_id);

        //dd($masterContactDet,$selContactDet);

        $arrUpdateData = array();

        if($masterContactDet->email !== $selContactDet->email)
        {
            //email is different
            $arrUpdateData["merged_email"] = $selContactDet->email;
        }

        if($masterContactDet->phone !== $selContactDet->phone)
        {
            //phone is different
            $arrUpdateData["merged_phone"] = $selContactDet->phone;
        }
        
        $arrUpdateData["is_master_contact"] = 1;
        
        //update master contact 
        $isUpdtedMaster = ContactForm::where('contact_form_id', $master_contact_form_id)
                        ->update($arrUpdateData);
         

        //update selected contact flag as merged
        $isUpdted =  ContactForm::where('contact_form_id', $sel_contact_form_id)
                      ->update(['is_merged' => 1, 
                                'parent_contact_id' => $master_contact_form_id]);

        return ($isUpdted > 0) ? true : false;                        
       
    }

     public function fetchContactDetailsForMerge($contact_form_id)
    {
        $details = ContactForm::select('email','phone','name')
                   ->where('contact_form_id',$contact_form_id)
                   ->first();
        return $details;
    }

    public function handleDeleteContact($contact_form_id)
    {   
        $message = "";
        $arrDet = array();
              
        //get contact attachments before delete    
        $contactImage = ContactForm::find($contact_form_id)->profile_image;
        $contactFile = ContactForm::find($contact_form_id)->additional_file;

        //delete main contact
        $isDeleted = ContactForm::where('contact_form_id',$contact_form_id)->delete();

        //delete custom fields of contact
        $this->deleteContactCustomField($contact_form_id);

        if($isDeleted == true)
        {
            $message = "Record deleted successfully";

            //unlink main contact uploaded image and file
            $this->handleUnlinkContactFileData($contactImage, $contactFile);

            //delete merged contact
            $this->handleDeleteMergedContact($contact_form_id);
            
        }
       $arrDet["isDeleted"] = $isDeleted;                                 
       $arrDet["message"] = $message; 
       return $arrDet;
    }

    public function handleDeleteMergedContact($contact_form_id)
    {
         //get merged contact details
        $mergedDetails = $this->getMergedContactDetails($contact_form_id);
            
        //get merged contact attachments before delete
        $mrgContactImage = $mergedDetails->profile_image ?? "";
        $mrgContactFile = $mergedDetails->additional_file ?? "";

        //delete merged contact
        $isDeleted = ContactForm::where('parent_contact_id',$contact_form_id)->delete();

        if($isDeleted == true)
        {
            //unlink merged contact uploaded image and file
            $this->handleUnlinkContactFileData($mrgContactImage, $mrgContactFile);
        }

        return $isDeleted;
        
    }

    public function handleUnlinkContactFileData($image, $file)
    {
        $imagePath = public_path(self::CONTACT_PROFILE_IMAGE_FOLDER);
        $filePath = public_path(self::CONTACT_ADDITIONAL_FILE_FOLDER);

        //unlink contact uploaded image
         $this->unlinkFileData($imagePath,$image);

         //unlink contact uploaded file
         $this->unlinkFileData($filePath,$file);

         return true;
    }

    public function getMergedContactDetails($contact_form_id)
    {
        return ContactForm::select('profile_image','additional_file')
                   ->where('parent_contact_id',$contact_form_id)
                   ->first();
    }

    public function saveCustomFieldsDetails($request)
    {
        $arrRet = array();

        $field_name = $request->input('field_name');
        $field_type = $request->input('field_type');
        $loggedinUserID = isset(Auth::user()->id) ? Auth::user()->id : 0;
                       
        $custom_field_id = $request->input('custom_field_id',0);

        if($custom_field_id > 0){
            //update
            $customDetails = CustomField::find($custom_field_id);
            $message = "Custom field updated successfully.";
            $customDetails->updated_by = $loggedinUserID;
        }else{
            //insert
            $customDetails = new CustomField(); 
            $message = "Custom field saved successfully.";
            $customDetails->created_by = $loggedinUserID;        
        }

        $customDetails->field_name = $field_name;
        $customDetails->field_type_id = $field_type;
      
        $isSaved = $customDetails->save(); 
        $lastInsertedId = $customDetails->custom_field_id;

        $arrRet["isSaved"] = $isSaved;
        $arrRet["lastInsertedId"] = $lastInsertedId;
        $arrRet["message"] = $message;

        return $arrRet;
    }

    public function getCustomFieldList($request)
    {      
        $customFields = CustomField::select('custom_field_id','field_name','field_type.field_type_name')
                                     ->leftJoin('field_type', 'field_type.field_type_id', '=', 'custom_field.field_type_id');
                
        return Datatables::of($customFields)
                ->addIndexColumn()                
                ->addColumn('action', function($customFields){
                        $custom_field_id = $customFields->custom_field_id;
                        $action = '<a href="javascript:;" onClick="getCustomFields.getCustomFieldDetails('.$custom_field_id.')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit </a>';
    
                        $action .= '<a href="javascript:;" onClick="getCustomFields.deleteCustomField('.$custom_field_id.')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"> | Delete</a>';
    
                        return $action;
                })
                ->rawColumns(['action'])
                ->make(true);    
    }

    public function handleDeleteCustomField($custom_field_id)
    {        
        $arrDet = array();

        //delete custom field 
        $isDeleted = CustomField::where('custom_field_id',$custom_field_id)->delete();

         $arrDet["isDeleted"] = $isDeleted;                                 
         $arrDet["message"] = ($isDeleted == true) ? "Record deleted successfully" : "Error in delete or record not available"; 
         return $arrDet;
    }
    
    public function fetchCustomFieldDetails($custom_field_id)
    {
        $details = CustomField::where('custom_field_id',$custom_field_id)->first();
        return $details;
    }

     public function getFieldTypes()
     {
       return FieldType::all();                    
     }

     public function getAvailableCustomFields()
     {
         //return CustomField::select('custom_field_id','field_name','field_type_id')->get();  

          return CustomField::select('custom_field_id','field_name','field_type.field_type_name')
                    ->leftJoin('field_type', 'field_type.field_type_id', '=', 'custom_field.field_type_id')
                    ->get();

          /*
          SELECT custom_field.custom_field_id, field_name, field_type.field_type_name, 
contact_custom_field.contact_form_id, contact_custom_field.custom_field_value  

FROM `custom_field` 

LEFT JOIN field_type on custom_field.field_type_id = field_type.field_type_id 
LEFT JOIN contact_custom_field on custom_field.custom_field_id = contact_custom_field.custom_field_id 

where contact_custom_field.contact_form_id = 1
          */          
     }

     public function getUniqueCustomFields()
     {
          return CustomField::select('custom_field.field_type_id','field_type.field_type_name')
                    ->join('field_type', 'field_type.field_type_id', '=', 'custom_field.field_type_id')
                    ->groupBy('custom_field.field_type_id','field_type.field_type_name')
                    ->get();
     }

     public function addContactCustomField($data)
     {
        $isInserted = ContactCustomField::insert($data);
        return $isInserted;
     }

     public function deleteContactCustomField($contact_form_id)
     {
         $isDeleted = ContactCustomField::where('contact_form_id',$contact_form_id)->delete();
         return $isDeleted;
     }

     public function getCustomFieldValue($custom_field_id,$contact_form_id)
     {

        return ContactCustomField::where("custom_field_id", $custom_field_id)
                                    ->where("contact_form_id", $contact_form_id)
                                    ->value('custom_field_value');
     }

     public function getContactMergedDetails($contact_form_id)
     {
          return ContactForm::select('merged_email','merged_phone')
                                ->where("contact_form_id", $contact_form_id)
                                ->first();
     }

     public function getContactCustomFieldDetails($contact_form_id){

         return CustomField::select('custom_field.field_name','contact_custom_field.custom_field_value',
                                    'field_type.field_type_name','contact_custom_field.contact_form_id')
                    ->leftJoin('field_type', 'field_type.field_type_id', '=', 'custom_field.field_type_id')
                    ->leftJoin('contact_custom_field', 'contact_custom_field.custom_field_id', '=', 'custom_field.custom_field_id')
                    ->where('contact_custom_field.contact_form_id', $contact_form_id)
                    ->orderBy('custom_field.custom_field_id', 'asc')
                    ->get();
     }

     public function getContactName($contact_form_id)
     {
        return ContactForm::where("contact_form_id", $contact_form_id)->value('name');
     }
}