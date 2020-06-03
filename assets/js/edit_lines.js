$(document).on('click', 'span.icon-remove', function () {
    var data_remove = $(this).attr('data-remove');
    var element = "tr.remove_" + data_remove;
    $(element).remove();
    make_order();
    update_number_rows();
    calculate_total_invoice_ttc();
    calculate_total_invoice_ht();
});

// delete rows
$(document).ready(function () {


    // add row in table invoices
    init();



    calculate_total_invoice_ttc();
    calculate_total_invoice_ht();

    // $('.datepicker').datepicker({'format' : 'dd/mm/yyyy'});
}); // end jQuery

// FUNCTIONS 	
function make_order() {
    var i = 1;
    $('#table_invoice tbody tr').each(function () {

        $(this).attr('data-id', i); // class to remove
        $(this).removeClass().addClass("remove_" + i); // class to remove
        $(this).find('.reference').attr('name', "row_ref_" + i);
        $(this).find('.description').attr('name', "row_description_" + i);
        $(this).find('.qtites').attr('name', "quantity_" + i);
        $(this).find('.price_ht').attr('name', "price_ht_" + i);
        $(this).find('.taxes').attr('name', "taxe_id_" + i);
        $(this).find('.price_ttc').attr('name', "price_ttc_" + i);
        $(this).find('.remise').attr('name', "remise_" + i);
        $(this).find('.total_ht').attr('name', "total_ht_" + i);
        $(this).find('.total_ttc').attr('name', "total_ttc_" + i);
        $(this).find('span.icon-remove').attr('data-remove', i);
        i = i + 1;
    });
    update_number_rows();

}

function update_number_rows() {
    var number = $('#table_invoice tbody tr').length;
    $('#rows_number').val(number);
}

function make_price() {
    var qtites = $(this).val();
    $(this).parents('tr').find('.price_ht').val(qtites);

    // calcul tva
    var price_ht = $(this).parents('tr').find('.price_ht').val();
    var tva = $(this).parents('tr').find('.taxes').val();
    var calcul = price_ht * tva;


    var price_ttc = $(this).parents('tr').find('.price_ttc').val(calcul.toFixed(2));


}

function calculate_total_invoice_ht() {
    var total = 0;
    $('input.total_ht').each(function () {
        total = total + Number($(this).val());
    });
    $('#total_invoice_ht').html(total.toFixed(2));
}

function calculate_total_invoice_ttc() {
    var total = 0;
    $('input.total_ttc').each(function () {
        total = total + Number($(this).val());
    });
    $('#total_invoice_ttc').html(total.toFixed(2));
}

