document.addEventListener("DOMContentLoaded", () => {
    const searchInputs = document.querySelectorAll(".search-input, .mobile-search-input");
    const resultsDiv = document.getElementById("searchResults");

    let timer;

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".search-form")) {
            resultsDiv.style.display = "none";
        }
    });

    searchInputs.forEach((searchInput) => {
        searchInput.addEventListener("keyup", () => {
            clearTimeout(timer);
            const query = searchInput.value.trim();

            if (query.length < 2) {
                resultsDiv.style.display = "none";
                return;
            }

            timer = setTimeout(() => {
                fetch(`../search_products.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(products => {
                        resultsDiv.innerHTML = "";
                        if (products.length === 0) {
                            resultsDiv.style.display = "none";
                            return;
                        }
                        products.forEach(product => {
                            const div = document.createElement("div");
                            div.innerHTML = `
                                <a href="../pages/productinfo.php?productId=${product.ProductID}">
                                    ${product.ProductName} - R${product.Price}
                                </a>
                            `;
                            resultsDiv.appendChild(div);
                        });
                        resultsDiv.style.display = "block";
                    })
                    .catch(error => {
                        console.error("Search error:", error);
                    });
            }, 300);
        });
    });
});
