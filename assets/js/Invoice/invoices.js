$(document).ready(function(){
    // Global Variable
    itemPrices = [];
    invoiceArray = [];
    editSelectedItem = [];

    // Autoload functions
    loadAllImportantFunction();

    // Events
    $("#saveBtn").click(function(){
        if ( $('#id').val() == '' ) {
            createInvoice();
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
        
        const selectedRow = invoiceArray.filter( item => item.invoice_id == parseInt( rows[0].innerHTML ))[0];
        
        itemPrices = selectedRow['selectedItemsPrice'].split(',');

        // Assigned Row Value per index
        $('#id').val( selectedRow['invoice_id'] );
        $('#customerSelect').val( selectedRow['user_id'] );
        $('#itemSelect').selectpicker();
        $('#itemSelect').selectpicker('val',  selectedRow['selectedItems'].split(','));
        $('#item_price').val( selectedRow['selectedItemsPrice'].split(',') );
        $('#total').val( selectedRow['total'] );
        $('#amount_paid').val( selectedRow['amount_paid'] );
        $('#transactionId').val( selectedRow['transaction_id'] );
        // Modify Save Button Text
        $('#saveBtn').text('Update');
    });

    // Delete row on edit button click
    $(document).on("click", ".delete", function(){	
        // Get Row clicked
        const rows = $(this).parents("tr").find("td:not(:last-child)");	

        // Assigned Row Value per index
        deleteItem( rows[0].innerHTML  );
    });
    

    // Print row on edit button click
    $(document).on("click", ".print", function(){	
        // Get Row clicked
        const rows = $(this).parents("tr").find("td:not(:last-child)");	

        const selectedRow = invoiceArray.filter( item => item.invoice_id == parseInt( rows[0].innerHTML ))[0];
        
        // Print Invoice
        tableRow = '';
        selectedItemName = selectedRow['selectedItemName'].split(',');

        for( let i = 0; i < selectedItemName.length; i++) {
            selectedItemPrices = selectedRow['selectedItemsPrice'].split(',');

            tableRow += `<tr>
                            <td><b>${selectedItemName[i]}</b></td>
                            <td>${selectedItemPrices[i]}</td>
                        </tr>`;
        }

        // Add Total Rows
        tableRow += `<tr>
                        <td>Total</td>
                        <td><b>${selectedRow['total']}</b></td>
                    </tr>`;

        $('body').append(`
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Invoice Transactions History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        Customer: <b> ${selectedRow['customer']} </b>
                    </div>
                    <div>
                        Amount Paid: <b> ${selectedRow['amount_paid']} </b>
                    </div>
                    <div>
                        Transaction Date: <b> ${selectedRow['transaction_date']} </b>
                    </div>
                    </br>
                    <div>
                        <table class="table" id="invoicesTbl">
                            <tbody>
                               ${tableRow}
                            </tbody>
                        </table> 
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>`);
    $('#exampleModalCenter').modal('show');
        console.log( selectedRow );
        
    });
    // SelectPick Event
    selectedItemPrice = [];
 
    $('#itemSelect').on("changed.bs.select", function ( e, clickedIndex, isSelected ) {
        if ( clickedIndex != null ) {
            if ( isSelected ) {
                
                if ( $('#total').val( ) == 0 ) {
                    selectedItemPrice = [];
                }

                selectedItemPrice.push( itemPrices[ clickedIndex ] );
                
                $('#item_price').val( selectedItemPrice.join(',') );
    
                // Compute Total Selected Item Price
                total = selectedItemPrice.reduce(function(a, b){
                            return parseFloat( a ) + parseFloat( b );
                        }, 0);
            } else {
                deselectItemPrices = itemPrices;

                selectedIndex = deselectItemPrices.length <= 0? 0 : clickedIndex > ( deselectItemPrices.length - 1) ? clickedIndex - 1: clickedIndex; 

                deselectItemPrices.splice( selectedIndex, 1);

                $('#item_price').val( deselectItemPrices.join(',') );
                
                // Compute Total Selected Item Price
                total =  deselectItemPrices.reduce(function(a, b){
                            return parseFloat( a ) + parseFloat( b );
                        }, 0);

                if ( deselectItemPrices.length == 0) {
                    getItems();
                }           
            }
           
            
            $('#total').val( total );   
        }
    });


    /* Functions */

    function getInvoices () {
        $.ajax({
            type: "GET",
            url: `${base_url}invoice/getInvoices`,
            dataType: "json",
            success: function( response ){
                const { message, data } = response;

                invoiceArray = data;
                
                let rows = '';
                
                // Removed Rows
                $('#invoicesTbl tbody tr').remove();
              
                for ( let x = 0; x <  data.length; x++) {  
                   rows += `<tr > 
                        <td scope="col">${data[x]['invoice_id'].toString().padStart(5, "0")}</td>
                        <td scope="col">${data[x]['customer']}</td>
                        <td scope="col">${data[x]['amount_paid']}</td>
                        <td scope="col">${data[x]['total']}</td>
                        <td scope="col">
                            <a class="print" title="Print Invoice" data-toggle="tooltip"><i class="material-icons">preview</i></a>
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">create</i></a>
                            <a class="delete" title="Add" data-toggle="tooltip"><i class="material-icons">delete</i></a>
                        </td>
                   </tr>`; 
                }


                // Append Rows
                $('#invoicesTbl tbody').append( rows );
            }
        });
    }

    function getUsers () {
        $.ajax({
            type: "GET",
            url: `${base_url}user/getUsers`,
            dataType: "json",
            success: function( response ){
                const { message, data } = response;

                for ( let i = 0; i < data.length; i++) {
                    $("#customerSelect").append(new Option( `${data[i]['first_name']} ${data[i]['last_name']}`,  data[i]['user_id']));
                }
            }
        });
    }

    function getItems () {
        $.ajax({
            type: "GET",
            url: `${base_url}item/getItems`,
            dataType: "json",
            success: function( response ){
                const { message, data } = response;

                $("#itemSelect").find('option').remove();

                for ( let i = 0; i < data.length; i++) {
                    itemPrices.push( data[i]['item_price'] );
                    $("#itemSelect").append( `<option value=${data[i]['item_id']}>${data[i]['item_name']}</option>` );
                }

                $("#itemSelect").selectpicker('refresh');
            }
        });
    }

    function createInvoice () {
        
        let requiredFields = [ 'customerSelect', 'itemSelect', 'amount_paid' ];
        let requiredFieldsText = [ 'SelectCustomer', 'Select Item', 'Amount Paid' ];

        // Check required Fields
        for ( let i = 0; i < requiredFields.length; i++ ) {
            if ( requiredFields[i] == 'itemSelect' &&  !$('.selectpicker').val() ) {
                return toastr.error(`Please Select Item.`,'System Warning')
            }

            if ( requiredFields[i] == 'customerSelect' &&  $('#customerSelect option:selected').val()  == 'Select Customer' ) {
                return toastr.error(`Please Select Customer.`,'System Warning')
            }

            if (  requiredFields[i] == 'amount_paid' &&  $( `#${requiredFields[i]}` ).val() < $( `#total` ).val() ) {
                return toastr.error(`Amount Paid must not be lesser than total.`,'System Warning')
            }

            if ( !$( `#${requiredFields[i]}` ).val() ) {
                return toastr.error(`${requiredFieldsText[i]} is required.`,'System Warning')
            }
        }

        let invoices = [];

        for ( let x = 0; x < $('.selectpicker').val().length; x++) {
            invoices.push({
                user_id: $('#customerSelect option:selected').val(),
                item_id: $('.selectpicker').val()[x]
            });
        }
        
        let formData = {
            invoices: invoices ,
            amount_paid: $('#amount_paid').val(),
            user_id: $('#customerSelect option:selected').val()
        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}invoice/createInvoice`,
            dataType: "json",
            success: function( response ){
                const { message } = response;
                
                // Reset Fields
                reset();
                
                // Reload
                getInvoices();
                
                return toastr.info( message )
            }
        });
    }

    function updateItem () {

        let requiredFields = [ 'customerSelect', 'itemSelect', 'amount_paid' ];
        let requiredFieldsText = [ 'SelectCustomer', 'Select Item', 'Amount Paid' ];

        // Check required Fields
        for ( let i = 0; i < requiredFields.length; i++ ) {
            if ( requiredFields[i] == 'itemSelect' &&  !$('.selectpicker').val() ) {
                return toastr.error(`Please Select Item.`,'System Warning')
            }

            if ( requiredFields[i] == 'customerSelect' &&  $('#customerSelect option:selected').val()  == 'Select Customer' ) {
                return toastr.error(`Please Select Customer.`,'System Warning')
            }

            if (  requiredFields[i] == 'amount_paid' &&  $( `#${requiredFields[i]}` ).val() < $( `#total` ).val() ) {
                return toastr.error(`Amount Paid must not be lesser than total.`,'System Warning')
            }

            if ( !$( `#${requiredFields[i]}` ).val() ) {
                return toastr.error(`${requiredFieldsText[i]} is required.`,'System Warning')
            }
        }

        let invoices = [];

        for ( let x = 0; x < $('.selectpicker').val().length; x++) {
            invoices.push({
                user_id: $('#customerSelect option:selected').val(),
                item_id: $('.selectpicker').val()[x]
            });
        }
        
        let formData = {
            invoices: JSON.stringify( invoices ),
            amount_paid: $('#amount_paid').val(),
            invoice_id: $('#id').val(),
            transaction_id: $('#transactionId').val()
        };

        $.ajax({
            type: "POST",
            data: formData,
            url: `${base_url}invoice/updateInvoice`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                if ( status ) {
                     // Reset Fields
                     reset();

                     // Reload
                     loadAllImportantFunction();
                    
                    return toastr.info( message )
                } else {
                    return toastr.error( message )
                }
               
            }
        });
    }

    function deleteItem ( invoice_id ) {
        $.ajax({
            type: "POSt",
            data: { invoice_id: parseInt( invoice_id ) },
            url: `${base_url}invoice/removeInvoice`,
            dataType: "json",
            success: function( response ){
                const { message, status } = response;
                
                // Reload
                loadAllImportantFunction();
               
                return toastr.error( message )
            }
        });
    }

    function reset () {
        // Reset Fields
        $('#id').val('');
        $('#transactionId').val('');
        $('#item_name').val('');
        $('#total').val('');
        $('#item_price').val('');
        $('#amount_paid').val('');
        $('#customerSelect').val('');
        $(".selectpicker").val('default');
        $(".selectpicker").selectpicker("refresh");
        $('#saveBtn').text('Save');
    }

    function loadAllImportantFunction () {
        getUsers();
        getItems();
        getInvoices();
    }

});