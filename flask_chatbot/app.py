# from flask import Flask, request, jsonify
# from flask_cors import CORS
# import joblib
# import json
# import os

# app = Flask(__name__)
# CORS(app)  # Enable CORS for all routes

# # Verify the file exists
# print("Current directory:", os.getcwd())
# print("Files in directory:", os.listdir('.'))

# try:
#     # Load the model
#     qa_pipeline = joblib.load('chatbot_model.pkl')
#     print("Model loaded successfully.")
# except Exception as e:
#     print(f"Error loading model: {e}")

# # Load school details from a JSON file
# with open('school_details.json', 'r') as f:
#     school_details = json.load(f)

# def generate_context(details):
#     context = f"""
#     Welcome to {details['school_name']}.
#     Address: {details['address']}
#     Contact: Phone - {details['contact']['phone']}, Email - {details['contact']['email']}
#     Programs offered: {', '.join(details['programs'])}
#     Admission Process: {details['admission_process']}
#     """
#     return context

# @app.route('/chatbot', methods=['POST'])
# def chatbot():
#     data = request.json
#     question = data['question']
#     print(f"Received question: {question}")
#     context = generate_context(school_details)
#     try:
#         answer = qa_pipeline(question=question, context=context)
#         print(f"Generated answer: {answer['answer']}")
#         return jsonify({'answer': answer['answer']})
#     except Exception as e:
#         print(f"Error generating answer: {e}")
#         return jsonify({'error': str(e)}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5001)
# from flask import Flask, request, jsonify
# from flask_cors import CORS
# import json
# import os

# app = Flask(__name__)
# CORS(app)  # Enable CORS for all routes

# # Load predefined questions and answers
# predefined_qna_path = 'predefined_qna.json'
# if not os.path.exists(predefined_qna_path):
#     raise FileNotFoundError(f"{predefined_qna_path} does not exist.")
# else:
#     print(f"Found predefined_qna.json at: {predefined_qna_path}")

# try:
#     with open(predefined_qna_path, 'r') as f:
#         predefined_qna = json.load(f)['questions_and_answers']
#     print(f"Loaded {len(predefined_qna)} predefined Q&A pairs.")
# except json.JSONDecodeError as e:
#     print(f"Error loading predefined_qna.json: {e}")
#     predefined_qna = []

# # Print the loaded Q&A pairs for verification
# for qna in predefined_qna:
#     print(f"Loaded Q&A Pair - Question: '{qna['question']}', Answer: '{qna['answer']}'")

# def find_predefined_answer(question):
#     question = question.lower().strip()
#     print(f"Searching for predefined answer for question: '{question}'")
#     for qna in predefined_qna:
#         qna_question = qna['question'].lower().strip()
#         print(f"Comparing '{question}' with predefined question: '{qna_question}'")
#         if question == qna_question:
#             print(f"Match found. Returning answer: '{qna['answer']}'")
#             return qna['answer']
#     print("No predefined answer found.")
#     return None

# @app.route('/chatbot', methods=['POST'])
# def chatbot():
#     try:
#         data = request.json
#         question = data['question']
#         print(f"Received question: '{question}'")

#         # Check for a predefined answer
#         predefined_answer = find_predefined_answer(question)
#         if predefined_answer:
#             print(f"Returning predefined answer: '{predefined_answer}'")
#             return jsonify({'answer': predefined_answer})

#         # If no predefined answer is found, return a default message
#         default_message = "I'm sorry, I cannot process your request right now."
#         print(f"No predefined answer found. Returning default message: '{default_message}'")
#         return jsonify({'answer': default_message})
#     except Exception as e:
#         print(f"Error in /chatbot endpoint: {e}")
#         return jsonify({'error': str(e)}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5001)

# from flask import Flask, request, jsonify
# from flask_cors import CORS
# import json
# import os
# import difflib

# app = Flask(__name__)
# CORS(app)

# # Load predefined questions and answers
# predefined_qna_path = 'predefined_qna.json'
# if not os.path.exists(predefined_qna_path):
#     raise FileNotFoundError(f"{predefined_qna_path} does not exist.")
# else:
#     print(f"Found predefined_qna.json at: {predefined_qna_path}")

# try:
#     with open(predefined_qna_path, 'r') as f:
#         predefined_qna = json.load(f)['questions_and_answers']
#     print(f"Loaded {len(predefined_qna)} predefined Q&A pairs.")
# except json.JSONDecodeError as e:
#     print(f"Error loading predefined_qna.json: {e}")
#     predefined_qna = []

