var addContact = {};

addContact.save = function (){
    try {
        
         //hide error and success messages disble button
         $('.successdiv').fadeOut(); 
         $('.errordiv').fadeOut();          
         $("#btnSubmit").prop("disabled", true);
         $("#btnSubmit").addClass("cursor-not-allowed");

         //show loder
         $('.clsSubmitLoader').fadeIn(); 
               
        /*var contact_form_id = $("#contact_form_id").val(); 
        var name = $("#name").val();
        var email = $("#email").val();
        var phone = $("#phone").val();        
                       
        var gender = $('input[name="gender"]:checked').val() || "";
        var profile_image = $('#profile_image').prop("files")[0] || "";
        var additional_file = $('#additional_file').prop("files")[0] || "";        
                
        var formData = new FormData();
        formData.append('contact_form_id',contact_form_id);
        formData.append('name',name);
        formData.append('email',email);
        formData.append('phone',phone);
        formData.append('gender',gender);
        formData.append('profile_image',profile_image);
        formData.append('additional_file',additional_file);*/
            
        
        /*if(profile_image !== undefined)
        formData.append('profile_image',profile_image); //add only if image is selected

        if(additional_file !== undefined)
        formData.append('additional_file',additional_file); //add only if file is selected*/
          
       var formData = new FormData($('#addContactForm')[0]);
        

        var ajaxURL = base_url.app_url + '/savecontact';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $.ajax({
            url: ajaxURL, 
            type: "POST",
            dataType: "json",     
            processData: false,
            contentType: false,       
            data: formData,
            success: function (result) {
                
               // console.log(result);

                var isSaved = result.isSaved;
                var message;

                if(isSaved == true)
                {
                    //saved succeesfully
                    //alert(result.message);
                                        
                    //$('#addContactForm')[0].reset();
                    $('#cntSuccessMsg').html('');
                    $('#cntSuccessMsg').append(result.message); 
                    $('.successdiv').fadeIn(); 

                    $("#contact_form_id").val(result.lastInsertedId);       
                    
                    if(result.showAdditionFile == true)
                    {
                        //bind and show additional file link
                        $("#linkFile").attr("href", result.additional_file_url);
                        $("#divLinkFile").fadeIn();
                    }

                    $(".successdiv").delay(3000).fadeOut("slow"); 

                }else{
                    //error in save/update 
                    alert("Error in database operation");
                    //message = 'Error in database operation';
                }

                 $("#btnSubmit").prop("disabled", false);
                 $("#btnSubmit").removeClass("cursor-not-allowed");

                 //hide loader
                 $('.clsSubmitLoader').fadeOut(); 

             },
             error: function (err) {  
                console.log(err);
                if(err.status == 422){
                    $('#validation-errors').html('');
                                 
                    $.each(err.responseJSON.errors, function(key,value) {
                        $('#validation-errors').append(`<li>${value}</li>`);                    
                    }); 

                    $('.errordiv').fadeIn();                    
                }

                  $("#btnSubmit").prop("disabled", false);      
                  $("#btnSubmit").removeClass("cursor-not-allowed");
                 
                   //hide loader
                   $('.clsSubmitLoader').fadeOut(); 
             }
          });

       

    } catch (error) {
        console.log('Error in save '+error);
        $("#btnSubmit").prop("disabled", false);
         $("#btnSubmit").removeClass("cursor-not-allowed");
          //hide loader
         $('.clsSubmitLoader').fadeOut(); 
    }
}

addContact.getContactDetails = function (){
    try {
        
        var contact_form_id = $("#contact_form_id").val();

        if(contact_form_id <= 0) return;
        
        var formData = new FormData();
        formData.append('contact_form_id',contact_form_id);

         $("#imgProfile").attr("src", "");

        var ajaxURL = base_url.app_url + '/getcontactdet';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $.ajax({
            url: ajaxURL, 
            type: "POST",
            dataType: "json",     
            processData: false,
            contentType: false,       
            data: formData,
            success: function (result) {
           
                var name = result.name;
                var email = result.email;
                var phone = result.phone;                
                var gender = result.gender;
                var profile_image_url = result.profile_image_url;
                var additional_file_url = result.additional_file_url;

                //BIND VALUES TO INPUT FIELD
                $("#name").val(name);
                $("#email").val(email);
                $("#phone").val(phone);
                $( "#"+gender).prop( "checked", true );
                $("#imgProfile").attr("src", profile_image_url);


                if(additional_file_url !== false) 
                {
                    //bind and show additional file link
                    $("#linkFile").attr("href", additional_file_url);
                    $("#divLinkFile").fadeIn();
                }
             },
             error: function (err) {                
                console.log(err);
             }
          });


    } catch (error) {
        console.log('Error in get Contact Details '+error);
    }
}



addContact.showCustomFields = function (contact_form_id){
    try {
      
        var formData = new FormData();
        formData.append('contact_form_id',contact_form_id);

        var ajaxURL = base_url.app_url + '/showcustomfields';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $.ajax({
            url: ajaxURL, 
            type: "POST",
            dataType: "json",     
            processData: false,
            contentType: false,       
            data: formData,
            success: function (result) {
                
                //console.log(result);
                
                $.each(result, function(key,value) {
                
                //console.log("key "+key+" value "+value.field_type_name);

                    if(value.field_type_name == "Textarea")
                    {   
                        //get textarea html schema
                        var divCustomFieldSchema = $("#divCustomFieldTextareaSchema").html();
                    }else{
                        //get other input schema ie.text, email, tel etc...
                        var divCustomFieldSchema = $("#divCustomFieldSchema").html();
                    }

                    // Convert to lowercase
                    var field_type_name = value.field_type_name.toLowerCase();

                    var custom_field_id = value.custom_field_id;
                    var custom_field_value = value.custom_field_value;
                     
                     divCustomFieldSchema = divCustomFieldSchema.replaceAll('{#field_type_name}',field_type_name);
                     divCustomFieldSchema = divCustomFieldSchema.replaceAll('{#id}',custom_field_id);
                     divCustomFieldSchema = divCustomFieldSchema.replaceAll('{#field_name}',value.field_name);   

                    //bind field html 
                    $("#divCustomFields").append(divCustomFieldSchema);

                    //bind field value
                    $("#"+field_type_name+"_"+custom_field_id).val(custom_field_value);

                }); 
                
             },
             error: function (err) {                
                console.log(err);
             }
          });


    } catch (error) {
        console.log('Error in show custom fields '+error);
    }
}


addContact.readURL = function (input){
    try {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#imgProfile').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
        
    } catch (error) {
        console.log('Error in read image URL '+error);
    }
}


