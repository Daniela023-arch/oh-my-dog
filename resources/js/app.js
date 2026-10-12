
import '../css/app.css';

/*
|--------------------------------------------------------------------------
| OH MY DOG! - JAVASCRIPT PRINCIPAL
|--------------------------------------------------------------------------
| Tema claro/oscuro, navegación, confirmaciones y citas.
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {
    initializeTheme();
    initializeSidebar();
    initializePublicMenu();
    initializeConfirmations();
    initializeAlerts();
    initializeAppointmentForm();
});

/* =========================================================
   TEMA CLARO Y OSCURO
========================================================= */

function initializeTheme() {
    const buttons = document.querySelectorAll(
        '[data-theme-toggle]'
    );

    const savedTheme = localStorage.getItem('ohmydog-theme');

    const preferredTheme = window.matchMedia(
        '(prefers-color-scheme: dark)'
    ).matches ? 'dark' : 'light';

    const theme = savedTheme === 'dark' ||
        savedTheme === 'light'
        ? savedTheme
        : preferredTheme;

    applyTheme(theme);

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const current = document.documentElement
                .getAttribute('data-theme');

            const next = current === 'dark'
                ? 'light'
                : 'dark';

            applyTheme(next);
            localStorage.setItem('ohmydog-theme', next);
        });
    });
}

function applyTheme(theme) {
    document.documentElement.setAttribute(
        'data-theme',
        theme
    );

    document.querySelectorAll('[data-theme-toggle]')
        .forEach(button => {
            button.setAttribute(
                'aria-label',
                theme === 'dark'
                    ? 'Activar modo claro'
                    : 'Activar modo oscuro'
            );

            button.setAttribute(
                'title',
                theme === 'dark'
                    ? 'Modo claro'
                    : 'Modo oscuro'
            );

            button.setAttribute(
                'aria-pressed',
                String(theme === 'dark')
            );
        });
}

/* =========================================================
   MENÚ LATERAL
========================================================= */

function initializeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector(
        '[data-sidebar-toggle]'
    );

    if (!sidebar || !toggle) {
        return;
    }

    function closeSidebar() {
        sidebar.classList.remove('open');

        document.querySelector('.sidebar-overlay')
            ?.remove();

        toggle.setAttribute('aria-expanded', 'false');

        document.body.style.overflow = '';
    }

    function openSidebar() {
        sidebar.classList.add('open');

        toggle.setAttribute('aria-expanded', 'true');

        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';

        overlay.addEventListener('click', closeSidebar);

        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';
    }

    toggle.addEventListener('click', () => {
        if (sidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            closeSidebar();
        }
    });

    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeSidebar);
    });
}

/* =========================================================
   MENÚ PÚBLICO
========================================================= */

function initializePublicMenu() {
    const toggle = document.querySelector(
        '[data-public-menu-toggle]'
    );

    const menu = document.querySelector('.public-links');

    if (!toggle || !menu) {
        return;
    }

    function closeMenu() {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => {
        const isOpen = menu.classList.toggle('open');

        toggle.setAttribute(
            'aria-expanded',
            String(isOpen)
        );
    });

    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    document.addEventListener('click', event => {
        if (
            !menu.contains(event.target) &&
            !toggle.contains(event.target)
        ) {
            closeMenu();
        }
    });
}

/* =========================================================
   CONFIRMACIONES
========================================================= */

