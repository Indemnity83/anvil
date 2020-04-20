from flask_restful import Resource


class DeployLog(Resource):
    def get(self):
        with open('/home/anvil/storage/deploy/deploy.log', 'r') as file:
            return file.read()
