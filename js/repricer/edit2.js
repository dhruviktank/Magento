document.observe('dom:loaded', function() {
    $$('body')[0].observe('click', '.edit-row', function(event) {
      event.stop();
      var editUrl = this.up('td').readAttribute('data-edit-url');
      var formKey = this.up('td').readAttribute('data-form-key');
      var itemId = this.up('td').readAttribute('data-item-id');
      
      var row = this.up('td').up('tr').select('td');
      ['comp_url', 'comp_sku', 'comp_price'].each(function(field, index) {
        var value = row[index + 2].down('.editable.' + itemId).textContent.strip();
        row[index + 2].down('.editable.' + itemId).replace('<input type="text" class="editable ' + itemId + '" value="' + value + '">');
      });
      
      var saveCell = '<td data-edit-url="' + editUrl + '" data-item-id="' + itemId + '" data-form-key="' + formKey + '"><a href="#" class="save-row">Save</a></td>';
      var cancelCell = '<td data-edit-url="' + editUrl + '" data-item-id="' + itemId + '" data-form-key="' + formKey + '"><a href="#" class="cancel-row">Cancel</a></td>';
      this.up('td').replace(saveCell + cancelCell);
    });
  
    $$('body')[0].observe('click', '.save-row', function(event) {
      event.stop();
      var editUrl = this.up('td').readAttribute('data-edit-url');
      var formKey = this.up('td').readAttribute('data-form-key');
      var itemId = this.up('td').readAttribute('data-item-id');
      
      var tds = this.up('td').up('tr').select('td');
      var data = {
        comp_url: tds[2].down('input.editable.' + itemId).value,
        comp_sku: tds[3].down('input.editable.' + itemId).value,
        comp_price: tds[4].down('input.editable.' + itemId).value,
        repricer_id: itemId,
        form_key: formKey
      };
      
      new Ajax.Request(editUrl, {
        method: 'post',
        parameters: data,
        onSuccess: function(response) {
          console.log(response.responseText);
          ['comp_url', 'comp_sku', 'comp_price'].each(function(field, index) {
            tds[index + 2].down('input.editable.' + itemId).replace(response.responseText[field]);
          });
          var editCell = '<td width="50px" class="editable" data-field="edit_link" data-item-id="' + itemId + '" data-form-key="' + formKey + '" data-edit-url="' + editUrl + '"><a href="#" class="edit-row">Edit</a></td>';
          this.up('tr').select('.save-row').invoke('up', 'td').replace(editCell);
          this.up('tr').select('.cancel-row').invoke('up', 'td').invoke('remove');
        }.bind(this),
        onFailure: function(xhr) {
          // Handle error
        }
      });
    });
  
    $$('body')[0].observe('click', '.cancel-row', function(event) {
      event.stop();
      var row = this.up('tr');
      var formKey = this.up('td').readAttribute('data-form-key');
      var editUrl = this.up('td').readAttribute('data-edit-url');
      var itemId = this.up('td').readAttribute('data-item-id');
      
      var tds = this.up('td').up('tr').select('td');
      ['comp_url', 'comp_sku', 'comp_price'].each(function(field, index) {
        tds[index + 2].down('input.editable.' + itemId).replace(tds[index + 2].down('input.editable.' + itemId).value);
      });
      
      var editCell = '<td width="50px" class="editable" data-field="edit_link" data-item-id="' + itemId + '" data-form-key="' + formKey + '" data-edit-url="' + editUrl + '"><a href="#" class="edit-row">Edit</a></td>';
      this.up('tr').select('.cancel-row').invoke('up', 'td').replace(editCell);
      this.up('tr').select('.save-row').invoke('up', 'td').invoke('remove');
    });
  });
  