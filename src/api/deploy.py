from flask_restful import Resource
import subprocess
import requests


class Deploy(Resource):
    script_path = '/srv/anvil/appdata/deploy.sh'
    log_file_path = '/srv/anvil/appdata/deploy.log'

    def post(self):
        log_file = open(self.log_file_path, 'w')

        # Start by dumping the system date at the top of the file then run the deploy script
        subprocess.run('date', stdout=log_file, stderr=log_file, cwd='/home/anvil')
        subprocess.Popen(['sh',self.script_path ], stdout=log_file, stderr=log_file, cwd='/home/anvil')

        # TODO: This response mirrors the structure of Forge, but obviously needs some work
        response = {
            'status': 'success',
            'server': {
                'id': '',
                'name': '',
            },
            'site': {
                'id': '',
                'name': '',
            },
            'commit_hash': '',
            'commit_url': '',
            'commit_author': '',
            'commit_message': '',
        }

        # TODO: this logic should be in webhooks.py
        requests.post('https://webhook.site/481d391d-086a-45dc-85be-508520fb1592', json=response)

        return response
