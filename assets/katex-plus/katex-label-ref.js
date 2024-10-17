function replaceReferences() {
    document.body.innerHTML = document.body.innerHTML.replace(/\\(eq)?ref{([^}]*)}/g, function(match, isEqRef, label) {
        return `<span class="katex-${isEqRef ? 'eqref' : 'ref'}">${label}</span>`;
    });
}

function processEquationsAndReferences() {
    const eqLabels = new Map();
    let autoNumber = 0;

    document.querySelectorAll('.katex-display').forEach((eq) => {
        const label = eq.querySelector('[id]');
        if (label) {
            const labelName = label.id;
            const tagElement = eq.querySelector('.tag');
            const eqnElement = eq.querySelector('.eqn-num');

            let tagtxt = '';

            if (tagElement) {
                tagtxt = tagElement.textContent.trim();
            }

            if (tagtxt.length > 1) {
                // Use the custom tag if present
                const match = tagtxt.match(/\(([^)]+)\)/);
                eqLabels.set(labelName, match && match[1] ? match[1].trim() : tagtxt);
            } else if (eqnElement) {
                // Use the auto-generated number
                autoNumber++;
                eqLabels.set(labelName, autoNumber.toString());
            } else {
                console.warn(`Label ${labelName} has no tag or eqn-num`);
            }
        }
    });

    const updateReferences = (selector, format) => {
        document.querySelectorAll(selector).forEach((ref) => {
            const refName = ref.textContent;
            ref.textContent = eqLabels.has(refName) ? format(eqLabels.get(refName)) : '?';
        });
    };

    updateReferences('.katex-ref', (label) => label);
    updateReferences('.katex-eqref', (label) => `(${label})`);
}

