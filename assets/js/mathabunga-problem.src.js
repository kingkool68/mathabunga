class MathabungaProblem extends HTMLElement {
	static get observedAttributes() {
		return ['layout', 'show-answer']; // Watch both layout and answer state
	}

	attributeChangedCallback(name, oldValue, newValue) {
		if (oldValue !== newValue) {
			this.connectedCallback();
		}
	}

	connectedCallback() {
		const a = parseInt(this.getAttribute('a')) || 0;
		const b = parseInt(this.getAttribute('b')) || 1;
		const operation = this.getAttribute('operation') || 'addition';
		const layout =
			this.getAttribute('layout') === 'horizontal'
				? 'horizontal'
				: 'vertical';
		const showAnswer = this.hasAttribute('show-answer');

		// 1. Establish the "Top" and "Bottom" numbers first
		let top = a;
		let bottom = b;
		let operator = '+';

		switch (operation) {
			case '-':
			case 'subtraction':
				operator = '-';
				if (b > a) [top, bottom] = [b, a]; // Swap to prevent negatives
				break;
			case '*':
			case 'multiplication':
				operator = '×';
				break;
			case '/':
			case 'division':
				operator = '÷';
				top = a * b; // Ensure whole number result
				bottom = b;
				break;
			default:
				operator = '+';
		}

		// 2. Now calculate the answer based on the final top/bottom
		let answer = 0;
		if (operator === '-') answer = top - bottom;
		else if (operator === '×') answer = top * bottom;
		else if (operator === '÷') answer = top / bottom;
		else answer = top + bottom;

		// 3. Set the layout data-attribute for your Sass targeting
		this.setAttribute('data-layout', layout);

		// 4. Render the HTML
		this.innerHTML = `
            <div class="mathabunga-problem-inner ${showAnswer ? 'has-answer' : ''}">
                <div class="mathabunga-top">${top}</div>
                <div class="mathabunga-bottom">
                    <span class="mathabunga-operator">${operator}</span>
                    ${bottom}
                </div>
                <div class="mathabunga-answer-space">
                    ${showAnswer ? `<span class="mathabunga-answer">${answer}</span>` : ''}
                </div>
            </div>
        `;
	}
}

if (!customElements.get('mathabunga-problem')) {
	customElements.define('mathabunga-problem', MathabungaProblem);
}
