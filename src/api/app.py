import os

from flask import Flask, jsonify, request
from flask_restful import Resource, Api
from flask_cors import CORS
from install import Install
from deploy import Deploy
from deploy_log import DeployLog
from deploy_script import DeployScript

# Init application
app = Flask(__name__)
api = Api(app)

# Enable CORS
CORS(app, resources={r'/*': {'origins': '*'}})


@app.route('/api')
def status():
    return {
        'is_deployed': len(os.listdir('/home/anvil/') ) > 0,
    }


api.add_resource(Install, '/api/install')
api.add_resource(Deploy, '/api/deploy')
api.add_resource(DeployLog, '/api/deploy/log')
api.add_resource(DeployScript, '/api/deploy/script')

if __name__ == '__main__':
    app.run(debug=True)