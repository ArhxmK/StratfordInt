<?php
session_start();
define("PAGE_TITLE", "Home");
require "assets/includes/header.php";
require "assets/components/navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo PAGE_TITLE; ?></title>
    <style>
       /* Include your existing styles */
body {
    background-image: url('assets/img/sebanner2.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.footer {
    background-color: #000223;
    color: white;
    padding: 40px 0;
}
.footer h5 {
    margin-bottom: 20px;
    font-weight: bold;
}
.footer p, .footer a {
    color: white;
}
.footer a:hover {
    text-decoration: none;
    color: #ddd;
}
.footer hr {
    border-color: white;
    border-width: 2px;
    width: 50px;
    margin-left: 0;
}
.footer .social-icons {
    margin-top: 20px;
}
.footer .social-icons a {
    margin-right: 10px;
    color: white;
}
.footer .social-icons a:hover {
    color: #ddd;
}
.footer-bottom {
    background-color: var(--bgMain);
    padding: 20px 0;
    text-align: center;
    color: white;
}
.admission-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.admission-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.admission-title h1 {
    font-size: 40px;
    text-align: center;
    flex: 1;
    margin: 10px;
}
.admission-logo {
    width: 100px;
    margin: 10px;
}
.admission-main {
    text-align: center;
}
.admission-steps {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
    flex-wrap: wrap;
}
.admission-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
    background-color: #fff;
    margin: 10px;
    min-width: 120px;
}
.admission-step .icon {
    font-size: 24px;
    margin-bottom: 5px;
}
.admission-step .label {
    font-size: 14px;
}
.line {
    height: 2px;
    background-color: #ddd;
    position: absolute;
    top: 12px;
    left: 50%;
    width: 100%;
    transform: translateX(-50%);
    z-index: -1;
}
.completed .icon, .completed .label, .line.completed {
    color: #0096FF;
}
.admission-step-content {
    display: none;
    text-align: left;
}
.admission-step-content.active {
    display: block;
}
.admission-form-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}
/* Button styles */
#prevButton, #nextButton {
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
#prevButton:hover, #nextButton:hover {
    background-color: #003366; /* Darker background color on hover */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* Enhanced shadow effect */
    transform: translateY(-2px); /* Slightly lift the button */
}

/* Button active effect (when clicked) */
#prevButton:active, #nextButton:active {
    background-color: #0001b0; /* Even darker background color on click */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Reduced shadow effect */
    transform: translateY(0); /* Reset button lift */
}

.admission-form-custom {
    background-color: #fff;
}
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 15px;
}
.form-group {
    flex: 1;
    min-width: calc(50% - 10px);
}
.form-group label {
    display: block;
    margin-bottom: 5px;
}
.form-group input, .form-group textarea {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}
/* Heading 1 styles */
h1 {
    color: #000223; /* Change text color */
    font-size: 36px; /* Change font size */
    font-weight: bold; /* Change font weight */
    margin-bottom: 20px; /* Adjust bottom margin */
}

/* Heading 2 styles */
h2 {
    color: #000223; /* Change text color */
    font-size: 28px; /* Change font size */
    font-weight: normal; /* Change font weight */
    margin-bottom: 15px; /* Adjust bottom margin */
}

/* Paragraph styles */
p {
    color: #000223; /* Change text color */
    font-size: 16px; /* Change font size */
    line-height: 1.6; /* Adjust line height for better readability */
    margin-bottom: 10px; /* Adjust bottom margin */
}

@media (max-width: 768px) {
    .form-group {
        min-width: 100%;
    }
}
@media (max-width: 480px) {
    .admission-logo {
        width: 60px;
    }
    .admission-title h1 {
        font-size: 20px;
    }
    .admission-main h2 {
        font-size: 18px;
    }
    .admission-form-navigation {
        width: 100%;
    }
    #prevButton, #nextButton {
        width: 100%;
    }
}
.admission-required {
    color: red;
}
input.error, textarea.error {
    border: 2px solid red !important; 
}

.dropdown-large {
    width: 100%; /* Adjust the width as needed */
    padding: 10px; /* Adjust padding for larger clickable area */
    font-size: 16px; /* Increase font size */
    border: 1px solid #ccc; /* Optional: border styling */
    border-radius: 4px; /* Optional: rounded corners */
}

