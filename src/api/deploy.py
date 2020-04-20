from flask_restful import Resource
import subprocess
import requests


class Deploy(Resource):
    def post(self):
        with open('/home/anvil/storage/deploy/deploy.log', 'w') as file:
            subprocess.run('date', stdout=file, stderr=file)
            out = subprocess.run(['sh', '/home/anvil/storage/deploy/deploy.sh'], cwd='/home/anvil', stdout=file, stderr=file)

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