function initializeConfirmations() {
    document.querySelectorAll(
        'form[data-confirm]'
    ).forEach(form => {
        form.addEventListener('submit', event => {
            const message = form.dataset.confirm ||
                '¿Deseas continuar con esta acción?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
}

/* =========================================================
   ALERTAS TEMPORALES
========================================================= */

function initializeAlerts() {
    document.querySelectorAll(
        '[data-auto-dismiss]'
    ).forEach(alert => {
        const delay = Number(
            alert.dataset.autoDismiss
        ) || 5000;

        window.setTimeout(() => {
            alert.remove();
        }, delay);
    });
}

/* =========================================================
   FORMULARIO DE CITAS
========================================================= */

function initializeAppointmentForm() {
    const form = document.querySelector(
        '[data-appointment-form]'
    );

    if (!form) {
        return;
    }

    const serviceInputs = form.querySelectorAll(
        'input[name="services[]"]'
    );

    const dateInput = form.querySelector(
        '[name="appointment_date"]'
    );

    const timeContainer = form.querySelector(
        '[data-time-slots]'
    );

    const startTimeInput = form.querySelector(
        '[name="start_time"]'
    );

    const priceOutput = form.querySelector(
        '[data-total-price]'
    );

    const durationOutput = form.querySelector(
        '[data-total-duration]'
    );

    const availabilityUrl = form.dataset.availabilityUrl;

    let requestController = null;

    const moneyFormatter = new Intl.NumberFormat(
        'es-MX',
        {
            style: 'currency',
            currency: 'MXN'
        }
    );

    function selectedServices() {
        return Array.from(serviceInputs)
            .filter(input => input.checked);
    }

    function calculateTotals() {
        const selected = selectedServices();

        let price = 0;
        let duration = 0;

        selected.forEach(input => {
            price += Number(input.dataset.price || 0);

            duration += Number(
                input.dataset.duration || 0
            );
        });

        if (priceOutput) {
            priceOutput.textContent =
                moneyFormatter.format(price);
        }

        if (durationOutput) {
            durationOutput.textContent =
                `${duration} minutos`;
        }

        return {
            price,
            duration,
            count: selected.length
        };
    }

    function clearTimeSelection() {
        if (startTimeInput) {
            startTimeInput.value = '';
        }

        if (timeContainer) {
            timeContainer.replaceChildren();
        }
    }

    function showMessage(message) {
        if (!timeContainer) {
            return;
        }

        const paragraph = document.createElement('p');
        paragraph.className = 'text-muted';
        paragraph.textContent = message;

        timeContainer.replaceChildren(paragraph);
    }

    function renderSlots(slots) {
        if (!timeContainer) {
            return;
        }

        timeContainer.replaceChildren();

        if (!slots.length) {
            showMessage(
                'No hay horarios disponibles para esta fecha.'
            );
            return;
        }

        slots.forEach(slot => {
            const button = document.createElement(
                'button'
            );

            button.type = 'button';
            button.className = 'time-slot';
            button.textContent =
                `${slot.start} - ${slot.end}`;

            button.dataset.startTime = slot.start;

            button.addEventListener('click', () => {
                timeContainer.querySelectorAll(
                    '.time-slot'
                ).forEach(item => {
                    item.classList.remove('selected');
                    item.setAttribute(
                        'aria-pressed',
                        'false'
                    );
                });

                button.classList.add('selected');
                button.setAttribute(
                    'aria-pressed',
                    'true'
                );

                if (startTimeInput) {
                    startTimeInput.value = slot.start;
                }
            });

            button.setAttribute(
                'aria-pressed',
                'false'
            );

            timeContainer.appendChild(button);
        });
    }

    async function loadAvailability() {
        const totals = calculateTotals();

        if (requestController) {
            requestController.abort();
            requestController = null;
        }

        clearTimeSelection();

        if (!dateInput || !dateInput.value) {
            showMessage('Selecciona una fecha.');
            return;
        }

        if (totals.count === 0) {
            showMessage(
                'Selecciona al menos un servicio.'
            );
            return;
        }

        if (!availabilityUrl) {
            showMessage(
                'No se configuró la consulta de horarios.'
            );
            return;
        }

        const controller = new AbortController();
        requestController = controller;

        showMessage('Consultando horarios disponibles...');

        try {
            const url = new URL(
                availabilityUrl,
                window.location.origin
            );

            url.searchParams.set(
                'date',
                dateInput.value
            );

            url.searchParams.set(
                'duration',
                totals.duration
            );

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error(
                    'No fue posible consultar la disponibilidad.'
                );
            }

            const data = await response.json();

            if (requestController !== controller) {
                return;
            }

            renderSlots(
                Array.isArray(data.slots)
                    ? data.slots
                    : []
            );

        } catch (error) {
            if (error.name !== 'AbortError') {
                showMessage(
                    'Ocurrió un error al consultar los horarios.'
                );
            }
        } finally {
            if (requestController === controller) {
                requestController = null;
            }
        }
    }

    serviceInputs.forEach(input => {
        input.addEventListener(
            'change',
            loadAvailability
        );
    });

    if (dateInput) {
        dateInput.addEventListener(
            'change',
            loadAvailability
        );
    }

    calculateTotals();

    if (dateInput && dateInput.value) {
        loadAvailability();
    }
}