.error {
    background-color: #ffcccc ; /*Light red background */
    border-color: #ff0000; /* Red border */
}


       </style>
</head>
<body>
    <br><br>
    <div class="admission-container">
    <header class="admission-header">
        <div class="admission-title">
            <h1>STRATFORD INTERNATIONAL SCHOOL</h1>
        </div>
    </header>
    <main class="admission-main">
        <h2>APPLICATION FOR NEW ADMISSION</h2>
        <br>
        <p>Fill all the mandatory fields shown in <span class="admission-required">*</span> to go to the next step</p>
        <p style="color: red;">NOTE: All the attachments should be named properly</p>
        <p style="color: red;">(EX: NIC_200448242842)</p>
        <br>

        <!-- Step Indicator -->
        <form id="admission-form" class="admission-form-custom" action="admissionprocess.php" method="post" enctype="multipart/form-data">
            <div class="admission-steps">
                <div class="admission-step completed" data-step="1">
                    <div class="icon completed"><i class="far fa-user"></i></div>
                    <div class="label completed">Student Details</div>
                </div>
                <div class="line completed"></div>
                <div class="admission-step" data-step="2">
                    <div class="icon"><i class="far fa-users"></i></div>
                    <div class="label">Parents & Guardians</div>
                </div>
                <div class="line"></div>
                <div class="admission-step" data-step="3">
                    <div class="icon"><i class="far fa-file"></i></div>
                    <div class="label">Message</div>
                </div>
                <div class="line"></div>
                <div class="admission-step" data-step="4">
                    <div class="icon"><i class="far fa-newspaper"></i></div>
                    <div class="label">Terms & Conditions</div>
                </div>
                <div class="line"></div>
                <div class="admission-step" data-step="5">
                    <div class="icon"><i class="far fa-check-circle"></i></div>
                    <div class="label">Submission</div>
                </div>
            </div>

            <!-- Form Sections -->
            <section class="admission-step-content active" data-step="1">
                <h3>Student Details</h3>
                <br><br>
                <div class="form-row">
                    <div class="form-group">
                        <label for="student_name">Name of student: <span class="admission-required">*</span></label>
                        <input type="text" id="student_name" name="student_name" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of birth: <span class="admission-required">*</span></label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality: <span class="admission-required">*</span></label>
                        <input type="text" id="nationality" name="nationality" required>
                    </div>
                    <div class="form-group">
                        <label for="religion">Religion: <span class="admission-required">*</span></label>
                        <input type="text" id="religion" name="religion" required>
                    </div>
                    <div class="form-group">
                        <label for="passport_size_photo">Passport Size Photo <span class="admission-required">*</span></label>
                        <input type="file" name="passport_size_photo" id="passport_size_photo" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label for="birth_certificate_copy">Birth Certificate Copy <span class="admission-required">*</span></label>
                        <input type="file" name="birth_certificate_copy" id="birth_certificate_copy" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                    <label for="sex">Sex <span class="admission-required">*</span></label>
                        <select id="sex" name="sex" class="dropdown-large" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="last_school">Last School attended: <span class="admission-required">*</span></label>
                        <input type="text" id="last_school" name="last_school" required>
                    </div>
                    <div class="form-group">
                        <label for="last_grade">Last Grade passed: <span class="admission-required">*</span></label>
                        <input type="text" id="last_grade" name="last_grade" required>
                    </div>
                    <div class="form-group">
                        <label for="admission_class">Class to which admission is sought: <span class="admission-required">*</span></label>
                        <input type="text" id="admission_class" name="admission_class" required>
                    </div>
                </div>
            </section>

            <section class="admission-step-content" data-step="2">
                <h3>Parent/Guardian Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fixed_line">Fixed Line <span class="admission-required">*</span></label>
                        <input type="tel" id="fixed_line" name="fixed_line" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile <span class="admission-required">*</span></label>
                        <input type="tel" id="mobile" name="mobile" required>
                    </div>
                    <div class="form-group">
                        <label for="parents_nic_copy">Parents NIC Copy <span class="admission-required">*</span></label>
                        <input type="file" name="parents_nic_copy" id="parents_nic_copy" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="admission-required">*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name">Name of Parent/Guardian <span class="admission-required">*</span></label>
                        <input type="text" id="parent_name" name="parent_name" required>
                    </div>
                    <div class="form-group">
                        <label for="occupation">Occupation <span class="admission-required">*</span></label>
                        <input type="text" id="occupation" name="occupation" required>
                    </div>
                    <div class="form-group">
                        <label for="official_address">Official Address <span class="admission-required">*</span></label>
                        <input type="text" id="official_address" name="official_address" required>
                    </div>
                    <div class="form-group">
                        <label for="home_address">Home Address <span class="admission-required">*</span></label>
                        <input type="text" id="home_address" name="home_address" required>
                    </div>
                    <div class="form-group">
                        <label for="office_contact">Office Contact No <span class="admission-required">*</span></label>
                        <input type="tel" id="office_contact" name="office_contact" required>
                    </div>
                    <div class="form-group">
                        <label for="office_mobile">Mobile <span class="admission-required">*</span></label>
                        <input type="tel" id="office_mobile" name="office_mobile" required>
                    </div>
                    <div class="form-group">
                        <label>Names of siblings in this school</label>
                        <div>
                            <input type="text" name="sibling_1_name" placeholder="Name">
                            <input type="text" name="sibling_1_grade" placeholder="Grade">
                        </div>
                        <div>
                            <input type="text" name="sibling_2_name" placeholder="Name">
                            <input type="text" name="sibling_2_grade" placeholder="Grade">
                        </div>
                        <div>
                            <input type="text" name="sibling_3_name" placeholder="Name">
                            <input type="text" name="sibling_3_grade" placeholder="Grade">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="extracurricular">Extracurricular activities participated in the previous school</label>
                        <textarea id="extracurricular" name="extracurricular"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="shortest_distance">Shortest distance to school <span class="admission-required">*</span></label>
                        <input type="text" id="shortest_distance" name="shortest_distance" required>
                    </div>
                    <div class="form-group">
                        <label for="gramasevaka_division">Gramasevaka Division <span class="admission-required">*</span></label>
                        <input type="text" id="gramasevaka_division" name="gramasevaka_division" required>
                    </div>
                    <div class="form-group">
                        <label for="mode_of_transport">Mode of Transport to school <span class="admission-required">*</span></label>
                        <input type="text" id="mode_of_transport" name="mode_of_transport" required>
                    </div>
                </div>
            </section>

            <section class="admission-step-content" data-step="3">
                <h3>Our Message</h3>
                <br><br>
                <p style="text-align: justify;">
                Deaf Parents,
                <br><br>
                On behalf of the staff and myself,
                I would like to welcome you to Stratford International
                School.
                We are the director of Stratford International School.
                Through our experience of teaching and management in various educational fields and
                institutions in Kalpitiya, we are truly proud to be a member of this community as it is a
                great honor to lead such an organization.
                <br><br>
                Stratford International School has a community of different nationalities that consists out
                of devoted students, supportive parents, and qualified staff. The collaboration among the
                staff members, parents, and students is remarkably outstanding, which will lead to the
                achievement of student success in future.
                The learning environment of Stratford International School is very friendly and extremely
                motivating, As a result, students are motivated to become active learners.
                <br><br>
                Our goal is to provide the student with the support and resources that will enable him/her to
                enjoy and excel in his/her work. We share a common commitment to basing decisions on
                student learning, and to helping making your experiences at Stratford International School
                be as positive and enriching as possible.
                <br><br>
                Yours sincerely,<br>
                Managing Director,<br>
                Stratford International.
                </p>
            </section>
            <section class="admission-step-content" data-step="4">
                <h3>Admission Guidelines:</h3>
    <br><br>
    <p style="text-align: justify;">
        <strong>a. Procedure:</strong><br>
        The admission form must be completed fully and accurately on the school parents’ portal
        system. All relevant information must be declared including details for any disciplinary,
        social, physical, medical, or psychological problems. Relevant medical and educational
        psychologist’s reports should be attached to the application documents to the school if present.
        All prospective students wishing to register and enroll at SIS must adhere to the strict
        admission policy. It is the policy of the school to selectively admit any student to the school
        that completes all application information along with all the documents necessary for
        registration, and successfully passes the admission interview and examination. As a
        prerequisite for the interview and placement test, all applications and
        Required documents must be received and approved by the school Registrar’s Office.
        All decisions regarding admission and placement will be made strictly in Registration best
        interest of the applicant; therefore, the school reserves the right to decline testing or
        placement.
        The school’s Registration Office committee will review the prospective student’s
        Application, transcripts and report cards of the past two years from the previous school to
        determine if the student is eligible for SIS placement exam.
        Once the admission committee has reviewed the applicant’s file, a standardized
        Admission and placement exam will be take place. Prospective students must successfully
        pass the admission exam and score specific grade level marks to be considered for
        placement.
        Priority of admission will be given to current re-enrolling students and their siblings.
        Students asking to register after the closing date of general registration will be placed on a
        first-come-first-serve basis.
        Once classes are full, prospective applicants will be placed on a waiting list, which does not
        guarantee placement.
        <br><br>
        <strong>b. Waiting List:</strong><br>
        The maximum number of students that may be admitted into the primary classes is 20
        to 24.
        When a grade level is full, the Registration office advises interested parents/guardians
        accordingly and then creates waiting lists.
        Placement on a waiting list does not guarantee acceptance. Generally, priority will be given
        to (1) siblings of students enrolled in the school, (2) existing SIS School students who
        require placement at an alternate level and (3) students of SIS School staff.
        <br><br>
        <strong>c. Entry Assessment:</strong><br>
        The school is not committed to taking a student on a first come /first served basic: it is committed to
        entering those students who best fit in with the school’s educational mission aims and objectives .all
        decision on who to admit into the school are the final responsibility of the Executive manager.
        <br><br>
        <strong>d. Records Requirements:</strong><br>
        All forms must be completed entirely and submitted to the school. No steps are
        proceeded until all required documentations have been submitted.
        The following documents should be submitted with the application form:
        <ul>
            <li>Passport size photos</li>
            <li>A copy of the student’s birth certificate</li>
            <li>A copy of the parents’/guardians’ valid passport/identity card</li>
            <li>Previous school leaving certificate</li>
        </ul>
        <br><br>
        <strong>e. Minimum Entrance Age:</strong><br>
        <strong>KG Section:</strong><br>
        <ul>
            <li>KG1: 03 years</li>
            <li>KG2: 04 years</li>
            <li>KG3: 05 years</li>
        </ul>
        <strong>Lower primary school:</strong><br>
        <ul>
            <li>Grade 1: 06 years</li>
            <li>Grade 2: 07 years</li>
            <li>Grade 3: 08 years</li>
        </ul>
        <strong>Upper primary School:</strong><br>
        <ul>
            <li>Grade 4: 09 years</li>
            <li>Grade 5: 10 years</li>
        </ul>
        <strong>Middle School:</strong><br>
        <ul>
            <li>Grade 6: 11 years</li>
            <li>Grade 7: 12 years</li>
            <li>Grade 8: 13 years</li>
        </ul>
        <br><br>
        <strong>f. Registration on Parents Portal:</strong><br>
        Parents have to fill in the registration form to provide the school with the necessary
        data about the student.
        This registration form includes the following documents:
        <ul>
            <li>list of the requested documents</li>
            <li>policy for payment of school fees form</li>
            <li>registration form includes students’ information – parents’ information according to their passports</li>
            <li>student‘s house map</li>
            <li>discipline inquiry form</li>
            <li>medical information form</li>
            <li>School information form, which includes the uniform requirements and books</li>
            <li>school contract</li>
        </ul>
        <br><br>
        <strong>STUDENTS CODE OF CONDUCT:</strong><br>
        All students are expected to comply with the provisions of the Parent/Student Handbook
        and Code of Conduct as presented and amended from time to time. Failure to do so may
        result in detention, suspension and/or expulsion. Repeated breaches of the
        Parent/Student Handbook and Code of Conduct may give rise to expulsion where
        deemed appropriate.
        <br><br>
        <strong>ASSEMBLY GUIDELINES:</strong><br>
        <ul>
            <li>The students should attend school regularly and punctually. The bell rings at 08.00 AM.</li>
            <li>All students should behave during assembly and participate in all exercises.</li>
        </ul>
        <br><br>
        <strong>GUIDELINES TO CLASSROOM DISCIPLINE:</strong><br>
        <ul>
            <li>Students should show the best performance in all parts of the school program.</li>
            <li>Students should be respectful and obedient to all lines with the school staff members.</li>
            <li>Students should be respectful to other students and their belongings.</li>
            <li>Students should be courteous, well mannered, and cheerful and cooperative.</li>
            <li>Students should be attentive inside the class.</li>
            <li>Students should complete satisfactorily the approved cause of assignments and study properly.</li>
            <li>Students should clean the place when they eat inside their classes.</li>
        </ul>
        <br><br>
        <strong>GUIDELINES IN SCHOOL:</strong><br>
        <ul>
            <li>Damaging school property will hold students responsible for the damage and will have to pay the costs of the damaged items.</li>
            <li>Chewing gum is not allowed inside the school premises.</li>
            <li>Spitting or using abusive language is completely prohibited.</li>
            <li>Eating is allowed only during lunch break.</li>
            <li>Students are not allowed to bring dangerous or inappropriate items to school. These include: Walkman, headsets, radios, electronic games, beepers, mobiles or laptops.</li>
            <li>Students are prohibited to take property that belongs to teachers or others.</li>
            <li>Students should behave respectfully to adults and fellow students through actions and words.</li>
            <li>Students should respect personal space of others.</li>
            <li>They should walk to the right in all staircases and hallways.</li>
            <li>They follow school behavior policies on all trips and school activities.</li>
            <li>English is the language used inside the school premises except in TAMIL OR SINHALA classes.</li>
        </ul>
        <br><br>
        <strong>GUIDELINES TO GENERAL APPEARANCE:</strong><br>
        <ul>
            <li>Students should always wear the school uniform.</li>
            <li>The school uniform is the student’s identity; the school expects the student to dress, groom, and be neat, clean and ironed.</li>
            <li>Students should not wear headgear, hats, caps, earmuffs, or sunglasses in the school premises.</li>
            <li>Female students should always tie their hair.</li>
            <li>All kinds of jewelry are not allowed at school.</li>
            <li>Students must wear black shoes; boots, sandals and slippers are not allowed.</li>
            <li>Cleanliness and neatness are parts of the personal hygiene, which will be checked for by the school doctor.</li>
            <li>Male students are not allowed to have long hair or fancy styles like spikes, skinhead, extremely short crew, and flattop.</li>
            <li>Female students are not allowed to wear lipstick nor nail polish.</li>
        </ul>
        <br><br>
        <strong>GUIDELINES TO PLAYGROUND RULES:</strong><br>
        <strong>Breaks:</strong>
        <ul>
            <li>Throwing stones, dirt, sticks, etc. is prohibited.</li>
            <li>Students must play only in the allocated area.</li>
            <li>Fighting is prohibited.</li>
            <li>Litter is to be placed in garbage containers.</li>
            <li>Students must use all playground equipment in a safe manner.</li>
            <li>Skateboards and personal toys are not allowed at school.</li>
            <li>Students are expected to leave the playground clean.</li>
        </ul>
        <br><br>
        <strong>HALLWAYS GUIDELINES:</strong><br>
        <ul>
            <li>Students should walk quietly in an orderly manner through the hallways.</li>
            <li>Students shouldn't play with fire extinguishers.</li>
            <li>Students shouldn't throw litter in hallways, washroom or entry ways.</li>
            <li>Only one student should be inside the bathroom.</li>
        </ul>
        <br><br>
        <strong>GUIDELINES TO LIBRARY, SCIENCE LAB and COMPUTER LAB:</strong><br>
        <ul>
            <li>Appropriate classroom rules apply in the library, science lab, and computer lab.</li>
            <li>Candy, food and drinks are not allowed in the library, science lab, and computer lab.</li>
            <li>Noise is to be suitable to the task at hand.</li>
        </ul>
        <br><br>
        <strong>LIBRARY PROCEDURES:</strong><br>
        <ul>
            <li>Students are allowed to check out books for a period of one week.</li>
            <li>Student may have more than one book checked out at any given time.</li>
            <li>Lost or damaged books have to be paid for or replaced by the student.</li>
            <li>There will be no more borrowing until payment has been received.</li>
        </ul>
        <br><br>
        <strong>Uniform:</strong><br>
        As part of SIS School identity, all students of SIS School are required to adhere
        to the School Uniform Policy as set out below. Students who report to School
        inappropriately attired may be removed from class and their parents contacted.
        If available, they may be asked to wear a suitable item from the School supply.
    </p>
            </section>

            <!-- Step 5: Submission -->
            <section class="admission-step-content" data-step="5">
                <h3>Submission</h3>
            </section>

            <!-- Navigation Buttons -->
            <div class="admission-form-navigation">
                <button type="button" id="prevButton">Previous</button>
                <button type="button" id="nextButton">Next</button>
            </div>
        </form>
    </main>
