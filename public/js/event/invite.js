function getUsers() {
  var name = document.getElementById("userSearch").value;
  var url = `/users/` + name;
  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw Error(response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      document.getElementById("listUser").innerHTML = "";
      data.forEach((user) => {
        var cardHtml =
          '<li class="list-group-item d-flex justify-content-between align-items-center">';
        cardHtml += user.name;
        cardHtml +=
          '<button type="button" class="btn btn-success">Invite</button>';
        cardHtml += "</li>";
        document
          .getElementById("listUser")
          .insertAdjacentHTML("beforeend", cardHtml);
      });
    })
    .catch((error) => {
      console.log(error);
    });
}
