from flask import request
from flask_restful import Resource


class DeployScript(Resource):
    def get(self):
        with open('/home/anvil/storage/deploy/deploy.sh', 'r') as file:
            return file.read()

    def post(self):
        with open('/home/anvil/storage/deploy/deploy.sh', 'w') as file:
            data = request.get_json()
            file.write(data['script'])
            return {
                'status': 'success',
            }
