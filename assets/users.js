//delete user

const users = document.getElementById("users");

if (users) {
  users.addEventListener("click", (e) => {
    if (e.target.getAttribute("data-action") === "delete") {
      if (confirm("Are you sure?")) {
        const id = e.target.getAttribute("data-id");

        fetch(`/delete-user/${id}`, { method: "DELETE" }).then((res) =>
          window.location.reload()
        );
      }
    }
  });
}

// active/disactive account

const accountCheckboxes = document.querySelectorAll("[data-account]");

if (accountCheckboxes) {
  accountCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      const id = checkbox.dataset.id;

      fetch(`/change-status/${id}`, { method: "POST" }).then((res) =>
        window.location.reload()
      );
    });
  });
}
