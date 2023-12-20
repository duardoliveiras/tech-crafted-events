function getUsers(eventId) {
  var name = document.getElementById("userSearch").value;
  if (name != "") {
    var url = `/users/` + name + `/event/` + eventId;
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw Error(response.statusText);
        }
        return response.json();
      })
      .then((data) => {
        document.getElementById("listUser").innerHTML = "";
        if (data.length == 0) {
          var cardHtml =
            '<li class="list-group-item d-flex justify-content-between align-items-center">';
          cardHtml += "No user found";
          cardHtml += "</li>";
          document
            .getElementById("listUser")
            .insertAdjacentHTML("beforeend", cardHtml);
        } else {
          data.forEach((user) => {
            const newRow = document.createElement("li");
            newRow.id = `${user.id}`;
            newRow.className =
              "list-group-item d-flex justify-content-between align-items-center";
            newRow.innerHTML = `
                        ${user.name}
                        <button type="button" class="btn btn-success" onclick="inviteUser('${user.id}')">Invite</button>
                    `;
            document.getElementById("listUser").appendChild(newRow);
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
  }
}

function inviteUser(id) {
  document.getElementById("listUser").innerHTML = "";
  const newRow = document.createElement("li");
  newRow.className =
    "list-group-item border-success text-success d-flex justify-content-between align-items-center";
  newRow.innerHTML = `User invited`;
  document.getElementById("listUser").appendChild(newRow);

  var url = "/invite/" + id + "/event/" + eventId;
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
      console.log("invite!");
    })
    .catch((error) => {
      console.error("Erro", error);
    });
}
