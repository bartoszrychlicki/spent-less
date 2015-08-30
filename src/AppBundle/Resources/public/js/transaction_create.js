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
    
    // tags recommendation
    var tags = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: Routing.generate('get_tags_list_as_json')
    });
    
    console.log(Routing.generate('get_tags_list_as_json'));
    // passing in `null` for the `options` arguments will result in the default
    // options being used
    
    $('#appbundle_transaction_tags').typeahead({
          hint: true,
          highlight: true,
          minLength: 0
    }, {
      name: 'tags',
      source: tags,
    });       
    
    //payee recomendation
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
          minLength: 0
    }, {
      name: 'payees',
      source: payees
    });   
});