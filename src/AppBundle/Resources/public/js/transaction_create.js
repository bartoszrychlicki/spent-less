$(function() {
    $("#switch_expense").on('change', function() {
        $("input#appbundle_transaction_isExpense_0").prop('checked', true);

    });
    $("#switch_income").on('change', function() {
        $("input#appbundle_transaction_isExpense_1").prop('checked', true);
    });
    
    // submit button change text
    $("#appbundle_transaction_submit").on('click', function() {
        $(this).button('loading');
    })
});