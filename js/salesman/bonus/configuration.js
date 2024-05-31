var salesConfig = new Class.create();

salesConfig.prototype = {
  initialize: function (configURL, formData) {
    this.configURL = configURL;
    this.formData = JSON.parse(formData);
    this.getConfiguration(this.formData);
  },
  getConfiguration: function (formData) {
    var formContainer = $$(".configuration_form")[0];
    var form = new Element("form", {
      id: "configuration_form",
      method: "POST",
      action: this.configURL,
    });

    var formkey = new Element("input", {
      type: "hidden",
      id: "form_key",
      name: "form_key",
      value: FORM_KEY,
    });
    form.insert(formkey);
    var entityIdInput = new Element("input", {
      type: "hidden",
      id: "entity_id",
      name: "entity_id",
      value: formData.entity_id ? formData.entity_id : "",
    });
    form.insert(entityIdInput);

    var nameLabel1 = new Element("label", { for: "start_date" }).update(
      "Start Date: "
    );
    var startDateInput = new Element("input", {
      type: "date",
      id: "start_date",
      name: "start_date",
      value: formData.start_date ? formData.start_date.substring(0, 10) : "",
    });
    form.insert(nameLabel1);
    form.insert(startDateInput);
    form.insert(new Element("br"));
    form.insert(new Element("br"));

    var nameLabel2 = new Element("label", { for: "end_date" }).update(
      "End Date: "
    );
    var endDateInput = new Element("input", {
      type: "date",
      id: "end_date",
      name: "end_date",
      value: formData.end_date ? formData.end_date.substring(0, 10) : "",
    });
    form.insert(nameLabel2);
    form.insert(endDateInput);
    form.insert(new Element("br"));
    form.insert(new Element("br"));

    var nameLabel3 = new Element("label", { for: "metric" }).update(
      "Bonus Metric: "
    );
    var bonusMetricInput = new Element("select", {
      id: "metric",
      name: "metric",
    });

    var metric = [
      "",
      "Total Worked Orders",
      "Total Upsell Orders",
      "Total Commission Orders",
      "Total Upsold",
      "Total Commission",
      "Product Upsold",
      "Shipping Upsold",
      "Tax Upsold",
      "Product Commission",
      "Shipping Commission",
      "Tax Commission",
    ];

    form.insert(nameLabel3);
    form.insert(bonusMetricInput);

    metric.forEach(function (metricValue) {
      var option1 = new Element("option", { value: metricValue }).update(
        metricValue
      );
      bonusMetricInput.insert(option1);
    });

    var selectedOption = formData.metric ? formData.metric : "";
    bonusMetricInput.value = selectedOption;

    form.insert(new Element("br"));
    form.insert(new Element("br"));

    var nameLabel4 = new Element("label", { for: "rank" }).update("Rank: ");
    var rank = new Element("input", {
      type: "text",
      id: "rank",
      name: "rank",
      value: formData.rank ? formData.rank : "",
    });

    form.insert(nameLabel4);
    form.insert(rank);
    form.insert(new Element("br"));
    form.insert(new Element("br"));

    var nameLabel5 = new Element("label", { for: "users in group" }).update(
      "Users in League: "
    );
    var usersInGroupInput = new Element("input", {
      type: "text",
      id: "users_in_league",
      name: "users_in_league",
      value: formData.users_in_league ? formData.users_in_league : "",
    });

    form.insert(nameLabel5);
    form.insert(usersInGroupInput);
    form.insert(new Element("br"));
    form.insert(new Element("br"));

    var submitButton = new Element("input", {
      type: "submit",
      id: "submit",
    });
    form.insert(submitButton);
    formContainer.insert(form);
  },
};

var createCards = new Class.create();
createCards.prototype = {
  initialize: function (cardData, usersInLeague, Url, Rank) {
    this.usersInLeague = usersInLeague;
    this.cardData = cardData;
    this.Url = Url;
    this.Rank = Rank;
    this.createGroupData(
      this.cardData,
      this.usersInLeague,
      this.Url,
      this.Rank
    );
  },

  createGroupData: function (data, usersInLeague, Url, Rank) {
    data = JSON.parse(data);
    var cardContainer = $$(".cards")[0];
    var buttonContainer = $$(".submitData")[0];

    var submitButton = new Element("input", {
      type: "submit",
      id: "sendDataButton",
      class: "form-button",
    });
    buttonContainer.insert(submitButton);

    for (var i = 0; i < Math.ceil(Rank / usersInLeague); i++) {
      var table = new Element("table", {
        class: "draggable-table",
        border: "1",
        style: "margin: 20px; border-collapse: collapse;",
        name: "table_" + i,
        id: "league_" + (i + 1),
      });

      var tr = new Element("tr", {
        style: "background-color: #f2f2f2; cursor: grab",
      });
      var th = new Element("th", {
        class: "draggable-header",
        colspan: "3",
        style: "padding: 8px; text-align: left;",
      }).update("League " + (i + 1));
      tr.insert(th);
      table.insert(tr);

      for (
        var j = i * usersInLeague;
        // j < Math.min((i + 1) * usersInLeague, data.length);
        j < Math.min((i + 1) * usersInLeague, Rank);
        j++
      ) {
        var tr = new Element("tr", {
          class: "draggable-row",
          draggable: "true",
          style: "cursor: grab",
        });
        var tdUsername = new Element("td", {
          class: "td-class",
          value: data[j].username,
          style: "padding: 8px; text-align: center; width: 100px",
        }).update(data[j].username);
        tr.insert(tdUsername);

        var tdBonus = new Element("td", {
          class: "bonusArray",
          id: "bonusArray",
          default: "0",
          style: "padding: 8px; text-align: center; width: 100px",
        });
        var bonusDropdown = new Element("select", {
          id: "selectBonus",
          class: "selectBonus",
          name: "bonus",
          style: "width: 100px",
        });
        var bonusArray = bonusArrayString.split(",");
        bonusArray.forEach(function (bonusValue) {
          var option = new Element("option", {
            value: bonusValue,
          }).update(bonusValue);
          if (bonusValue === data[j].bonus) {
            option.selected = true; // Set the default value
          }
          bonusDropdown.insert(option);
        });
        tdBonus.insert(bonusDropdown);
        tr.insert(tdBonus);
        table.insert(tr);
      }
      cardContainer.insert(table);
    }
  },
};

