import {I18n} from 'i18n-js';
import en from './lang/en.json';
import sk from './lang/sk.json';

const locale = document.documentElement.lang;
const baseLocale = locale.split('-')[0];

const i18n = new I18n({en, sk});

i18n.locale = baseLocale;
i18n.defaultLocale = 'en';

const locales: Record<string, typeof en> = {en, sk};
const editorMessages = (locales[baseLocale] ?? en).editor;

export {i18n as translator, editorMessages};
