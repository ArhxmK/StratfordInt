// window.onload = function() {
//     const sidebar = document.getElementById('sidebar');
//     const content = document.getElementById('content');
//     const sidebarToggle = document.getElementById('sidebarToggle');

//     let isSidebarCollapsed = false; // Initial state: sidebar expanded

//     sidebarToggle.addEventListener('click', function (event) {
//         event.stopPropagation(); // Prevent any other event triggers
//         console.log('Toggle button clicked on mobile');

//         // Check if the sidebar is collapsed
//         if (isSidebarCollapsed) {
//             sidebar.classList.remove('collapsed');
//             content.classList.remove('collapsed');
//             sidebarToggle.style.color = 'white';
//         } else {
//             sidebar.classList.add('collapsed');
//             content.classList.add('collapsed');
//             sidebarToggle.style.color = 'black';
//         }

//         // Toggle the state
//         isSidebarCollapsed = !isSidebarCollapsed;
//     });

//     // Prevent body clicks from collapsing sidebar (specifically for mobile)
//     document.body.addEventListener('click', function () {
//         if (!isSidebarCollapsed) {
//             sidebar.classList.add('collapsed');
//             content.classList.add('collapsed');
//             sidebarToggle.style.color = 'black';
//             isSidebarCollapsed = true;
//         }
//     });
// };

window.onload = function() {
    // Try to get both possible content div IDs
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content') || document.getElementById('main-content'); // Support both IDs
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Check if the required elements exist
    if (sidebar && content && sidebarToggle) {
        let isSidebarCollapsed = sidebar.classList.contains('collapsed');

        // Add event listener to the sidebar toggle button
        sidebarToggle.addEventListener('click', function(event) {
            event.stopPropagation();  // Prevent event bubbling

            if (isSidebarCollapsed) {
                // Open the sidebar
                sidebar.classList.remove('collapsed');
                content.classList.remove('collapsed');
                sidebarToggle.style.color = 'white';
            } else {
                // Close the sidebar
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
                sidebarToggle.style.color = 'black';
            }

            // Toggle the state
            isSidebarCollapsed = !isSidebarCollapsed;
        });
    } else {
        console.error("Required elements not found (#sidebar, #content or #main-content, #sidebarToggle). Check HTML structure.");
    }
};

