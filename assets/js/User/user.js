$(document).ready(function(){
   
    // Autoload functions
    getUsers();

    // Events
    $("#saveBtn").click(function(){
        if ( $('#id').val() == '' ) {
            creatUser();
        } else {
            updateUser();
        }
        

    });

    $('#resetBtn').click( function () {
        reset();
    });

    // Edit row on edit button click
	$(document).on("click", ".edit", function(){	
        // Get Row clicked
        const rows = $(this).parents("tr").find("td:not(:last-child)");	

        // Assigned Row Value per index
        $('#id').val( rows[0].innerHTML );
        $('#first_name').val( rows[1].innerHTML );
        $('#last_name').val( rows[2].innerHTML );
        $('#address').val( rows[3].innerHTML );
        $('#email').val( rows[4].innerHTML );
        $('#password').val( rows[5].innerHTML );

        // Modify Save Button Text
        $('#saveBtn').text('Update');
    });

    // Delet row on edit button click
    $(document).on("click", ".delete", function(){	
        // Get Row clicked
        const rows = $(this).parents("tr").find("td:not(:last-child)");	

        // Assigned Row Value per index
        deleteUser( rows[0].innerHTML  );
    });

    $("#customerChk").change(function() {
        if(this.checked) {
           $('#email').css('display', 'none');
           $('#password').css('display', 'none');
        } else {
            $('#email').css('display', 'block');
            $('#password').css('display', 'block');
        }
    });

    /* Functions */

    function getUsers () {
        $.ajax({
            type: "GET",
            url: `${base_url}user/getUsers`,
            dataType: "json",
            success: function( response ){
                const { message, data } = response;
                
                let rows = '';
                
                // Removed Rows
                $('#usersTbl tbody tr').remove();
              
                for ( let x = 0; x <  data.length; x++) {
                   rows += `<tr> 
                        <td scope="col">${data[x]['user_id'].toString().padStart(5, "0")}</td>
                        <td scope="col">${data[x]['first_name']}</td>
                        <td scope="col">${data[x]['last_name']}</td>
                        <td scope="col">${data[x]['address']}</td>
                        <td scope="col">${data[x]['email']}</td>
                        <td scope="col" style="display:none">${data[x]['password']}</td>
                        <td scope="col">
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">create</i></a>
                            <a class="delete" title="Add" data-toggle="tooltip"><i class="material-icons">delete</i></a>
                        </td>
                   </tr>`; 
                }

                // Append Rows
                $('#usersTbl tbody').append( rows );
            }
        });
    }

    function creatUser () {
        let isCustomer = $("#customerChk").prop('checked');

        let requiredFields = isCustomer ? [ 'first_name', 'last_name' ] : [ 'first_name', 'last_name', 'email', 'password'];

        // Check required Fields
        for ( let i = 0; i < requiredFields.length; i++ ) {
            if ( !$( `#${requiredFields[i]}` ).val() ) {
                return toastr.error(`${requiredFields[i]} is required.`,'System Warning')
            }
        }

        let formData = {
            first_name  : $('#first_name').val(),
            last_name   : $('#last_name').val(),
            address     : $('#address').val(),
            email       : $('#email').val(),
            password    : $('#password').val(),
            isCustomer  : isCustomer
        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}user/createUser`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                if ( status ) {
                      // Reset Fields
                    reset();
                    
                    // Reload
                    getUsers();
                    
                    return toastr.info( message )
                } else {
                    return toastr.error( message )
                }
              
            }
        });
    }

    function updateUser () {
        let isCustomer = $("#customerChk").prop('checked');

        let formData = {
            user_id          : $('#id').val(),
            isCustomer      : isCustomer,
            updatedData : {
                first_name  : $('#first_name').val(),
                last_name   : $('#last_name').val(),
                address     : $('#address').val(),
                email       : $('#email').val(),
                password    : $('#password').val(),
            }

        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}user/updateUser`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                if ( status ) {
                    // Reset Fields
                    reset();
                    
                    // Reload
                    getUsers();
                    
                    return toastr.info( message )
                } else {
                    return toastr.error( message )
                }
               
            }
        });
    }

    function deleteUser ( user_id ) {
        $.ajax({
            type: "POSt",
            data: { user_id: user_id },
            url: `${base_url}user/archiveUser`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;

                // Reload
                getUsers();
                console.log(  message)
                return toastr.error( message )
               
            }
        });
    }

    function reset () {
        // Reset Fields
        $('#id').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#address').val('');
        $('#email').val('');
        $('#password').val('');
        $('#saveBtn').text('Save');
    }

});