import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

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



const initModals = () => {
	const openButtons = document.querySelectorAll('[data-open-modal]');
	const closeButtons = document.querySelectorAll('[data-close-modal]');

	openButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const modalId = button.getAttribute('data-open-modal');
			const modal = modalId ? document.getElementById(modalId) : null;

			if (modal instanceof HTMLDialogElement) {
				modal.showModal();
			}
		});
	});

	closeButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const modalId = button.getAttribute('data-close-modal');
			const modal = modalId ? document.getElementById(modalId) : button.closest('dialog');

			if (modal instanceof HTMLDialogElement) {
				modal.close();
			}
		});
	});
};

document.addEventListener('DOMContentLoaded', () => {
	initModals();
});
