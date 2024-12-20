class ThemeManager {
  constructor() {
      this.themeToggle = document.querySelector('.theme-toggle');
      this.mobileThemeToggle = document.querySelector('.theme-toggle-mobile');
      this.body = document.body;
      this.darkModeKey = 'darkMode';
      
      this.init();
  }

  init() {
      // Check if toggle elements exist
      if (!this.themeToggle && !this.mobileThemeToggle) {
          console.error('Theme toggle elements not found');
          return;
      }

      // Set initial theme based on saved preference
      this.setInitialTheme();
      
      // Add event listeners
      if (this.themeToggle) {
          this.themeToggle.addEventListener('click', () => this.toggleTheme());
      }
      if (this.mobileThemeToggle) {
          this.mobileThemeToggle.addEventListener('click', () => this.toggleTheme());
      }
      
      // Optional: Listen for system theme changes
      this.watchSystemTheme();
  }

  setInitialTheme() {
      const savedTheme = localStorage.getItem(this.darkModeKey);
      
      if (savedTheme === 'dark') {
          this.enableDarkMode();
      } else if (savedTheme === 'light') {
          this.enableLightMode();
      } else {
          // If no saved preference, check system preference
          this.checkSystemTheme();
      }
  }

  enableDarkMode() {
      this.body.setAttribute('data-theme', 'dark');
      if (this.themeToggle) this.themeToggle.classList.add('dark');
      if (this.mobileThemeToggle) this.mobileThemeToggle.classList.add('dark');
      localStorage.setItem(this.darkModeKey, 'dark');
  }

  enableLightMode() {
      this.body.setAttribute('data-theme', 'light');
      if (this.themeToggle) this.themeToggle.classList.remove('dark');
      if (this.mobileThemeToggle) this.mobileThemeToggle.classList.remove('dark');
      localStorage.setItem(this.darkModeKey, 'light');
  }

  toggleTheme() {
      if (this.body.getAttribute('data-theme') === 'light') {
          this.enableDarkMode();
      } else {
          this.enableLightMode();
      }
  }

  checkSystemTheme() {
      // Check if user's system is set to dark mode
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          this.enableDarkMode();
      } else {
          this.enableLightMode();
      }
  }

  watchSystemTheme() {
      // Watch for system theme changes
      if (window.matchMedia) {
          window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
              if (!localStorage.getItem(this.darkModeKey)) {
                  // Only auto-switch if user hasn't set a preference
                  if (e.matches) {
                      this.enableDarkMode();
                  } else {
                      this.enableLightMode();
                  }
              }
          });
      }
  }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  new ThemeManager();
});