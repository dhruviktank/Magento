varienGrid.prototype.saveFilter = function () {
    var filters = $$('#' + this.containerId + ' .filter input', '#' + this.containerId + ' .filter select');
    // var elements = [];
    var elements = {};
    for (var i in filters) {
        if (filters[i].value && filters[i].value.length)
            elements[filters[i].name] = filters[i].value;
        // elements.push(filters[i]);
    }
    if ($$('.save_customer').length > 0)
        var reportType = 2
    else
        var reportType = 1
    console.log(elements);
    // var data = { 'filters': encode_base64(Form.serializeElements(elements)), 'report_type': reportType }
    var data = { 'filters': JSON.stringify(elements), 'report_type': reportType }
    new Ajax.Request('http://127.0.0.1/1SBMagento/index.php/admin/catalog_product/saveReport/key/2626e8eb96b814dfe8a3a0ab33bd2c4e/', {
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
}

varienGrid.prototype.applySavedFilters = function () {
    var self = this;
    var reportType = $$('.save_customer').length > 0 ? 2 : 1;
    new Ajax.Request(' http://127.0.0.1/1SBMagento/index.php/admin/catalog_product/getSavedReport/', {
        method: 'post',
        parameters: { reportType: reportType },
        onSuccess: function (response) {
            var filter = JSON.parse(response.responseText);
            console.log(filter);
            if (!self.doFilterCallback || (self.doFilterCallback && self.doFilterCallback())) {
                var filterParam = encode_base64(Object.toQueryString(filter));
                self.reload(self.addVarToUrl(self.filterVar, filterParam));
            }
        },
        onFailure: function () {
            console.log('Failed to retrieve saved filters.');
        }
    });
};

var FilterReportGrid = new Class.create();
FilterReportGrid.prototype = {
    initialize: function (containerId, dropdownId, loadButtonId, loadUrl) {
        this.containerId = containerId;
        this.dropdownId = dropdownId;
        this.loadButtonId = loadButtonId;
        this.loadUrl = loadUrl;
        this.onLoadClick = this.loadUserReport.bindAsEventListener(this);
        Event.observe($(this.loadButtonId), 'click', this.onLoadClick)
    },
    loadUserReport: function () {
        var userId = $F(this.dropdownId);
        if (userId) {
            new Ajax.Request(this.loadUrl, {
                parameters: { 'user_id': userId },
                method: 'post',
                onFailure: function (e) {
                    console.log(e);
                },
                onSuccess: function (response) {
                    try {
                        // var data = JSON.parse(response.responseText);
                        // console.log(data);
                        $(this.containerId).update(response.responseText);
                    } catch (e) {
                        $(this.containerId).update("");
                    }
                }.bind(this)
            })
        } else {
            console.log("nothing");
        }
    },
    // updateContainer: function (data) {
    //     var container = $(this.containerId);
    //     container.update(""); 
    //     var heading = new Element('h1');
    //     heading.update($F(this.dropdownId));
    //     container.insert(heading);
    //     if (data && data.length > 0) {
    //         var table = new Element('table', { 'class': 'report-table' });

    //         var thead = new Element('thead');
    //         var headerRow = new Element('tr');
    //         ['ID', 'Report Type', 'Filter Data'].forEach(function (header) {
    //             headerRow.insert(new Element('th').update(header));
    //         });
    //         thead.insert(headerRow);
    //         table.insert(thead);

    //         var tbody = new Element('tbody');
    //         data.forEach(function (item) {
    //             var row = new Element('tr');
    //             var reportType = (item.report_type == 1) ? 'Product' : 'Customer';
    //             row.insert(new Element('td').update(item.id));
    //             row.insert(new Element('td').update(reportType));
    //             row.insert(new Element('td').update(item.filter_data));
    //             tbody.insert(row);
    //         });
    //         table.insert(tbody);
    //         container.insert(table);
    //         // console.log(table);
    //         // console.log(container);

    //     } else {
    //         container.update("No data found");
    //     }
    // }
}

document.observe('dom:loaded', function () {

    var reportType = $$('.save_customer').length > 0 ? 2 : 1;
    var container = $('report-table-container');
    if (!container) {
        if (reportType == 1) {
            productGridJsObject.applySavedFilters();
        } else {
            customerGridJsObject.applySavedFilters();
        }
    }
})