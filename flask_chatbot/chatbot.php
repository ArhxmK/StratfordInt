<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<style>
    .chatbot-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: url('assets/img/robot_icon.png') no-repeat center center;
        background-size: cover;
        cursor: pointer;
        z-index: 1000;
        border-radius: 50%; /* Makes the icon circular */
        border: 2px solid #ccc; /* Optional: Adds a border around the circle */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Adds a slight shadow for better visibility */
    }

    #chatbox {
        position: fixed;
        bottom: 80px;
        right: 20px;
        max-width: 400px;
        width: 90%; /* Adjust width for better responsiveness */
        display: none; /* Hidden by default */
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 600px) {
        #chatbox {
            width: 95%; /* Full width for small screens */
            right: 2.5%; /* Center horizontally */
            bottom: 20px; /* Adjust position */
        }
        .chatbot-toggle {
            width: 40px;
            height: 40px;
        }
        .chatbot-icon {
            width: 40px;
            height: 40px;
        }
    }

    #chat-content {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
    }

    .user-question, .bot-answer {
        padding: 10px;
        margin: 10px 0;
        border-radius: 10px;
    }

    .user-question {
        background-color: #000223; /* Updated color */
        color: white;
        text-align: right;
    }

    .bot-answer {
        background-color: #d0d0d0;
    }

    #user-input {
        width: calc(100% - 60px);
        padding: 10px;
        margin: 10px 0;
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    button {
        padding: 10px;
        margin-left: 10px;
        border-radius: 10px;
        border: 1px solid #ccc;
        background-color: #000223; /* Updated color */
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #0001b0;
    }

    .error-message {
        color: red;
        margin-top: 10px;
    }

    .chatbot-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto;
        display: block;
        background: url('assets/img/robot_icon.png') no-repeat center center;
        background-size: cover;
        border-radius: 50%; /* Makes the icon circular */
        border: 2px solid #ccc; /* Optional: Adds a border around the circle */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Adds a slight shadow for better visibility */
    }

    .chatbot-heading {
        text-align: center;
        margin-top: 10px;
    }

    .chatbot-heading h3 {
        margin: 0;
        color: black;
    }

    .chatbot-status {
        text-align: center;
        color: grey;
    }

    .chatbot-status .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: lightgreen;
        border-radius: 50%;
        margin-right: 5px;
    }

    .recommended-questions {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .recommended-question {
        padding: 10px;
        margin: 5px 0;
        background-color: #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
    }

    .recommended-question:hover {
        background-color: #d0d0d0;
    }
</style>

<div class="chatbot-toggle" onclick="toggleChatbot()"></div>
<div id="chatbox">
    <br>
    <div class="chatbot-icon"></div>
    <div class="chatbot-heading">
        <h3>Chatbot</h3>
    </div>
    <div class="chatbot-status">
        <span class="status-dot"></span>
        <span>Online</span>
    </div>
    <div id="recommended-questions" class="recommended-questions"></div>
    <div id="chat-content"></div>
    <input type="text" id="user-input" placeholder="Ask me anything...">
    <button onclick="sendQuestion()">Send</button>
    <div id="error-message" class="error-message"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('flask_chatbot/predefined_qna.json')
            .then(response => response.json())
            .then(data => {
                displayRecommendedQuestions(data.questions_and_answers.slice(0, 3));
            })
            .catch(error => console.error('Error loading predefined questions:', error));
    });

    function displayRecommendedQuestions(questions) {
        const recommendedQuestionsDiv = document.getElementById('recommended-questions');
        recommendedQuestionsDiv.innerHTML = '';

        questions.forEach(qna => {
            const questionDiv = document.createElement('div');
            questionDiv.className = 'recommended-question';
            questionDiv.textContent = qna.question;
            questionDiv.onclick = () => selectRecommendedQuestion(qna.question);
            recommendedQuestionsDiv.appendChild(questionDiv);
        });
    }

    function selectRecommendedQuestion(question) {
        document.getElementById('user-input').value = question;
        sendQuestion();
    }

    function toggleChatbot() {
        const chatbox = document.getElementById('chatbox');
        chatbox.style.display = chatbox.style.display === 'none' ? 'block' : 'none';
    }

    function sendQuestion() {
        const question = document.getElementById('user-input').value;
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = ''; // Clear previous error message

        fetch('http://127.0.0.1:5001/chatbot', {  // Use your deployed API URL if not running locally
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ question: question })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(`HTTP error! status: ${response.status}, message: ${text}`); });
            }
            return response.json();
        })
        .then(data => {
            const chatContent = document.getElementById('chat-content');
            chatContent.innerHTML += `<div class="user-question">${question}</div>`;
            chatContent.innerHTML += `<div class="bot-answer">${data.answer}</div>`;
            document.getElementById('user-input').value = '';
        })
        .catch(error => {
            errorMessage.textContent = `Error: ${error.message}`;
        });
    }
</script>
