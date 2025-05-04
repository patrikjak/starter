import EditorJS, {OutputData} from '@editorjs/editorjs';
import Header from '@editorjs/header';
import RawTool from '@editorjs/raw';
import EditorjsList from '@editorjs/list';
import ImageTool from '@editorjs/image';
import Form from '../../../vendor/patrikjak/utils/resources/assets/js/form/Form';
import axios, {AxiosResponse} from "axios";
import notify from "../../../vendor/patrikjak/utils/resources/assets/js/utils/notification";
import {getData} from "../../../vendor/patrikjak/utils/resources/assets/js/helpers/general";

const uploadImageUrl: string = getData(document.querySelector('#editorjs'), 'upload-image-url');
const fetchImageUrl: string = getData(document.querySelector('#editorjs'), 'fetch-image-url');
const articleContentUrl: string = getData(document.querySelector('#editorjs'), 'article-content-url');
const csrfTokenMeta: HTMLMetaElement = document.head.querySelector('meta[name="csrf-token"]');
const csrfToken: string = csrfTokenMeta.content;

const editMode: boolean = document.querySelector('.edit-article') !== null;
let data: OutputData|object = {};

if (editMode) {
    data = await getArticleContent();
}

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
    // @ts-ignore
    data: data,
    autofocus: true,
    placeholder: 'Start writing your article...',
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

async function getArticleContent(): Promise<OutputData> {
    return await axios.get(articleContentUrl).then(response => {
        return JSON.parse(response.data.content);
    });
}