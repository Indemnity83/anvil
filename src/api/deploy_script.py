from flask import request
from flask_restful import Resource


class DeployScript(Resource):
    script_path = '/srv/anvil/appdata/deploy.sh'

    def get(self):
        script_file = open(self.script_path, 'r')

        return script_file.read()

    def post(self):
        script_file = open(self.script_path, 'w')

        data = request.get_json()
        script_file.write(data['script'])
        
        return {
            'status': 'success',
        }
