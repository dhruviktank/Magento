var j = jQuery.noConflict();
varienGrid.prototype.doFilter = function () {
    var filters = $$('#' + this.containerId + ' .filter input', '#' + this.containerId + ' .filter select');
    filters.push(bannerGrid_filter_banner)
    filters.push(bannerGrid_filter_status_1)
    // console.log(filters); return;
    var elements = [];
    for (var i in filters) {
        if (filters[i].value && filters[i].value.length) elements.push(filters[i]);
    }
    if (!this.doFilterCallback || (this.doFilterCallback && this.doFilterCallback())) {
        this.reload(this.addVarToUrl(this.filterVar, encode_base64(Form.serializeElements(elements))));
    }
};

j(document).ready(function (){
    
})


// var j = jQuery.noConflict();

// j(document).ready(function () {
//   var medium = {};
//   var fields = {};
  
//   j("body").on("click", ".edit-row", function (e) {
//     var editButton = j(this);
//     var editUrl = editButton.data("edit-url");
//     var contactId = editButton.data("contact-id");
//     medium = editButton.data("medium");
//     fields = editButton.data("fields");

//     var row = editButton.closest("tr");

//     var editableCells = row.find("td.editable");

//     editableCells.each(function (index) {
//       let originalValue = j(this).text().trim();
//       if(index == 2){
//         var dropdown = '<select class="edit-dropdown">';
//           Object.keys(medium).forEach((element) => {
//             dropdown +=
//               '<option value="' +
//               element +
//               '" ' +
//               (originalValue == medium[element] ? "selected" : "") +
//               ' data-original="' +
//               originalValue +
//               '">' +
//               medium[element] +
//               "</option>";
//           });
//         dropdown += "</select>";
//         j(this).html(dropdown);
//       }
//       else{
//         j(this).html(
//         '<input type="text" class="edit-input" value="' +
//           originalValue +
//           '" data-original="' +
//           originalValue +
//           '">'
//         );
//       }
//     });

//     editButton.hide();

//     var saveButton = j(
//       '<a href="#" data-edit-url="' +
//         editUrl +
//         '" data-contact-id="' +
//         contactId +
//         '" data-medium="' +
//         medium +
//         '" class="save-button">Save</a>'
//     );
//     var cancelButton = j(
//       '<a href="#" style="padding-left:5px" data-edit-url="' +
//         editUrl +
//         '" data-contact-id="' +
//         contactId +
//         '" data-medium="' +
//         medium +
//         '"  class="cancel-button">Cancel</a>'
//     );
//     var buttonContainer = j("<div>")
//       .addClass("button-container")
//       .append(saveButton, cancelButton);

//     var cell = editButton.closest("td");
//     cell.empty().append(buttonContainer);

//     editableCells.first().find(".edit-input").focus();
//   });

//   j("body").on("click", ".cancel-button", function (e) {
//     e.preventDefault();
//     var cancelButton = j(this);
//     var editUrl = cancelButton.data("edit-url");
//     var contactId = cancelButton.data("contact-id");

//     var row = cancelButton.closest("tr");

//     var editableCells = row.find("td.editable");

//     editableCells.each(function (index) {
//       if (index == 2){
//         var originalValue = j(this)
//           .find("select")
//           .children("option:selected")
//           .data("original");
//         j(this).text(originalValue);
//       }
//       else{
//         var originalValue = j(this).find(".edit-input").data("original");
//         j(this).text(originalValue);
//       }
//     });

//     let newMedium = JSON.stringify(medium);

//     var cell = cancelButton.closest("td");
//     var a = document.createElement("a"); // Create a new anchor element
//     a.innerText = "Edit";
//     a.setAttribute("href", "#");
//     a.setAttribute("class", "edit-row");
//     a.setAttribute("data-edit-url", editUrl);
//     a.setAttribute("data-medium", newMedium);
//     a.setAttribute("data-contact-id", contactId);
//     cell.empty().html(a);
//   });

//   j("body").on("click", ".save-button", function (e) {
//     e.preventDefault();
//     var saveButton = j(this);
//     var editUrl = saveButton.data("edit-url");
//     var contactId = saveButton.data("contact-id");

//     var row = saveButton.closest("tr");

//     var editableCells = row.find("td.editable");
//     var editedData = {};

//     editableCells.each(function (index) {
//       var field = fields[index];
//       if (index == 2){
//         value = j(this).find("select").val();
//         editedData[field] = value;
//         j(this).text(medium[value]);

//       } 
//       else {
//         let value = j(this).find(".edit-input").val().trim();
//         editedData[field] = value;
//         j(this).text(value);
//       }
//     });

//     var params = { edited_data: editedData, contact_id: contactId };
//     var data = JSON.stringify(params);
//     new Ajax.Request(editUrl, {
//       method: "post",
//       parameters: { edited_data: data },
//       onSuccess: function (response) {
//         console.log(response);
//       },
//     });

//     let newMedium = JSON.stringify(medium);

//     var cell = saveButton.closest("td");
//     var a = new Element("a");
//     a.innerText = "Edit";
//     a.setAttribute("href", "#");
//     a.setAttribute("class", "edit-row");
//     a.setAttribute("data-edit-url", editUrl);
//     a.setAttribute("data-medium", newMedium);
//     a.setAttribute("data-contact-id", contactId);
//     cell.empty().html(a);
//   });

// });
