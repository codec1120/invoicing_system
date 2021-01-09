$(document).ready(function(){ 
    getSales();


    $("#datepickerYear").datepicker({
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years"
    });

    $("#datepickerMonth").datepicker({
        format: "mm",
        viewMode: "months", 
        minViewMode: "months"
    });

    $("#datepickerDay").datepicker({
        format: "d",
        viewMode: "day", 
        minViewMode: "day"
    });


    $("#viewBtn").click(function(){
        getSales( $('#datepickerYear').val(), $('#datepickerMonth').val(), $('#datepickerDay').val()  );
    });

    function getSales ( year, month, day) {
        $.ajax({
            type: "GET",
            url: `${base_url}dashboard/getSales`,
            data: {
                year: year,
                day: day,
                month: month
            },
            dataType: "json",
            success: function( response ){
                const { message, data } = response;

                invoiceArray = data;
                
                let rows = '';
                
                // Removed Rows
                $('#dashboardTbl tbody tr').remove();
                total = 0;
              
                for ( let x = 0; x <  data.length; x++) {  
                total += parseFloat( data[x]['sales'] );

                   rows += `<tr > 
                        <td scope="col">${data[x]['item_name']}</td>
                        <td scope="col">${data[x]['sales']}</td>
                   </tr>`; 
                }

                // Add Total Row
                rows += `<tr > 
                        <td scope="col"> <b>Total</b> </td>
                        <td scope="col"> <b>${total}</b></td>
                   </tr>`; 
                // Append Rows
                $('#dashboardTbl tbody').append( rows );
            }
        });
    }
})