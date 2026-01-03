class MathabungaWorksheet extends HTMLElement {
	static get observedAttributes() {
		return ['count', 'operations', 'max', 'show-answers', 'layout'];
	}

	attributeChangedCallback() {
		this.generate();
	}

	connectedCallback() {
		if (!this.innerHTML.trim()) {
			this.generate();
		}
	}

	generate() {
		const count = parseInt(this.getAttribute('count')) || 12;
		const max = parseInt(this.getAttribute('max')) || 10;
		const layout = this.getAttribute('layout') || 'vertical';
		const showAnswers = this.hasAttribute('show-answers');

		// Convert comma-separated string to array: "addition,subtraction" -> ['+', '-']
		const allowedOps = (this.getAttribute('operations') || '+').split(',');

		let html = `<div class="worksheet-grid ${layout === 'horizontal' ? 'is-horizontal' : ''}">`;
		let answerHtml = html;

		for (let i = 0; i < count; i++) {
			const op =
				allowedOps[Math.floor(Math.random() * allowedOps.length)];
			const a = Math.floor(Math.random() * (max + 1));
			const b = Math.floor(Math.random() * (max + 1));

			html += `
                <mathabunga-problem
                    a="${a}"
                    b="${b}"
                    operation="${op}"
                    layout="${layout}"
				>
                </mathabunga-problem>`;

			answerHtml += `
                <mathabunga-problem
                    a="${a}"
                    b="${b}"
                    operation="${op}"
                    layout="${layout}"
                    show-answer
				>
                </mathabunga-problem>`;
		}

		html += `</div>`;
		answerHtml += `</div>`;
		this.innerHTML = html + answerHtml;
	}
}

customElements.define('mathabunga-worksheet', MathabungaWorksheet);
