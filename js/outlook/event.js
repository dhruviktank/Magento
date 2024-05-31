var EventManager = Class.create({
    initialize: function (addEventButtonId, eventTablesContainerId) {
        this.eventCount = 0;
        this.eventTablesContainer = $(eventTablesContainerId);
        this.addEventButton = $(addEventButtonId);
        this.addEventButton.observe("click", this.addNewEventTable.bind(this));
    },

    addNewEventTable: function (event) {
        event.stop();
        this.eventCount++;
        var groupID = this.eventCount;
        var table = document.createElement("table");
        table.setAttribute("id", "event-table-" + groupID);
        table.setAttribute("border", "1");

        var thead = document.createElement("thead");
        var trHead = document.createElement("tr");
        var headers = ["Field", "Condition", "Value", "Event", "Delete Event"];
        headers.forEach(function (headerText) {
            var th = document.createElement("th");
            th.textContent = headerText;
            trHead.appendChild(th);
        });
        thead.appendChild(trHead);
        table.appendChild(thead);

        var tbody = document.createElement("tbody");
        var trBody = document.createElement("tr");
        trBody.classList.add("event-" + groupID);

        // Field Dropdown
        var tdField = document.createElement("td");
        var selectField = document.createElement("select");
        selectField.classList.add("field-select");
        var fields = ["from", "to", "subject"];
        fields.forEach(function (field) {
            var option = document.createElement("option");
            option.setAttribute("value", field);
            option.textContent = field.charAt(0).toUpperCase() + field.slice(1);
            selectField.appendChild(option);
        });
        tdField.appendChild(selectField);
        trBody.appendChild(tdField);

        // Condition Dropdown
        var tdCondition = document.createElement("td");
        var selectCondition = document.createElement("select");
        selectCondition.classList.add("condition-select");
        var conditions = ["Equals", "Contains"];
        conditions.forEach(function (condition) {
            var option = document.createElement("option");
            option.textContent = condition;
            selectCondition.appendChild(option);
        });
        tdCondition.appendChild(selectCondition);
        trBody.appendChild(tdCondition);

        // Value Input
        var tdValue = document.createElement("td");
        var inputValue = document.createElement("input");
        inputValue.setAttribute("type", "text");
        inputValue.classList.add("value-input");
        tdValue.appendChild(inputValue);
        trBody.appendChild(tdValue);

        // Event Dropdown
        var tdEvent = document.createElement("td");
        tdEvent.setAttribute("rowspan", "1");
        var inputEvent = document.createElement("input");
        inputEvent.setAttribute("type", "text");
        inputEvent.classList.add("event-input");
        tdEvent.appendChild(inputEvent);
        trBody.appendChild(tdEvent);
    

        var addConditionBtn = document.createElement("button");
        addConditionBtn.textContent = "Add Condition";
        addConditionBtn.classList.add("add-condition");
        addConditionBtn.setAttribute("data-groupid", groupID);
        tdEvent.appendChild(addConditionBtn);

        var deleteConditionBtn = document.createElement("button");
        deleteConditionBtn.textContent = "Delete Condition";
        deleteConditionBtn.classList.add("delete-condition");
        tdEvent.appendChild(deleteConditionBtn);

        trBody.appendChild(tdEvent);

        // Delete Event Button
        var deleteEventBtn = document.createElement("button");
        deleteEventBtn.textContent = "Delete Event";
        deleteEventBtn.classList.add("delete-event");
        deleteEventBtn.setAttribute("data-groupid", groupID);
        var tdDeleteEvent = document.createElement("td");
        tdDeleteEvent.appendChild(deleteEventBtn);
        trBody.appendChild(tdDeleteEvent);

        tbody.appendChild(trBody);
        table.appendChild(tbody);
        this.eventTablesContainer.appendChild(table);

        $$("#event-table-" + groupID + " .add-condition").invoke(
            "observe",
            "click",
            this.addConditionRow.bind(this, groupID)
        );
        $$("#event-table-" + groupID + " .delete-condition").invoke(
            "observe",
            "click",
            this.deleteCondition.bind(this, groupID)
        );
        $$("#event-table-" + groupID + " .delete-event").invoke(
            "observe",
            "click",
            this.deleteEvent.bind(this, groupID)
        );
    },

    addConditionRow: function (groupID, event) {
        event.stop(); // Prevent the default behavior of the button
        var newRow = document.createElement("tr");
        newRow.classList.add("event-" + groupID);

        // Field Dropdown
        var tdField = document.createElement("td");
        var selectField = document.createElement("select");
        selectField.classList.add("field-select");
        var fields = ["from", "to", "subject"];
        fields.forEach(function (field) {
            var option = document.createElement("option");
            option.setAttribute("value", field);
            option.textContent = field.charAt(0).toUpperCase() + field.slice(1);
            selectField.appendChild(option);
        });
        tdField.appendChild(selectField);
        newRow.appendChild(tdField);

        // Condition Dropdown
        var tdCondition = document.createElement("td");
        var selectCondition = document.createElement("select");
        selectCondition.classList.add("condition-select");
        var conditions = ["Equals", "Contains"];
        conditions.forEach(function (condition) {
            var option = document.createElement("option");
            option.textContent = condition;
            selectCondition.appendChild(option);
        });
        tdCondition.appendChild(selectCondition);
        newRow.appendChild(tdCondition);

        // Value Input
        var tdValue = document.createElement("td");
        var inputValue = document.createElement("input");
        inputValue.setAttribute("type", "text");
        inputValue.classList.add("value-input");
        tdValue.appendChild(inputValue);
        newRow.appendChild(tdValue);

        $$("#event-table-" + groupID + " tbody tr")
            .last()
            .insert({ after: newRow });
        this.updateEventRowSpan(groupID);
    },

    updateEventRowSpan: function (groupID) {
        var rowCount = $$("#event-table-" + groupID + " .event-" + groupID).length;
        $$("#event-table-" + groupID + " .event-section").invoke(
            "writeAttribute",
            "rowspan",
            rowCount
        );
    },

    deleteCondition: function (groupID, event) {
        event.stop(); // Prevent the default behavior of the button
        var rowCount = $$("#event-table-" + groupID + " .event-" + groupID).length;
        if (rowCount > 1) {
            $$("#event-table-" + groupID + " .event-" + groupID)
                .last()
                .remove();
            this.updateEventRowSpan(groupID);
        }
    },

    deleteEvent: function (groupID) {
        $("event-table-" + groupID).remove();
        this.eventCount--;
    },

    collectEventData: function() {
        var eventDataGroups = {}; // Object to store event data grouped by group_id
        var eventTables = $$("#event-tables-container table");
        eventTables.forEach(function(table) {
            var groupID = table.getAttribute("id").split("-").pop();
            var constEvent = null; // Initialize the constEvent variable
    
            var rows = $$("#" + table.getAttribute("id") + " tbody tr");
    
            rows.forEach(function(row) {
                var field = row.querySelector(".field-select").value;
                var condition = row.querySelector(".condition-select").value;
                var value = row.querySelector(".value-input").value;
    
                // Check if the event input exists and assign it to constEvent if it does
                var eventInput = row.querySelector(".event-input");
                if (eventInput && eventInput.value) {
                    constEvent = eventInput.value;
                }
    
                // Use the constEvent value for the event field
                var event = constEvent;
    
                // Add event data to the corresponding group_id
                if (!eventDataGroups[groupID]) {
                    eventDataGroups[groupID] = [];
                }
                eventDataGroups[groupID].push({
                    field: field,
                    condition: condition,
                    value: value,
                    event: event
                });
            });
        });
    
        return eventDataGroups;
    },

    sendEventData: function() {
        // event.stop;
        var eventData = this.collectEventData();
        // console.log(eventData);
        // return;
        new Ajax.Request('http://127.0.0.1/magento/index.php/admin/event/save/form_key/'+ FORM_KEY, {
            method: 'post',
            contentType: 'application/json',
            postBody: JSON.stringify({ events: eventData }),
            onSuccess: function(response) {
                // Handle successful response
                console.log('Data saved successfully');
            },
            onFailure: function(response) {
                // Handle error response
                console.log('Failed to save data');
            }
        });
    }
});