function init() {
    $('.qtites').change(function () {

        var qtites = $(this).val().replace(',', '.');
        var price_ht = $(this).parents('tr').find('.price_ht').val().replace(',', '.');
        var tva = $(this).parents('tr').find('.taxes').val();
        var calcul = price_ht * tva;
        var remise = $(this).parents('tr').find('.remise').val().replace(',', '.');

        if (remise > 0) {
            remise = 1 - (remise / 100);
        } else {
            remise = 1;
        }

        // price ttc
        $(this).parents('tr').find('.price_ttc').val(calcul.toFixed(2));
        // total ht
        var total_ht = (price_ht * remise) * qtites;
        var total_ttc = ((price_ht * remise) * tva) * qtites;
        // p
        $(this).parents('tr').find('p.total_ht').html(total_ht.toFixed(2));
        $(this).parents('tr').find('p.total_ttc').html(total_ttc.toFixed(2));
        // input
        $(this).parents('tr').find('input.total_ht').val(total_ht.toFixed(2));
        $(this).parents('tr').find('input.total_ttc').val(total_ttc.toFixed(2));
        //totaux
        calculate_total_invoice_ttc();
        calculate_total_invoice_ht();


    });

    $('.price_ht').keyup(function () {

        var price_ht = $(this).val().replace(',', '.');
        var tva = $(this).parents('tr').find('.taxes').val();
        var calcul = price_ht * tva;
        var remise = $(this).parents('tr').find('.remise').val().replace(',', '.');
        var qtites = $(this).parents('tr').find('.qtites').val().replace(',', '.');
        if (remise > 0) {
            remise = 1 - (remise / 100);
        } else {
            remise = 1;
        }

        // price ttc
        $(this).parents('tr').find('.price_ttc').val(calcul.toFixed(2));
        // total ht
        var total_ht = (price_ht * remise) * qtites;
        var total_ttc = ((price_ht * remise) * tva) * qtites;
        // p
        $(this).parents('tr').find('p.total_ht').html(total_ht.toFixed(2));
        $(this).parents('tr').find('p.total_ttc').html(total_ttc.toFixed(2));
        // input
        $(this).parents('tr').find('input.total_ht').val(total_ht.toFixed(2));
        $(this).parents('tr').find('input.total_ttc').val(total_ttc.toFixed(2));
        //totaux
        calculate_total_invoice_ttc();
        calculate_total_invoice_ht();

    });
    $('.taxes').change(function () {
        var price_ht = $(this).parents('tr').find('.price_ht').val().replace(',', '.');
        var tva = $(this).val();
        var calcul = price_ht * tva;
        var remise = $(this).parents('tr').find('.remise').val().replace(',', '.');
        var qtites = $(this).parents('tr').find('.qtites').val().replace(',', '.');
        if (remise > 0) {
            remise = 1 - (remise / 100);
        } else {
            remise = 1;
        }

        // price ttc
        $(this).parents('tr').find('.price_ttc').val(calcul.toFixed(2));
        // total ht
        var total_ht = (price_ht * remise) * qtites;
        var total_ttc = ((price_ht * remise) * tva) * qtites;
        // p
        $(this).parents('tr').find('p.total_ht').html(total_ht.toFixed(2));
        $(this).parents('tr').find('p.total_ttc').html(total_ttc.toFixed(2));
        // input
        $(this).parents('tr').find('input.total_ht').val(total_ht.toFixed(2));
        $(this).parents('tr').find('input.total_ttc').val(total_ttc.toFixed(2));
        //totaux
        calculate_total_invoice_ttc();
        calculate_total_invoice_ht();
    });

    $('.price_ttc').keyup(function () {

        var price_ttc = $(this).val().replace(',', '.');
        var tva = $(this).parents('tr').find('.taxes').val();
        var calcul = price_ttc / tva;
        var remise = $(this).parents('tr').find('.remise').val().replace(',', '.');
        var qtites = $(this).parents('tr').find('.qtites').val().replace(',', '.');
        if (remise > 0) {
            remise = 1 - (remise / 100);
        } else {
            remise = 1;
        }

        // price ttc
        $(this).parents('tr').find('.price_ht').val(calcul.toFixed(2));
        // total ht
        var price_ht = $(this).parents('tr').find('.price_ht').val().replace(',', '.');

        var total_ht = (price_ht * remise) * qtites;
        var total_ttc = ((price_ht * remise) * tva) * qtites;
        // p
        $(this).parents('tr').find('p.total_ht').html(total_ht.toFixed(2));
        $(this).parents('tr').find('p.total_ttc').html(total_ttc.toFixed(2));
        // input
        $(this).parents('tr').find('input.total_ht').val(total_ht.toFixed(2));
        $(this).parents('tr').find('input.total_ttc').val(total_ttc.toFixed(2));
        //totaux
        calculate_total_invoice_ttc();
        calculate_total_invoice_ht();
    });

    $('.remise').keyup(function () {

        var price_ht = $(this).parents('tr').find('.price_ht').val().replace(',', '.');
        var tva = $(this).parents('tr').find('.taxes').val();
        var calcul = price_ht * tva;
        var remise = $(this).val().replace(',', '.');
        var qtites = $(this).parents('tr').find('.qtites').val().replace(',', '.');
        if (remise > 0) {
            remise = 1 - (remise / 100);
        } else {
            remise = 1;
        }

        // price ttc
        $(this).parents('tr').find('.price_ttc').val(calcul.toFixed(2));
        // total ht
        var total_ht = (price_ht * remise) * qtites;
        var total_ttc = ((price_ht * remise) * tva) * qtites;
        // p
        $(this).parents('tr').find('p.total_ht').html(total_ht.toFixed(2));
        $(this).parents('tr').find('p.total_ttc').html(total_ttc.toFixed(2));
        // input
        $(this).parents('tr').find('input.total_ht').val(total_ht.toFixed(2));
        $(this).parents('tr').find('input.total_ttc').val(total_ttc.toFixed(2));
        //totaux
        calculate_total_invoice_ttc();
        calculate_total_invoice_ht();
    });
}


