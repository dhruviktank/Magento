var j = jQuery.noConflict();

j(document).ready(function () {
  // Event handler for capturing user input
  j("body").on("click", ".edit-row", function (e) {
    e.preventDefault();
    var editUrl = j(this).closest('td').data("edit-url");
    var formKey = j(this).closest('td').data("form-key");
    // console.log(editUrl)
    var itemId = j(this).closest('td').data("item-id");
    console.log(j(this).closest('td'));
    // console.log(itemId);

    var row = j(this).parents('td').eq(1).siblings();
    var compUrl = row.eq(2).find('td.editable.' + itemId).text();
    var compSku = row.eq(3).find('td.editable.' + itemId).text();
    var compPrice = row.eq(4).find('td.editable.' + itemId).text();
    // console.log(row);
    row.eq(2).find('td.editable.' + itemId).attr('contenteditable', 'true').html('<input type="text" class="editable '+itemId+'" value="'+compUrl+'">');
    row.eq(3).find('td.editable.' + itemId).attr('contenteditable', 'true').html('<input type="text" class="editable '+itemId+'" value="'+compSku+'">');
    row.eq(4).find('td.editable.' + itemId).attr('contenteditable', 'true').html('<input type="text" class="editable '+itemId+'" value="'+compPrice+'">');
    // Change the button to Save and add Cancel button
    var saveCell = '<td data-edit-url="'+editUrl+'" data-item-id='+itemId+' data-form-key='+formKey+'><a href="#" class="save-row">Save</a></td>';
    var cancelCell = '<td data-edit-url="'+editUrl+'" data-item-id='+itemId+' data-form-key='+formKey+'><a href="#" class="cancel-row">Cancel</a></td>';
    j(this)
      .closest("td")
      .replaceWith(saveCell + cancelCell);
  });

  // Event handler for handling save button click
  j("body").on("click", ".save-row", function (e) {
    e.preventDefault();
    var editUrl =  j(this).closest('td').data("edit-url");
    var formKey = j(this).closest('td').data("form-key");
    var itemId = j(this).closest('td').data("item-id");
    // console.log(itemId)
    var tds = j(this).parents('td').eq(1).siblings();
    
    var compUrl = tds.eq(2).find('input.editable.' + itemId).val();
    var compSku = tds.eq(3).find('input.editable.' + itemId).val();
    var compPrice = tds.eq(4).find('input.editable.' + itemId).val();
    var row = j(this).closest("tr");
    var data = {
      // form_key: formKey,

      comp_url: compUrl,
      comp_sku: compSku,
      comp_price: compPrice,
      repricer_id: itemId,
      form_key: formKey
    };
    console.log(data);
    j.ajax({
      url: editUrl,
      type: "POST",
      data: data,
      success: function (response) {
        console.log(response)
        // Handle success response
        // For example, update the row with updated data
        // Or show a success message
      },
      error: function (xhr, status, error) {
        // Handle error
      },
    });
    tds.eq(2).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+compUrl+'');
    tds.eq(3).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+compSku+'');
    tds.eq(4).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+compPrice+'');

    var editCell =
    '<td width="50px" class="editable" data-field="edit_link" data-item-id="'+itemId+'" data-form-key="'+formKey+'" data-edit-url="'+editUrl+'"><a href="#" class="edit-row">Edit</a></td>';
    row.find(".save-row").closest("td").replaceWith(editCell);
    row.find(".cancel-row").closest("td").remove();
  });

  // Event handler for handling cancel button click
  j("body").on("click", ".cancel-row", function (e) {
    e.preventDefault();
    var row = j(this).closest("tr");
    var formKey = j(this).closest('td').data("form-key");
    var editUrl =  j(this).closest('td').data("edit-url");

    var tds = j(this).parents('td').eq(1).siblings();
    var itemId = j(this).closest('td').data("item-id");
    var compUrl = tds.eq(2).find('input.editable.' + itemId).val();
    var compSku = tds.eq(3).find('input.editable.' + itemId).val();
    var compPrice = tds.eq(4).find('input.editable.' + itemId).val();
    var arr = { 
      compUrl : compUrl ,
      compSku : compSku ,
      compPrice : compPrice ,
    }
    console.log(arr);
    // var itemId = j(this).;
    // console.log(itemId);
    // console.log(itemId.eq(1).data("item-id"));
    // console.log(row);
    tds.eq(2).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+arr['compUrl']+'');
    tds.eq(3).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+arr['compUrl']+'');
    tds.eq(4).find('input.editable.' + itemId).parent().removeAttr("contenteditable").html(''+arr['compUrl']+'');
    var editCell =
      '<td width="50px" class="editable" data-field="edit_link" data-item-id="'+itemId+'" data-form-key="'+formKey+'" data-edit-url="'+editUrl+'"><a href="#" class="edit-row">Edit</a></td>';
    row.find(".cancel-row").closest("td").replaceWith(editCell);
    row.find(".save-row").closest("td").remove();
  });
});
