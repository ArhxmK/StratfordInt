<?php
session_start();
define("PAGE_TITLE", "Home");
require "assets/dbh/connector.php";
require "assets/includes/header.php";
require "assets/components/navbar.php";
?>

<style>
    .contact-wrap {
        background-color: #edf6ff;
    }
    /* Button styles */
    .btn-primary {
        background-color: #000223; /* Background color */
        color: #fff; /* Text color */
        border: none; /* Remove default border */
        padding: 10px 20px; /* Padding inside the button */
        cursor: pointer; /* Change cursor to pointer on hover */
        border-radius: 12px; /* Curved edges */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow effect */
        font-size: 16px; /* Font size */
        font-weight: bold; /* Font weight */
        transition: all 0.3s ease; /* Smooth transition for hover effects */
    }

    /* Button hover effect */
    .btn-primary:hover {
        background-color: #003366; /* Darker background color on hover */
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* Enhanced shadow effect */
        transform: translateY(-2px); /* Slightly lift the button */
    }

    /* Button active effect (when clicked) */
    .btn-primary:active {
        background-color: #0001b0; /* Even darker background color on click */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Reduced shadow effect */
        transform: translateY(0); /* Reset button lift */
    }
</style>

<div class="sbanner">
    <div class="overlay"></div>
    <h1>Inquiry</h1> <!-- Example: About Us, Contact Us -->
</div>
<br><br>
<section class="ftco-section">
    <div class="container">
        <br><br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="wrapper">
                    <div class="row no-gutters mb-5">
                        <div class="col-md-7">
                            <div class="contact-wrap w-100 p-md-5 p-4">
                                <h3 class="mb-4">Let's Talk</h3>
                                <div id="form-message-warning" class="mb-4" style="display: none; color: red;">
                                    Please fill out all fields.
                                </div>
                                <div id="form-message-success" class="mb-4">
                                    How Can We Help You?
                                </div>
                                <form action="https://api.web3forms.com/submit" method="POST" id="contactForm" name="contactForm" class="contactForm">
                                    <input type="hidden" name="access_key" value="8daed13e-306b-40d0-9480-5b24a4f75e4c" />
                                    <input type="hidden" name="subject" value="New Submission from Web3Forms" />
                                    <input type="checkbox" name="botcheck" id="" style="display: none;" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label" for="name">Full Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
                                                <div class="invalid-feedback">
                                                    Please provide your full name.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label" for="email">Email Address</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                                <div class="invalid-feedback">
                                                    Please provide a valid email address.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label" for="subject">Subject</label>
                                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
                                                <div class="invalid-feedback">
                                                    Please provide a subject.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label" for="message">Message</label>
                                                <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message" required></textarea>
                                                <div class="invalid-feedback">
                                                    Please enter your message.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" value="Send Message" class="btn btn-primary">
                                                <div class="submitting"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex align-items-stretch">
                            <div id="map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.3628710008597!2d79.76319156492956!3d8.237489192781998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afd0d00402c99d3%3A0xcae03f4d245955de!2sStratford%20international%20School!5e0!3m2!1sen!2slk!4v1721745098189!5m2!1sen!2slk" width="370" height="590" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php
require "assets/includes/footer.php";
require "assets/includes/whatsapp.php";
?>