# # Print the loaded Q&A pairs for verification
# for qna in predefined_qna:
#     print(f"Loaded Q&A Pair - Question: '{qna['question']}', Answer: '{qna['answer']}'")

# # Helper function to normalize input for better matching
# def normalize_input(question):
#     question = question.lower().strip()
    
#     # Further extended synonym mappings based on potential user queries
#     synonym_mapping = {
#         # Greetings
#         'hi': 'hello', 'hii': 'hello', 'hey': 'hello', 'hello': 'hello',
        
#         # Contact Information
#         'contact': 'contact number', 'contacts': 'contact number', 'phone': 'contact number', 'call': 'contact number', 'reach': 'contact number', 'phone number': 'contact number',
        
#         # Fees and Costs
#         'fee': 'fees', 'fees': 'fees', 'price': 'fees', 'cost': 'fees', 'payment': 'fees', 'tuition': 'fees', 'charges': 'fees',
        
#         # Application/Admission Process
#         'apply': 'how to apply', 'application': 'how to apply', 'admission apply': 'how to apply', 'admissions': 'admission process', 'how to admission': 'admission process', 'admission steps': 'admission process', 'registration': 'how to apply', 'enroll': 'how to apply', 'sign up': 'how to apply', 'fill form': 'how to apply',
        
#         # Form Steps and Troubles
#         'next step': 'next button not working', 'step 2': 'next button not working', 'cannot proceed': 'next button not working', 'move to next': 'next button not working',
        
#         # Form-related Troubles
#         'form issue': 'trouble with form', 'form problem': 'trouble with form', 'form trouble': 'trouble with form', 'error with form': 'trouble with form', 'stuck in form': 'trouble with form',
        
#         # Document Submission and Uploads
#         'upload': 'file upload', 'uploads': 'file upload', 'attachments': 'file upload', 'attach file': 'file upload', 'submit file': 'file upload', 'file format': 'file upload', 'upload problem': 'file upload', 'cannot upload': 'file upload', 'upload error': 'file upload', 'submit document': 'file upload', 'upload document': 'file upload',
        
#         # File Naming
#         'file name': 'file naming', 'name file': 'file naming', 'proper file name': 'file naming', 'naming file': 'file naming', 'document name': 'file naming',
        
#         # Required Documents
#         'required docs': 'required documents', 'required documents': 'required documents', 'necessary documents': 'required documents', 'necessary files': 'required documents', 'needed files': 'required documents', 'needed docs': 'required documents',
        
#         # Grades/Classes
#         'grades': 'grades offered', 'classes': 'grades offered', 'grade levels': 'grades offered', 'year levels': 'grades offered', 'levels': 'grades offered', 'school grade': 'grades offered', 'grade admission': 'grades offered',
        
#         # Admission Guidelines
#         'guidelines': 'admission guidelines', 'admission guidelines': 'admission guidelines', 'rules for admission': 'admission guidelines', 'admission rules': 'admission guidelines', 'admission procedure': 'admission guidelines', 'how to get in': 'admission guidelines', 'instructions': 'admission guidelines', 'requirements for admission': 'admission guidelines',
        
#         # Deadlines
#         'deadlines': 'deadline', 'due date': 'deadline', 'submission date': 'deadline', 'last date': 'deadline', 'final date': 'deadline', 'application deadline': 'deadline', 'when to submit': 'deadline', 'final submission date': 'deadline',
        
#         # Interview and Process
#         'interview': 'admission process', 'admission interview': 'admission process', 'process steps': 'admission process', 'what is the process': 'admission process', 'how long admission process': 'admission process',
        
#         # Miscellaneous
#         'help': 'help', 'support': 'help', 'guidance': 'help', 'assistance': 'help',
#         'trouble': 'trouble', 'issue': 'trouble', 'problem': 'trouble',
#         'calendar': 'schedule', 'timetable': 'schedule', 'schedule': 'schedule', 'academic calendar': 'schedule',
#         'how to fix': 'trouble', 'error': 'trouble'
#     }

#     # Replace synonyms or close variations
#     for key, value in synonym_mapping.items():
#         if key in question:
#             question = question.replace(key, value)
    
#     return question

# # Helper function to match user input with predefined answers using normalized keywords and fuzzy matching
# def find_predefined_answer(question):
#     normalized_question = normalize_input(question)
#     print(f"Searching for answer for normalized question: '{normalized_question}'")
    
