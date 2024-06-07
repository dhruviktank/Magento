var j = jQuery.noConflict();
var FileManagerGrid = new Class.create();
FileManagerGrid.prototype = {
  initialize: function () {

  }
}
// var selectedPath = null;
// function pathDropdownChanged(path, redirectUrl) {
//   // console.log(path);
//   selectedPath = path;
//   new Ajax.Request(redirectUrl, {
//     method: "post",
//     evalScripts: true,
//     parameters: { folderPath: btoa(path) },
//     onSuccess: function (response) {
//       $("gridContainer").update(response.responseText);
//     },
//   });
// }

// function inlineEdit(element) {
//   if (element.getElementsByTagName("input").length > 0) {
//     return;
//   }

//   var data = element.getAttribute("data");
//   var editUrl = element.getAttribute("data-edit-url");
//   var parsedData = JSON.parse(data);
//   // var oldFileName = parsedData.basename;
//   element.innerHTML = "";

//   var input = document.createElement("input");
//   input.type = "text";
//   input.value = parsedData.filename;
//   element.appendChild(input);

//   var saveButton = document.createElement("button");
//   saveButton.innerHTML = "Save";
//   saveButton.onclick = function (e) {
//     e.preventDefault();
//     var newFileName = input.value + "." + parsedData.extension;

//     var params = {
//       newFileName: newFileName,
//       oldFileName: parsedData.basename,
//       fullpath: parsedData.fullpath,
//       systemPath: parsedData.system_path,
//     };

//     var data = JSON.stringify(params);

//     new Ajax.Request(editUrl, {
//       method: "post",
//       parameters: { edited_data: data },
//       onSuccess: function (response) {
//         input.style.display = "none";
//         saveButton.style.display = "none";
//         cancelButton.style.display = "none";
//         element.innerHTML = input.value;
//       },
//     });
//   };
//   element.appendChild(saveButton);

//   var cancelButton = document.createElement("button");
//   cancelButton.innerHTML = "Cancel";
//   cancelButton.onclick = function (e) {
//     e.preventDefault();
//     e.stopPropagation();
//     input.style.display = "none";
//     saveButton.style.display = "none";
//     cancelButton.style.display = "none";
//     element.innerHTML = input.value;
//   };

//   element.appendChild(cancelButton);
// }
// -----------------------------------------------
var j = jQuery.noConflict();
var selectedPath = null;

function pathDropdownChanged(path, redirectUrl) {
  selectedPath = path;
  new Ajax.Request(redirectUrl, {
    method: "post",
    evalScripts: true,
    parameters: { folderPath: path },
    onSuccess: function (response) {
      $('gridContainer').update(response.responseText);
    },
  });
}

function inlineEdit(obj) {
  // Check if the element is already in edit mode
  if (obj.classList.contains('editing')) {
    return;
  }

  var data = obj.getAttribute('data');
  var editUrl = obj.getAttribute('data-url');
  data = JSON.parse(data);
  var filename = data.filename;
  var value = data.system_path;
  console.log(value);

  var input = document.createElement('input');
  input.type = 'text';
  input.value = filename;

  var saveButton = document.createElement('button');
  saveButton.innerText = 'Save';
  saveButton.onclick = function () {
    var newFilename = input.value;
    if (newFilename === filename) {
      console.log('Filename unchanged');
      return;
    }

    new Ajax.Request(editUrl, {
      method: "post",
      evalScripts: true,
      parameters: {
        value: value,
        oldFilename: data.fullpath,
        newFilename: newFilename + '.' + data.extension
      },
      onSuccess: function (response) {
        document.body.innerHTML = response.responseText;
      },
      onFailure: function () {
        console.log('Error renaming file');
      }
    });
  };

  var cancelButton = document.createElement('button');
  cancelButton.innerText = 'Cancel';
  cancelButton.onclick = function (event) {
    console.log(j(event.target).parent('div').eq(0))
    j(event.target).parent('div').eq(0).html('');
    // obj.innerHTML = filename;
    obj.classList.remove('editing');
  };

  obj.innerHTML = '';
  obj.appendChild(input);
  obj.appendChild(saveButton);
  obj.appendChild(cancelButton);

  // Add the 'editing' class to indicate that the element is in edit mode
  obj.classList.add('editing');
}

// -----------------------------------------------
// var selectedPath = null;
// function pathDropdownChanged(path, redirectUrl) {
//   selectedPath = path;
//   new Ajax.Request(redirectUrl, {
//     method: "post",
//     evalScripts: true,
//     parameters: { folderPath: path },
//     onSuccess: function (response) {
//       $('gridContainer').update(response.responseText);
//     },
//   });
// }

// function inlineEdit(obj) {
//   var data = obj.getAttribute('data');
//   data = JSON.parse(data);
//   var filename = data.filename;

//   var input = document.createElement('input');
//   input.type = 'text';
//   input.value = filename;

//   var saveButton = document.createElement('button');
//   saveButton.innerText = 'Save';
//   saveButton.onclick = function () {
//     console.log('Saved: ' + input.value);
//     obj.innerHTML = input.value;
//   };

//   var cancelButton = document.createElement('button');
//   cancelButton.innerText = 'Cancel';
//   cancelButton.onclick = function () {
//     console.log('Edit cancelled');
//     obj.innerHTML = '';
//     obj.appendChild(originalContent);
//     obj.innerHTML = input.value;
//   };

//   var originalContent = obj.cloneNode(true);

//   obj.innerHTML = '';
//   obj.appendChild(input);
//   // obj.appendChild(document.createElement('br')); // Add a line break
//   obj.appendChild(saveButton);
//   obj.appendChild(cancelButton);
// }

varienGrid.prototype.doFilter = function (element) {
  this.reloadParams = { folderPath: $F('path-dropdown') };

  var filters = $$(
    "#" + this.containerId + " .filter input",
    "#" + this.containerId + " .filter select"
  );
  var elements = [];
  for (var i in filters) {
    if (filters[i].value && filters[i].value.length) elements.push(filters[i]);
  }
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

varienGrid.prototype.doSort = function (event) {
  var element = Event.findElement(event, "a");
  this.reloadParams = { folderPath: $F('path-dropdown') };
  if (element.name && element.title) {
    this.addVarToUrl(this.sortVar, element.name);
    this.addVarToUrl(this.dirVar, element.title);
    this.reload(this.url);
  }
  Event.stop(event);
  return false;
};

varienGrid.prototype.resetFilter = function () {
  this.reloadParams = { folderPath: $F('path-dropdown') };
  this.reload(this.addVarToUrl(this.filterVar, ""));
};
varienGrid.prototype.setPage = function (pageNumber) {
  this.reloadParams = { folderPath: $F('path-dropdown') };
  this.reload(this.addVarToUrl(this.pageVar, pageNumber));
};
varienGrid.prototype.loadByElement = function (element) {
  this.reloadParams = { folderPath: $F('path-dropdown') };
  if (element && element.name) {
    this.reload(this.addVarToUrl(element.name, element.value));
  }
};