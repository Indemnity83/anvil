from flask import request
from flask_restful import Resource


class WebHooks(Resource):
    def get(self):
        with open('../storage/deploy.sh', 'r') as file:
            return file.read()

    def post(self):
        with open('../storage/deploy.sh', 'w') as file:
            data = request.get_json()
            file.write(data['script'])
            return {'success': True}