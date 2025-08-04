var getContact = {
    dtTable: null,
};


getContact.mergeContact = function () {
    try {

        var master_contact_form_id = $("#master_contact_form_id").val();
        var sel_contact_form_id = $("#sel_contact_form_id").val();

        if (master_contact_form_id <= 0) return;


        var formData = new FormData();
        formData.append('master_contact_form_id', master_contact_form_id);
        formData.append('sel_contact_form_id', sel_contact_form_id);

        var ajaxURL = base_url.app_url + '/mergecontact';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            url: ajaxURL,
            type: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (result) {

                /*const $modalElement = $('#small-modal')[0]; // Get the raw DOM element
                const modal = new Modal($modalElement); // Re-initialize or get existing instance
                modal.hide();*/

                if (result.isMerged == true) {
                    $("#btnMerge").prop("disabled", true);
                    $("#btnMerge").addClass("btnDisabled");

                    $("#mrgInfoMsg").text("Record merged successfully");
                    $(".mrgInfodiv").fadeIn();

                    getContact.dtTable.ajax.reload();
                } else {
                    $("#mrgInfoMsg").text("Error in merge record");
                    $(".mrgInfodiv").fadeIn();
                }


            },
            error: function (err) {
                console.log(err);
            }
        });


    } catch (error) {
        console.log('Error in merge contact details ' + error);
        $("#btnMerge").prop("disabled", false);
        $("#btnMerge").removeClass("btnDisabled");
        $(".mrgInfodiv").hide();
    }
}

getContact.getMergeDetails = function (contact_form_id) {
    try {

        if (contact_form_id <= 0) return;

        $("#btnMerge").prop("disabled", false);
        $("#btnMerge").removeClass("btnDisabled");
        $(".mrgInfodiv").hide();

        var formData = new FormData();
        formData.append('contact_form_id', contact_form_id);

        $("#sel_contact_form_id").val(contact_form_id);

        var ajaxURL = base_url.app_url + '/getmergedetails';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            url: ajaxURL,
            type: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (result) {

                $("#divMrgContactDropDwn").html(result.rawHTML);
                $("#selContactName").text(result.name);

                const $modalElement = $('#small-modal')[0]; // Get the raw DOM element
                const modal = new Modal($modalElement); // Re-initialize or get existing instance
                modal.show();
            },
            error: function (err) {
                console.log(err);
            }
        });


    } catch (error) {
        console.log('Error in get merge details ' + error);
    }
}

getContact.deleteContact = function (contact_form_id) {

    //if (confirm('Are you sure to delete record?')) {    
    //}

    if (contact_form_id <= 0) return;

    try {

        if (confirm('Are you sure to delete record permanently?')) {

            var ajaxURL = base_url.app_url + '/deletecontact';

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                url: ajaxURL,
                type: "POST",
                dataType: "json",
                //processData: false,
                //contentType: false,       
                data: { 'contact_form_id': contact_form_id },
                success: function (result) {

                    var isDeleted = result.isDeleted;

                    if (isDeleted == true) {
                        //deleted succeesfully
                        alert(result.message);
                        getContact.dtTable.ajax.reload();

                    } else {
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
        console.log('Error in delete contact' + error);
    }

}

getContact.closeDetailModal = function () {
    try {
        const $modalElement = $('#detail-modal')[0]; // Get the raw DOM element
        const modal = new Modal($modalElement); // Re-initialize or get existing instance
        modal.hide();

    } catch (error) {
        console.log('Error in close detail modal ' + error);
    }
}
getContact.closeModal = function () {
    try {
        const $modalElement = $('#small-modal')[0]; // Get the raw DOM element
        const modal = new Modal($modalElement); // Re-initialize or get existing instance
        modal.hide();

    } catch (error) {
        console.log('Error in close merge modal ' + error);
    }
}


getContact.getContactOtherDetails = function (contact_form_id) {
    try {

        if (contact_form_id <= 0) return;

        //set blank
        $("#spanMergedEmail").text("");
        $("#spanMergedPhone").text("");
        $("#spanContactName").text("");
        $("#divCustomFieldData").html('');
        $("#linkFile").attr("href", "#");

        var formData = new FormData();
        formData.append('contact_form_id', contact_form_id);

        var ajaxURL = base_url.app_url + '/getcontactotherdetails';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            url: ajaxURL,
            type: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (result) {

                var mergedDetails = result.mergedDetails;
                var customFieldDetails = result.customFieldDetails;
                var additional_file_url = result.additional_file_url;
               
                //bind selected contact name
                $("#spanContactName").text(result.name);

                //bind selected contact merged data
                var merged_email = mergedDetails.merged_email || "NA";
                var merged_phone = mergedDetails.merged_phone || "NA";
                $("#spanMergedEmail").text(merged_email);
                $("#spanMergedPhone").text(merged_phone);

                 if(additional_file_url !== false) 
                {   
                    //bind additional file link
                    $("#linkFile").attr("href", additional_file_url);
                    $("#linkFile").text("Click here to check file");
                }else{
                    $("#linkFile").text("NA");
                }
                             
                //bind selected contact custom field data
                if (customFieldDetails.length > 0) {
                    $.each(customFieldDetails, function (key, value) {

                        var field_name = value.field_name || "-";
                        var custom_field_value = value.custom_field_value || "-";

                        var customFieldHTML = `<div class="flex items-center space-x-2">
                                <span class="text-blue-500">${field_name}:</span>
                                <span>${custom_field_value}</span>
                            </div>`;

                        $("#divCustomFieldData").append(customFieldHTML);
                    });
                } else {
                    //show not available
                    $("#divCustomFieldData").html('<span class="text-blue-500 font-bold">Custom field data not available</span>');
                }

                //show modal
                const $modalElement = $('#detail-modal')[0]; // Get the raw DOM element
                const modal = new Modal($modalElement); // Re-initialize or get existing instance
                modal.show();
            },
            error: function (err) {
                console.log(err);
            }
        });


    } catch (error) {
        console.log('Error in get other details of contact' + error);
    }
}

getContact.getContactList = function () {
    try {
        var name = $("#name").val();
        var email = $("#email").val();
        var gender = $("#gender").val();

        //console.log(name, email, gender);

        var ajaxURL = base_url.app_url + '/getcontact';

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        //if (getContact.dtTable === null) {
        //new datatable initialization
        getContact.dtTable = $('.contact-data-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            //data: formData,
            //ajax: ajaxURL,
            ajax: {
                'url': ajaxURL,
                'data': { name: name, email: email, gender: gender }
            },
            /*aoColumnDefs: [
            {'bSortable': false, 'aTargets': [0]},
            {'bSearchable': false, 'aTargets': [0]}
            ],*/
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'name', name: 'name', width: '15%' },
                { data: 'email', name: 'email', width: '20%' },
                { data: 'phone', name: 'phone', width: '15%' },
                { data: 'gender', name: 'gender', width: '5%' },
                { data: 'profile_image', name: 'profile_image', orderable: false, searchable: false, width: '15%' },
                { data: 'action', name: 'action', orderable: false, searchable: false, width: '25%' },
            ]
        });
        /*} else {
            // datatable initializatized reload it
            getContact.dtTable.ajax.reload();
        }*/

    } catch (error) {
        console.log('Error in get contact list ' + error);
    }
}