var getCustomFields = {
    dtTable: null,
};


getCustomFields.save = function () {
    try {

         //hide error and success messages disble button
         $('.successdiv').fadeOut(); 
         $('.errordiv').fadeOut();          
         $("#btnAdd").prop("disabled", true);
         $("#btnAdd").addClass("cursor-not-allowed");

         //show loder
         $('.clsSubmitLoader').fadeIn(); 

         var field_name = $("#field_name").val();
         var field_type = $("#field_type").val();
         var custom_field_id = $("#custom_field_id").val();
        
        var formData = new FormData();
        formData.append('field_name', field_name);
        formData.append('field_type', field_type);
        formData.append('custom_field_id', custom_field_id);

        var ajaxURL = base_url.app_url + '/savecustomfields';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            url: ajaxURL,
            type: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (result) {
                
                var isSaved = result.isSaved;
                
                if(isSaved == true)
                {
                    //saved succeesfully
                    //alert(result.message);
                                        
                    $('#addCustomFieldsForm')[0].reset();
                    $('#cntSuccessMsg').html('');
                    $('#cntSuccessMsg').append(result.message); 
                    $('.successdiv').fadeIn(); 
                    $("#btnAdd").html('Add');

                    //$("#custom_field_id").val(result.lastInsertedId);       
                    getCustomFields.dtTable.ajax.reload();
                     $(".successdiv").delay(3000).fadeOut("slow"); 

                }else{
                    //error in save/update 
                    alert("Error in database operation");
                    //message = 'Error in database operation';
                }
                   
                $("#btnAdd").prop("disabled", false);
                 $("#btnAdd").removeClass("cursor-not-allowed");
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
                  $("#btnAdd").prop("disabled", false);     
                  $("#btnAdd").removeClass("cursor-not-allowed");                  
                   //hide loader
                   $('.clsSubmitLoader').fadeOut();   
            }
        });


    } catch (error) {
        console.log('Error in add custom fields details ' + error);   
        $("#btnAdd").prop("disabled", false);     
        $("#btnAdd").removeClass("cursor-not-allowed");                  
        //hide loader
        $('.clsSubmitLoader').fadeOut();       
    }
}


getCustomFields.getCustomFieldList = function () {
    try {

        var ajaxURL = base_url.app_url + '/getcustomfield';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        getCustomFields.dtTable = $('.cf-data-table').DataTable({
            processing: true,
            serverSide: true,
           //destroy: true,
            //data: formData,
            //ajax: ajaxURL,
            ajax: {
                'url': ajaxURL,
                'data': {}
            },          
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'field_name', name: 'field_name', width: '20%' },
                { data: 'field_type_name', name: 'field_type.field_type_name', width: '15%'},
                { data: 'action', name: 'action', orderable: false, searchable: false, width: '20%' },
            ]
        });
       
    } catch (error) {
        console.log('Error in get custom field list ' + error);
    }
}


getCustomFields.deleteCustomField = function (custom_field_id){

    //if (confirm('Are you sure to delete record?')) {    
    //}

    if (custom_field_id <= 0) return;

    try {

        if (confirm('Are you sure to delete record permanently?')) { 
            
        var ajaxURL = base_url.app_url + '/deletecustomfield';
        
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $.ajax({
            url: ajaxURL, 
            type: "POST",
            dataType: "json",     
            //processData: false,
            //contentType: false,       
            data: {'custom_field_id':custom_field_id},
            success: function (result) {
             
                var isDeleted = result.isDeleted;

                if(isDeleted == true)
                {
                    //deleted succeesfully
                    alert(result.message);
                    getCustomFields.dtTable.ajax.reload();

                }else{
                    //error in delete
                    alert("Error in database operation");
                }

             },
             error: function (err) {                
                console.log(err);
             }
          });

        }

    } catch (error) {
        console.log('Error in delete custom field'+error);
    }    
}


getCustomFields.getCustomFieldDetails = function (custom_field_id){
    try {
        
        if(custom_field_id <= 0) return;
        
        var formData = new FormData();
        formData.append('custom_field_id',custom_field_id);

        var ajaxURL = base_url.app_url + '/getcustomfielddet';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $.ajax({
            url: ajaxURL, 
            type: "POST",
            dataType: "json",     
            processData: false,
            contentType: false,       
            data: formData,
            success: function (result) {
           
                var field_name = result.field_name;          
                var field_type = result.field_type;          
                var custom_field_id = result.custom_field_id;          

                //BIND VALUES TO INPUT FIELD
                $("#field_name").val(field_name);
                $("#field_type").val(field_type);
                $("#custom_field_id").val(result.custom_field_id);  
                
                 $("#btnAdd").html('Update');

                $('html, body').animate({
                    scrollTop: $("#divScrollTop").offset().top
                }, 1000);

             },
             error: function (err) {                
                console.log(err);
             }
          });


    } catch (error) {
        console.log('Error in get custom field Details '+error);
    }
}