// document.observe('dom::loaded', function () {

// })

varienGrid.prototype.saveFilter = function () {
    var filters = $$('#' + this.containerId + ' .filter input', '#' + this.containerId + ' .filter select');
    var elements = {};
    for (var i in filters) {
        if (filters[i].value && filters[i].value.length) 
            elements[filters[i].name] = filters[i].value;
    }
    // console.log($$('.save_customer').length);
    if($$('.save_customer').length > 0)
        var reportType = 2
    else
        var reportType = 1
    var data = {'filters' : JSON.stringify(elements), 'report_type' : reportType}
    new Ajax.Request('http://127.0.0.1/1SBMagento/index.php/admin/filterreport/saveReport/key/2626e8eb96b814dfe8a3a0ab33bd2c4e/', {
        parameters: data,
        evalScripts: true,
        method: 'post',
        onFailure: function (error) {
            alert('Failed to Save ' + error);
        },
        onSuccess: function (response) {
            console.log(response.responseText);
        },
    });

    // console.log(elements);
}