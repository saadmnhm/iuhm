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






    // Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const dashboardWrapper = document.querySelector('.dashboard-wrapper');
    
    // Create overlay element if it doesn't exist
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay && sidebarToggle) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        dashboardWrapper.appendChild(overlay);
    }
    
    if (sidebarToggle && sidebar && overlay) {
        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    }
});