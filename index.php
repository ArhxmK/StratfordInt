<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("PAGE_TITLE", "Home");
require "assets/dbh/connector.php";
require "assets/includes/header.php";
require "assets/components/navbar.php";
?>

<style>
    
p{
    text-align: justify;
}
 /* school section */
.school-section {
    padding: 20px;
}

.school-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 auto;
    max-width: 1200px;
}

.school-text {
    flex: 1;
    padding: 20px;
    text-align: left;
    position: relative;
    transform: translateX(-100%);
    opacity: 0;
    transition: all 1s ease-out;
}

.school-text h1 {
    font-size: 2.5em;
    margin-bottom: 10px;
}

.school-text h2 {
    font-size: 1.5em;
    color: #555;
    margin-bottom: 10px;
}

.underline {
    width: 50px;
    height: 3px;
    background-color: #000223;
    margin-bottom: 20px;
}

.school-image {
    flex: 1;
    padding: 20px;
    transform: translateX(100%);
    opacity: 0;
    transition: all 1s ease-out;
}

.school-image img {
    max-width: 100%;
    border-radius: 10px;
}

@media (min-width: 768px) {
    .school-content {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-start;
    }

    .school-text {
        max-width: 60%;
    }

    .school-image {
        max-width: 40%;
    }

    .school-text h1 {
        font-size: 1.5em;
    }

    .school-text h2 {
        font-size: 1em;
        color: #555;
    }

    .school-text p {
        font-size: 1em;
    }
}

/* Animation CSS */
.show .school-text {
    transform: translateX(0);
    opacity: 1;
}

