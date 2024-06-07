var j = jQuery.noConflict();
var EventManager = Class.create({
    initialize: function (containerId, addButtonId, saveButtonId, eventTableContainerId, saveUrl, configId, fetchUrl) {
        this.containerId = containerId;
        this.eventTableContainerId = eventTableContainerId;
        this.fetchUrl = fetchUrl;
        this.saveUrl = saveUrl;
        this.configId = configId;
        this.addButtonId = addButtonId;
        this.saveButtonId = saveButtonId;
        this.onAddEvent = this.addEvent.bindAsEventListener(this);
        this.onAddCondition = this.addCondition.bindAsEventListener(this);
        this.onDeleteEvent = this.removeEvent.bindAsEventListener(this);
        this.onDeleteCondition = this.removeCondition.bindAsEventListener(this);
        this.onSaveEvent = this.saveEvent.bindAsEventListener(this);
        this.initEventManager();
    },
    initEventManager: function () {
        $(this.addButtonId).observe("click", this.onAddEvent);
        $(this.saveButtonId).observe("click", this.onSaveEvent);
        this.loadEvents();
    },
    loadEvents: function () {
        this.fetchEvents()
            .then((response) => {
                const events = {};
                response.forEach((event) => {
                    if (!events[event.group_id]) {
                        events[event.group_id] = [];
                    }
                    events[event.group_id].push({
                        field: event.field,
                        condition: event.condition,
                        value: event.value,
                        event: event.event,
                        eventId: event.event_id,
                    });
                });

                Object.keys(events).forEach((groupId) => {
                    this.addEventWithConditions(events[groupId], groupId);
                });
            })
            .catch((error) => {
                console.error('Failed to load events:', error);
            });
    },
    fetchEvents: function () {
        var self = this;
        return new Promise(function (resolve, reject) {
            new Ajax.Request(self.fetchUrl, {
                parameters: { 'configId': self.configId },
                evalScripts: true,
                method: 'post',
                onFailure: function (e) {
                    reject(e);
                },
                onSuccess: function (response) {
                    resolve(JSON.parse(response.responseText));
                },
            });
        });
    },
    addEventWithConditions: function (conditions, groupId) {
        const eventTableContainer = $(this.eventTableContainerId);
        const table = new Element('table', { 'border': 1, 'class': 'event-table' });
        j(table).data('groupId', groupId);

        table.insert(this.prepareHeaders());
        const tbody = new Element('tbody');
        
        conditions.forEach((condition, index) => {
            const isFirstCondition = index === 0;
            const isLastCondition = index === conditions.length - 1;
            tbody.insert(this.prepareCondition(isFirstCondition, condition, isLastCondition));
        });

        table.insert(tbody);
        eventTableContainer.insert(table);
    },
    addEvent: function (event) {
        event.stop();
        var eventTableContainer = $(this.eventTableContainerId);
        var eventTable = $$('#' + this.eventTableContainerId + ' .event-table');
        var table = new Element('table', { 'border': 1, 'class': 'event-table' });
        j(table).data('groupId', eventTable.length + 1)

        table.insert(this.prepareHeaders());
        var tbody = new Element('tbody');
        tbody.insert(this.prepareCondition(true));
        table.insert(tbody);
        eventTableContainer.insert(table);
    },

    removeEvent: function (event) {
        event.stop();
        j(event.target).closest('table').eq(0).remove();
    },
    prepareHeaders: function () {
        var thead = new Element('thead');
        var tr = new Element('tr');
        ["Field", "Condition", "Value", "", "Event", "Delete Event"].forEach(function (headerLabel) {
            var th = new Element('th').update(headerLabel);
            tr.insert(th);
        });
        thead.insert(tr);
        return thead;
    },

    addCondition: function (event) {
        event.stop();
        var table = j(event.target).closest('table').eq(0);
        var newRowSpan = Number(table.find('.event-input').attr('rowspan')) + 1
        table.find('.event-input').attr('rowspan', newRowSpan);
        table.find('.event-remove-button').attr('rowspan', newRowSpan);
        table.find('tbody').eq(0).append(this.prepareCondition(false, {}, true));
        (event.target).stopObserving("click", this.onAddCondition);
        (event.target).observe("click", this.onDeleteCondition);
        (event.target).update('Delete Condition');
    },
    removeCondition: function (event) {
        event.stop();
        var conditionRow = j(event.target).closest('tr');
        var table = j(event.target).closest('table').eq(0);
        var newRowSpan = Number(table.find('.event-input').attr('rowspan')) - 1;
        table.find('.event-input').attr('rowspan', newRowSpan);
        table.find('.event-remove-button').attr('rowspan', newRowSpan);
        var tds = conditionRow.find('td');
        if (tds.length == 6) {
            conditionRow.next().append(conditionRow.find('td').slice(4))
            conditionRow.remove();
        } else {
            conditionRow.remove();
        }
    },
    prepareCondition: function (isFirst, condition = {}, isLast = false) {
        const tr = new Element('tr');
        const fieldTd = new Element('td').update(this.createDropDown(['from', 'to', 'subject'], condition.field));
        const conditionTd = new Element('td').update(this.createDropDown(['Equals', 'Contains'], condition.condition));
        const inputTd = new Element('td').update(new Element('input', { 'value': condition.value || '' }));
        
        const actionBtn = new Element('button').update(isLast ? 'Add Condition' : 'Delete Condition');
        actionBtn.observe("click", isLast ? this.onAddCondition : this.onDeleteCondition);
        const actionTd = new Element('td').update(actionBtn);
        
        tr.insert(fieldTd);
        tr.insert(conditionTd);
        tr.insert(inputTd);
        tr.insert(actionTd);
        j(tr).data('eventId', condition.eventId);
        
        if (isFirst) {
            const eventInput = new Element('input', { 'type': 'text', 'value': condition.event || '' });
            tr.insert(new Element('td', { 'rowspan': 1, 'class': 'event-input' }).update(eventInput));
            const removeEventBtn = new Element('button').update('Remove Event');
            removeEventBtn.observe("click", this.onDeleteEvent);
            tr.insert(new Element('td', { 'rowspan': 1, 'class': 'event-remove-button' }).update(removeEventBtn));
        }
        
        return tr;
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
    prepareEventData: function () {
        const eventTables = $$('#' + this.eventTableContainerId + ' table');
        const data = [];
        const configId = this.configId;

        eventTables.each((table) => {
            const groupId = Number(j(table).data('groupId'));
            const event = j(table).find('.event-input').find('input').val();
            const conditions = [];

            j(table).find('tbody').find('tr').each(function () {
                const tds = j(this).find('td');
                console.log(j(this));
                const condition = {
                    field: tds.eq(0).find('select').val(),
                    condition: tds.eq(1).find('select').val(),
                    value: tds.eq(2).find('input').val(),
                    eventId: j(this).data('eventId'),
                    event: event
                };
                conditions.push(condition);
            });

            const obj = {
                groupId: groupId,
                configId: configId,
                condition: conditions
            };
            data.push(obj);
        });
        return data;
    },

    saveEvent: function (event) {
        event.stop();
        var data = this.prepareEventData();
        new Ajax.Request(this.saveUrl, {
            parameters: { 'data': JSON.stringify(data), 'configId': this.configId },
            method: 'post',
            onFailure: function (error) {
                alert('Failed to Save ' + error);
            },
            onSuccess: function (response) {
                console.log(response);
            },
        });
    },

});
