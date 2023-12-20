function getNotifications() {
  document.getElementById("btnEvent").classList.add("active");
  document.getElementById("btnInvite").classList.remove("active");

  fetch("/load-notifications")
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      console.log(data);
      document.getElementById("notificacoesContainer").innerHTML = "";

      data.forEach((notification) => {
        var cardHtml = '<div class="card mb-4 border-0 shadow-sm">';
        cardHtml += '<div class="card-body">';
        cardHtml += '<div class="d-flex align-items-center">';
        cardHtml +=
          '<img class="rounded-circle shadow-1-strong me-2" src="' +
          assetUrl +
          "/" +
          notification.event_notification.event.image_url +
          '" alt="' +
          "/" +
          notification.event_notification.event.name +
          '" width="50" height="50"/>';
        cardHtml +=
          '<p class="card-text text-muted mb-4">' +
          notification.event_notification.notification_text +
          "</p>";
        cardHtml += "</div>";
        cardHtml +=
          '<a href="' +
          routeEventsShow.replace(
            ":id",
            notification.event_notification.event.id
          ) +
          '" class="btn btn-primary m-1 btn-sm">';
        cardHtml += '<i class="bi bi-eye"></i> View Event </a>';
        cardHtml +=
          '<button onclick="readNotification(' +
          notification.id +
          ',\'notification\')" class="btn btn-secondary btn-sm">Read</button>';
        cardHtml += "</div>";
        cardHtml += "</div>";

        document
          .getElementById("notificacoesContainer")
          .insertAdjacentHTML("beforeend", cardHtml);
      });
    })

    .catch((error) => {
      console.log(error);
    });
}

function getInvites() {
  document.getElementById("btnInvite").classList.add("active");
  document.getElementById("btnEvent").classList.remove("active");
  fetch("/load-invites")
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      var notificacoesContainer = document.getElementById(
        "notificacoesContainer"
      );

      notificacoesContainer.innerHTML = "";

      data.forEach((invite) => {
        var cardHtml = '<div class="card mb-4 border-0 shadow-sm">';
        cardHtml += '<div class="card-body">';
        cardHtml += '<div class="d-flex align-items-center">';
        cardHtml +=
          '<img class="rounded-circle shadow-1-strong me-2" src="' +
          assetUrl +
          "/" +
          invite.events.image_url +
          '" alt="' +
          "/" +
          invite.events.name +
          '" width="50" height="50"/>';
        cardHtml +=
          '<p class="card-text text-muted mb-4">' + invite.text + "</p>";
        cardHtml += "</div>";
        cardHtml +=
          '<a href="' +
          routeEventsShow.replace(":id", invite.events.id) +
          '" class="btn btn-primary m-1 btn-sm">';
        cardHtml += '<i class="bi bi-eye"></i> View Event </a>';
        cardHtml +=
          "<button onclick=\"acquireInvite('" +
          invite.events.id +
          '\')" class="btn btn-success btn-sm">Accept</button>';
        cardHtml +=
          "<button onclick=\"readNotification('" +
          invite.events.id +
          "', 'invite')\" class=\"m-1 btn btn-danger btn-sm\">Reject</button>";

        cardHtml += "</div>";
        cardHtml += "</div>";

        document
          .getElementById("notificacoesContainer")
          .insertAdjacentHTML("beforeend", cardHtml);
      });
    })
    .catch((error) => {
      console.log(error);
    });
}

function acquireInvite(eventId) {
  var url = "/events/" + eventId + "/ticket/invite";
  var options = {
    method: "POST",
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
      readNotification(eventId, "invite");
      return response.json();
    })
    .then((data) => {
      //window.location.href = data.redirect;
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}

function updateRead() {
  document.getElementById("formUpdateRead").submit();
}

function readNotification(notificationId, type) {
  var url = "/update-read/" + type + "/" + notificationId;

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
      type == "notification" ? getNotifications() : getInvites();
      return response.json();
    })
    .then((data) => {
      if (data.qt_notification == 1) {
        const icon = document.getElementById("notification-ico");
        icon.innerHTML =
          '<svg xmlns=" http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 448 512" style="fill: rgba(0, 0, 0, 0.65);"> <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z" /> </svg>';
      }
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}