</div>
<script>
// const steps = document.querySelectorAll('.admission-step');
// const contents = document.querySelectorAll('.admission-step-content');
// const prevButton = document.getElementById('prevButton');
// const nextButton = document.getElementById('nextButton');
// let currentStep = 0;

// function showStep(stepIndex) {
//     steps.forEach((step, index) => {
//         step.classList.toggle('completed', index < stepIndex);
//         step.classList.toggle('active', index === stepIndex);
//     });
//     contents.forEach((content, index) => {
//         content.classList.toggle('active', index === stepIndex);
//     });
// }

// function validateStep(stepIndex) {
//     const inputs = contents[stepIndex].querySelectorAll('input, textarea, select');
//     let valid = true;

//     inputs.forEach(input => {
//         // Reset error state
//         input.classList.remove('error');

//         // Check for required fields
//         if (input.required && !input.value) {
//             input.classList.add('error');
//             valid = false;
//         }

//         // Numeric validation for telephone numbers
//         if (input.type === 'tel' && isNaN(input.value)) {
//             input.classList.add('error');
//             valid = false;
//             alert('Please enter a valid numeric value for phone numbers.');
//         }

//         // Specific field validation for 'Sex'
//         // if (input.id === 'sex' && !['Male', 'Female'].includes(input.value)) {
//         //     input.classList.add('error');
//         //     valid = false;
//         //     alert('Sex must be either Male or Female.');
//         // }
//     });

