import {TableWrapper} from '../../../vendor/patrikjak/utils/resources/assets/js/interfaces/table';
import {doAction} from '../../../vendor/patrikjak/utils/resources/assets/js/table/actions';

const rolesTableWrapper: TableWrapper = document.querySelector('.pj-table-wrapper#roles-table');

if (rolesTableWrapper) {
    doAction(rolesTableWrapper, 'manage_permissions', (roleId: string): void => {
        window.location.href = `/users/roles/${roleId}/permissions`;
    });
}