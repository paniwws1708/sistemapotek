    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const topHeader = document.querySelector(".top-header");
        if (topHeader) {
            const toggleBtn = document.createElement("button");
            toggleBtn.className = "sidebar-toggle";
            toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
            topHeader.insertBefore(toggleBtn, topHeader.firstChild);
            
            toggleBtn.addEventListener("click", function() {
                const sidebar = document.querySelector(".sidebar");
                sidebar.classList.toggle("active");
                
                let overlay = document.querySelector(".sidebar-overlay");
                if(!overlay) {
                    overlay = document.createElement("div");
                    overlay.className = "sidebar-overlay";
                    document.body.appendChild(overlay);
                    overlay.addEventListener("click", function() {
                        sidebar.classList.remove("active");
                        overlay.classList.remove("active");
                    });
                }
                overlay.classList.toggle("active");
            });
        }
    });
    </script>
</body>
</html>
