import './bootstrap';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Quote from '@editorjs/quote';

window.EditorJS = EditorJS;
window.EditorTools = {
	header: Header,
	list: List,
	quote: Quote,
};
