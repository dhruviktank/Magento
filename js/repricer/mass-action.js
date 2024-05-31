var j = jQuery.noConflict();

j(document).ready(function () {
  var checkboxes = document.querySelectorAll(
    ".massaction-checkbox, .comp-checkbox"
  );

  checkboxes.forEach(function (checkbox) {
    checkbox.style.display = "none";
  });

  j("body").on("change", ".massaction-checkbox", function (e) {
    var isChecked = j(this).prop("checked");

    var row = j(this).closest("tr");
    var checkboxesToCheck = row.find(".comp-checkbox");
    checkboxesToCheck.prop("checked", isChecked);

    // Check if all "comp-checkbox" checkboxes are unchecked, then uncheck the "massaction-checkbox"
    if (!isChecked && checkboxesToCheck.filter(":checked").length === 0) {
      row.find(".massaction-checkbox").prop("checked", false);
    }
  });

  j("body").on("change", ".comp-checkbox", function (e) {
    checkMassCheckbox(this);
    // var isChecked = j(this).prop("checked");
    // var row = j(this).closest("tr").parent().closest("tr");
    // // console.log(row);
    // var checkboxToCheck = row.find(".massaction-checkbox");
    // checkboxToCheck.prop("checked", isChecked);

    // // Check if there are any "comp-checkbox" checkboxes checked
    // var anyChecked = row.find(".comp-checkbox:checked").length > 0;
    // checkboxToCheck.prop("checked", anyChecked);
  });
});

(varienGridMassaction.prototype.findCheckbox = function (evt) {
  if (
    ["a", "input", "select"].indexOf(
      Event.element(evt).tagName.toLowerCase()
    ) !== -1
  ) {
    return false;
  }
  checkbox = false;
  Event.findElement(evt, "tr")
    .select(".comp-checkbox,.massaction-checkbox")
    .each(
      function (element) {
        if (element.isMassactionCheckbox) {
          checkbox = element;
        }
      }.bind(this)
    );
  return checkbox;
}),
  (varienGridMassaction.prototype.getCheckboxes = function () {
    var result = [];
    this.grid.rows.each(function (row) {
      var checkboxes = row.select(".comp-checkbox, .massaction-checkbox");
      checkboxes.each(function (checkbox) {
        result.push(checkbox);
      });
    });
    return result;
  }),
  (varienGridMassaction.prototype.setCheckbox = function (checkbox) {
    if (checkbox.checked) {
      this.checkedString = varienStringArray.add(
        checkbox.value,
        this.checkedString
      );
    } else {
      let values = checkbox.value;
      const arrvalues = values.split(",");
      for (let i = 0; i < arrvalues.length; i++) {
        // console.log(arrvalues[i]);
        this.checkedString = varienStringArray.remove(
          arrvalues[i],
          this.checkedString
        );
      }
    }
    this.updateCount();
  }),
  (varienGridMassaction.prototype.checkCheckboxes = function () {
    this.getCheckboxes().each(function (checkbox) {
      checkMassCheckbox(checkbox);
      checkbox.checked = varienStringArray.has(checkbox.value, this.checkedString);
    }.bind(this));
  });

//   checkCheckboxes: function() {
// this.getCheckboxes().each(function(checkbox) {
//     checkbox.checked = varienStringArray.has(checkbox.value, this.checkedString);
// }.bind(this));
// },
function checkMassCheckbox(checkbox){
  var isChecked = j(checkbox).prop("checked");
  var row = j(checkbox).closest("tr").parent().closest("tr");
  var checkboxToCheck = row.find(".massaction-checkbox");
  checkboxToCheck.prop("checked", isChecked);
  var anyChecked = row.find(".comp-checkbox:checked").length > 0;
  checkboxToCheck.prop("checked", anyChecked);
}