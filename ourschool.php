<?php
session_start();
define("PAGE_TITLE", "Home");
require "assets/includes/header.php";
require "assets/components/navbar.php";
?>
<style>
/* body {
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
} */

.header {
    text-align: center;
    padding: 20px;
    color: black;
}

.header h1 {
    margin: 0;
}

.intro, .content {
    margin: 20px auto;
    width: 80%;
}

p {
    text-align: justify;
}

/* h2 {
    color: #000223;
} */

.images {
    display: flex;
    justify-content: space-between;
    margin: 20px auto;
    width: 80%;
}

.images img {
    width: 30%;
    height: auto;
    transition: transform 0.2s;
}

.images img:hover {
    transform: scale(1.05);
}


</style>
<div class="sbanner">
        <div class="overlay"></div>
        <h1>Our School</h1> <!-- Example: About Us, Contact Us -->
    </div>

<br><br>

<header class="header">
        <h1>Stratford International College: Nurturing Excellence in Education and Environmental Stewardship</h1>
    </header>
    
    <section class="intro">
        <p>Located amidst the natural beauty of Kalpitiya, Sri Lanka, Stratford International College is dedicated to providing a holistic educational experience that integrates international and Sri Lankan curricula. From early childhood through advanced levels of education, our college is committed to nurturing young minds, preparing them academically and socially to excel in a competitive global environment.</p>
    </section>
    
    <section class="content">
        <h2>Educational Approach and Curriculum</h2>
        <br>
        <p>At Stratford International College, our curriculum is meticulously designed to meet global educational standards while incorporating elements of the Sri Lankan national curriculum. From KG-01 through A/L levels, students benefit from a comprehensive range of subjects including Mathematics, Science, Languages, and Humanities. Our educational approach emphasizes not only academic excellence but also the development of critical thinking, creativity, and problem-solving skills essential for success in the modern world.</p>
        <br>
        <p>In addition to core subjects, specialized programs such as the UCMAS Abacus and Phonics with Spoken English courses enrich students' learning experiences. The UCMAS Abacus program enhances mental arithmetic, concentration, and problem-solving abilities, while Phonics and Spoken English courses improve language fluency and communication skills, preparing students for effective global communication.</p>
        <br>
        <h2>Extracurricular and Co-curricular Activities</h2>
        <br>
        <p>Education at Stratford International College extends beyond textbooks to include a rich array of extracurricular and co-curricular activities. Students are encouraged to participate actively in sports, cultural events, artistic pursuits, and community service initiatives. These activities promote holistic development, fostering qualities of leadership, teamwork, and social responsibility among students.</p>
        <br>
        <section class="images">
        <img src="assets/img/simage3.jpg">
        <img src="assets/img/simage4.jpg">
        <img src="assets/img/simage5.jpg">
        </section>
        <br>
        <h2>Infrastructure and Learning Environment</h2>
        <br>
        <p>Our college boasts modern facilities designed to enhance the learning experience. State-of-the-art classrooms equipped with advanced teaching aids, computer labs, multimedia resources, and online learning platforms ensure that students receive a well-rounded education that incorporates technology seamlessly into traditional teaching methods.</p>
        <br>
        <p>Located in Kalpitiya, renowned for its natural beauty and cultural heritage, our campus provides a stimulating environment conducive to learning both inside and outside the classroom. Educational excursions, environmental studies, and recreational activities are integral parts of our curriculum, enriching students' understanding of the world and fostering a deep appreciation for nature and culture.</p>
        <br>
        <h2>Faculty and Supportive Community</h2>
        <br>
        <p>Central to our educational mission is our dedicated team of educators who are passionate about teaching and mentoring students. They provide personalized attention, guidance, and support to ensure that each student reaches their full potential academically, socially, and emotionally. Our college community values respect, integrity, and empathy, creating a supportive and inclusive environment where every student feels valued and motivated to achieve their goals.</p>
        <br>
        <h2>Environmental Stewardship through the Eco Club</h2>
        <br>
        <p>Stratford International College is proud to be registered with the Eco Club, reflecting our commitment to environmental stewardship and sustainability. Through the Eco Club, students actively engage in initiatives that promote environmental awareness and eco-friendly practices both on campus and within the broader community.</p>
        <br>
        <p>Environmental education is integrated into our curriculum, with subjects like Environmental Science and Geography providing students with a deeper understanding of ecological balance, conservation strategies, and climate change mitigation. Hands-on activities such as tree planting, beach clean-ups, and recycling programs are organized regularly to instill a sense of environmental responsibility and empower students to become advocates for environmental sustainability.</p>
        <br>
        <h2>Community Engagement and Values</h2>
        <br>
        <p>Beyond academic and environmental initiatives, our college encourages students to participate in community service projects that make a positive impact on society. Collaborative efforts with local organizations, outreach programs in neighboring schools, and participation in cultural and social events strengthen students' sense of civic duty and cultural awareness.</p>
    </section>
    
    <br><br>
<?php
require "assets/includes/footer.php";
?>
