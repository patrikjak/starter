import {TableWrapper} from '../../../vendor/patrikjak/utils/resources/assets/js/interfaces/table';
import {doAction} from '../../../vendor/patrikjak/utils/resources/assets/js/table/actions';

const metadataTableWrapper: TableWrapper = document.querySelector('.pj-table-wrapper#metadata-table');

if (metadataTableWrapper) {
    doAction(metadataTableWrapper, 'edit', (metadataId: string): void => {
        window.location.href = `/metadata/${metadataId}/edit`;
    });
}