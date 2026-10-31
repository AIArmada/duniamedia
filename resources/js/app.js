const menuToggle = document.querySelector('[data-menu-toggle]');
const primaryNav = document.getElementById('primary-nav');

if (menuToggle && primaryNav) {
    menuToggle.addEventListener('click', () => {
        const isOpen = primaryNav.classList.toggle('open');

        menuToggle.setAttribute('aria-expanded', String(isOpen));
        document.body.classList.toggle('menu-open', isOpen);
    });

    primaryNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            primaryNav.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
        });
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
