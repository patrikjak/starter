import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import RawTool from '@editorjs/raw';
import EditorjsList from '@editorjs/list';
import ImageTool from '@editorjs/image';
import Form from '../../../vendor/patrikjak/utils/resources/assets/js/form/Form';
import {AxiosResponse} from "axios";
import notify from "../../../vendor/patrikjak/utils/resources/assets/js/utils/notification";
import {getData} from "../../../vendor/patrikjak/utils/resources/assets/js/helpers/general";

const uploadImageUrl: string = getData(document.querySelector('#editorjs'), 'upload-image-url');
const fetchImageUrl: string = getData(document.querySelector('#editorjs'), 'fetch-image-url');
const csrfTokenMeta: HTMLMetaElement = document.head.querySelector('meta[name="csrf-token"]');
const csrfToken: string = csrfTokenMeta.content;

const editor = new EditorJS({
    tools: {
        header: Header,
        list: {
            class: EditorjsList,
            inlineToolbar: true,
            config: {
                defaultStyle: 'unordered',
                counterTypes: ['numeric'],
            },
        },
        image: {
            class: ImageTool,
            config: {
                additionalRequestHeaders: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                endpoints: {
                    byFile: uploadImageUrl,
                    byUrl: fetchImageUrl,
                },
            },
        },
        raw: RawTool,
    },
});

new Form()
    // @ts-ignore
    .setAdditionalData(async function (): Promise<FormData> {
        const additionalData = new FormData();
        const contentData = await editor.save().then(OutputData => OutputData);

        additionalData.append('content', JSON.stringify(contentData));

        return additionalData;
    })
    .setErrorCallback(function (form: HTMLFormElement, response: AxiosResponse): void {
        if (response.status !== 422) {
            Form.defaultErrorCallback(form, response);
        }

        const errors = response.data.errors;
        const errorInputs: string[] = Object.getOwnPropertyNames(errors);

        if (errorInputs.includes('content.blocks')) {
            notify(errors['content.blocks'], 'Ooops', 'error');
        }

        Form.defaultErrorCallback(form, response);

    })
    .bindSubmit();
