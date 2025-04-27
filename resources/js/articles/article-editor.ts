import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import RawTool from '@editorjs/raw';
import EditorjsList from '@editorjs/list';
import ImageTool from '@editorjs/image';
import {IconListBulleted, IconListNumbered} from '@codexteam/icons';
import Form from '../../../vendor/patrikjak/utils/resources/assets/js/form/Form';
import {AxiosResponse} from "axios";
import notify from "../../../vendor/patrikjak/utils/resources/assets/js/utils/notification";

const editor = new EditorJS({
    tools: {
        header: Header,
        list: {
            class: EditorjsList,
            inlineToolbar: true,
            config: {
                defaultStyle: 'unordered',
            },
            toolbox: [
                {
                    icon: IconListBulleted,
                    title: 'Unordered List',
                    data: {
                        style: 'unordered',
                    },
                },
                {
                    icon: IconListNumbered,
                    title: 'Ordered List',
                    data: {
                        style: 'ordered',
                    },
                },
            ],
        },
        image: {
            class: ImageTool,
            config: {
                endpoints: {
                    byFile: 'http://localhost:8008/uploadFile', // Your backend file uploader endpoint
                    byUrl: 'http://localhost:8008/fetchUrl', // Your endpoint that provides uploading by Url
                },
            },
        },
        raw: RawTool,
    }
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

/*setInterval((): void => {
    editor.save().then((outputData) => {
        console.log('Article data: ', outputData)
    }).catch((error) => {
        console.log('Saving failed: ', error)
    });
}, 10000);*/
