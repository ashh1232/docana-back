from flask import Flask


appFlask = Flask(__name__)

@appFlask.route('/')
def index():
    return "Hello World!"
   