import os
import shutil
import subprocess
from flask import request
from flask_restful import Resource


class Install(Resource):
    install_script = '/srv/anvil/scripts/install.sh'
    log_file_path = '/srv/anvil/appdata/install.log'

    def post(self):
        log_file = open(self.log_file_path, 'w')

        install = request.get_json()
        repository = 'https://github.com/' + install['repository']

        # TODO: provide feedback to user on progress/status
        subprocess.Popen(['sh', self.install_script, repository, install['branch']], stdout=log_file, stderr=log_file, cwd='/home/anvil')

        return {'status': 'success'}

    def delete(self):
        dirpath = '/home/anvil'
        for filename in os.listdir(dirpath):
            filepath = os.path.join(dirpath, filename)
            try:
                shutil.rmtree(filepath)
            except OSError:
                os.remove(filepath)

        return {'status': 'success'}
