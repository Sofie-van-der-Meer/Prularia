class ProductSearch {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.searchDropdown = document.getElementById('searchDropdown');
        this.products = document.querySelectorAll('.product-card');
        this.searchButton = document.querySelector('.search-button');
        
        this.init();
    }
  
    init() {
        if (!this.validateElements()) return;
        this.setupEventListeners();
    }
  
    validateElements() {
        if (!this.searchInput || !this.searchDropdown || !this.products.length) {
            console.error('Required elements not found');
            return false;
        }
        return true;
    }
  
    setupEventListeners() {
        // Live search voor dropdown
        this.searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim().toLowerCase();
            if (searchTerm.length >= 2) {
                this.updateDropdown(searchTerm);
            } else {
                this.hideDropdown();
            }
        });
  
        // Enter toets voor direct zoeken
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.searchInput.value.trim().toLowerCase();
                this.performFullSearch(searchTerm);
                this.hideDropdown();
            }
        });
  
        // Click buiten dropdown om te sluiten
        document.addEventListener('click', (e) => {
            if (!this.searchDropdown.contains(e.target) && 
                !this.searchInput.contains(e.target)) {
                this.hideDropdown();
            }
        });
  
        // Search button
        if (this.searchButton) {
            this.searchButton.addEventListener('click', () => {
                const searchTerm = this.searchInput.value.trim().toLowerCase();
                this.performFullSearch(searchTerm);
                this.hideDropdown();
            });
        }
    }
  
    updateDropdown(searchTerm) {
        this.searchDropdown.innerHTML = '';
        let resultsFound = false;
  
        this.products.forEach(product => {
            if (this.productMatchesSearch(product, searchTerm)) {
                this.addProductToDropdown(product);
                resultsFound = true;
            }
        });
  
        if (resultsFound) {
            this.showDropdown();
        } else {
            this.showNoResultsDropdown();
        }
    }
  
    addProductToDropdown(product) {
        const item = document.createElement('div');
        item.classList.add('search-dropdown-item');
  
        const title = product.querySelector('.card-title').textContent;
        const price = product.querySelector('.price').textContent;
  
        item.innerHTML = `
            <div class="dropdown-item-content">
                <span class="dropdown-item-title">${this.escapeHtml(title)}</span>
                <span class="dropdown-item-price">${this.escapeHtml(price)}</span>
            </div>
        `;
  
        item.addEventListener('click', () => {
            this.selectProduct(product);
        });
  
        this.searchDropdown.appendChild(item);
    }
  
    performFullSearch(searchTerm) {
        if (!searchTerm) {
            this.showAllProducts();
            return;
        }
  
        let foundMatch = false;
        this.products.forEach(product => {
            if (this.productMatchesSearch(product, searchTerm)) {
                product.style.display = 'block';
                foundMatch = true;
            } else {
                product.style.display = 'none';
            }
        });
  
        if (!foundMatch) {
            // Toon eventueel een "geen resultaten" bericht
            console.log('Geen producten gevonden');
        }
    }
  
    productMatchesSearch(product, searchTerm) {
        const searchFields = [
            '.card-title',
            '.description',
            '.price',
            '.details'
        ].map(selector => product.querySelector(selector)?.textContent?.toLowerCase() || '');
  
        return searchFields.some(field => field.includes(searchTerm));
    }
  
    selectProduct(product) {
        const title = product.querySelector('.card-title').textContent;
        this.searchInput.value = title;
        this.performFullSearch(title);
        this.hideDropdown();
    }
  
    showNoResultsDropdown() {
        this.searchDropdown.innerHTML = `
            <div class="no-results">
                Geen producten gevonden
            </div>
        `;
        this.showDropdown();
    }
  
    showAllProducts() {
        this.products.forEach(product => {
            product.style.display = 'block';
        });
    }
  
    showDropdown() {
        this.searchDropdown.style.display = 'block';
    }
  
    hideDropdown() {
        this.searchDropdown.style.display = 'none';
    }
  
    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    new ProductSearch();
  });