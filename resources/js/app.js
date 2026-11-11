document.documentElement.classList.add('js');

const header = document.querySelector('[data-site-header]');

if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileNav = document.querySelector('[data-mobile-nav]');

if (menuToggle && mobileNav) {
    const setMenu = (open) => {
        menuToggle.setAttribute('aria-expanded', String(open));
        mobileNav.hidden = !open;
        document.body.classList.toggle('is-locked', open);
        menuToggle.querySelector('.sr-only').textContent = open ? 'Close menu' : 'Open menu';

        const main = document.querySelector('main');
        const footer = document.querySelector('.site-footer');

        if (main) {
            main.inert = open;
        }

        if (footer) {
            footer.inert = open;
        }

        if (open) {
            mobileNav.querySelector('a, summary, button')?.focus();
        }
    };

    menuToggle.addEventListener('click', () => setMenu(menuToggle.getAttribute('aria-expanded') !== 'true'));

    mobileNav.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setMenu(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
            setMenu(false);
            menuToggle.focus();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024 && menuToggle.getAttribute('aria-expanded') === 'true') {
            setMenu(false);
        }
    });
}

document.querySelectorAll('.nav-group').forEach((group) => {
    group.addEventListener('toggle', () => {
        if (!group.open) {
            return;
        }

        document.querySelectorAll('.nav-group[open]').forEach((other) => {
            if (other !== group) {
                other.open = false;
            }
        });
    });
});

document.addEventListener('click', (event) => {
    document.querySelectorAll('.nav-group[open]').forEach((group) => {
        if (!group.contains(event.target)) {
            group.open = false;
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('.nav-group[open]').forEach((group) => {
            group.open = false;
            group.querySelector('summary')?.focus();
        });
    }
});

const filterRail = document.querySelector('[data-filter-rail]');
const filterBackdrop = document.querySelector('[data-filter-backdrop]');

if (filterRail) {
    const mobileFilters = window.matchMedia('(max-width: 1024px)');
    let filterTrigger = null;

    const syncFilterAccess = () => {
        filterRail.inert = mobileFilters.matches && !filterRail.classList.contains('is-open');
    };

    syncFilterAccess();
    mobileFilters.addEventListener('change', syncFilterAccess);

    const setFilters = (open) => {
        filterRail.classList.toggle('is-open', open);
        filterBackdrop?.classList.toggle('is-open', open);
        document.body.classList.toggle('is-locked', open);
        syncFilterAccess();
        document.querySelectorAll('[data-filter-open]').forEach((button) => {
            button.setAttribute('aria-expanded', String(open));
        });

        if (open) {
            filterRail.querySelector('[data-filter-close]')?.focus();
        } else {
            filterTrigger?.focus();
        }
    };

    document.querySelectorAll('[data-filter-open]').forEach((button) => {
        button.addEventListener('click', () => {
            filterTrigger = button;
            setFilters(true);
        });
    });

    document.querySelectorAll('[data-filter-close]').forEach((button) => {
        button.addEventListener('click', () => setFilters(false));
    });

    filterBackdrop?.addEventListener('click', () => setFilters(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && filterRail.classList.contains('is-open')) {
            setFilters(false);
        }
    });
}

document.querySelectorAll('[data-video-cover]').forEach((frame) => {
    const video = frame.querySelector('video');
    const playButton = frame.querySelector('[data-video-play]');

    if (!video || !playButton) {
        return;
    }

    playButton.addEventListener('click', () => {
        frame.classList.add('is-playing');
        video.controls = true;

        const playback = video.play();

        if (playback && typeof playback.catch === 'function') {
            playback.catch(() => {
                video.controls = true;
            });
        }
    });
});

document.querySelectorAll('[data-rc]').forEach((scope) => {
    const tabs = scope.querySelectorAll('[data-rc-tab]');
    const panels = scope.querySelectorAll('[data-rc-panel]');

    if (!tabs.length || !panels.length) {
        return;
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const key = tab.dataset.rcTab;

            tabs.forEach((item) => {
                const active = item === tab;
                item.classList.toggle('is-active', active);
                item.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            panels.forEach((panel) => {
                panel.classList.toggle('is-active', panel.dataset.rcPanel === key);
            });
        });
    });
});

const connectFormErrors = () => {
    document.querySelectorAll('.form-error').forEach((error, index) => {
        const field = error.closest('.field, label');
        const control = field?.querySelector('input, select, textarea');

        if (!control) {
            return;
        }

        if (!error.id) {
            error.id = `form-error-${index}-${Math.random().toString(36).slice(2, 7)}`;
        }

        error.setAttribute('role', 'alert');
        control.setAttribute('aria-invalid', 'true');
        control.setAttribute('aria-describedby', [control.getAttribute('aria-describedby'), error.id].filter(Boolean).join(' '));
    });
};

connectFormErrors();

if ('MutationObserver' in window) {
    const formObserver = new MutationObserver(() => connectFormErrors());
    formObserver.observe(document.body, { childList: true, subtree: true });
}

document.addEventListener('livewire:navigated', connectFormErrors);

if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && 'IntersectionObserver' in window) {
    const revealTargets = document.querySelectorAll('[data-reveal]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    revealTargets.forEach((target) => observer.observe(target));
} else {
    document.querySelectorAll('[data-reveal]').forEach((target) => target.classList.add('is-visible'));
}
