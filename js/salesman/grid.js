document.addEventListener("DOMContentLoaded", function () {

  var selectedSalesmen = [];

  document.getElementById("checkboxes").addEventListener("change", function () {
    selectedSalesmen = Array.from(
      document.querySelectorAll("#checkboxes input[type='checkbox']:checked")
    ).map((option) => option.value);
  });
  varienGrid.prototype.doFilter = function () {
    var filters = $$(
      "#" + this.containerId + " .filter input",
      "#" + this.containerId + " .filter select"
    );
    
    console.log(selectedSalesmen);
    selectedSalesmen.forEach(function (salesman) {
      var input = document.createElement("input");
      input.type = "hidden";
      input.id = "checkboxes_"+salesman;
      input.name = "username_"+salesman;
      input.value = salesman;
      filters.push(input);
    });

    let fromDate = document.getElementById("filter_date_from").value;
    let toDate = document.getElementById("filter_date_to").value;

    var fromDateInput = document.createElement("input");
    fromDateInput.type = "hidden";
    fromDateInput.id = "filter_date_from";
    fromDateInput.name = "created_at[from]";
    fromDateInput.value = formatDate(fromDate);
    filters.push(fromDateInput);

    var toDateInput = document.createElement("input");
    toDateInput.type = "hidden";
    toDateInput.id = "filter_date_to";
    toDateInput.name = "created_at[to]";
    toDateInput.value = formatDate(toDate);
    filters.push(toDateInput);

    var elements = filters.filter(function (filter) {
      return filter.value && filter.value.length;
    });

    if (
      !this.doFilterCallback ||
      (this.doFilterCallback && this.doFilterCallback())
    ) {
      this.reload(
        this.addVarToUrl(
          this.filterVar,
          encode_base64(Form.serializeElements(elements))
        )
      );
    }
  };

  function formatDate(inputDate) {
    let parts = inputDate.split("-");
    let year = parts[0];
    let month = parts[1];
    let day = parts[2];

    return month + "/" + day + "/" + year;
  }
});
