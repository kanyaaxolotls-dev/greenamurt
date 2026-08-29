 $(function () {

    $("body").on("click", "#frm_earning_settings #addNewButton", function (e) {

        e.preventDefault();

        validateAddressBookForm();

    });
 });	
 function validateAddressBookForm() {

    if (!$('#frm_earning_settings').validate({

        highlight: true,

        validableStyle: 'validate',

        bubble: true

    })) {

        return false;

    }
	//$("#addNewButton").prop("disabled", true);

   // $("#addNewButton span").text(" Saving..");
	$('#frm_earning_settings').submit();
	//alert("PPP");
	
 }
