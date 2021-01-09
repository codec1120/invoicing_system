$(document).ready(function(){
    // Autoload functions
    getItem();

    // Events
    $("#saveBtn").click(function(){
        if ( $('#id').val() == '' ) {
            createItem();
        } else {
            updateItem();
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
        $('#item_name').val( rows[1].innerHTML );
        $('#item_price').val( rows[2].innerHTML );

        // Modify Save Button Text
        $('#saveBtn').text('Update');
    });

    // Delet row on edit button click
    $(document).on("click", ".delete", function(){	
        // Get Row clicked
        const rows = $(this).parents("tr").find("td:not(:last-child)");	

        // Assigned Row Value per index
        deleteItem( rows[0].innerHTML  );
    });

    /* Functions */

    function getItem () {
        $.ajax({
            type: "GET",
            url: `${base_url}item/getItems`,
            dataType: "json",
            success: function( response ){
                const { message, data } = response;
                
                let rows = '';
                
                // Removed Rows
                $('#itemsTbl tbody tr').remove();
              
                for ( let x = 0; x <  data.length; x++) {  
                   rows += `<tr > 
                        <td scope="col">${data[x]['item_id'].toString().padStart(5, "0")}</td>
                        <td scope="col">${data[x]['item_name']}</td>
                        <td scope="col">${data[x]['item_price']}</td>
                        <td scope="col">
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">create</i></a>
                            <a class="delete" title="Add" data-toggle="tooltip"><i class="material-icons">delete</i></a>
                        </td>
                   </tr>`; 
                }


                // Append Rows
                $('#itemsTbl tbody').append( rows );
            }
        });
    }

    function createItem () {
        
        let requiredFields = [ 'item_name', 'item_price' ];

        // Check required Fields
        for ( let i = 0; i < requiredFields.length; i++ ) {
            if ( !$( `#${requiredFields[i]}` ).val() ) {
                return toastr.error(`${requiredFields[i]} is required.`,'System Warning')
            }
        }

        let formData = {
            item_name  : $('#item_name').val(),
            item_price   : $('#item_price').val(),
        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}item/createItem`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                if ( status) {
                     // Reset Fields
                        reset();
                        
                        // Reload
                        getItem();
                        
                        return toastr.info( message )
                } else {
                    return toastr.error( message )
                }
               
            }
        });
    }

    function updateItem () {
        let formData = {
            item_id          : $('#id').val(),
            updatedData : {
                item_name  : $('#item_name').val(),
                item_price   : $('#item_price').val()
            }
        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}item/updateItem`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                if ( status ) {
                     // Reset Fields
                     reset();

                     // Reload
                    getItem();
                    
                    return toastr.info( message )
                } else {
                    return toastr.error( message )
                }
               
            }
        });
    }

    function deleteItem ( item_id ) {
        $.ajax({
            type: "POSt",
            data: { item_id: item_id },
            url: `${base_url}item/removeItem`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                // Reload
                getItem();
               
                return toastr.error( message )
            }
        });
    }

    function reset () {
        // Reset Fields
        $('#id').val('');
        $('#item_name').val('');
        $('#item_price').val('');
        $('#saveBtn').text('Save');
    }

});