document.addEventListener("DOMContentLoaded", function () {
  var tables = document.querySelectorAll(".draggable-table");
  var rows = document.querySelectorAll(".draggable-row");

  rows.forEach((row) => {
    row.addEventListener("dragstart", () => {
      row.classList.add("dragging");
    });
    row.addEventListener("dragend", () => {
      row.classList.remove("dragging");
    });
  });

  var leagueData = {};
  tables.forEach((table) => {
    var leagueName = table.getAttribute("id");
    var users = [];
    var bonus = {};

    table.querySelectorAll(".draggable-row").forEach((row) => {
      var username = row.querySelector(".td-class").getAttribute("value");
      var bonusValue = row.querySelector(".selectBonus").value;
      users.push(username);
      bonus[username] = bonusValue;
    });
    leagueData[leagueName] = { users, bonus };

    table.addEventListener("dragover", (event) => {
      event.preventDefault();
    });

    table.addEventListener("drop", (event) => {
      event.preventDefault();

      const draggableRow = document.querySelector(".dragging");
      const afterElement = getElementAfterDrag(table, event.clientY);
      if (afterElement == null) {
        if (table.querySelectorAll(".draggable-row").length === 0) {
          table.appendChild(draggableRow);
        } else {
          table.insertBefore(draggableRow, afterElement);
        }
      } else {
        table.insertBefore(draggableRow, afterElement);
      }
      updateLeagueRows();
      for (let table in leagueData) {
        if (
          document.querySelectorAll("#" + table + " .draggable-row").length >=
          userInLeague
        ) {
          document.getElementById(table).style.backgroundColor = "lightcoral";
        }
      }
    });

    var bonusDropdowns = table.querySelectorAll(".selectBonus");
    bonusDropdowns.forEach((dropdown) => {
      dropdown.addEventListener("change", () => {
        var username = dropdown
          .closest(".draggable-row")
          .querySelector(".td-class")
          .getAttribute("value");
        var bonusValue = dropdown.value;
        console.log(username, bonusValue);
        updateLeagueRows();
      });
    });
  });
  console.log(leagueData);

  function getElementAfterDrag(table, y) {
    const draggables = [
      ...table.querySelectorAll(".draggable-row:not(.dragging)"),
    ];
    return draggables.reduce(
      (closest, row) => {
        const box = row.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
          return { offset: offset, element: row };
        } else {
          return closest;
        }
      },
      { offset: Number.NEGATIVE_INFINITY }
    ).element;
  }

  function updateLeagueRows() {
    leagueData = {};
    tables.forEach((table) => {
      var leagueName = table.getAttribute("id");
      var users = [];
      var bonus = {};

      table.querySelectorAll(".draggable-row").forEach((row) => {
        var username = row.querySelector(".td-class").getAttribute("value");
        var bonusValue = row.querySelector(".selectBonus").value;
        users.push(username);
        bonus[username] = bonusValue;
      });

      leagueData[leagueName] = { users, bonus };
    });
    console.log(leagueData);
  }

  jQuery("#sendDataButton").click(function () {
    var jsonString = JSON.stringify(Object.assign({}, leagueData));
    sendDataToController(sendDataUrl, jsonString);
  });

  function sendDataToController(Url) {
    const url = window.location.href;
    var parts = url.split("/");
    var idIndex = parts.indexOf("id");
    var idValue = parts[idIndex + 1];

    var jsonData = JSON.stringify(Object.assign({}, leagueData));
    // console.log(leagueData);
    jQuery.ajax({
      url: Url + "form_key/" + FORM_KEY,
      type: "POST",
      data: {
        data: jsonData,
        config_id: idValue,
      },
      dataType: "json",
      success: function (response) {
        console.log("Data sent successfully:", response);
      },
      error: function (xhr, status, error) {
        console.error("Error sending data:", error);
        console.log("XHR object:", xhr);
      },
    });
  }
});
