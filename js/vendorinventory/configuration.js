var j = jQuery.noConflict();
var Configuration = Class.create();
Configuration.prototype = {
    initialize: function (options) {
        this.containerId = options.containerId;
        this.getheaderActionUrl = options.url;
        this.saveActionUrl = options.saveUrl;
        this.getConfigUrl = options.getConfigUrl
        this.formKey = options.form_key;
        this.configId = false;
        this.currentFile = null;
        this.headers = null;
        this.loadUploadContainer();
    },
    loadUploadContainer: function () {
        var brandDropdown = $(this.containerId).down('#brand-dropdown');
        var fileUploadContainer = $(this.containerId).down('#file-upload-container');
        var self = this;
        brandDropdown.observe('change', function () {
            if (this.value) {
                fileUploadContainer.innerHTML = '';
                self.getConfiguration({ 'brandId': this.value })
                    .then(function (response) {
                        if (response != false) {
                            self.configId = response.configId;
                            self.headers = response.headers;
                            self.renderTable(response.headers, true);
                            self.loadTable(response.config);
                        } else {
                            j("#table-container")[0].innerHTML = '';
                            fileUploadContainer.innerHTML = '<input type="file" id="file-upload" accept=".csv,.xml,.xls" name="file-upload"><button id="upload-btn">Upload</button>';
                            self.configId = false;
                            $('upload-btn').observe('click', function (event) {
                                event.preventDefault();
                                if (self.headers && !confirm("Current File Configuration Will be Lost"))
                                    return;
                                self.currentFile = document.getElementById("file-upload").files;
                                self.getHeaders();
                            });
                        }
                    })
            } else {
                j("#table-container")[0].innerHTML = '';
                self.headers = null;
                self.currentFile = null;
                self.configId = false;
                fileUploadContainer.innerHTML = '';
            }
        });
    },
    getConfiguration: function (request) {
        var self = this;
        return new Promise(function (resolve, reject) {
            new Ajax.Request(self.getConfigUrl, {
                loaderArea: self.containerId,
                parameters: request,
                evalScripts: true,
                method: 'post',
                onFailure: function (e) {
                    reject(e); // Reject the promise with an error message
                },
                onSuccess: function (response) {
                    if (self.isJson(response.responseText)) {
                        var responseData = JSON.parse(response.responseText);
                        if (responseData.hasOwnProperty('headers') && responseData.hasOwnProperty('config')) {
                            resolve(responseData); // Resolve the promise with the response data
                        } else {
                            resolve(false); // Reject the promise with an error message
                        }
                    } else {
                        reject("Invalid JSON response"); // Reject the promise with an error message
                    }
                },
            });
        });
    },
    getHeaders: function () {
        var self = this;
        var headerUrl = this.getheaderActionUrl;
        var formKey = this.formKey;
        var files = this.currentFile;
        if (files.length > 0) {
            var formData = new FormData();
            formData.append('form_key', formKey);
            formData.append('header-file', files[0]);
            j.ajax({
                url: headerUrl,
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false, // Prevent jQuery from setting the content type
                beforeSend: function () {
                    showLoader(); // Show loader before AJAX request is sent
                },
                success: function (response) {
                    var response = JSON.parse(response);
                    if (response.headers) {
                        self.headers = response.headers;
                        self.renderTable(response.headers);
                    }
                },
                error: function () {
                    alert('Failed to retrieve CSV headers.');
                },
                complete: function () {
                    hideLoader(); // Hide loader after AJAX request is complete
                }
            });
        } else {
            alert('Please select a file.');
        }
    },
    renderTable: function (headers, config = false) {
        var self = this;
        var tableContainer = document.getElementById('table-container');
        tableContainer.innerHTML = '';
        var tableHeader = ['ISB Columns', 'Brand Column', 'Data Type', 'Condition Operator', 'Condition Value'];
        var ISBColumns = ['sku', 'instock', 'instock_qty', 'restock_date', 'restock_qty', 'status', 'discontinued'];
        var brandColumn = headers;
        var dataType = ['Count', 'Text', 'Number', 'Date'];
        var conditionOperator = ['=', '>', '<', '>=', '<=', '!='];
        // create table element
        var table = new Element('table');
        table.border = 1;
        // create header row
        var tr1 = new Element('tr');
        tableHeader.forEach(function (header) {
            var th = new Element('th');
            th.textContent = header;
            tr1.appendChild(th);
        })
        table.appendChild(tr1);
        for (var i = 0; i < ISBColumns.length; i++) {

            var tr = new Element('tr');
            tr.classList.add('row_' + i);
            tr.setAttribute("row_id", "row_" + i);
            tr.setAttribute("name", ISBColumns[i]);
            var brandSelect = this.createDropDown(brandColumn);
            var dataTypeSelect = this.createDropDown(dataType);
            j(dataTypeSelect).on('change', (event) => {
                this.changeInputType(event.target.value, j(event.target).parents('tr').find('td').eq(4).find('input'));
            })
            var conditionOperatorSelect = this.createDropDown(conditionOperator);


            var td1 = new Element('td');
            td1.textContent = ISBColumns[i];

            var td2 = new Element('td');
            td2.appendChild(brandSelect);

            var td3 = new Element('td');
            td3.appendChild(dataTypeSelect);

            var td4 = new Element('td');
            td4.appendChild(conditionOperatorSelect);

            var td5 = new Element('td');
            var input = new Element('input');
            input.type = 'text';
            td5.appendChild(input);

            var td6 = new Element('td');
            var addButton = this.createButton('Add');
            addButton.onclick = (event) => {
                this.handleAdd(event.target);
            };
            td6.appendChild(addButton);

            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(td4);
            tr.appendChild(td5);
            tr.appendChild(td6);
            table.appendChild(tr);
        }
        tableContainer.appendChild(table)
        var saveBtn = this.createButton('save');
        saveBtn.onclick = () => {
            self.handleSave();
        };
        tableContainer.appendChild(saveBtn);
        if (!config) {
            var resetBtn = this.createButton('reset');
            resetBtn.onclick = () => {
                self.handleReset();
            };
            tableContainer.appendChild(resetBtn);
        }
    },
    handleReset: function () {
        var self = this;
        self.getHeaders();
    },
    handleAdd: function (button) {
        var currentRow = j(button).parents("tr")[0];
        var row_id = currentRow.getAttribute("row_id");
        var row_count = j("#table-container").children("table").find("tr[class=" + row_id + "]").length;
        var rowClone = currentRow.cloneNode(true);
        rowClone.firstChild.innerText = '';
        j(rowClone).find('td').eq(4).find('input').val('');
        var p = new Element('p');
        this.appendAndOrRadioGroup(p, row_id, row_count, "OR");

        var lastTd = rowClone.lastElementChild;
        if (lastTd) {
            rowClone.removeChild(lastTd);
        }
        j(rowClone).find('select').eq(0).before(p);
        j(rowClone).find('tds').eq(2).find('select').on('change', (e) => {
            this.changeInputType(e.target.value, j(e.target).parents('tr').find('td').eq(4).find('input'))
        });
        var removeBtn = this.createButton('Delete');
        removeBtn.observe('click', (event) => {
            this.handleDelete(event.target);
        })
        rowClone.append(new Element('td').appendChild(removeBtn));
        $(currentRow).after(rowClone);
    },
    handleDelete: function (button) {
        var currentRow = button.parentNode;
        currentRow.parentNode.removeChild(currentRow);
    },
    prepareArray: function () {
        var brandId = j('#brand-dropdown').val();
        var configArray = {};
        configArray[brandId] = {};
        j("#table-container table tr").not(":first").each(function () {
            var obj = {};
            var tds = j(this).find("td");
            var name = j(this).attr('name');

            var brandCol = tds.eq(1).find('select').val();
            if (brandCol) {
                obj[brandCol] = {
                    'data_type': tds.eq(2).find('select').val(),
                    'condition_operator': tds.eq(3).find('select').val(),
                    'condition_value': tds.eq(4).find('input').val()
                }
            }

            if (configArray[brandId].hasOwnProperty(name)) {
                var radioValue = tds.eq(1).find('input[type="radio"]:checked').val();
                if (radioValue) {
                    // If radio button is checked, include its value in the object
                    configArray[brandId][name].push(radioValue);
                }
                // If it exists, push the obj to the existing array
                configArray[brandId][name].push(obj);
            } else {
                // If it doesn't exist, create a new array with obj
                configArray[brandId][name] = [obj];
            }
        })
        return configArray;
    },
    loadTable: function (config) {
        var self = this;
        j('#table-container table tr').not(':first').each(function (index, tr) {
            var rowConfig = config[tr.getAttribute('name')];
            var p;
            var prevRow = tr;
            rowConfig.forEach(function (row, _index) {
                if (_index >= 1) {
                    if (row == 'AND' || row == 'OR') {
                        var row_id = tr.getAttribute("row_id");
                        var row_count = j("#table-container").children("table").find("tr[class=" + row_id + "]").length;

                        p = new Element('p');
                        self.appendAndOrRadioGroup(p, row_id, row_count, row);
                    } else {
                        var rowClone = tr.cloneNode(true);
                        j(rowClone).find('select').eq(0).before(p);
                        var tds = j(rowClone).find("td");
                        for (var _row in row) {
                            tds.eq(1).find('select').val(_row);
                            tds.eq(2).find('select').val(row[_row]['data_type']);
                            tds.eq(3).find('select').val(row[_row]['condition_operator']);
                            self.changeInputType(row[_row]['data_type'], tds.eq(4).find('input'));
                            tds.eq(4).find('input').val(row[_row]['condition_value']);
                        }
                        tds.eq(2).find('select').on('change', (event) => {
                            self.changeInputType(event.target.value, j(target).parents('tr').find('td').eq(4).find('input'));
                        })
                        var lastTd = rowClone.lastElementChild;
                        if (lastTd) {
                            rowClone.removeChild(lastTd);
                        }
                        var removeBtn = self.createButton('Delete');
                        removeBtn.observe('click', (event) => {
                            self.handleDelete(event.target);
                        })
                        rowClone.append(new Element('td').appendChild(removeBtn));
                        rowClone.firstChild.innerText = '';

                        $(prevRow).after(rowClone);
                        prevRow = rowClone;
                    }
                }
                else {
                    var tds = j(tr).find("td");
                    for (var _row in row) {
                        tds.eq(1).find('select').val(_row)
                        tds.eq(2).find('select').val(row[_row]['data_type'])
                        tds.eq(3).find('select').val(row[_row]['condition_operator'])
                        self.changeInputType(row[_row]['data_type'], tds.eq(4).find('input'));
                        tds.eq(4).find('input').val(row[_row]['condition_value'])
                    }
                }
            })
        })
    },
    changeInputType: function (value, element) {
        var inputType = 'text';
        var elementValue = j(element).val();
        switch (value) {
            case "Date":
                inputType = 'date';
                break;
            case "Number":
            case "Count":
                inputType = 'number';
                break;
            default:
                inputType = 'text';
                break;
        }
        element.attr('type', inputType);
        element.val(elementValue);
    },
    handleSave: function () {
        var self = this;
        var configurationArray = this.prepareArray();
        var saveUrl = this.saveActionUrl;
        var data = {
            'configuration': JSON.stringify(configurationArray),
            'headers': this.headers,
        }
        if (self.configId != false) {
            data.configId = self.configId;
        }
        new Ajax.Request(saveUrl, {
            loaderArea: self.containerId,
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
    },
    isJson: function (str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    },
    appendAndOrRadioGroup: function (element, row_id, row_count, row) {
        element.appendChild(this.createRadioInput("radio_and_" + row_id + "_" + (row_count + 1), 'condition_' + row_id + '_' + (row_count + 1), 'AND', row == 'AND'));
        element.appendChild(this.createLabel("AND", "radio_and_" + row_id + "_" + (row_count + 1)))
        element.appendChild(this.createRadioInput("radio_or_" + row_id + "_" + (row_count + 1), 'condition_' + row_id + '_' + (row_count + 1), 'OR', row == 'OR'))
        element.appendChild(this.createLabel("OR", "radio_or_" + row_id + "_" + (row_count + 1)))
    },
    createRadioInput: function (id, name, value, checked = false) {
        var radioInput = new Element('input');
        radioInput.id = id;
        radioInput.name = name;
        radioInput.type = 'radio';
        radioInput.value = value;
        radioInput.checked = checked;
        return radioInput;
    },
    createLabel: function (text, for_id) {
        var label = new Element('label');
        label.setAttribute('for', for_id);
        label.textContent = text;
        return label;
    },
    createButton: function (text) {
        var button = new Element('button');
        button.textContent = text;
        return button;
    },
    createDropDown: function (options, selectedValue) {
        var select = new Element('select');
        for (var i = 0; i < options.length; i++) {
            var option = new Element('option');
            option.innerText = options[i];
            option.value = options[i];
            if (options[i] == selectedValue) {
                option.selected = true;
            }
            select.appendChild(option);
        }
        return select;
    },
}

var showLoader = function () {
    Element.show('loading-mask');
};

var hideLoader = function () {
    Element.hide('loading-mask');
};