import os
import shutil
import subprocess
from flask import request
from flask_restful import Resource


class Install(Resource):
    def post(self):
        install = request.get_json()
        repository = 'https://github.com/' + install['repository']
        # Clone repository
        subprocess.run('git clone --depth=1 -b ' + install['branch'] + ' ' + repository + ' .',
                       shell=True, cwd='/home/anvil')

        # Install composer dependencies
        if install['dependencies']:
            subprocess.run('composer install --optimize-autoloader --no-dev',
                       shell=True, cwd='/home/anvil')

        # Deploy the .env file
        subprocess.run('cp .env.example .env',
                       shell=True, cwd='/home/anvil')

        # Generate Key
        subprocess.run('php artisan key:generate',
                       shell=True, cwd='/home/anvil')

        # Make sqlite database file
        subprocess.run('touch storage/database.sqlite',
                       shell=True, cwd='/home/anvil')

        # Make default deploy script
        subprocess.run('mkdir -p storage/deploy')
        subprocess.run('touch storage/deploy/deploy.sh',
                        shell=True, cwd='/home/anvil')

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
