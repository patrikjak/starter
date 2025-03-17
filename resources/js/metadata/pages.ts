import {TableWrapper} from '../../../vendor/patrikjak/utils/resources/assets/js/interfaces/table';
import {doAction} from '../../../vendor/patrikjak/utils/resources/assets/js/table/actions';
import {dispatchUpdateEvent} from '../../../vendor/patrikjak/utils/resources/assets/js/table/table';
import axios from "axios";

const pagesTableWrapper: TableWrapper = document.querySelector('.pj-table-wrapper#pages-table');

if (pagesTableWrapper) {
    doAction(pagesTableWrapper, 'edit', (pageId: string): void => {
        window.location.href = `/pages/${pageId}/edit`;
    });

    doAction(pagesTableWrapper, 'delete', (pageId: string): void => {
        axios.delete(`/api/metadata/pages/${pageId}`).then(response => {
            dispatchUpdateEvent(pagesTableWrapper);
        });
    });
}