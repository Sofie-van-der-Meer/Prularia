const price = document.getElementById('icon-for-sort_price');
const a_z = document.getElementById('icon-for-sort_name');
const rating = document.getElementById('icon-for-sort_rating');

function sortProducts(sortType) {
    const btn = document.getElementById('sort_' + sortType);
    switch (sortType) {
        case 'price':
            (btn.dataset.dir == 'asc') ? 
            products.sort((a, b) => a.price - b.price) : 
            products.sort((a, b) => b.price - a.price);

            (btn.dataset.dir == 'asc') ?
            price.classList.replace('fa-arrow-down-short-wide', 'fa-arrow-up-short-wide') :
            price.classList.replace('fa-arrow-up-short-wide', 'fa-arrow-down-short-wide') ;
            break;
        case 'a-z':
            (btn.dataset.dir == 'asc') ? 
            products.sort((a, b) => a.name.localeCompare(b.name)) :
            products.sort((a, b) => b.name.localeCompare(a.name));

            (btn.dataset.dir == 'asc') ?
            a_z.classList.replace('fa-arrow-down-a-z', 'fa-arrow-up-a-z'):
            a_z.classList.replace('fa-arrow-up-a-z', 'fa-arrow-down-a-z') ;
            break;
        case 'rating':
            (btn.dataset.dir == 'asc') ? 
            products.sort((a, b) => b.averagescore - a.averagescore) :
            products.sort((a, b) => a.averagescore - b.averagescore);

            (btn.dataset.dir == 'asc') ?
            rating.classList.replace('fa-arrow-down-wide-short', 'fa-arrow-up-wide-short') :
            rating.classList.replace('fa-arrow-up-wide-short', 'fa-arrow-down-wide-short') ;
            break;
        default:
            console.error(`invalid sort type: ${sortType}`)
    }
    btn.dataset.dir = (btn.dataset.dir == 'asc') ? 'desc' : 'asc';
    showProducts();
}
