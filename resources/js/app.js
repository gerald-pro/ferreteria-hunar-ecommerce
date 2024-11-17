import './bootstrap';
import flatpickr from 'flatpickr';
import Swal from 'sweetalert2';
import TomSelect from 'tom-select';
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;
window.TomSelect = TomSelect;
window.Swal = Swal;


document.addEventListener('DOMContentLoaded', () => {
    const preloader = document.getElementById('preloader');

    if (preloader) {
        // Cambiar la opacidad a 0 para iniciar la transición
        preloader.style.opacity = '0';

        setTimeout(() => {
            preloader.style.display = 'none';
        }, 350);
    }

    const lightSwitches = document.querySelectorAll('.light-switch');
    const html = document.documentElement;

    // Función para actualizar el modo oscuro
    const updateDarkMode = (isDark) => {
        if (isDark) {
            html.classList.add('dark');
            html.style.colorScheme = 'dark';
            localStorage.setItem('dark-mode', 'true');
        } else {
            html.classList.remove('dark');
            html.style.colorScheme = 'light';
            localStorage.setItem('dark-mode', 'false');
        }
    };

    // Inicializar el modo oscuro basado en localStorage
    const storedDarkMode = localStorage.getItem('dark-mode');
    if (storedDarkMode) {
        updateDarkMode(storedDarkMode === 'true');
        lightSwitches.forEach(sw => sw.checked = (storedDarkMode === 'true'));
    }

    // Manejar cambios en el interruptor de modo oscuro
    if (lightSwitches.length > 0) {
        lightSwitches.forEach(lightSwitch => {
            lightSwitch.addEventListener('change', () => {
                updateDarkMode(lightSwitch.checked);
                lightSwitches.forEach(sw => sw.checked = lightSwitch.checked);
            });
        });
    }

    // Código para manejar los temas (niño, joven, adulto)
    const radios = document.querySelectorAll('input[name="theme-options"]');
    const themeClasses = ['theme-enfant', 'theme-jeune', 'theme-adult'];

    const updateTheme = (theme) => {
        themeClasses.forEach(cls => html.classList.remove(cls));
        const themeClass = `theme-${theme}`;
        if (themeClasses.includes(themeClass)) {
            localStorage.setItem('theme', themeClass);
            html.classList.add(themeClass);
        } else {
            localStorage.setItem('theme', 'theme-adult');
            html.classList.add('theme-adult');
        }
    };

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            updateTheme(this.value);
        });
    });

    // Inicializar el tema basado en localStorage
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
        const theme = storedTheme.replace('theme-', '');
        updateTheme(theme);
        const selectedRadio = document.getElementById(`option-${theme}`);
        if (selectedRadio) selectedRadio.checked = true;
    } else {
        updateTheme('adult');
        const selectedRadio = document.getElementById(`option-adult`);
        if (selectedRadio) selectedRadio.checked = true;
    }

    // Flatpickr
    flatpickr('.datepicker', {
        mode: 'range',
        static: true,
        monthSelectorType: 'static',
        dateFormat: 'M j, Y',
        defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
        prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
        nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
        onReady: (selectedDates, dateStr, instance) => {
            // eslint-disable-next-line no-param-reassign
            instance.element.value = dateStr.replace('to', '-');
            const customClass = instance.element.getAttribute('data-class');
            instance.calendarContainer.classList.add(customClass);
        },
        onChange: (selectedDates, dateStr, instance) => {
            // eslint-disable-next-line no-param-reassign
            instance.element.value = dateStr.replace('to', '-');
        },
    });
});