//     return valid;
// }

// prevButton.addEventListener('click', () => {
//     if (currentStep > 0) {
//         currentStep--;
//         showStep(currentStep);
//     }
// });

// nextButton.addEventListener('click', () => {
//     if (currentStep < steps.length - 1) {
//         if (validateStep(currentStep)) {
//             currentStep++;
//             showStep(currentStep);
//         } else {
//             alert('Please fill in all required fields correctly.');
//         }
//     } else {
//         // Submit the form on the final step
//         document.getElementById('admission-form').submit();
//     }
// });

// // Initialize the form
// showStep(currentStep);

document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.admission-step');
    const contents = document.querySelectorAll('.admission-step-content');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            if (index <= stepIndex) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else {
                step.classList.remove('completed');
                step.classList.remove('active');
            }
            if (index === stepIndex) {
                step.classList.add('active');
            }
        });

        contents.forEach((content, index) => {
            content.classList.toggle('active', index === stepIndex);
        });

        // Hide/show navigation buttons based on the step index
        prevButton.style.display = stepIndex === 0 ? 'none' : 'inline-block';
        nextButton.textContent = stepIndex === steps.length - 1 ? 'Submit' : 'Next';
    }

    function validateStep(stepIndex) {
        const inputs = contents[stepIndex].querySelectorAll('input, textarea, select');
        let valid = true;

        inputs.forEach(input => {
            input.classList.remove('error');

            if (input.required && !input.value) {
                input.classList.add('error');
                valid = false;
            }

            if (input.type === 'tel' && isNaN(input.value)) {
                input.classList.add('error');
                valid = false;
                alert('Please enter a valid numeric value for phone numbers.');
            }
        });

        return valid;
    }

    prevButton.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            } else {
                alert('Please fill in all required fields correctly.');
            }
        } else {
            // Final step: Submit the form
            if (validateStep(currentStep)) {
                document.getElementById('admission-form').submit();
            } else {
                alert('Please fill in all required fields correctly.');
            }
        }
    });

    // Initialize the form
    showStep(currentStep);
});

</script>
<br><br>
<?php
require "assets/includes/footer.php";
require "flask_chatbot/chatbot.php"; // Include the chatbot at the end of the page
?>
</body>
</html>