.show .school-image {
    transform: translateX(0);
    opacity: 1;
}
/* school section end */
.article-wall-container {
            background-color: #edf6ff; /* Set the background color */
            padding: 40px 20px; /* Add padding to create space inside the section */
            border-radius: 8px; /* Optional: Add rounded corners */
            margin: 0; /* Remove margin to avoid extra space */
        }
        .article-wall {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px; /* Adjust the bottom margin for spacing */
        }
        .article-wall h2 {
            font-size: 2.5em;
            font-weight: bold;
            margin: 0;
        }
        .article-wall p {
            font-size: 1.2em;
            color: #555;
            margin: 0;
        }
        .view-all-btn {
            border: 2px solid #000;
            padding: 10px 20px;
            font-size: 1em;
            text-decoration: none;
            color: #000;
            transition: background-color 0.3s, color 0.3s;
        }
        .view-all-btn:hover {
            background-color: #000;
            color: #fff;
        }
        .main-content {
            margin-bottom: 0; /* Remove margin at the bottom of the main content */
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
    
.why-us-section {
    background-color: #f2f2ff;
    padding: 50px 0;
    overflow: hidden;
}

.why-us-container {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.why-us-image {
    flex: 1;
    margin-right: 40px;
    transform: translateX(-100%);
    opacity: 0;
    transition: all 1s ease-out;
}

.why-us-image img {
    max-width: 100%;
    border-radius: 10px;
}

.why-us-content {
    flex: 2;
    opacity: 0;
    transform: translateY(50px);
    transition: all 1s ease-out;
}

.why-us-heading {
    font-size: 32px;
    color: #3e4b9a;
    margin-bottom: 20px;
    font-family: 'Arial', sans-serif;
}

.why-us-paragraph {
    font-size: 18px;
    line-height: 1.6;
    color: #333;
    font-family: 'Arial', sans-serif;
    margin-bottom: 15px;
    opacity: 0;
    transform: translateX(100%);
    transition: all 1s ease-out;
}

.show .why-us-image {
    transform: translateX(0);
    opacity: 1;
}

.show .why-us-content {
    transform: translateY(0);
    opacity: 1;
}

.show .why-us-paragraph {
    transform: translateX(0);
    opacity: 1;
}


/* homepage curriculum section */
.content-section {
    background-color: #000;
    color: #fff;
    padding: 40px;
    font-family: Arial, sans-serif;
    overflow: hidden;
}

/* Initial state for animation */
.heading, .subheading, .curriculum-item {
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.heading {
    font-size: 36px;
    font-weight: bold;
}

.subheading {
    margin-top: 10px;
    font-size: 18px;
    text-align: center;
}

.curriculum {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;
}

.curriculum-item {
    flex: 1;
    min-width: 200px;
    margin: 10px;
}

.curriculum-item h3 {
    font-size: 20px;
    font-weight: bold;
}

.curriculum-item p {
    font-size: 16px;
}

.view-all-button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    color: #000;
    background-color: #fff;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
}

/* When the section becomes visible, apply these styles */
.show .heading, .show .subheading, .show .curriculum-item {
    opacity: 1;
    transform: translateY(0);
}


/* About Section Animation */
.about-section {
  overflow: hidden;
}

/* Initial hidden state for animation */
.about-image, .about-small-image {
  opacity: 0;
  transform: translateX(-100%);
  transition: all 1s ease-out;
}

.about-heading {
  opacity: 0;
  transform: translateY(100px);
  transition: all 1s ease-out;
}

.about-subtitle, .about-text {
  opacity: 0;
  transform: translateX(-100%);
  transition: all 1s ease-out;
}

/* When the section becomes visible */
.show .about-image,
.show .about-small-image,
.show .about-heading,
.show .about-subtitle,
.show .about-text {
  opacity: 1;
  transform: translateX(0);
}

.show .about-heading {
  transform: translateY(0);
}

/* Stats Section */
.potential-section {
  background-color: #000223; /* Navy blue */
  color: white;
  padding: 50px 0;
  overflow: hidden;
}

.section-heading {
  font-size: 36px;
  font-weight: bold;
  margin-bottom: 30px;
  opacity: 0;
  transform: translateY(50px);
  transition: all 1s ease-out;
}

.stat-box h2, .stat-box p6 {
  opacity: 0;
  transform: translateY(50px);
  transition: all 1s ease-out;
}

.stat-box h2 {
  font-size: 48px;
  font-weight: bold;
  color: #ffc107; /* Gold for numbers */
  margin-bottom: 0px;
  display: inline-block;
}

.stat-box h2::after {
  content: "+";
  font-size: 36px;
  color: #ffc107;
}

.stat-box p6 {
  display: block;
  font-size: 18px;
  color: white;
  margin-top: 10px;
}

/* When the section becomes visible, apply these styles */
.show .section-heading,
.show .stat-box h2,
.show .stat-box p6 {
  opacity: 1;
  transform: translateY(0);
}

@media screen and (max-width: 768px) {
  .section-heading {
    font-size: 28px;
  }

  .stat-box h2 {
    font-size: 36px;
  }
  
  .stat-box p6 {
    font-size: 16px;
  }
}



</style>

<!-- Hero 1 - Bootstrap Brain Component -->
<section class="bsb-hero-1 px-3 bsb-overlay bsb-hover-pull" style="background-image: url(assets/img/simage15.png);">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-12 col-md-11 col-lg-9 col-xl-7 col-xxl-6 text-center text-white">
        <h2 class="display-3 fw-bold mb-3">Explore Your Potential</h2>
        <p1 class="lead mb-5"> Embark on a Comprehensive Learning Adventure at Stratford International</p1>
        <br><br>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <a href="admission.php" class="btn bsb-btn-xl btn-light gap-3">Apply now!</a>
        <a href="course.php" class="btn bsb-btn-xl btn-outline-light">View Courses</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- School Section -->
<div class="school-section">
    <div class="school-content">
        <div class="school-text">
            <h1>Stratford International School</h1>
            <br>
            <h2>Tradition-Education-Excellence</h2>
            <br>
            <div class="underline"></div>
            <br>
            <p>Stratford International School - Kalpitiya is one of the oldest International Schools in Sri Lanka and was founded in 1985. Situated at 232, Bauddhaloka Mawatha, Colombo 7. Wycherley boasts of over 35 years of Academic Excellence that has resulted in exemplary achievements of our students, with quality education, on par with global standards. Wycherley is also the First and Only Comprehensive School following the Cambridge Curriculum from UKG (Year 1) – Grade 12 in Sri Lanka.</p>
        </div>
        <div class="school-image">
            <img src="assets/img/simage20.JPG" alt="Wycherley International School">
        </div>
    </div>
</div>
<!-- End -->

<section class="bg-01">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="se-box">
                        <div class="icon">
                           <i class="fal fa-chalkboard-teacher"></i>
                        </div>
                        <div class="content">
                            <h3>Professional Teachers</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                   <div class="se-box">
                       <div class="icon">
                       <i class="fal fa-school"></i>
                       </div>
                       <div class="content">
                           <h3>Learn Anywhere Online</h3>
                           <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                       </div>
                   </div>
               </div>

               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                   <div class="se-box">
                       <div class="icon">
                       <i class="fal fa-book"></i>
                       </div>
                       <div class="content">
                           <h3>Graduation Certificate</h3>
                           <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                       </div>
                   </div>
               </div>

               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                   <div class="se-box">
                       <div class="icon">
                           <i class="fal fa-backpack"></i>
                       </div>
                       <div class="content">
                           <h3>Over 1000 Scholarship</h3>
                           <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>

  <!-- Stats Section Start -->
<div class="potential-section">
  <div class="container">
    <br>
    <h2 class="section-heading text-center">Our Journey of Excellence</h2>
    <br><br>
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-4 col-md-4 col-sm-12 stat-box text-center">
        <h2 class="counter" data-target="1000">0</h2>
        <p6>Number of Students</p6>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12 stat-box text-center">
        <h2 class="counter" data-target="500">0</h2>
        <p6>Number of Teachers</p6>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12 stat-box text-center">
        <h2 class="counter" data-target="4">0</h2>
        <p6>Years of Excellence</p6>
      </div>
    </div>
    <br><br>
  </div>
</div>
<!-- Stats Section End -->



<!-- Stats Section End -->


    <!-- <section class="content-section">
        <div class="heading">A multiethnic and multicultural schooling environment that creates global citizens.</div>
        <div class="subheading" style="text-align: center;">
        At Lyceum students will gain all the tools necessary to live up to their aspirations. Every mind is carefully nurtured at our comprehensive school.
        </div> 
        <div class="curriculum">
            <div class="curriculum-item">
                <h3>Primary Education</h3>
                <p>We provide a comprehensive Primary School experience for our students.</p>
            </div>
            <div class="curriculum-item">
                <h3>National Curriculum</h3>
                <p>We offer the state-approved government O/Ls and A/Ls curriculum taught in English Medium.</p>
            </div>
            <div class="curriculum-item">
                <h3>Secondary Education</h3>
                <p>We offer a well-rounded Secondary School experience for our students.</p>
            </div>
            <div class="curriculum-item">
                <h3>International Curriculum</h3>
                <p>We offer the International Curriculum for O/Ls and A/Ls taught in English Medium.</p>
            </div>
        </div>
        <button type="button" class="btn bsb-btn-xl btn-outline-light">View Curriculum</button>
    </section> -->
    
<!-- About Start -->
<!-- <div class="container-fluid py-5 about-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-5 about-image">
        <img
          class="img-fluid rounded mb-5 mb-lg-0 img-zoom"
          src="assets/img/simage12.JPG"
          alt=""
        />
      </div>
      <div class="col-lg-7 about-content">
        <p class="section-title pr-5 about-subtitle">
          <span class="pr-2">Learn About Us</span>
        </p>
        <h1 class="mb-4 about-heading">Best School For Your Kids</h1>
        <div class="underline"></div>
        <p class="about-text">
          Invidunt lorem justo sanctus clita. Erat lorem labore ea, justo
          dolor lorem ipsum ut sed eos, ipsum et dolor kasd sit ea justo.
          Erat justo sed sed diam. Ea et erat ut sed diam sea ipsum est
          dolor
        </p>
        <div class="row pt-2 pb-4">
          <div class="col-6 col-md-4 about-small-image">
            <img class="img-fluid rounded img-zoom" src="assets/img/simage9.JPG" alt="" />
          </div>
          <div class="col-6 col-md-8">
            <ul class="list-inline m-0">
              <li class="py-2 border-top border-bottom">
                <i class="fa fa-check text-primary mr-3"></i>Labore eos amet
                dolor amet diam
              </li>
              <li class="py-2 border-bottom">
                <i class="fa fa-check text-primary mr-3"></i>Etsea et sit
                dolor amet ipsum
              </li>
              <li class="py-2 border-bottom">
                <i class="fa fa-check text-primary mr-3"></i>Diam dolor diam
                elitripsum vero.
              </li>
            </ul>
          </div>
        </div>
        <a href="#" class="btn btn-primary">Learn More</a>
      </div>
    </div>
  </div>
</div> -->
<!-- About End -->



<!-- Why us -->
<div class="why-us-section">
    <div class="why-us-container">
        <div class="why-us-image">
            <img src="assets/img/simage9.JPG" alt="University Placements">
        </div>
        <div class="why-us-content">
            <h2 class="why-us-heading">University Placements</h2>
            <p class="why-us-paragraph">
                Stafford International School in Colombo, Sri Lanka students in 2020 have secured some of the most prestigious university placements with our students gaining placements into prestigious Ivy League schools such as Yale, Cornell, Dartmouth, and University of Pennsylvania. Schools such as Yale have acceptance rates of less than 5%, with only a handful of Sri Lankans obtaining places in their undergraduate programs in the last 10 years.
            </p>
            <p class="why-us-paragraph">
                Outgoing Deputy Head Prefect of School Shan Gunasekera has secured a place at Yale University, Cornell University, and Dartmouth College. Other students have offers from top British universities and merit scholarships from universities such as NYU Abu Dhabi and NYU Shanghai.
            </p>
        </div>
    </div>
</div>
<!-- Why us end -->


<!-- Add News & Blogs Section here -->
<div class="article-wall-container">
    <div class="container">
        <div class="article-wall">
            <div>
                <p>SEE WHAT'S NEW</p>
                <h2>News & Blogs</h2>
            </div>
            <a href="news&blogs.php" class="view-all-btn">View All Articles →</a>
        </div>

        <?php
        include_once "assets/components/newsCard.php";

        // Fetch the 3 most recent news from the database
        $sql = "SELECT * FROM blogs ORDER BY b_id DESC LIMIT 3";
        $result = mysqli_query($conn, $sql);
        ?>
        <div class="main-content" id="main-content">
            <div class="row">
                <?php while ($news = mysqli_fetch_assoc($result)): ?>
                    <?php
                    // Use the relative path stored in the database
                    newsCard($news['image_path'], $news['subject'], $news['article'], $news['b_id']);
                    ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>


    <script>
        window.addEventListener('load', function() {
        setTimeout(function() {
        document.querySelector('.why-us-container').classList.add('show');
        }, 500); // Delay before animation starts
        });

        window.addEventListener('load', function() {
        setTimeout(function() {
        document.querySelector('.school-content').classList.add('show');
        }, 500); // Adjust delay if needed
        });

        document.addEventListener('DOMContentLoaded', function() {
    const contentSection = document.querySelector('.content-section');

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                contentSection.classList.add('show');
            }
         });
    });

    observer.observe(contentSection); 
    });

    window.addEventListener('load', function() {
    setTimeout(function() {
        document.querySelector('.about-section').classList.add('show');
    }, 500); // Delay for animation to start
    });


    // Counter effect for numbers
    window.addEventListener('load', function() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        counter.innerText = '0';
        const updateCounter = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / 200;

            if(count < target) {
                counter.innerText = `${Math.ceil(count + increment)}`;
                setTimeout(updateCounter, 10);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    }); 
    });

    window.addEventListener('load', function() {
    // Add 'show' class to trigger animations
    document.querySelector('.potential-section').classList.add('show');

    // Counter effect for numbers
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        counter.innerText = '0';
        const updateCounter = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / 200;

            if(count < target) {
                counter.innerText = `${Math.ceil(count + increment)}`;
                setTimeout(updateCounter, 10);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    });
    });



    </script>

    <!-- Other sections or footer -->
    <?php
    require "assets/includes/footer.php";
    require "flask_chatbot/chatbot.php"; // Include the chatbot at the end of the page
    ?>
