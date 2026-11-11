import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

let media;
let refreshTimer;

const hasMotionTargets = () => Boolean(
    document.querySelector('[data-hero-art], [data-process], [data-timeline]'),
);

const initHeroParallax = () => {
    const art = document.querySelector('[data-hero-art]');
    const hero = art?.closest('.home-hero');

    if (!art || !hero) {
        return;
    }

    const main = art.querySelector('[data-hero-shot="main"]');
    const top = art.querySelector('[data-hero-shot="top"]');
    const bottom = art.querySelector('[data-hero-shot="bottom"]');
    const ribbon = art.querySelector('[data-hero-ribbon]');

    if (!main || !top || !bottom) {
        return;
    }

    const timeline = gsap.timeline({
        scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: 'bottom top',
            scrub: 0.8,
        },
    });

    timeline
        .to(main, { y: -16, ease: 'none' }, 0)
        .to(top, { y: 12, ease: 'none' }, 0)
        .to(bottom, { y: -8, ease: 'none' }, 0);

    if (ribbon) {
        timeline.to(ribbon, { y: 16, opacity: 0.35, ease: 'none' }, 0);
    }
};

const initProcessThread = () => {
    const section = document.querySelector('[data-process]');

    if (!section) {
        return;
    }

    const steps = section.querySelectorAll('.process-step');
    const line = section.querySelector('[data-process-line]');
    const stepsWrap = section.querySelector('.process__steps');

    if (steps.length) {
        gsap.from(steps, {
            opacity: 0,
            y: 18,
            duration: 0.55,
            ease: 'power2.out',
            stagger: 0.08,
            scrollTrigger: {
                trigger: section,
                start: 'top 74%',
                once: true,
            },
        });
    }

    if (!line || !stepsWrap) {
        return;
    }

    gsap.set(line, { strokeDasharray: 1, strokeDashoffset: 1 });

    gsap.to(line, {
        strokeDashoffset: 0,
        ease: 'none',
        scrollTrigger: {
            trigger: stepsWrap,
            start: 'top 78%',
            end: 'bottom 64%',
            scrub: 0.6,
        },
    });
};

const initAboutTimeline = () => {
    const timeline = document.querySelector('[data-timeline]');

    if (!timeline) {
        return;
    }

    const line = timeline.querySelector('[data-timeline-line]');
    const articles = timeline.querySelectorAll('article');

    if (line) {
        gsap.fromTo(line, { scaleY: 0 }, {
            scaleY: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: timeline,
                start: 'top 80%',
                end: 'bottom 68%',
                scrub: 0.7,
            },
        });
    }

    articles.forEach((article) => {
        gsap.from(article, {
            opacity: 0,
            y: 14,
            duration: 0.5,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: article,
                start: 'top 87%',
                once: true,
                onEnter: () => article.classList.add('is-visible'),
            },
        });
    });
};

export const initScrollMotion = () => {
    media?.revert();
    clearTimeout(refreshTimer);

    if (!hasMotionTargets()) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    media = gsap.matchMedia();

    media.add('(prefers-reduced-motion: no-preference)', () => {
        initProcessThread();
        initAboutTimeline();
    });

    media.add('(min-width: 1025px) and (prefers-reduced-motion: no-preference)', () => {
        initHeroParallax();
    });

    refreshTimer = window.setTimeout(() => ScrollTrigger.refresh(), 120);

    if (document.fonts?.ready) {
        document.fonts.ready.then(() => ScrollTrigger.refresh());
    }
};
