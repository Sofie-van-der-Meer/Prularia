const amountOfProducts = products.length;
const amountOfPages = Math.ceil(amountOfProducts / fullLength);
const btnPreviousPage = document.getElementById('btn_previous-page');
const firstPage = document.getElementById('first-page');
const pagesBefore = document.getElementById('pages-before');
const previousPage = document.getElementById('previous-page');
const currentPage = document.getElementById('current-page');
const nextPage = document.getElementById('next-page');
const pagesAfter = document.getElementById('pages-after');
const lastPage = document.getElementById('last-page');
const btnNextPage = document.getElementById('btn_next-page');

console.log(amountOfProducts);
console.log(amountOfPages);
console.log(lastPage);

lastPage.innerText = amountOfPages;

function switchPage(amount) {
    switch (amount) {
        case -1:
        case 1:
            page += amount;
            break;
        case 'first':
            page = 1;
            break; 
        case 'last':
            page = Number(lastPage.innerText);
            break;   
        default:
            break;
    }
    switch (page) {
        case 1:
            btnPreviousPage.hidden = true;
            firstPage.hidden = true;
            pagesBefore.hidden = true;
            previousPage.hidden = true;

            nextPage.hidden = false;
            pagesAfter.hidden = false;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
        case 2:
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = true;
            previousPage.hidden = true;

            nextPage.hidden = false;
            pagesAfter.hidden = false;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
        case 3:
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = true;
            previousPage.hidden = false;

            nextPage.hidden = false;
            pagesAfter.hidden = false;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
        case amountOfPages - 2:
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = false;
            previousPage.hidden = false;

            nextPage.hidden = false;
            pagesAfter.hidden = true;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
        case amountOfPages - 1:
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = false;
            previousPage.hidden = false;

            nextPage.hidden = true;
            pagesAfter.hidden = true;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
        case amountOfPages:
            console.log('yes');
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = false;
            previousPage.hidden = false;

            nextPage.hidden = true;
            pagesAfter.hidden = true;
            lastPage.hidden = true;
            btnNextPage.hidden = true;
            break;
    
        default:
            btnPreviousPage.hidden = false;
            firstPage.hidden = false;
            pagesBefore.hidden = false;
            previousPage.hidden = false;

            nextPage.hidden = false;
            pagesAfter.hidden = false;
            lastPage.hidden = false;
            btnNextPage.hidden = false;
            break;
    }
    console.log(page);
    console.log(amountOfPages);
    console.log(amountOfPages == page);
    previousPage.innerText = page - 1;
    currentPage.innerText = page;
    nextPage.innerText = page + 1;
    showProducts();
}
