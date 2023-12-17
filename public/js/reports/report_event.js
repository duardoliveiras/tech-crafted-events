var rowsPerPag = 5;
var eventIdGlobal;
var userIdGlobal;

function getEventReportsView(id, name, type) {
  var title = document.getElementById("reportModalLabel");
  title.innerText = "Report " + name;
  var reason = document.getElementById("reportReason");
  reason.value = "All";
  console.log(type);
  if (type == "event") {
    eventIdGlobal = id;
    reason.onchange = function () {
      getEventReports(id, 1);
    };
    getEventReports(id, 1);
  } else {
    userIdGlobal = id;
    reason.onchange = function () {
      getCommentReports(id, 1);
    };
    getCommentReports(id, 1);
  }
}

function getEventReports(eventId, page) {
  var reason = document.getElementById("reportReason").value;

  fetch(`/admin/reports/reports-events/${eventId}/${reason}`)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      document.getElementById("tableBody").innerHTML = "";
      pagination(data);

      if (data.length > 0) {
        var curr_pag = document.getElementById("pg" + page);
        curr_pag.classList.add("disabled");

        var i = (page - 1) * 5;
        var len = i + rowsPerPag;

        var len = data.length - i < 5 ? data.length : i + rowsPerPag;

        for (i; i < len; i++) {
          var report = data[i];
          const newRow = document.createElement("tr");
          newRow.id = `${report.id}`;
          newRow.innerHTML = `
                        <td>${report.user.name}</td>
                        <td>${report.reason}</td>
                        <td>${report.description}</td>
                        <td>
                            <button id="check" type="button" class="btn btn-success" onclick="checkOneReportEvent('${report.id}')">Check</button>
                        </td>
                    `;
          document.getElementById("tableBody").appendChild(newRow);
        }
      }
    })

    .catch((error) => {
      console.log(error);
    });
}

function getCommentReports(userId, page) {
  var reason = document.getElementById("reportReason").value;
  console.log(reason);
  fetch(`/admin/reports/reports-comments/${userId}/${reason}`)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      document.getElementById("tableBody").innerHTML = "";
      pagination(data);

      if (data.length > 0) {
        var curr_pag = document.getElementById("pg" + page);
        curr_pag.classList.add("disabled");

        var i = (page - 1) * 5;
        var len = i + rowsPerPag;

        var len = data.length - i < 5 ? data.length : i + rowsPerPag;

        for (i; i < len; i++) {
          var report = data[i];
          const newRow = document.createElement("tr");
          newRow.id = `${report.id}`;
          newRow.innerHTML = `
                        <td>${report.user.name}</td>
                        <td>${report.reason}</td>
                        <td>${report.description}</td>
                        <td>
                            <button id="check" type="button" class="btn btn-success" onclick="checkOneReportComment('${report.id}')">Check</button>
                        </td>
                    `;
          document.getElementById("tableBody").appendChild(newRow);
        }
      }
    })

    .catch((error) => {
      console.log(error);
    });
}

function pagination(data) {
  var keys = Object.keys(data);
  var qt = keys.length;
  var pags = Math.ceil(qt / rowsPerPag);
  var elementoHTML = "";

  document.getElementById("pagination").innerHTML = "";
  for (var i = 1; i <= pags; i++) {
    elementoHTML +=
      '<li class="page-item" id="pg' +
      i +
      '"><a class="page-link" href="javascript:void(0);" onclick="getEventReports(\'' +
      eventIdGlobal +
      "', " +
      i +
      ')">' +
      i +
      "</a></li>";
  }
  document
    .getElementById("pagination")
    .insertAdjacentHTML("beforeend", elementoHTML);
}

function checkOneReportEvent(id) {
  var url = "/admin/reports/check-event/" + id;
  console.log(url);
  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      console.log("check!");
      getEventReports(eventIdGlobal, 1);
      //updateLineColor(id);
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}

function checkOneReportComment(id) {
  var url = "/admin/reports/check-comment/" + id;
  console.log(url);
  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      console.log("check!");
      getCommentReports(userIdGlobal, 1);
      //updateLineColor(id);
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}

// wear out
function updateLineColor(id) {
  var currLine = document.getElementById(id);
  currLine.classList.add("table-success");
  var checkBtn = currLine.querySelector("#check");
  checkBtn.classList.remove("btn-success");
  checkBtn.classList.add("btn-secondary");
}

function banEvent(eventId) {
  var url = "/admin/reports/ban/event/" + eventId;

  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      window.location = route_reports;
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}

function banComment(commentId) {
  var url = "/admin/reports/ban/comment/" + commentId;
  console.log(url);
  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      window.location = route_reports;
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}

function check_all_event(eventId) {
  var url = "/events/" + eventId + "/check-all-event";
  console.log(url);

  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      window.location.href = "/admin/reports";
    })

    .catch((error) => {
      console.error("Erro", error);
    });
}

function check_all_comment(userId) {
  var url = "/events/" + userId + "/check-all-comment";
  console.log(url);

  var options = {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({}),
  };

  fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      window.location.href = "/admin/reports";
    })

    .catch((error) => {
      console.error("Erro", error);
    });
}
