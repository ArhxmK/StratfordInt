<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<style>
    .whatsapp-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: url('assets/img/whatsapp_icon.png') no-repeat center center;
        background-size: cover;
        cursor: pointer;
        z-index: 1000;
        border-radius: 50%; /* Makes the icon circular */
        border: 2px solid #25D366; /* Optional: Adds a border around the circle */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Adds a slight shadow for better visibility */
    }

    @media (max-width: 600px) {
        .whatsapp-toggle {
            width: 40px;
            height: 40px;
        }
    }
</style>

<div class="whatsapp-toggle" onclick="redirectToWhatsApp()"></div>

<script>
    function redirectToWhatsApp() {
        const whatsappLink = "https://wa.me/yourNumber"; // Replace with your WhatsApp chat link
        window.location.href = whatsappLink;
    }
</script>
