import {bindPasswordVisibilitySwitch} from '../../vendor/patrikjak/utils/resources/assets/js/form/helper';
import {bindTableFunctions} from '../../vendor/patrikjak/utils/resources/assets/js/table/table';
import {bindDropdowns} from '../../vendor/patrikjak/utils/resources/assets/js/utils/dropdown';
import Form from "../../vendor/patrikjak/utils/resources/assets/js/form/Form";

const userNavigationButton: HTMLElement = document.querySelector('.navigation .user .button');
const userNavigationItems: HTMLElement = document.querySelector('.user-items');

bindPasswordVisibilitySwitch();
bindTableFunctions();
bindDropdowns();

new Form().bindSubmit();

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

function bindClosingUserItems(): void {
    document.querySelector('body').addEventListener('click', function bindClosing(event: MouseEvent): void {
        const target: HTMLElement = event.target as HTMLElement;
        const isItem: boolean = userNavigationItems === target.closest('.user-items');

        if (!isItem) {
            userNavigationItems.classList.remove('active');
            userNavigationButton.classList.remove('active');
            document.querySelector('body').removeEventListener('click', bindClosing);
        }
    });
}

const logoutFormItem: HTMLElement = document.querySelector('.logout-form a');
logoutFormItem.addEventListener('click', (event: MouseEvent): void => {
    event.preventDefault();
    logoutFormItem.closest('form').submit();
});

const navigationItemArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.navigation .item .arrow-wrapper');

navigationItemArrows.forEach((arrowWrapper: HTMLElement): void => {
    arrowWrapper.addEventListener('click', (event: MouseEvent): void => {
        event.preventDefault();

        const arrow: HTMLElement = arrowWrapper.querySelector('.arrow');
        const itemWrapper: HTMLElement = arrow.closest('.item-wrapper');
        const subItems: HTMLElement = itemWrapper.querySelector('.sub-items');

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