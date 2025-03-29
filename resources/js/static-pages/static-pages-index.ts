import {TableWrapper} from '../../../vendor/patrikjak/utils/resources/assets/js/interfaces/table';
import {doAction} from '../../../vendor/patrikjak/utils/resources/assets/js/table/actions';
import {dispatchUpdateEvent} from '../../../vendor/patrikjak/utils/resources/assets/js/table/table';
import axios from "axios";

const staticPagesTableWrapper: TableWrapper = document.querySelector('.pj-table-wrapper#static-pages-table');

if (staticPagesTableWrapper) {
    doAction(staticPagesTableWrapper, 'edit', (pageId: string): void => {
        window.location.href = `/static-pages/${pageId}/edit`;
    });

    doAction(staticPagesTableWrapper, 'delete', (pageId: string): void => {
        axios.delete(`/api/static-pages/${pageId}`).then(response => {
            dispatchUpdateEvent(staticPagesTableWrapper);
        });
    });
}