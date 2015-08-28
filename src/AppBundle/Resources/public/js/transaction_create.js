$(function() {
    $("#switch_expense").on('change', function() {
        $("input#appbundle_transaction_isExpense_0").prop('checked', true);

    });
    $("#switch_income").on('change', function() {
        $("input#appbundle_transaction_isExpense_1").prop('checked', true);
    });
    
    // submit button change text”
    $("#appbundle_transaction_submit").on('click', function() {
        $(this).button('loading');
    })
    

    var payees = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: Routing.generate('get_payee_list_as_json')
    });
    
    // passing in `null` for the `options` arguments will result in the default
    // options being used
    $('#appbundle_transaction_payee').typeahead({
          hint: true,
          highlight: true,
          minLength: 1
    }, {
      name: 'payees',
      source: payees
    });    
});