#     # Fuzzy matching using difflib
#     for qna in predefined_qna:
#         predefined_question = normalize_input(qna['question'])
#         match_ratio = difflib.SequenceMatcher(None, normalized_question, predefined_question).ratio()
#         print(f"Matching '{normalized_question}' with '{predefined_question}' has ratio: {match_ratio}")
#         if match_ratio > 0.7:  # Threshold for close matching
#             print(f"Fuzzy match found. Returning answer: '{qna['answer']}'")
#             return qna['answer']
    
#     # If no match is found, return None
#     print("No predefined or fuzzy match found.")
#     return None

# @app.route('/chatbot', methods=['POST'])
# def chatbot():
#     try:
#         data = request.json
#         question = data['question']
#         print(f"Received question: '{question}'")

#         # Check for a predefined or fuzzy match
#         predefined_answer = find_predefined_answer(question)
#         if predefined_answer:
#             print(f"Returning predefined/fuzzy answer: '{predefined_answer}'")
#             return jsonify({'answer': predefined_answer})

#         # If no predefined answer is found, return a default message
#         default_message = "I'm sorry, I cannot process your request right now."
#         print(f"No predefined answer found. Returning default message: '{default_message}'")
#         return jsonify({'answer': default_message})
#     except Exception as e:
#         print(f"Error in /chatbot endpoint: {e}")
#         return jsonify({'error': str(e)}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5001)

# from flask import Flask, request, jsonify
# from flask_cors import CORS
# import json
# import os
# from difflib import SequenceMatcher  # For fuzzy matching

# app = Flask(__name__)
# CORS(app)

# # Load predefined questions and answers
# predefined_qna_path = 'predefined_qna.json'
# if not os.path.exists(predefined_qna_path):
#     raise FileNotFoundError(f"{predefined_qna_path} does not exist.")
# else:
#     print(f"Found predefined_qna.json at: {predefined_qna_path}")

# try:
#     with open(predefined_qna_path, 'r') as f:
#         predefined_qna = json.load(f)['questions_and_answers']
#     print(f"Loaded {len(predefined_qna)} predefined Q&A pairs.")
# except json.JSONDecodeError as e:
#     print(f"Error loading predefined_qna.json: {e}")
#     predefined_qna = []

# # Helper function to normalize input (converting to lowercase and trimming spaces)
# def normalize_input(text):
#     return text.lower().strip()

# # Function to apply fuzzy matching between user input and predefined questions or synonyms
# def find_predefined_answer(question):
#     question = normalize_input(question)  # Normalize user input

#     best_match = None
#     highest_score = 0

#     # Loop through all predefined Q&A pairs
#     for qna in predefined_qna:
#         # Include both the main question and its synonyms for matching
#         all_possible_questions = [qna['question']] + qna.get('synonyms', [])

#         # Compare the user input with each question and its synonyms
#         for possible_question in all_possible_questions:
#             possible_question_normalized = normalize_input(possible_question)

#             # Calculate the similarity score between the user input and the question/synonym
#             score = SequenceMatcher(None, question, possible_question_normalized).ratio()

#             # Keep track of the best match
#             if score > highest_score:
#                 highest_score = score
#                 best_match = qna['answer']

#     # Only return the answer if the match is above a certain threshold (e.g., 0.7)
#     if highest_score > 0.7:
#         return best_match
#     else:
#         return "I'm sorry, I don't quite understand your question. Could you clarify?"

# # Add context handling (basic)
# context = {}

# @app.route('/chatbot', methods=['POST'])
# def chatbot():
#     try:
#         # Get user input from request
#         data = request.json
#         question = data.get('question', '')

#         if not question:
#             return jsonify({'answer': "Please ask a question."})

#         # Normalize and process the question
#         question_normalized = normalize_input(question)

#         # Check for context
#         if 'previous_question' in context:
#             previous_question = context['previous_question']
#             print(f"Previous question: {previous_question}")

#         # Find the best match for the user input
#         answer = find_predefined_answer(question_normalized)

#         # Store the context (the current question becomes the previous one)
#         context['previous_question'] = question_normalized

#         return jsonify({'answer': answer})

#     except Exception as e:
#         print(f"Error in /chatbot endpoint: {e}")
#         return jsonify({'error': str(e)}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5001)

