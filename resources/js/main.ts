// ─── Nav collapse ─────────────────────────────────────────────────────────────

const NAV_COLLAPSED_KEY = 'pjstarter_nav_collapsed';
const navigation: HTMLElement = document.querySelector('.navigation');
const navCollapseBtn: HTMLElement = document.getElementById('nav-collapse-btn');

if (navigation && navCollapseBtn) {
    const isCollapsed = localStorage.getItem(NAV_COLLAPSED_KEY) === '1';

    if (isCollapsed) {
        navigation.classList.add('collapsed');
    }

    // Remove the no-transition init class after first frame so animations work normally
    requestAnimationFrame((): void => {
        document.documentElement.classList.remove('nav-collapsed-init');
    });

    navCollapseBtn.addEventListener('click', (): void => {
        const collapsed = navigation.classList.toggle('collapsed');
        localStorage.setItem(NAV_COLLAPSED_KEY, collapsed ? '1' : '0');
    });
}

// ─────────────────────────────────────────────────────────────────────────────

const userNavigationButton: HTMLElement = document.querySelector('.navigation .user');
const userNavigationItems: HTMLElement = document.querySelector('.user-items');

const articlesForm = document.querySelector('#article-form');

if (!articlesForm) {
    new window.pjutils.Form().bindSubmit();
}

if (userNavigationButton) {
    userNavigationButton.addEventListener('click', (): void => {
        const isOpen: boolean = userNavigationItems.classList.contains('active');

        if (!isOpen) {
            userNavigationItems.classList.add('active');
            userNavigationButton.classList.add('active');

            setTimeout((): void => {
                bindClosingUserItems();
            }, 0);
        }
    });
}

function bindClosingUserItems(): void {
    document.querySelector('body').addEventListener('click', function bindClosing(event: MouseEvent): void {
        const target: HTMLElement = event.target as HTMLElement;
        const isItem: boolean = userNavigationItems === target.closest('.user-items');

        if (userNavigationButton && !isItem) {
            userNavigationItems.classList.remove('active');
            userNavigationButton.classList.remove('active');
            document.querySelector('body').removeEventListener('click', bindClosing);
        }
    });
}

const logoutFormItem: HTMLElement = document.querySelector('.logout-form a');

if (logoutFormItem) {
    logoutFormItem.addEventListener('click', (event: MouseEvent): void => {
        event.preventDefault();
        logoutFormItem.closest('form').submit();
    });
}

// ─── Nav item tooltips (collapsed mode) ───────────────────────────────────────

const navTooltip: HTMLDivElement = document.createElement('div');
navTooltip.className = 'nav-tooltip';
document.body.appendChild(navTooltip);

document.querySelectorAll<HTMLElement>('[data-nav-tooltip]').forEach((item: HTMLElement): void => {
    item.addEventListener('mouseenter', (): void => {
        const nav: HTMLElement = document.querySelector('.navigation');

        if (!nav?.classList.contains('collapsed')) {
            return;
        }

        const rect = item.getBoundingClientRect();
        navTooltip.textContent = item.dataset.navTooltip;
        navTooltip.style.top = `${rect.top + rect.height / 2}px`;
        navTooltip.style.left = `${rect.right + 10}px`;
        navTooltip.style.transform = 'translateY(-50%)';
        navTooltip.classList.add('visible');
    });

    item.addEventListener('mouseleave', (): void => {
        navTooltip.classList.remove('visible');
    });
});

// ─────────────────────────────────────────────────────────────────────────────

const navigationItemArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.navigation .item .arrow-wrapper');

if (navigationItemArrows) {
    navigationItemArrows.forEach((arrowWrapper: HTMLElement): void => {
        arrowWrapper.addEventListener('click', (event: MouseEvent): void => {
            event.preventDefault();

            const arrow: HTMLElement = arrowWrapper.querySelector('.arrow');
            const itemWrapper: HTMLElement = arrow.closest('.item-wrapper');

            itemWrapper.classList.toggle('active');

            if (itemWrapper.classList.contains('active')) {
                arrow.classList.remove('down');
                arrow.classList.add('up');
            } else {
                arrow.classList.remove('up');
                arrow.classList.add('down');
            }
        });
    });
}