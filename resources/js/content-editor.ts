import EditorJS, {OutputData} from '@editorjs/editorjs';
import Header from '@editorjs/header';
import RawTool from '@editorjs/raw';
import EditorjsList from '@editorjs/list';
import ImageTool from '@editorjs/image';
import axios, {AxiosResponse} from "axios";
import Underline from "@editorjs/underline";
import {translator, editorMessages} from "./translator";

const editorElement: Element = document.querySelector('#editorjs');
const uploadImageUrl: string = window.pjutils.getData(editorElement, 'upload-image-url');
const fetchImageUrl: string = window.pjutils.getData(editorElement, 'fetch-image-url');
const contentUrl: string|null = window.pjutils.getData(editorElement, 'content-url') ?? null;
const toolsConfig: string = window.pjutils.getData(editorElement, 'tools') ?? '';
const csrfTokenMeta: HTMLMetaElement = document.head.querySelector('meta[name="csrf-token"]');
const csrfToken: string = csrfTokenMeta.content;

const t = (key: string): string => translator.t(`editor.${key}`);

const availableTools: Record<string, unknown> = {
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
            features: {
                border: false,
                stretch: false,
                caption: 'optional',
                background: false,
            },
        },
    },
    raw: RawTool,
    underline: Underline,
};

const requestedTools: string[] = toolsConfig.split(',').map((tool) => tool.trim()).filter(Boolean);
const tools: Record<string, unknown> = {};

for (const toolName of requestedTools) {
    if (availableTools[toolName] !== undefined) {
        tools[toolName] = availableTools[toolName];
    }
}

const editMode: boolean = contentUrl !== null && contentUrl !== '';
let data: OutputData = {} as OutputData;

if (editMode) {
    data = await getContent();
}

const editor = new EditorJS({
    tools,
    data: data,
    autofocus: true,
    placeholder: t('placeholder'),
    i18n: {
        messages: {
            toolNames: editorMessages.toolNames,
            tools: editorMessages.tools,
            blockTunes: editorMessages.blockTunes,
            ui: editorMessages.ui,
        },
    },
});

new window.pjutils.Form()
    // @ts-ignore
    .setAdditionalData(async function (): Promise<FormData> {
        const additionalData = new FormData();
        const contentData = await editor.save().then(OutputData => OutputData);

        additionalData.append('content', JSON.stringify(contentData));

        return additionalData;
    })
    .setErrorCallback(function (form: HTMLFormElement, response: AxiosResponse): void {
        if (response.status !== 422) {
            window.pjutils.Form.defaultErrorCallback(form, response);
        }

        const errors = response.data.errors;
        const errorInputs: string[] = Object.getOwnPropertyNames(errors);

        if (errorInputs.includes('content.blocks')) {
            window.pjutils.notify(errors['content.blocks'], t('error_title'), 'error');
        }

        window.pjutils.Form.defaultErrorCallback(form, response);
    })
    .bindSubmit();

async function getContent(): Promise<OutputData> {
    return await axios.get(contentUrl).then(response => {
        return JSON.parse(response.data.content);
    }).catch(() => {
        window.pjutils.notify(t('load_error'), t('error_title'), 'error');

        return {} as OutputData;
    });
}