from flask import Flask, request, jsonify
from flask_cors import CORS
import json
import os
from difflib import SequenceMatcher  # For fuzzy matching

app = Flask(__name__)
CORS(app)

# Load predefined questions and answers
predefined_qna_path = 'predefined_qna.json'
if not os.path.exists(predefined_qna_path):
    raise FileNotFoundError(f"{predefined_qna_path} does not exist.")
else:
    print(f"Found predefined_qna.json at: {predefined_qna_path}")

try:
    with open(predefined_qna_path, 'r') as f:
        predefined_qna = json.load(f)['questions_and_answers']
    print(f"Loaded {len(predefined_qna)} predefined Q&A pairs.")
except json.JSONDecodeError as e:
    print(f"Error loading predefined_qna.json: {e}")
    predefined_qna = []

# Helper function to normalize input for better matching
def normalize_input(question):
    question = question.lower().strip()
    
    # Further extended synonym mappings based on potential user queries
    synonym_mapping = {
        'hi': 'hello', 'hii': 'hello', 'hey': 'hello', 'hello': 'hello',
        'contact': 'contact number', 'contacts': 'contact number', 'phone': 'contact number', 'call': 'contact number', 'reach': 'contact number', 'phone number': 'contact number',
        'fee': 'fees', 'price': 'fees', 'cost': 'fees', 'payment': 'fees', 'tuition': 'fees', 'charges': 'fees',
        'apply': 'how to apply', 'application': 'how to apply', 'admission apply': 'how to apply', 'admissions': 'admission process', 'how to admission': 'admission process', 
        'enroll': 'how to apply', 'sign up': 'how to apply', 'fill form': 'how to apply',
        'next step': 'next button not working', 'step 2': 'next button not working', 'cannot proceed': 'next button not working', 'move to next': 'next button not working',
        'upload': 'file upload', 'uploads': 'file upload', 'attachments': 'file upload', 'submit document': 'file upload', 'file format': 'file upload',
        'file name': 'file naming', 'name file': 'file naming', 'proper file name': 'file naming', 'naming file': 'file naming',
        'required docs': 'required documents', 'needed files': 'required documents',
        'grades': 'grades offered', 'classes': 'grades offered', 'grade levels': 'grades offered',
        'guidelines': 'admission guidelines', 'rules for admission': 'admission guidelines', 'admission rules': 'admission guidelines',
        'deadlines': 'deadline', 'due date': 'deadline', 'last date': 'deadline', 'submission date': 'deadline',
        'interview': 'admission process', 'admission interview': 'admission process',
        'calendar': 'schedule', 'timetable': 'schedule', 'academic calendar': 'schedule',
        'help': 'help', 'support': 'help', 'assistance': 'help'
    }

    # Replace synonyms or close variations
    for key, value in synonym_mapping.items():
        if key in question:
            question = question.replace(key, value)
    
    return question

# Function to apply fuzzy matching between user input and predefined questions or synonyms
def find_predefined_answer(question):
    question = normalize_input(question)  # Normalize user input

    best_match = None
    highest_score = 0

    # Loop through all predefined Q&A pairs
    for qna in predefined_qna:
        # Include both the main question and its synonyms for matching
        all_possible_questions = [qna['question']] + qna.get('synonyms', [])

        # Compare the user input with each question and its synonyms
        for possible_question in all_possible_questions:
            possible_question_normalized = normalize_input(possible_question)

            # Calculate the similarity score between the user input and the question/synonym
            score = SequenceMatcher(None, question, possible_question_normalized).ratio()

            # Keep track of the best match
            if score > highest_score:
                highest_score = score
                best_match = qna['answer']

    # Only return the answer if the match is above a certain threshold (e.g., 0.7)
    if highest_score > 0.7:
        return best_match
    else:
        return "I'm sorry, I don't quite understand your question. Could you clarify?"

# Context management (optional, for future expansion)
context = {}

@app.route('/chatbot', methods=['POST'])
def chatbot():
    try:
        # Get user input from request
        data = request.json
        question = data.get('question', '')

        if not question:
            return jsonify({'answer': "Please ask a question."})

        # Normalize and process the question
        question_normalized = normalize_input(question)

        # Find the best match for the user input
        answer = find_predefined_answer(question_normalized)

        # Store the context (optional, for future use)
        context['previous_question'] = question_normalized

        return jsonify({'answer': answer})

    except Exception as e:
        print(f"Error in /chatbot endpoint: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)
