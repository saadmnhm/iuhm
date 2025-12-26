    function autoResize(textarea) {
        textarea.style.height = 'auto'; 
        textarea.style.height = textarea.scrollHeight + 'px'; 
    }

    function initAllTextareas() {
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(autoResize);
    }

    // Auto-resize on input
    document.addEventListener('input', function (event) {
        if (event.target.tagName.toLowerCase() !== 'textarea') return;
        autoResize(event.target);
    }, false);

    // Initialize on page load
    window.addEventListener('load', initAllTextareas, false);

    // Reinitialize after Livewire updates the DOM
    document.addEventListener('livewire:navigated', initAllTextareas);
    document.addEventListener('livewire:update', initAllTextareas);
    
    // For Livewire v3 - hook into morphing lifecycle
    Livewire.hook('morph.updated', ({ el, component }) => {
        initAllTextareas();
    });

    // Alternative: Use MutationObserver to detect new textareas
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Check if the added node is a textarea
                    if (node.tagName && node.tagName.toLowerCase() === 'textarea') {
                        autoResize(node);
                    }
                    // Check if the added node contains textareas
                    const textareas = node.querySelectorAll ? node.querySelectorAll('textarea') : [];
                    textareas.forEach(autoResize);
                }
            });
        });
    });

    // Start observing the document body for added nodes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });





