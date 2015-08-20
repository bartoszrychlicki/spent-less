$(function() {
    $("#switch_expense").on('change', function() {
        console.log('ustaw na wydatek');
        $("input#appbundle_transaction_isExpense_0").prop('checked', true);

    });
    $("#switch_income").on('change', function() {
        console.log('ustaw na przychod');
        $("input#appbundle_transaction_isExpense_1").prop('checked', true);
    });
});