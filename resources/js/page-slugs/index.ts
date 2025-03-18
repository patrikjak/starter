import {TableWrapper} from '../../../vendor/patrikjak/utils/resources/assets/js/interfaces/table';
import {doAction} from '../../../vendor/patrikjak/utils/resources/assets/js/table/actions';
import {dispatchUpdateEvent} from '../../../vendor/patrikjak/utils/resources/assets/js/table/table';
import axios from "axios";

const pageSlugsTableWrapper: TableWrapper = document.querySelector('.pj-table-wrapper#page-slugs-table');

if (pageSlugsTableWrapper) {
    doAction(pageSlugsTableWrapper, 'edit', (pageId: string): void => {
        window.location.href = `/page-slugs/${pageId}/edit`;
    });

    doAction(pageSlugsTableWrapper, 'delete', (pageId: string): void => {
        axios.delete(`/api/page-slugs/${pageId}`).then(response => {
            dispatchUpdateEvent(pageSlugsTableWrapper);
        });
    });
}