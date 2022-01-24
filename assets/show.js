// delete product
const products = document.getElementById("products");

if (products) {
  products.addEventListener("click", (e) => {
    if (e.target.className === "btn btn-sm btn-danger px-3 d-inline") {
      if (confirm("Are you sure?")) {
        const id = e.target.getAttribute("data-id");

        fetch(`/delete-product/${id}`, {
          method: "DELETE",
        }).then((res) => window.location.reload());
      }
    }
  });
}

//select products to export

const exportButton = document.getElementById("export-button");

if (exportButton) {
  exportButton.addEventListener("click", (e) => {
    e.preventDefault;

    const itemsSelectors = document.querySelectorAll(
      'input[name="item-selector"]:checked'
    );

    if (itemsSelectors.length) {
      const ids = [];
      itemsSelectors.forEach((itemSelector) => {
        const id = itemSelector.getAttribute("data-id");
        ids.push(id);
      });
      if (ids) {
        const url = `/export/products?ids=` + ids;

        window.location.href = url;
      }
    } else {
      alert("Select products...");
    }
  });
}

// max products per page

const rangeSelector = document.getElementById("maxproductsperpage");

if (rangeSelector) {
  rangeSelector.addEventListener("change", () => {
    const value = rangeSelector.options[rangeSelector.selectedIndex].value;
    const url = `/show/${value}`;
    console.log(url);

    window.location.href = url;
  });
}
